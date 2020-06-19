import java.util.HashMap;
import java.util.Map;
import org.asteriskjava.fastagi.*;
import java.sql.*;

public class DeviceCheck extends BaseAgiScript implements AgiChannel {
private Map<Integer,Integer> dest_map= new HashMap<>();
public boolean time_val;
public void service(AgiRequest request, AgiChannel channel) throws AgiException
{
	Mappings map= new Mappings();
	String reg_number,channel_name,limit,end_time,uniqueId,date,final_status,dest_flag,dg_id,Rec_Permission = null,qq1,call_status = null,dst = null,call_rec_path = null;
	int call_limit,device_group,device_id = 0,device_num;
	try{
		answer();
		System.out.println("-----------------DeviceCheck----------------");
		uniqueId= getFullVariable("${UNIQUEID}");
		String source= getFullVariable("${CALLERID(num)}");
		channel_name= getName();
		reg_number= getFullVariable("${reg_num}");
		date= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d)}");
		String s_time= getFullVariable("${STRFTIME(${EPOCH},,%Y-%m-%d %H:%M:%S)}");
		dg_id= getFullVariable("${d_id}");
		dest_flag= getFullVariable("${d_flag}");
		Rec_Permission= getFullVariable("${Rec_Permission}");
		System.out.println("Device Id from Device check is "+dg_id);
		System.out.println("device Flag is  "+dest_flag);
		limit= getFullVariable("${limit}");
		System.out.println("passed call limit is "+limit);
		call_limit= Integer.parseInt(limit);
		call_limit*= 1000; 
		System.out.println("call limit is "+call_limit);
		if(dest_flag.equals("1")){
			System.out.println("----------dest_group ID is-----------"+dg_id);
			ResultSet device_data= DatabaseConn.executeQuery("select sd_id from DEVICE_GROUP where sdg_id= '"+dg_id+"'");
			ResultSet device_data1= null;
			while(device_data.next())					//Device Check
			{
				device_num= device_data.getInt("sd_id");
				System.out.println("DEVICE_ID IS "+device_num);
				if(device_num != 0){
					qq1= "select call_status,dst,src from USERCALL_DETAILS where (dst='"+device_num+"' OR src='"+device_num+"') AND date(start_time) = '"+date+"' AND call_status in ('START', 'ANSWER', 'LOGIN')";
					System.out.println("Data is "+qq1);
					device_data1= DatabaseConn.executeQuery(qq1);
					if(device_data1.next()){
						dst=device_data1.getString("dst");
						System.out.println("THE DEVICE IS BUSY!!!"+call_status);
					}else{
//						final_status="CALL ENDED";
//						call_status="HANGUP";
//						end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
						System.out.println("THE DEVICE IS NOT BUSY!!!"+call_status);
						if(Rec_Permission.equals("0"))
						{
							System.out.println("RECORDING NOT REQUIRED.");
							DatabaseConn.executeUpdate("update USERCALL_DETAILS set `dst`='"+device_num+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
							exec("Dial","SIP/"+device_num+",60,L("+call_limit+":"+(call_limit/2)+":"+(call_limit/4)+":${LIMIT_WARNING_FILE})");							
							//call_status="HANGUP";
							final_status="CALL ENDED";
							end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
							DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
							return;
						}else{											//if recording is required
							System.out.println("RECORDING IS REQUIRED.");
							ResultSet Globals_data= DatabaseConn.executeQuery("SELECT var_value_assigned FROM GLOBALS WHERE var_name= 'CALL_REC_PATH'");
							if(Globals_data.next()){
								call_rec_path= Globals_data.getString(1);
								System.out.println("The path for recording calls is : "+call_rec_path);
							}
							else{
								System.out.println("RECORDING PATH NOT PROPER");
								call_status= "INTERNAL ERROR";
								DatabaseConn.executeUpdate("update USERCALL_DETAILS set `call_status`='"+call_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
							}
							String rec_file= call_rec_path+reg_number+"_"+source+"_"+s_time+"INCOMING.gsm";
							String recording= reg_number+"_"+source+"_"+s_time+"INCOMING.gsm";
							System.out.println("===IM HERE===CALLING");
							DatabaseConn.executeUpdate("update USERCALL_DETAILS set `dst`='"+device_num+"',`recorded_filename`= '"+recording+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
							exec("MixMonitor",rec_file+",W");				//Uncomment to record the call 
							exec("Dial","SIP/"+device_num+",60,L("+call_limit+":"+(call_limit/2)+":"+(call_limit/4)+":${LIMIT_WARNING_FILE})");
							final_status="CALL ENDED";
							end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
							DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
							return;						
						}
					}
				}
			}
			call_status= "BUSY";
			final_status="CALL ENDED";
			streamFile("call-fwd-on-busy");
			end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
			DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
			System.out.println("----------ALL DIVESES ARE BUSY------------");
			setVariable("mCanCall", "1");
			return;
		}
		else
		{
			System.out.println("Im inside the device_number check");
			device_id= Integer.parseInt(dg_id);
			dest_map= map.get_device_map();
			device_group= dest_map.get(device_id);
			System.out.println("device group is "+device_group);
			ResultSet device_data= DatabaseConn.executeQuery("select sd_id from DEVICE_GROUP where sdg_id= '"+device_group+"'");
			ResultSet device_data1=null;
			while(device_data.next())
			{
				device_num= device_data.getInt("sd_id");
				System.out.println("DEVICE_ID IS "+device_num);
				if(device_num != 0)
				{
					qq1= "select call_status,dst,src from USERCALL_DETAILS where (dst='"+device_num+"' OR src='"+device_num+"') AND date(start_time) = '"+date+"' AND call_status in ('START', 'ANSWER', 'LOGIN')";					
					System.out.println("Data is "+qq1);
					device_data1= DatabaseConn.executeQuery(qq1);
					if(device_data1.next())
					{
						dst=device_data1.getString("dst");
						System.out.println("THE DEVICE IS BUSY!!!"+call_status);
						System.out.println("THE DEVICE NUMBER IS!!!"+dst);
					}
					else
					{
//						final_status="CALL ENDED";
//						call_status="HANGUP";
//						end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
//						DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
							System.out.println("THE DEVICE IS NOT BUSY!!!"+call_status);
							if(Rec_Permission.equals("0"))
							{
								System.out.println("RECORDING NOT REQUIRED.");
								DatabaseConn.executeUpdate("update USERCALL_DETAILS set `dst`='"+device_num+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
								exec("Dial","SIP/"+device_num+",60,L("+call_limit+":"+(call_limit/2)+":"+(call_limit/4)+":${LIMIT_WARNING_FILE})");
//								call_status="HANGUP";
								final_status="CALL ENDED";
								end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
								DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
								return;
							}
							else	//if recording is required
							{
								System.out.println("RECORDING REQUIRED.");
								ResultSet Globals_data = DatabaseConn.executeQuery("SELECT var_value_assigned FROM GLOBALS WHERE var_name= 'CALL_REC_PATH'");
								if(Globals_data.next())
								{
									call_rec_path= Globals_data.getString(1);
									System.out.println("The path for recording calls is : "+call_rec_path);
								}
								else
								{
									System.out.println("OOPS!!! RECORDING PATH NOT PROPER");
									call_status= "INVALID REC PATH";
									DatabaseConn.executeUpdate("update USERCALL_DETAILS set `call_status`='"+call_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
								}
								System.out.println("===IM HERE===CALLING");
								String rec_file= call_rec_path+reg_number+"_"+source+"_"+s_time+"INCOMING.gsm";
								String recording= reg_number+"_"+source+"_"+s_time+"INCOMING.gsm";
								System.out.println("===IM HERE===CALLING");
								DatabaseConn.executeUpdate("update USERCALL_DETAILS set `dst`='"+device_num+"',`recorded_filename`= '"+recording+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
								exec("MixMonitor",rec_file+",W");
								exec("Dial","SIP/"+device_num+",60,L("+call_limit+":"+(call_limit/2)+":"+(call_limit/4)+":${LIMIT_WARNING_FILE})");
								final_status="CALL ENDED";
								end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
								DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
								return;
							} 
						}
					}
				}
			call_status= "BUSY";
			final_status="CALL ENDED";
			streamFile("call-fwd-on-busy");
			end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
			DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`='"+end_time+"',`call_status`='"+call_status+"',`final_status`= '"+final_status+"' where user_id='"+reg_number+"' and unique_id ='"+uniqueId+"' and channel='"+channel_name+"'");
			System.out.println("----------ALL DIVESES ARE BUSY------------");
			setVariable("mCanCall", "1");
			return;
			}
		}catch(AgiException | SQLException  e)
		{
		    System.out.println("----Error----"+e);
			e.printStackTrace();
		}
	}
}
//...........Thanks Swami..........I hope Device Check is Done...