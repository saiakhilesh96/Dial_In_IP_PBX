/*
 * ************************AUM SRI SAI RAM********************************
 */

import org.asteriskjava.fastagi.*;
import com.mysql.jdbc.StringUtils;
//import java.sql.*;
public class CustomerService extends BaseAgiScript
{
	//private String bal_ext,pin_ext;
	public void service(AgiRequest request, AgiChannel channel) throws AgiException 
	{
		System.out.println("Class : CustomerService");
		answer();
		System.out.println("device from which the call is happening is: "+this.getFullVariable("${CALLERID(num)}"));
		
		try 
		{
//			ResultSet r1= DatabaseConn.executeQuery("SELECT var_value_assigned FROM GLOBALS WHERE var_name= 'EXT_BAL'");
//			if(r1.next())
//				bal_ext= r1.getString(1);
//			else
//				return;
//			
//			ResultSet r2= DatabaseConn.executeQuery("SELECT var_value_assigned FROM GLOBALS WHERE var_name='EXT_PIN'");
//			if(r2.next())
//				pin_ext= r2.getString(1);
//			
//			streamFile("vm-press");
//			sayAlpha(bal_ext);
//			streamFile("IVR/bal_check");
//			sayAlpha(pin_ext);
			String selected_service= getData("IVR/menu", 5000);
			if(StringUtils.isNullOrEmpty(selected_service))
				return;
			else
				if(selected_service.equals("1"))
					new Balance().service(request, channel);
				else if(selected_service.equals("2"))
					new NewPassword().service(request, channel);
		}
		catch (AgiHangupException e) 
		{
			e.printStackTrace();
		}
	}
}