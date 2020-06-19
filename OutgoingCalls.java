/*
 ***********************************AUM SRI SAI RAM************************************
 * out going calls to the external world are handled in this class
 */
import org.asteriskjava.fastagi.*;
import java.sql.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class OutgoingCalls extends BaseAgiScript
{
	private String user,channel,dev,usr_grps,a_status,pin,u_s,pass,ch_id,s_d,w_l_ex,ph_no,o_pre,r_p,dialable,gd_id,call_rec_path,bal_r,start,end,rp,ps;
	private int u_id,d_id,u_grp,d_grp,t_id,p_id,call_lim,total_dur,credit_lim,ct_id,dur_sec,time_spent,u_access,course_id,batch;
	Float cost;
	private long avail_dur,limited_dur,possible_dur,per_call_time_limit;
	private float bal;
	private Map<Integer,Integer> d_map= new HashMap<>();
	public ArrayList<Integer> u_grps= new ArrayList<>();
	public ArrayList<Float> r_list= new ArrayList<Float>();
	public ArrayList<String> bamt= new ArrayList<>();
	public boolean time_val;
	public String pass_1,pass_2,pass_3;
	@SuppressWarnings("finally")
	public void service(AgiRequest arg0, AgiChannel arg1) throws AgiException 
	{
		System.out.println("CLASS : OutgoingCalls");
		answer();
		dev= getFullVariable("${CALLERID(num)}");
		user= getData("IVR/user-id",5000);
		channel= getName();
		ch_id= getFullVariable("${UNIQUEID}");
		setVariable("CAN_CALL", "0");
		String s_time= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");

		//EMPTY USER ID CHECK
		if(user.isEmpty())	//if nothing is entered
		{
			String empty_user= "INSERT INTO USERCALL_DETAILS (`unique_id`,`call_status`,`src`,`call_dir`,`start_time`,`channel`,`final_status`) VALUES('"+ch_id+"','EMPTY USERID','"+dev+"','2','"+s_time+"','"+channel+"','CALL ENDED')";
			DatabaseConn.executeUpdate(empty_user);
			streamFile("IVR/uid_null");
			return;
		}
		u_id= Integer.parseInt(user);
		d_id= Integer.parseInt(dev);
		Mappings map= new Mappings();
		d_map= map.get_device_map();
		d_grp= d_map.get(d_id);
		try 
		{
			//System.out.println("SELECT * FROM USERS AS a JOIN USER_GROUP AS b WHERE (a.ug_id = b.ug_id) AND a.user_id= "+u_id);
			String usr_qry= "SELECT * FROM USERS AS a JOIN USER_GROUP AS b WHERE (a.ug_id = b.ug_id) AND a.user_id= "+u_id;
			ResultSet rs= DatabaseConn.executeQuery(usr_qry);

			//the above query is going to return only one row
			if(rs.next())	//if user-id exists with the ID we entered!!!
			{
				Validations val= new Validations();
				u_grp= rs.getInt("ug_id");							//gets the ID of the group to which the user belongs
				pin= rs.getString("pin");							//gets the encrypted user pin
				u_s= rs.getString("status");						//gets the user status whether the user is active or not
				bal= rs.getFloat("balance");							//gets the  current balance amount of the user
				p_id= rs.getInt("plan_id");							//GETS THE PLAN ID OF THE USER'S GROUP
				call_lim= rs.getInt("per_call_time_limit");			//gets the per call time limit of the group
				total_dur= rs.getInt("total_user_call_duration");	//gets the total call duration that the user is given 
				credit_lim= rs.getInt("credit_limit");				//the credit limit extent of the user w.r.to group
				//freq= rs.getString("frequency");					//the frequency of the duration
				w_l_ex= rs.getString("whitelist_exemption");		//indicates whether white list check is required or not
				r_p= rs.getString("recording_permission");			//indicates whether a call is allowed to record or not
				time_spent= rs.getInt("time_used");	//indicates the total amount of time the user has used for that particular frequency
				u_access= rs.getInt("access_level");	//indicates the access level of the user (admin/staff/student)
				course_id= rs.getInt("course_id");	//the course-id which represents the course the user is studying
				batch= rs.getInt("batch");	//the joining year of the user
				
				bal_r= ""+bal;
				
				//check whether the user is a student and he is allowed to make a call in the given period.
				boolean stu= val.isAllowed(u_id, course_id, batch, u_access);
//				System.out.println("the boolean is "+stu);
				if(stu == false)
				{
					//String s1= "UPDATE USERCALL_DETAILS SET call_status= 'INVALID PERIOD' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
					String invalid_period= "INSERT INTO USERCALL_DETAILS (`unique_id`,`call_status`,`src`,`call_dir`,`start_time`,`channel`,`final_status`) VALUES('"+ch_id+"','INVALID PERIOD','"+dev+"','2','"+s_time+"','"+channel+"','CALL ENDED')";
					DatabaseConn.executeUpdate(invalid_period);
					streamFile("IVR/invalid-period");
					return;
				}
				
				//check 2 user status check
				if(u_s.equals("0"))	//if user is found inactive
				{
					String inactiveuser= "INSERT INTO USERCALL_DETAILS (`unique_id`,`user_id`,`call_status`,`src`,`call_dir`,`start_time`,`channel`,`final_status`) VALUES('"+ch_id+"',"+u_id+",'INACTIVE USER','"+dev+"','2','"+s_time+"','"+channel+"','CALL ENDED')";
					DatabaseConn.executeUpdate(inactiveuser);
					streamFile("IVR/user-inactive");
					return;
				}
				//check 3 simultaneous login check
				String sim_login_qry= "SELECT call_status FROM USERCALL_DETAILS WHERE user_id= "+u_id+" AND final_status= 'CALL LIVE'";
				ResultSet rs1= DatabaseConn.executeQuery(sim_login_qry);	//
				if(rs1.next())	//if some call is active with current user and device i.e., user is logged in any other device
				{
					String dup_user= "INSERT INTO USERCALL_DETAILS (`unique_id`,`user_id`,`call_status`,`src`,`call_dir`,`start_time`,`channel`,`final_status`) VALUES('"+ch_id+"',"+u_id+",'DUPLICATE USER','"+dev+"','2','"+s_time+"','"+channel+"','CALL ENDED')";
					DatabaseConn.executeUpdate(dup_user);
					streamFile("IVR/sim_login");
					return;
				}
				pass_1= channel+"_"+ch_id+"_"+user+"_START_"+dev+"_2_"+s_time+"_"+bal_r+"_"+time_spent;
				setVariable("PASS1", pass_1);
				//Ask for password
				System.out.println("password please.....");
				pass= getData("vm-password",10000);
				//check 4 password check
				if(pass.isEmpty())	//password has not been entered
				{
					//System.err.println("PASSWORD NOT ENTERED");
					String empty_pin= "UPDATE USERCALL_DETAILS SET call_status= 'EMPTY PIN',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
					DatabaseConn.executeUpdate(empty_pin);
					streamFile("IVR/empty-pin");
					return;
				}
				
				int p_c= val.check_password(pin, pass);
				if(p_c == 1)	//if the password is correct
				{
					//System.out.println("LOGIN SUCCESSFUL");
					pass_2= "LOGIN";
					setVariable("PASS2", pass_2);
					u_grps= map.getgroups(u_grp);
					usr_grps= u_grps.toString();
					usr_grps= usr_grps.substring(1, usr_grps.length()-1);
					String rule= "SELECT * FROM OUTGOING_EXTERNAL_CALL_RULES WHERE rule_status= '1' AND ((caller_flag= '0' AND caller_id= "+u_id+") OR (caller_flag= '1' AND caller_id IN ("+usr_grps+"))) AND ((source_flag= '0' AND source_id= "+d_id+") OR (source_flag= '1' AND source_id="+d_grp+")) ORDER BY priority";
					System.out.println(rule);
					ResultSet rs2= DatabaseConn.executeQuery(rule);
					if(rs2.next())
					{
						t_id= rs2.getInt("ts_id");
						a_status= rs2.getString("allow_status");
//						if(r_status.equals("0"))	//if the rule itself is made active or not
//						{
//							streamFile("sorry");
//							DatabaseConn.executeUpdate("UPDATE USERCALL_DETAILS SET call_status= 'INACTIVE RULE',final_status= 'CALL ENDED'  WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'");
//							return;
//						}
						if(a_status.equals("0"))	//if the rule defined the user not to make calls
						{
							System.out.println("not allowed in rules");
							String call_denied= "UPDATE USERCALL_DETAILS SET call_status= 'CALL DENIED',final_status= 'CALL ENDED'  WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
							System.out.println(call_denied);
							DatabaseConn.executeUpdate(call_denied);
//							streamFile("demo-nomatch");
							streamFile("IVR/call_not_allowed");
							return;
						}
						boolean bal_chk= val.check_balance(bal, credit_lim);
						if(!(bal_chk))
						{
							String low_bal= "UPDATE USERCALL_DETAILS SET call_status= 'INSUFFICIENT BALANCE',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
							System.out.println(low_bal);
							DatabaseConn.executeUpdate(low_bal);
							streamFile("IVR/insuff-bal");
							return;
						}
						//total_dur*= 60;	//total user call duration is converted into seconds.
						avail_dur= val.get_duration(total_dur,time_spent);	//get the available total duration in seconds for the user w.r.t to user's time
						System.out.println("Available duration for the time period in seconds : "+avail_dur);
						if(avail_dur <= 0)	//if the user has consumed all of his duration time units
						{
							String less_time= "UPDATE USERCALL_DETAILS SET call_status= 'INSUFFICIENT TIME BALANCE',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
							System.out.println(less_time);
							DatabaseConn.executeUpdate(less_time);
							streamFile("IVR/dur-exceed");
							return;
						}
						//some duration is available to make a call for the user
						total_dur*= 1000;	//converts the total initial duration into milliseconds
						avail_dur*= 1000;	//converts the available duration into milliseconds
						if(w_l_ex.equals("1"))	//exempted from whitelist check
						{
							System.out.println("WHITE LIST EXEMPTED");
							//ext= getData("vm-enter-num-to-call",15000);
							if(r_p.equals("0"))	//recording not required
							{
								
							}
							if(r_p.equals("1"))	//recording required
							{
								
							}
						}
						else	//white list check has to happen ie., the speed dial has to happen now
						{
							s_d= getData("speed-dial",10000);
							//String try= DatabaseConn.executeQuery("SELECT * from WHITELIST_CONTACTS where speeddial= '"+s_d+"'");
							System.out.println();
							if(s_d.isEmpty())
							{
								String empty_speed_dial= "UPDATE USERCALL_DETAILS SET call_status= 'EMPTY SPEED DIAL',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
								System.out.println(empty_speed_dial);
								DatabaseConn.executeUpdate(empty_speed_dial);
								streamFile("speed-dial-empty");
								return;
							}
							String whitelist_qry= "SELECT phone_number,o_prefix,a.calltype_id,start_date,end_date FROM WHITELIST_CONTACTS AS a JOIN CALL_TYPE AS b ON a.calltype_id=b.calltype_id WHERE a.user_id= "+u_id+" AND a.speed_dial= "+s_d;
							System.out.println(whitelist_qry);
							ResultSet rs3= DatabaseConn.executeQuery(whitelist_qry);
							if(rs3.next())
							{
								System.out.println("number is there");
								ph_no= rs3.getString("phone_number");
								o_pre= rs3.getString("o_prefix");
								ct_id= rs3.getInt("calltype_id");
								start= rs3.getString("start_date");
								end= rs3.getString("end_date");
								boolean wl= val.check_wl_validity(start,end);
								if(wl == false)
								{
									String invalid_wl_period= "UPDATE USERCALL_DETAILS SET call_status= 'INVALID WHITELIST PERIOD',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
									System.out.println(invalid_wl_period);
									DatabaseConn.executeUpdate(invalid_wl_period);
									streamFile("IVR/invalid-wl-period");
									return;
								}
								System.out.println("number is not expired");
								r_list= val.check_plan(p_id, ct_id);
								if(r_list.isEmpty())
								{
									String invalid_plan= "UPDATE USERCALL_DETAILS SET call_status= 'INVALID PLAN',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
									System.out.println(invalid_plan);
									DatabaseConn.executeUpdate(invalid_plan);
									streamFile("IVR/invalid-plan");
									return;
								}
								System.out.println(r_list);
								time_val= val.check_time(t_id);
								if(time_val == false)
								{
									String invalid_time= "UPDATE USERCALL_DETAILS SET call_status= 'INVALID TIME',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
									System.out.println(invalid_time);
									DatabaseConn.executeUpdate(invalid_time);
									streamFile("IVR/invalid-timeslot");
									return;
								}
								cost= r_list.get(0);
								float d= r_list.get(1);
								dur_sec= (int) d;
								bal= bal - credit_lim;
								bal*= 100;
								possible_dur= (int) ((bal * dur_sec) / cost);
								//possible_dur*= 60000;
								possible_dur*= 1000;
								per_call_time_limit= call_lim * 1000;
								System.out.println("possible duration in milli seconds w.r.to balance amount is "+possible_dur);
								System.out.println("possible duration in milli seconds w.r.to available duration is "+avail_dur);
								System.out.println("possible duration in milli seconds w.r.to percalltimelimit amount is "+per_call_time_limit);
								limited_dur= val.getmin(possible_dur,avail_dur,per_call_time_limit);
								System.out.println("THE final limited duration is : "+limited_dur);
								dialable= o_pre+ph_no;
								System.out.println(dialable);
								gd_id= val.get_gateway();
								if(gd_id.isEmpty())
								{	//the gateway device is not there in the globals
									String no_pri= "UPDATE USERCALL_DETAILS SET call_status= 'INTERNAL ERROR',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
									System.out.println(no_pri);
									DatabaseConn.executeUpdate(no_pri);
									streamFile("please-try-call-later");
									return;
								}
								System.out.println(gd_id);
								bamt= val.get_balanceamount(bal_r);
								System.out.println(bamt);
								rp= bamt.get(0);
								ps= bamt.get(1);
								if(bal > 0)
								{
									streamFile("IVR/balance");
									sayNumber(rp);
									streamFile("IVR/rupee");
									sayNumber(ps);
									streamFile("IVR/paise");
								}
								else
								{
									streamFile("IVR/balance");
									streamFile("minus");
									sayNumber(rp);
									streamFile("IVR/rupee");
									sayNumber(ps);
									streamFile("IVR/paise");
								}
								pass_3= dialable+"_DIALLING_"+cost+"_"+dur_sec;
								setVariable("PASS3", pass_3);
								setVariable("LIMIT_WARNING_FILE", "beep");
								if(r_p.equals("0"))	//recording not required
								{
									System.out.println("Recording not required");
									exec("Dial",gd_id+dialable+",60,L("+limited_dur+":"+(limited_dur/2)+":"+(limited_dur/4)+":${LIMIT_WARNING_FILE})");
									return;
								}
								else	//if recording is required
								{
									System.out.println("REcording required");
									//ResultSet rs5= stmt.executeQuery("SELECT var_value_assigned FROM GLOBALS WHERE var_name= 'CALL_REC_PATH'");
									call_rec_path= val.get_rec_path();
									if(call_rec_path.isEmpty())
									{	//if there is no proper recpath
										String no_rec= "UPDATE USERCALL_DETAILS SET call_status= 'INTERNAL ERROR',final_status= 'CALL ENDED'  WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
										System.out.println(no_rec);
										DatabaseConn.executeUpdate(no_rec);
										streamFile("IVR/invalid-rec-path");
										return;
									}
									String rec_file= call_rec_path+u_id+"_"+dialable+"_"+s_time+"OUTGOING.gsm";
									String recording= u_id+"_"+dialable+"_"+s_time+"OUTGOING.gsm";
									String rec_path= "UPDATE USERCALL_DETAILS SET recorded_filename= '"+recording+"' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
									System.out.println(rec_path);
									DatabaseConn.executeUpdate(rec_path);
									exec("MixMonitor",rec_file+",W");
									exec("Dial",gd_id+dialable+",60,L("+limited_dur+":"+(limited_dur/2)+":"+(limited_dur/4)+":${LIMIT_WARNING_FILE})");
									return;
								}
							}
							else	//if the speed dial is entered wrong
							{
								String invalid_sd= "UPDATE USERCALL_DETAILS SET call_status= 'INVALID SPEED DIAL',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
								System.out.println(invalid_sd);
								DatabaseConn.executeUpdate(invalid_sd);
								streamFile("IVR/invalid-speed-dial");
								return;
							}
						}
					}
					else	//if no rules are defined for this call
					{
						System.out.println("There are no rules defined.");
						String no_rules= "UPDATE USERCALL_DETAILS SET call_status= 'NO RULES',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
						System.out.println(no_rules);
						DatabaseConn.executeUpdate(no_rules);
						streamFile("demo-nomatch");
						return;
					}
				}
				else 
				{
					//if the password entered is wrong
					if(p_c == -1)
					{
						String wrong_pin= "UPDATE USERCALL_DETAILS SET call_status= 'INVALID PIN',final_status= 'CALL ENDED' WHERE channel= '"+channel+"' AND unique_id= '"+ch_id+"'";
						System.out.println(wrong_pin);
						DatabaseConn.executeUpdate(wrong_pin);
						streamFile("vm-invalidpassword");
						return;
					}
				}
			}
			else	//if the user ID itself is only not valid
			{
				String wrong_user= "INSERT INTO USERCALL_DETAILS (`unique_id`,`user_id`,`call_status`,`src`,`call_dir`,`start_time`,`channel`,`final_status`) VALUES('"+ch_id+"',"+u_id+",'INVALID USER','"+dev+"','2','"+s_time+"','"+channel+"','CALL ENDED')";
				System.out.println(wrong_user);
				DatabaseConn.executeUpdate(wrong_user);
				streamFile("vm-incorrect");
				return;
			}
		}
		catch (AgiHangupException |SQLException e) 
		{
			//final_status= true;
			System.out.println("Exception in OUTGOING CALLS");
			e.printStackTrace();
		}
		finally
		{
			String end= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
			DatabaseConn.executeUpdate("UPDATE USERCALL_DETAILS SET final_status= 'CALL ENDED',end_time= '"+end+"' WHERE unique_id= '"+ch_id+"' AND channel= '"+channel+"'");
			return;
		}
	}	//end of service method
}	//end of OutgoingCalls class