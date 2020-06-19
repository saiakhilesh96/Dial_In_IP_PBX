/*
 * *******************************************AUM SRI SAI RAM**********************************
 */

import java.sql.*;
import java.time.LocalTime;
import org.asteriskjava.fastagi.*;
public class IntercomCalls extends BaseAgiScript
{
	private String dev,ext,channel,uniqueid,finalstatus,callstatus;
	private int d_id;

	@SuppressWarnings("finally")
	public void service(AgiRequest arg0, AgiChannel arg1) throws AgiException 
	{
		System.out.println("CLASS : Intercom");
		answer();
		channel= getName();
		uniqueid= getUniqueId();
		dev= getFullVariable("${CALLERID(num)}");
		String s_time= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
		String s_t= getFullVariable("${STRFTIME(${EPOCH},,%H:%M:%S)}");
		LocalTime st= LocalTime.parse(s_t);
		//System.out.println("st = "+st);
		int s_sec= st.toSecondOfDay();
		//System.out.println("start second is : "+s_sec);
		String int_pass_1= ""+channel+"_"+uniqueid+"_"+dev+"_"+s_time+"_"+"CALL LIVE"+"_"+"0";
		setVariable("PASS_INT1",int_pass_1);
		
//		String entry= "INSERT INTO USERCALL_DETAILS (`channel`,`unique_id`,`src`,`start_time`,`final_status`,`call_dir`) VALUES ('"+channel+"','"+uniqueid+"','"+dev+"','"+s_time+"','CALL LIVE','0')"; 
//		DatabaseConn.executeUpdate(entry);
		ext= getData("vm-enter-num-to-call",5000);
		if(ext.isEmpty())
		{
			finalstatus= "CALL ENDED";
			callstatus= "EMPTY EXTENSION";
			String ext_null= "UPDATE USERCALL_DETAILS SET final_status= '"+finalstatus+"', call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
			DatabaseConn.executeUpdate(ext_null);
			return;
		}
		String int_pass2= ext;
		setVariable("PASS_INT2", int_pass2);
//		String update_ext= "UPDATE USERCALL_DETAILS SET dst= '"+ext+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
//		DatabaseConn.executeUpdate(update_ext);
		try
		{
			String d_qry= "SELECT sd_id FROM SIP_DEVICES WHERE sd_extension= "+ext;
			ResultSet r= DatabaseConn.executeQuery(d_qry);
			if(r.next())
			{
				d_id= r.getInt(1);
				exec("Dial","SIP/"+d_id+",60");
				return;
			}
			else	//if the extension is invalid
			{
				finalstatus= "CALL ENDED";
				callstatus= "INVALID EXTENSION";
				String invalid_ext= "UPDATE USERCALL_DETAILS SET final_status= '"+finalstatus+"', call_status= '"+callstatus+"' WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
				DatabaseConn.executeUpdate(invalid_ext);
				streamFile("pbx-invalid");
				return;
			}			
		}
		catch(AgiHangupException | SQLException e)
		{
			//e.printStackTrace();
		}
		finally
		{
			System.out.println("HANGING UP INTERCOM CALLS");
			String endtime= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
			String e_t= getFullVariable("${STRFTIME(${EPOCH},,%H:%M:%S)}");
			LocalTime et= LocalTime.parse(e_t);
			//System.out.println("et = "+et);
			int e_sec= et.toSecondOfDay();
			//System.out.println("end second is : "+e_sec);
			int dur= e_sec - s_sec;
			//System.out.println("the total duration will be "+dur);
			String final_qry= "UPDATE USERCALL_DETAILS SET final_status= 'CALL ENDED',end_time= '"+endtime+"',call_dur= "+dur+" WHERE channel= '"+channel+"' AND unique_id= '"+uniqueid+"'";
			//System.out.println(final_qry);
			DatabaseConn.executeUpdate(final_qry);
			return;
		}
	}	//end of service method of Intercom class
}