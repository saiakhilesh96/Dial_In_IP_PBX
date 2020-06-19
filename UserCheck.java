import org.asteriskjava.fastagi.*;
import java.sql.*;

public class UserCheck extends BaseAgiScript implements AgiChannel
{
	public void service(AgiRequest request, AgiChannel channel) throws AgiException
	{
		String reg_number = null,pin,password,status="1",ug_id,avail_dur,u_status = null,channel_name = null,source,uniqueId = null,finalstatus,call_status = null,start_time,end_time,q1,q2,firstEntry,secondEntry;
		int time_used,available_duration,total_dur = 0,courseid,batch,u_access,per_call_limit = 0;
		try
		{
			answer();
			System.out.println("------------UserCheck Part------------------------");
			streamFile("IVR/welcome");
			System.out.println("--Initial variable which will control the Flow of Incoming-- "+getFullVariable("${mCanCall}"));
			setVariable("mCanCall", "0");						//Turning to 0 
			uniqueId= getFullVariable("${UNIQUEID}");
			channel_name= getName();
			source= getFullVariable("${CALLERID(num)}");	//--------substring required or not-------
			start_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
			System.out.println("Phone Number is :"+source);
			firstEntry= channel_name+"_"+uniqueId+"_"+source+"_"+start_time;
			setVariable("FIRSTENTRY", firstEntry);
			//DatabaseConn.executeUpdate("INSERT into USERCALL_DETAILS(`channel`,`unique_id`,`call_status`,`src`,`call_dir`,`time_of_call`) values('"+channel_name+"','"+uniqueId+"','"+call_status+"','"+source+"','"+call_dir+"','"+Datetime+"')");
			//stmt.executeUpdate("INSERT into USERCALL_DETAILS(`channel`,`unique_id`,`call_status`,`src`,`call_dir`,`time_of_call`) values('"+channel_name+"','"+uniqueId+"','"+call_status+"','"+source+"','"+call_dir+"','"+Datetime+"')");
			reg_number= getData("IVR/user-id",5000);
			//-------------Check reg_num ----------------
			if(reg_number.isEmpty())
			{	
				streamFile("IVR/uid_null");
				System.out.println("user-id not entered");
				call_status="EMPTY USERID";
				finalstatus= "CALL ENDED";
				end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
				String q0= "update USERCALL_DETAILS set `call_status`= '"+call_status+"',`final_status`= '"+finalstatus+"',`end_time`= '"+end_time+"' where channel= '"+channel_name+"' AND unique_id= '"+uniqueId+"'";
				DatabaseConn.executeUpdate(q0);
				System.err.println("REG_NUM NOT ENTERED");
				setVariable("mCanCall", "0");
				return;
			}
			int u_id= Integer.parseInt(reg_number);
			System.out.println("Reg Number  is :"+reg_number);
			Validations val= new Validations();
			setVariable("reg_num", reg_number);
			q1= "select pin,status,time_used,ug_id,course_id,batch,access_level from USERS where user_id='"+reg_number+"'";
			ResultSet user_data= DatabaseConn.executeQuery(q1);
			if (user_data.next())
			{
					pin = user_data.getString(1);
					u_status= user_data.getString(2);
					time_used= user_data.getInt(3);
					ug_id= user_data.getString(4);
					courseid= user_data.getInt(5);
					batch= user_data.getInt(6);
					u_access= user_data.getInt(7);
					String insertuserid= "update USERCALL_DETAILS set `user_id`='"+reg_number+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"'";
					DatabaseConn.executeUpdate(insertuserid);
					if(u_status.equals(status))
					{	
						boolean stu= val.isAllowed(u_id, courseid, batch, u_access);
						if(stu == false)
						{
							call_status= "INVALID PERIOD";
							finalstatus= "CALL ENDED";
							String Ac_check= "update USERCALL_DETAILS set `call_status`= '"+call_status+"', `final_status`= '"+finalstatus+"' where channel= '"+channel+"' and unique_id= '"+uniqueId+"' and user_id='"+reg_number+"'";
							DatabaseConn.executeUpdate(Ac_check);
							streamFile("IVR/invalid-period");
							setVariable("mCanCall", "0");
							return;
						}
						q2= "select total_user_call_duration,per_call_time_limit from USER_GROUP where ug_id='"+ug_id+"'";
						ResultSet user_grp= DatabaseConn.executeQuery(q2);
						if(user_grp.next())
						{
							total_dur= user_grp.getInt("total_user_call_duration");
							per_call_limit= user_grp.getInt(2);
						}
						//total_dur*=60;
						available_duration= val.get_duration(total_dur, time_used);
						if(available_duration > 0)
						{   
							int limit= Math.min(per_call_limit, available_duration);
							setVariable("limit", ""+limit);
							password= getData("vm-password",5000);
							if(password.isEmpty())													//password has not been entered
							{	
								call_status="EMPTY PIN";
								finalstatus= "CALL ENDED";
								end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
								String empty_pin= "update USERCALL_DETAILS set `call_status`='"+call_status+"',final_status= '"+finalstatus+"',end_time= '"+end_time+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"' and user_id= '"+reg_number+"'";
								DatabaseConn.executeUpdate(empty_pin);
								streamFile("IVR/empty-pin");
								System.err.println("PASSWORD NOT ENTERED");
								setVariable("mCanCall", "0");
								return;
							}
							System.out.println("Password  is :"+password);
							int pass_check= val.check_password(pin, password);
							if(pass_check == 1)														//if the password is correct
							{
								System.out.println("Correct Password");
								call_status= "LOGIN";
								secondEntry= reg_number+"_"+call_status;
								setVariable("SECONDENTRY", secondEntry);
								//DatabaseConn.executeUpdate("update USERCALL_DETAILS set `call_status`='"+call_status+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"'");
								setVariable("mCanCall", "1"); 										// setting variable to 1 is not required becoz its already 1 but fine.
								return;
							}
							else
							{
								call_status="INVALID PIN";
								finalstatus= "CALL ENDED";
								end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
								String invalid_pin= "update USERCALL_DETAILS set `call_status`='"+call_status+"',final_status= '"+finalstatus+"',end_time= '"+end_time+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"' and user_id='"+reg_number+"'";
								DatabaseConn.executeUpdate(invalid_pin);
								System.out.println("Incorrect Password");
								streamFile("vm-invalidpassword");
								setVariable("mCanCall", "0");
								return;
							}
						}
						else
						{
							streamFile("IVR/dur-exceed");
							call_status="INSUFFICIENT TIME BALANCE";
							finalstatus= "CALL ENDED";
							end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
							String no_time= "update USERCALL_DETAILS set `call_status`='"+call_status+"',final_status= '"+finalstatus+"',end_time= '"+end_time+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"' and user_id='"+reg_number+"'";
							DatabaseConn.executeUpdate(no_time);
							setVariable("mCanCall", "0");
							return;
						}
					}
					else
					{
						System.out.println("INACTIVE USER");
						call_status="INACTIVE USER";
						finalstatus= "CALL ENDED";
						end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
						String inactiveuser= "update USERCALL_DETAILS set `call_status`='"+call_status+"',final_status= '"+finalstatus+"',end_time= '"+end_time+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"' and user_id='"+reg_number+"'";
						DatabaseConn.executeUpdate(inactiveuser);
						streamFile("IVR/user-inactive");
						setVariable("mCanCall", "0");
						return;
					}
			}
			else
			{
				call_status="INVALID USER";
				finalstatus= "CALL ENDED";
				end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
				String invalid_user= "update USERCALL_DETAILS set `user_id`= '"+reg_number+"',`call_status`='"+call_status+"',`final_status`= '"+finalstatus+"',`end_time`= '"+end_time+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"'";
				System.out.println("Reg Number is Invalid");
				DatabaseConn.executeUpdate(invalid_user);
				streamFile("vm-incorrect");
				setVariable("mCanCall", "0");
				return;
			}
		}
		catch(AgiHangupException | SQLException  e)
		{
		    System.out.println("------Error From USERCHECK-----"+e);
			e.printStackTrace();
			call_status="INTERNAL ERROR";
			finalstatus= "CALL ENDED";
			end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
			String invalid_user= "update USERCALL_DETAILS set `call_status`='"+call_status+"',`final_status`= '"+finalstatus+"',`end_time`= '"+end_time+"' where channel='"+channel_name+"' AND unique_id= '"+uniqueId+"'";			
			DatabaseConn.executeUpdate(invalid_user);
		}
	}
}
//------------------Thanks Swami...Hoping UserCheck is Done-----------------------
