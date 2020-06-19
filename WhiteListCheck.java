import org.asteriskjava.fastagi.*;
import java.sql.*;
import java.util.ArrayList;

public class WhiteListCheck extends BaseAgiScript implements AgiChannel {
	public ArrayList<Integer> u_groups= new ArrayList<>();
	
	public void service(AgiRequest request, AgiChannel channel) throws AgiException {
		String start,end,reg_number,uniqueId,channel_name,source,contact_num = null,I_Prefix,WL_exemp,finalstatus,end_time,FullPhoneNumber = null,q0,q1,q2,call_status;
		int calltypeid,u_group = 0;
		try{
			answer();
			System.out.println("------------WhiteListCheck Part------------------------");
			reg_number= getFullVariable("${reg_num}");
			source	= getFullVariable("${CALLERID(num)}");
			uniqueId= getFullVariable("${UNIQUEID}");
			channel_name= getName();
			Validations val= new Validations();
			q0= "SELECT ug_id FROM USERS where user_id= '"+reg_number+"'";
			ResultSet user_group= DatabaseConn.executeQuery(q0);
			if(user_group.next()){
				u_group= user_group.getInt("ug_id");
			}
			q1= "SELECT whitelist_exemption from USER_GROUP where ug_id = '"+u_group+"'";
			ResultSet User_data= DatabaseConn.executeQuery(q1);
			if(User_data.next()){
				WL_exemp= User_data.getString("whitelist_exemption");
				System.out.println("WL is " + WL_exemp);
				if(WL_exemp.equals("0")){			//This means NO exempt from W_L, So we have to check.
					q1= "select phone_number,calltype_id,start_date,end_date from WHITELIST_CONTACTS where user_id='"+reg_number+"'";
					ResultSet contact_data= DatabaseConn.executeQuery(q1);
					ResultSet contact_data1= null;
					while(contact_data.next())
					{
						contact_num= contact_data.getString("phone_number");
						calltypeid= contact_data.getInt("calltype_id");
						start= contact_data.getString("start_date");
						end= contact_data.getString("end_date");
						System.out.println(""+start);
						System.out.println(""+end);
						
						System.out.println("Contact number is " +contact_num);
						System.out.println("Contact number is " +calltypeid);
						if(source.contains(contact_num))
						{
							System.out.println("The original Contact number is " +contact_num);
							q2= "select i_prefix from CALL_TYPE where calltype_id= '"+calltypeid+"'";
							contact_data1= DatabaseConn.executeQuery(q2);
							if (!contact_data1.next()){
								System.err.println("ERROR: No Match of I_Prefix");
								streamFile("IVR/i-prefix");
								call_status="INVALID I-PREFIX";
								finalstatus= "CALL ENDED";
								end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
								String invalid_prefix= "update USERCALL_DETAILS set `call_status`='"+call_status+"',`final_status`= '"+finalstatus+"',`end_time`= '"+end_time+"' where user_id ='"+reg_number+"' AND channel='"+channel_name+"' AND unique_id= '"+uniqueId+"'";
								DatabaseConn.executeUpdate(invalid_prefix);
								setVariable("mCanCall", "0");
								return;
							}
							I_Prefix= contact_data1.getString("i_prefix");
							System.out.println("ACCECPTED!!! PHONE number " +contact_num);
							System.out.println("ACCECPTED!!! CallType is " +calltypeid); 
							System.out.println("ACCECPTED!!! I_Prefix is " +I_Prefix);
							FullPhoneNumber= I_Prefix+contact_num;
							System.out.println("The FULL Phone Number is " +FullPhoneNumber);
							if(FullPhoneNumber.equals(source)){
								System.out.println("----------PHONE NUMBER MATCHED---------- ");
								boolean wl= val.check_wl_validity(start,end);
								if(wl == false)
								{
									end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
									call_status ="INVALID WHITELIST PERIOD";
									finalstatus= "CALL ENDED";
									String invalid_wl_period= "UPDATE USERCALL_DETAILS SET `call_status`= '"+call_status+"',`final_status`= '"+finalstatus+"',`end_time`= '"+end_time+"' WHERE channel= '"+channel_name+"' AND unique_id= '"+uniqueId+"' and user_id = '"+reg_number+"'";
									System.out.println(invalid_wl_period);
									DatabaseConn.executeUpdate(invalid_wl_period);
									streamFile("IVR/invalid-wl-period");
									setVariable("mCanCall", "0");
									return;
								}
								setVariable("mCanCall", "1");
								return;
							}
							
							break;
						}
					}
					streamFile("IVR/invalid-phonenumber");
					finalstatus= "CALL ENDED";
					call_status="WHITELIST VIOLATION";
					end_time= getFullVariable("${STRFTIME(${EPOCH},, %Y-%m-%d %H:%M:%S)}");
					DatabaseConn.executeUpdate("update USERCALL_DETAILS set `end_time`= '"+end_time+"',`call_status`='"+call_status+"',`final_status`= '"+finalstatus+"' where user_id='"+reg_number+"' AND unique_id='"+uniqueId+"' AND channel='"+channel_name+"'");
					setVariable("mCanCall", "0");
					return;
				}else{
					System.out.println("---IM Inside WhiteList Exemption--- and it will return 1 and go to incoming rule check");
					setVariable("mCanCall", "1");
					return;
				}
			}
		}catch(AgiException | SQLException  e){
		    //System.out.println("--Error--"+e);
//			e.printStackTrace();
		}
	}
}	
//------------Thanks Swami.........I hope Validating Contact Number is done----------