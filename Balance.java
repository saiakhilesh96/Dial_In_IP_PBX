/*
 * *************************************AUM SRI SAI RAM***********************************
 */
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalTime;
import java.util.ArrayList;

import org.asteriskjava.fastagi.*;


public class Balance extends BaseAgiScript
{
	private String user,pin,bal,password,u_s,rp= "",ps= "",callstatus,finalstatus;
	private int u_id,course_id,batch,u_access,timeused,/*group,*/totaldur;
	private float balance;
	@SuppressWarnings("finally")
	public void service(AgiRequest arg0, AgiChannel arg1) throws AgiException 
	{
		answer();
		String start_time= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
		String s_t= getFullVariable("${STRFTIME(${EPOCH},,%H:%M:%S)}");
		LocalTime st= LocalTime.parse(s_t);
		int s_sec= st.toSecondOfDay();
		Validations val= new Validations();
		
		String channel= getName();
		String uniqueid= getUniqueId();
		String src= getFullVariable("${CALLERID(num)}");
		
		try
		{
			//check whether the user is a student and he is allowed to make a call in the given period.
			callstatus= "BALANCE CHECK";
			finalstatus= "CALL LIVE";
			String insertqry= "INSERT INTO USERCALL_DETAILS (`channel`,`unique_id`,`start_time`,`answer_time`,`src`,`call_dir`,`call_status`,`final_status`) VALUES ('"+channel+"','"+uniqueid+"','"+start_time+"','"+start_time+"','"+src+"','3','"+callstatus+"','"+finalstatus+"')";
			DatabaseConn.executeUpdate(insertqry);
			user= getData("IVR/user-id",5000);
			u_id= Integer.parseInt(user);
			if(user.isEmpty())
			{
				streamFile("IVR/uid_null");
				return;
			}
			String q1= "SELECT a.course_id,a.batch,a.balance,a.access_level,a.status,a.pin,a.time_used,b.total_user_call_duration FROM USERS AS a JOIN USER_GROUP AS b WHERE a.user_id= "+user+" AND (a.ug_id = b.ug_id)";
			ResultSet rs= DatabaseConn.executeQuery(q1);
			if(rs.next())	//if user-id is correct
			{
				String q2= "UPDATE USERCALL_DETAILS SET user_id= "+user+" WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(q2);
				course_id= rs.getInt(1);
				batch= rs.getInt(2);
				balance= rs.getFloat(3);
				u_access= rs.getInt(4);
				u_s= rs.getString(5);
				pin= rs.getString(6);
				timeused= rs.getInt(7);
				//group= rs.getInt(8);
				totaldur= rs.getInt(8);
			}
			else
			{
				callstatus= "INVALID USER";
				String q3= "UPDATE USERCALL_DETAILS SET user_id= "+user+",call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(q3);
				streamFile("vm-incorrect");
				return;
			}
			
			//check 2 user status check
			if(u_s.equals("0"))	//if user is found inactive
			{
				streamFile("IVR/user-inactive");
				callstatus= "INACTIVE USER";
				String inactiveuser= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(inactiveuser);
				return;
			}
			
			boolean stu= val.isAllowed(u_id, course_id, batch, u_access);
			System.out.println("the boolean is "+stu);
			if(stu == false)
			{
				callstatus= "INVALID PERIOD";
				String q4= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(q4);
				streamFile("IVR/invalid-student");
				return;
			}
			
			
			
			
			//check 3 simultaneous login check
			String sim_login_qry= "SELECT call_status FROM USERCALL_DETAILS WHERE user_id= "+u_id+" AND final_status= 'CALL LIVE'";
			System.out.println(sim_login_qry);
			ResultSet rs1= DatabaseConn.executeQuery(sim_login_qry);
			if(rs1.next() == false)
			{
				System.out.println("empty resultset");
			}
			else
			{
				System.out.println("resultset not empty");
			}
			if(rs1.next())	//if some call is active with current user i.e., user is logged in any other device
			{
				System.err.println("simulteneous login");
				streamFile("IVR/sim_login");
				callstatus= "DUPLICATE USER";
				String dup_user= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(dup_user);
				return;
			}
			password= getData("vm-password",5000);
			if(password.isEmpty())
			{
				callstatus= "EMPTY PIN";
				String emptypin= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(emptypin);
				streamFile("IVR/empty-pin");
				return;
			}
			int pc= val.check_password(pin, password);
			if(pc == 1)
			{
				bal= ""+balance;
				Validations vald= new Validations();
				ArrayList<String> b_amt= vald.get_balanceamount(bal);
				rp= b_amt.get(0);
				ps= b_amt.get(1);
				int totalsec= totaldur;
				int balancesec= totalsec - timeused;
				int usedmin= timeused / 60;
				String um= ""+usedmin;
				int usedsec= timeused % 60;
				String us= ""+usedsec;
				int balmin= balancesec / 60;
				String bm= ""+balmin;
				int balsec= balancesec % 60;
				String bs= ""+balsec;
				if(balance < 0)	//if its a negative balance
				{
					streamFile("IVR/balance");
					streamFile("minus");
					sayNumber(rp);
					streamFile("IVR/rupee");
					sayNumber(ps);
					streamFile("IVR/paise");
					streamFile("IVR/you-consumed");
					sayNumber(um);
					streamFile("minutes");
					sayNumber(us);
					streamFile("seconds");
					streamFile("IVR/time-balance");
					sayNumber(bm);
					streamFile("minutes");
					sayNumber(bs);
					streamFile("seconds");
					streamFile("auth-thankyou");
					return;
				}
				//if the balance is positive
				streamFile("IVR/balance");
				sayNumber(rp);
				streamFile("IVR/rupee");
				sayNumber(ps);
				streamFile("IVR/paise");
				streamFile("IVR/you-consumed");
				sayNumber(um);
				streamFile("minutes");
				sayNumber(us);
				streamFile("seconds");
				streamFile("IVR/time-balance");
				sayNumber(bm);
				streamFile("minutes");
				sayNumber(bs);
				streamFile("seconds");
				streamFile("auth-thankyou");
				return;
			}
			else
			{
				callstatus= "INVALID PIN";
				String invalidpin= "UPDATE USERCALL_DETAILS SET call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(invalidpin);
				streamFile("vm-invalidpassword");
				return;
			}
		}
		catch(SQLException | AgiHangupException e)
		{
			e.printStackTrace();
		}
		finally
		{
			System.out.println("FINISHED BALANCE CHECK");
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
