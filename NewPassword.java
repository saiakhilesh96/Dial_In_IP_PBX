/*
 * ************************AUM SRI SAI RAM********************************
 */
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalTime;

import org.asteriskjava.fastagi.*;
public class NewPassword extends BaseAgiScript
{
	private String user,pin,password,u_s,p1,p2,callstatus,finalstatus;
	private int u_id,course_id,batch,u_access;
	@SuppressWarnings("finally")
	public void service(AgiRequest arg0, AgiChannel arg1) throws AgiException 
	{
		answer();
		String start_time= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
		String[] st_t= start_time.split(" ");
		String s_t= st_t[1];
		LocalTime st= LocalTime.parse(s_t);
		int s_sec= st.toSecondOfDay();
		Validations val= new Validations();
		String channel= getName();
		String uniqueid= getUniqueId();
		String src= getFullVariable("${CALLERID(num)}");
		
		try
		{
			//check whether the user is a student and he is allowed to make a call in the given period.
			finalstatus= "CALL LIVE";
			callstatus= "PIN CHANGE";
			String enter= "INSERT INTO USERCALL_DETAILS (`channel`,`unique_id`,`src`,`start_time`,`answer_time`,`call_dir`,`final_status`,`call_status`) VALUES ('"+channel+"','"+uniqueid+"','"+src+"','"+start_time+"','"+start_time+"','3','"+finalstatus+"','"+callstatus+"')";
			DatabaseConn.executeUpdate(enter);
			user= getData("IVR/user-id",5000);
			u_id= Integer.parseInt(user);
			if(user.isEmpty())
			{
				finalstatus= "CALL ENDED";
				callstatus= "EMPTY USER";
				String empty_user= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(empty_user);
				streamFile("IVR/uid_null");
				return;
			}
			String insert_user= "UPDATE USERCALL_DETAILS SET user_id= "+u_id+" WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
			DatabaseConn.executeUpdate(insert_user);
			String q1= "SELECT course_id,batch,pin,access_level,status FROM USERS WHERE user_id= "+user;
			ResultSet rs= DatabaseConn.executeQuery(q1);
			if(rs.next())
			{
				course_id= rs.getInt(1);
				batch= rs.getInt(2);
				pin= rs.getString(3);
				u_access= rs.getInt(4);
				u_s= rs.getString(5);
				
				// user status check
				if(u_s.equals("0"))	//if user is found inactive
				{
					callstatus= "INACTIVE USER";
					String inactive_user= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
					DatabaseConn.executeUpdate(inactive_user);
					streamFile("IVR/user-inactive");
					return;
				}
				//check - academic duration 
				boolean stu= val.isAllowed(u_id, course_id, batch, u_access);
				//System.out.println("the boolean is "+stu);
				if(stu == false)
				{
					finalstatus= "CALL ENDED";
					callstatus= "INVALID PERIOD";
					String invalid_period= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
					DatabaseConn.executeUpdate(invalid_period);
					streamFile("IVR/invalid-student");
					return;
				}
				
				//check simultaneous login
				String sim_login_qry= "SELECT call_status FROM USERCALL_DETAILS WHERE user_id= "+u_id+" AND final_status= 'CALL LIVE'";
				ResultSet rs1= DatabaseConn.executeQuery(sim_login_qry);
				if(rs1.next() == false)
				{
					System.out.println("empty resultset");
				}
				else
				{
					System.out.println("resultset not empty");
				}
				if(rs1.next())	//if some call is active with current user and device i.e., user is logged in any other device
				{
					callstatus= "DUPLICATE USER";
					String dup_user= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
					DatabaseConn.executeUpdate(dup_user);
					streamFile("IVR/sim_login");
					return;
				}
				password= getData("vm-password",5000);
				if(password.isEmpty())
				{
					callstatus= "EMPTY PIN";
					String empty_pin= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
					DatabaseConn.executeUpdate(empty_pin);
					streamFile("IVR/empty-pin");
					return;
				}
				int pc= val.check_password(pin, password);
				if(pc == 1)
				{
					//please enter new password
					p1= getData("vm-newpassword",5000);
					if(p1.isEmpty())
					{
						callstatus= "NEW PIN EMPTY";
						String empty_newpin= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
						DatabaseConn.executeUpdate(empty_newpin);
						streamFile("im-sorry");
						return;
					}
					p2= getData("vm-reenterpassword",5000);
					if(p1.equals(p2))
					{
						System.out.println("Password is changing");
						String update_pin= "UPDATE USERS SET pin= MD5("+p1+") WHERE user_id= "+user;
						DatabaseConn.executeUpdate(update_pin);
						streamFile("IVR/pin-update");
						streamFile("auth-thankyou");
						return;
					}
					else	//if the re-entered pin doesn't match with the entered pin.
					{
						callstatus= "PIN MISMATCH";
						String pin_mismatch= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
						DatabaseConn.executeUpdate(pin_mismatch);
						streamFile("im-sorry");
						return;
					}
				}
				else
				{
					callstatus= "INVALID PIN";
					String invalid_pin= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
					DatabaseConn.executeUpdate(invalid_pin);
					streamFile("vm-invalidpassword");
				}
			}
			else	//if the user do not exists and the login is invalid
			{
				callstatus= "INVALID USER";
				String invalid_user= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(invalid_user);
				streamFile("vm-incorrect");
				return;
			}
			
		}
		catch(SQLException | AgiHangupException e)
		{
			
		}
		finally
		{
			System.out.println("HANGING PIN CHANGE");
			String endtime= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
			String e_t= getFullVariable("${STRFTIME(${EPOCH},,%H:%M:%S)}");
			LocalTime et= LocalTime.parse(e_t);
			int e_sec= et.toSecondOfDay();
			int dur= e_sec - s_sec;
			String final_qry= "UPDATE USERCALL_DETAILS SET final_status= 'CALL ENDED',end_time= '"+endtime+"',call_dur= "+dur+" WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
			DatabaseConn.executeUpdate(final_qry);
			return;
		}
		
	}

}
