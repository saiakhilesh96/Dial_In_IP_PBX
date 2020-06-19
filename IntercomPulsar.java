/*
 * *******************************AUM SRI SAI RAM********************************
 * project - DialIn
 * class - IntercomPulsar
 */

import java.time.LocalDateTime;
import java.time.LocalTime;

public class IntercomPulsar
{
	public String var_1,var_2;
	public String channel,unique_id,call_status,src,dst,call_dir,start_time,answer_time,end_time,final_status;
	public int user_id,call_dur= 0,dur_sec,time_used;
	public float chg_paise,user_balance,cost_rupee;
	public boolean runner= true;
	public int bill_sec= 0;
	public float call_cost= 0;
	public IntercomPulsar()
	{
	}
	
	public IntercomPulsar(String x)
	{
		this.var_1= x;
		String[] p1= var_1.split("_");
		this.channel= p1[0];
		this.unique_id= p1[1];
		this.src= p1[2];
		this.start_time= p1[3];
		this.final_status= p1[4];
		this.call_dir= p1[5];
		this.call_status= "INTERCOM";
	}
	
	public void insert_pass1()	//METHOD FOR CREATING THE RECORD FOR THE CALL FOR THE FIRST TIME IN THE USERCALL_DETAILS
	{
		String entry= "INSERT INTO USERCALL_DETAILS (`channel`,`unique_id`,`src`,`start_time`,`final_status`,`call_dir`,`call_status`) VALUES ('"+channel+"','"+unique_id+"','"+src+"','"+start_time+"','"+final_status+"','"+call_dir+"','"+call_status+"')"; 
		System.out.println(entry);
		DatabaseConn.executeUpdate(entry);
	}
	
	public void update_pass2(String extension)	//once the user dials the extension the record gets updated
	{
		this.dst= extension;
		String update_ext= "UPDATE USERCALL_DETAILS SET dst= '"+dst+"' WHERE channel= '"+channel+"' AND unique_id= '"+unique_id+"'";
		System.out.println(update_ext);
		DatabaseConn.executeUpdate(update_ext);
	}
	
	
	public String get_channel()
	{
		return channel;
	}
	
	public String get_call_status()
	{
		return call_status;
	}
    public void pickup()	//once the other party picks up the call
    {
    	this.call_status= "ANSWER";
    	this.final_status= "CALL LIVE";
    	LocalDateTime ans= LocalDateTime.now();
    	this.answer_time= ""+ans;
    	String pickup_qry= "UPDATE USERCALL_DETAILS SET call_status= '"+call_status+"', answer_time= '"+ans+"',final_status= '"+final_status+"' WHERE unique_id= '"+unique_id+"' AND channel= '"+channel+"'";
		System.out.println(pickup_qry);
    	DatabaseConn.executeUpdate(pickup_qry);
    }
//    public void run() 
//	{
//		while(runner)
//		{
//			try //this is where the counting of the seconds happens
//			{
//				bill_sec+= 1;
//				DatabaseConn.executeUpdate("UPDATE USERCALL_DETAILS SET bill_sec= "+bill_sec+" WHERE unique_id= '"+unique_id+"' AND channel= '"+channel+"' AND user_id= "+user_id);
//				Thread.sleep(1000);
//			}
//			catch (InterruptedException e) 
//			{
//				System.out.println("Exception "+e);
//				e.printStackTrace();
//			}
//		}
//	}
    
    public void failedhangup(String ds)	//in case the call failed for any technical reason
    {
    	call_status= ds;
    	final_status= "CALL ENDED";
    	String failed_hangup_qry= "UPDATE USERCALL_DETAILS SET call_status= '"+call_status+"' WHERE channel= '"+channel+"' AND unique_id= '"+unique_id+"'";
    	System.out.println(failed_hangup_qry);
    	DatabaseConn.executeUpdate(failed_hangup_qry);
    }
    
    public void answeredhangup()	//the call successfully went through and the call has been ended now
    {
    	System.out.println("Hang up for an answered call");
    	call_status= "CALL ANSWERED";
    	final_status= "CALL ENDED";
    	LocalDateTime enddatetime= LocalDateTime.now();
    	this.end_time= ""+enddatetime;
    	System.out.println("end datetime is "+end_time);
    	String[] se= end_time.split("T");
    	LocalTime end= LocalTime.parse(se[1]);
    	System.out.println("end time "+end);
    	
    	String[] answertime= answer_time.split("T");
    	LocalTime anstime= LocalTime.parse(answertime[1]);
    	System.out.println("ans time "+anstime);

    	int anssec= anstime.toSecondOfDay();
    	int endsec= end.toSecondOfDay();
    	bill_sec= endsec - anssec;
    	bill_sec+= 1;
    	System.out.println(bill_sec);
    	
    	String end_qry= "UPDATE USERCALL_DETAILS SET call_status= '"+call_status+"',end_time= '"+end_time+"',bill_sec= "+bill_sec+",final_status= '"+final_status+"' WHERE unique_id= '"+unique_id+"' AND channel= '"+channel+"'";
		System.out.println(end_qry);
		DatabaseConn.executeUpdate(end_qry);
    }
}
