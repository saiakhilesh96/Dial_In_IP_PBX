/*
 *************************************AUM SRI SAI RAM*******************************
 */

import java.io.FileReader;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;
import java.util.Properties;

import org.asteriskjava.manager.AuthenticationFailedException;
import org.asteriskjava.manager.ManagerConnection;
import org.asteriskjava.manager.ManagerConnectionFactory;
import org.asteriskjava.manager.ManagerEventListener;
import org.asteriskjava.manager.TimeoutException;
import org.asteriskjava.manager.event.HangupEvent;
import org.asteriskjava.manager.event.ManagerEvent;
import org.asteriskjava.manager.event.VarSetEvent;

import com.mysql.jdbc.StringUtils;

public class IntercomEvents implements ManagerEventListener
{
    private ManagerConnection managerConnection;
    public static String var;
    public static String d_val;
    public static String unique_id,varset1,varset2,varset3;
    public String chan_name,chan_state;
	private Map<String,IntercomPulsar> intercom_map= new HashMap<>();
	private String host,manager,secret;
    public IntercomEvents()
    {
    	FileReader int_file_reader;
		try 
		{
			int_file_reader = new FileReader("manager.properties");
			Properties p=new Properties();  
			p.load(int_file_reader);
			host= p.getProperty("host");
			manager= p.getProperty("manager");
			secret= p.getProperty("secret");
		} 
		catch (IOException e) 
		{
			e.printStackTrace();
		}
		

//        ManagerConnectionFactory factory = new ManagerConnectionFactory("localhost", "manager", "sairam");
        ManagerConnectionFactory factory = new ManagerConnectionFactory(host,manager,secret);
        this.managerConnection = factory.createManagerConnection();
    }

    public void run() throws IOException, AuthenticationFailedException,TimeoutException, InterruptedException
    {
        // register for events
        managerConnection.addEventListener(this);

        // connect to Asterisk and log in
        managerConnection.login();
        while(true)
        {
        	// request channel state
            //managerConnection.sendAction(new StatusAction());
            // wait 10 seconds for events to come in
            Thread.sleep(10000000);
        }
        // and finally log off and disconnect
        //managerConnection.logoff();
    }

	public void onManagerEvent(ManagerEvent event)
    {
        //Just print received events
        //System.out.println("Event is : "+event);
    	if(event instanceof VarSetEvent)
    	{
    		VarSetEvent vse= (VarSetEvent)event;
    		var= vse.getVariable();
    		//System.out.println("variable  : "+var+" VALUE : "+vse.getValue());
    		if(var.equals("PASS_INT1"))	//For the first time to create a record in the USERCALL_DETAILS
    		{
    			System.out.println("PASS_INT1 has been set");
    			varset1= vse.getValue();
    			IntercomPulsar p= new IntercomPulsar(varset1);
    			p.insert_pass1();
    			chan_name= p.get_channel();
    			intercom_map.put(chan_name, p);
    		}
    		if(var.equals("PASS_INT2"))	//To update the status of an already inserted record
    		{
    			varset2= vse.getValue();
    			intercom_map.get(vse.getChannel()).update_pass2(varset2);
    		}
    		if(var.equals("DIALSTATUS"))
    		{
    			d_val= vse.getValue();
				System.out.println(""+vse.getVariable() + ":" + d_val+":"+vse.getUniqueId());

    			if(StringUtils.isNullOrEmpty(vse.getValue()))
    			{
    				System.out.println(""+vse.getVariable() + ":" + d_val+":"+vse.getUniqueId());
    			}
    			else
    			{
    				d_val= vse.getValue();
    				if(d_val.equals("ANSWER"))
    				{
        				System.out.println(""+vse.getVariable() + ":" + d_val+":"+vse.getUniqueId());
    					chan_state= intercom_map.get(vse.getChannel()).get_call_status();
    					System.out.println("state derived from the pulsar"+chan_state);
    					if(chan_state.equals("ANSWER"))	//this us the second answer which actually means the call is over.
    					{
    						System.out.println("End of call .. call Hangs up");
    						//intercom_map.get(vse.getChannel()).answeredhangup();
    						String chan= vse.getChannel();
    						IntercomPulsar ip= intercom_map.get(chan);
    						ip.answeredhangup();
    						intercom_map.remove(chan);
    						System.out.println("size of the hash map is : "+intercom_map.size());
    					}
    					else
    					{
    						//the first answer has happened i.e., the other party has picked the call up
    						System.out.println("PICKUP HAS HAPPENED.");
    						System.out.println("DIAL STATUS : "+d_val);
    						IntercomPulsar p= intercom_map.get(vse.getChannel());
    						p.pickup();
//    						Thread t= new Thread(p);
//    						t.start();
    					}
    				}
    				else	//this means that the call has failed.
    				{
    					System.out.println("Dial status : "+vse.getValue());
    					String chan= vse.getChannel();
    					IntercomPulsar p= intercom_map.get(chan);
    					p.failedhangup(vse.getValue());
    					intercom_map.remove(chan);
    					System.out.println("size of the hash map is : "+intercom_map.size());
    					//p.call_status= vse.getValue();
    				}
    			}
    		}
    		
//    		if(var.equals("ANSWEREDTIME"))
//    		{
//    			if(StringUtils.isNullOrEmpty(vse.getValue()))
//    			{
//    				System.out.println(""+vse.getVariable() + ":" + d_val+":"+vse.getUniqueId());
//    			}
//    			else
//    			{
//    				System.out.println("End of call .. call Hangs up");
//    				System.out.println(""+vse.getVariable() + ":" + d_val+":"+vse.getUniqueId());
//    				IntercomPulsar p= intercom_map.get(vse.getChannel());
//					p.hangup(vse.getValue());
//					String chan= vse.getChannel();
//					intercom_map.remove(chan);
//					System.out.println("size of the hash map is : "+intercom_map.size());
//    			}
//    		}
    	}
    	if(event instanceof HangupEvent)
    	{
    		HangupEvent he= (HangupEvent) event;
    		System.out.println("The cause of intercom hang"+he.getCauseTxt());
    		String chan= he.getChannel();
			intercom_map.remove(chan);
			System.out.println("size of the hash map is : "+intercom_map.size());
    	}
    }
    public static void main(String[] args) throws Exception
    {
        IntercomEvents IntercomEvents;
        IntercomEvents = new IntercomEvents();
        IntercomEvents.run();
    }
}
