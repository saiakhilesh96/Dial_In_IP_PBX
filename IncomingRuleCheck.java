import java.util.ArrayList;
import org.asteriskjava.fastagi.*;
import java.sql.*;

public class IncomingRuleCheck extends BaseAgiScript implements AgiChannel {
	
	public ArrayList<Integer> u_groups= new ArrayList<>();
	public boolean time_val;
	
	@SuppressWarnings("unused")
	public void service(AgiRequest request, AgiChannel channel) throws AgiException {
		Mappings map= new Mappings();
		String reg_number,u_status,uniqueId,finalstatus,end_time,channel_name,dest_flag,usr_grps,d_id,calltime_lim,totalDuration,Datetime,WL_exempt,Rec_Permission = null,timeslot_id,q1,qq1,call_status,dst = null,rule_status,allow_status,frequency = null,cur_status,call_rec_path;
		int user_id,u_group = 0,calltime_limit = 0,total_dur = 0,device_group,device_id = 0,priority,recever_id,ts_id;
		try{
			answer();
			System.out.println("-----------------IncomingRuleCheck----------------");
			System.out.println("passed contact number is "+getFullVariable("${number}"));
			reg_number= getFullVariable("${reg_num}");
			Datetime = getFullVariable("${datetime}");
			uniqueId= getFullVariable("${UNIQUEID}");
			channel_name= getName();
			Validations val= new Validations();
			ResultSet u_data= DatabaseConn.executeQuery("SELECT * FROM USERS AS a JOIN USER_GROUP AS b WHERE (a.ug_id = b.ug_id) AND a.user_id= '"+reg_number+"'");
			if(u_data.next()){
				u_group= u_data.getInt("ug_id");	
				u_status= u_data.getString("status");
				calltime_limit= u_data.getInt("per_call_time_limit");
				total_dur= u_data.getInt("total_user_call_duration");
				frequency= u_data.getString("frequency");
				Rec_Permission= u_data.getString("recording_permission");
				calltime_lim=""+calltime_limit;
				totalDuration=""+total_dur;
				setVariable("calltime_limit", calltime_lim);
				setVariable("total_duration", totalDuration);
				setVariable("Rec_Permission", Rec_Permission);
			}
			user_id= Integer.parseInt(reg_number);
			u_groups= map.getgroups(u_group);
			usr_grps= u_groups.toString();
			usr_grps= usr_grps.substring(1, usr_grps.length()-1);
			System.out.println("GOURPS  "+u_groups);
			System.out.println("The Groups Are : "+usr_grps);
			q1= "SELECT * FROM INCOMING_EXTERNAL_CALL_RULES WHERE ((receiver_flag= '0' AND receiver_id= "+reg_number+") OR (receiver_flag= '1' AND receiver_id IN ("+usr_grps+"))) AND rule_status= '1' ORDER BY priority";
			ResultSet IncomingRule_data= DatabaseConn.executeQuery(q1);
			if(IncomingRule_data.next()){
				priority	= IncomingRule_data.getInt("priority");
				recever_id	= IncomingRule_data.getInt("receiver_id");
				device_id	= IncomingRule_data.getInt("dest_id");
				dest_flag	= IncomingRule_data.getString("dest_flag");
				timeslot_id	= IncomingRule_data.getString("ts_id");
				allow_status= IncomingRule_data.getString("allow_status");
				rule_status	= IncomingRule_data.getString("rule_status");
				System.out.println("DEVICE_id is "+device_id);
				System.out.println("Ts_id is "+timeslot_id);
				System.out.println("device Flag "+dest_flag);
				d_id= ""+device_id;
				setVariable("d_id", d_id);
				setVariable("d_flag", dest_flag);
				/*if(rule_status.equals("0")){
					System.out.println("call not allowed becoz rule_status inactive in incoming rules");
					streamFile("call_not_allowed");
					call_status= "INACTIVE RULE";
					finalstatus = "CALL ENDED";
					end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
					String rulestatus= "update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`='"+finalstatus+"' where user_id='"+reg_number+"' AND unique_id='"+uniqueId+"' AND channel='"+channel_name+"'";
					DatabaseConn.executeUpdate(rulestatus);
					setVariable("mCanCall", "0");
					return;
				}*/
				if(allow_status.equals("0"))	//if the rule defined the user not to make calls
				{
					System.out.println("call not allowed becoz status denyed in incoming rules");
					streamFile("IVR/call_not_allowed");
					call_status="CALL DENIED";
					finalstatus = "CALL ENDED";
					end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
					String userstatus= "update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`='"+finalstatus+"' where user_id='"+reg_number+"' AND unique_id='"+uniqueId+"' AND channel='"+channel_name+"'";
					DatabaseConn.executeUpdate(userstatus);
					setVariable("mCanCall", "0");
					return; 
				}
				ts_id= Integer.parseInt(timeslot_id);
				System.out.println("The passed time slot id variable to Time slot Check "+ts_id);
				System.out.println("---------Time_slot Check-----------");
				time_val= val.check_time(ts_id);
				if(time_val == false)
				{
					streamFile("IVR/invalid-timeslot");
					call_status= "INVALID TIME";
					finalstatus = "CALL ENDED";
					end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
					String invalidtime= "update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`='"+finalstatus+"' where user_id='"+reg_number+"' AND unique_id='"+uniqueId+"' AND channel='"+channel_name+"'";
					DatabaseConn.executeUpdate(invalidtime);
					setVariable("mCanCall", "0");
					return;
				}
				System.out.println("----------End Of The Incoming Rule Check------------");
				setVariable("mCanCall", "1");
				return;
		}else{
			System.out.println("There is No Rule");
			streamFile("IVR/call_not_allowed");
			call_status= "NO RULES";
			finalstatus = "CALL ENDED";
			end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
			String rulestatus= "update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`='"+finalstatus+"' where user_id='"+reg_number+"' AND unique_id='"+uniqueId+"' AND channel='"+channel_name+"'";
			DatabaseConn.executeUpdate(rulestatus);
			setVariable("mCanCall", "0");
			return;
		}
			
		}catch(AgiException | SQLException  e){
		    System.out.println("----Error----"+e);
			e.printStackTrace();
		}
	}
}
//------------------Thanks Swami......I Hope Incoming Rule Check is Done........