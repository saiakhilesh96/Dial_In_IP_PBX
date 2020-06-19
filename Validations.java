/*
 ***************************************************AUM SRI SAI RAM*******************************************
 */
import java.sql.*;
import java.time.LocalDate;
import java.time.LocalTime;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;


public class Validations
{
	public Validations()
	{
	}
	
	public boolean isStudent(int user_id,int course_id,int batch)
	{
		int dur,sd,sm,sy,ed,em,ey;
		String s,e;
//		System.out.println("isStudent");
		try 
		{
//			System.out.println("SELECT course_duration from COURSE WHERE course_id= "+course_id);
			ResultSet r= DatabaseConn.executeQuery("SELECT * FROM COURSE WHERE course_id= "+course_id);
			if(r.next())
			{
				dur= r.getInt("course_duration");
//				System.out.println("SELECT * FROM ACADEMIC_DURATION");
				ResultSet rs= DatabaseConn.executeQuery("SELECT * FROM ACADEMIC_DURATION");
				if(rs.next())
				{
					sd= rs.getInt("start_day");
//					System.out.println(sd);
					sm= rs.getInt("start_month");
					ed= rs.getInt("end_day");
					em= rs.getInt("end_month");
					sy= batch;
					ey= batch + dur;
					if(sm < 10)
						s= ""+sy+"-0"+sm+"-"+sd;
					else
						s= ""+sy+"-"+sm+"-"+sd;
						
					if(em < 10)
						e= ""+ey+"-0"+em+"-"+ed;
					else
						e= ""+ey+"-"+em+"-"+ed;
//					System.out.println(s+"/////"+e);
					LocalDate startDate = LocalDate.parse(s);
			        LocalDate endDate = LocalDate.parse(e);
			        LocalDate curDate= LocalDate.now();
//			        System.out.println(startDate+"___"+endDate+"___"+curDate);
			        if((curDate.equals(startDate) || curDate.isAfter(startDate)) && (curDate.equals(endDate) || curDate.isBefore(endDate)))
					{
			        	return true;
					}
			        else
			        {
			        	return false;
			        }
				}
				return false;
			}
			return false;
		}
		catch (SQLException ex) 
		{
			ex.printStackTrace();
		}
		return false;
	}
	
	public boolean cancall() //checks for  vacation period or not
	{
//		System.out.println("canCall");
		int sd,sm,sy,ed,em,ey;
		String s,e;
		LocalDate curDate= LocalDate.now();
		int month= curDate.getMonthValue();
		int year= curDate.getYear();
		try
		{
			ResultSet rs= DatabaseConn.executeQuery("SELECT * FROM ACADEMIC_DURATION");
			if(rs.next())
			{
				sd= rs.getInt(1);
				sm= rs.getInt(2);
				ed= rs.getInt(3);
				em= rs.getInt(4);
				if(month >= 5)	//if its between May and December
				{
					sy= year;
					ey= year + 1;
					if(sm < 10)
						s= ""+sy+"-0"+sm+"-"+sd;
					else
						s= ""+sy+"-"+sm+"-"+sd;
						
					if(em < 10)
						e= ""+ey+"-0"+em+"-"+ed;
					else
						e= ""+ey+"-"+em+"-"+ed;
					LocalDate startDate = LocalDate.parse(s);
			        LocalDate endDate = LocalDate.parse(e);
//			        System.out.println("startDate "+startDate);
//			        System.out.println("curDate "+curDate);
//			        System.out.println("endDate "+endDate);
			        if((curDate.equals(startDate) || curDate.isAfter(startDate)) && (curDate.equals(endDate) || curDate.isBefore(endDate)))
			        	return true;
			        else
			        	return false;
				}
				else	//if its between January and April
				{
					sy= year - 1;
					ey= year;
					if(sm < 10)
						s= ""+sy+"-0"+sm+"-"+sd;
					else
						s= ""+sy+"-"+sm+"-"+sd;
						
					if(em < 10)
						e= ""+ey+"-0"+em+"-"+ed;
					else
						e= ""+ey+"-"+em+"-"+ed;
					LocalDate startDate = LocalDate.parse(s);
			        LocalDate endDate = LocalDate.parse(e);
//			        System.out.println("startDate "+startDate);
//			        System.out.println("curDate "+curDate);
//			        System.out.println("endDate "+endDate);
			        if((curDate.equals(startDate) || curDate.isAfter(startDate)) && (curDate.equals(endDate) || curDate.isBefore(endDate)))
			        	return true;
			        else
			        	return false;
				}
			}
		}
		catch(SQLException ex)
		{
			ex.printStackTrace();
		}
		return false;
	}
	
	public boolean isAllowed(int user_id,int course_id,int batch,int access)
	{
//		System.out.println("isAllowed");
		if(access != 3)
			return true;
		boolean x= isStudent(user_id, course_id, batch);
//		System.out.println("isStudent "+x);
		if(x)
		{
			boolean y= cancall();
			if(y)
			{
				return true;
			}
			return false;
		}
		return false;
	}
	
	
	public static String getMD5(String input,int len) 
	{
        try 
        {
            MessageDigest md = MessageDigest.getInstance("MD5");
            byte[] messageDigest = md.digest(input.getBytes());
            BigInteger number = new BigInteger(1, messageDigest);
            String hashtext = number.toString(16);
            // Now we need to zero pad it if you actually want the full 32 chars.
            while (hashtext.length() < len) 
            {
                hashtext = "0" + hashtext;
            }
            return hashtext;
        }
        catch (NoSuchAlgorithmException e) 
        {
            throw new RuntimeException(e);
        }
    }
	//method to check the password entered by a user
	public int check_password(String pin,String password)
	{
	   int l=pin.length();
	   String e_pin= getMD5(password, l);
	   if(pin.equals(e_pin))
	   {
		   return 1;
	   }
	   else
	   {
		   return -1;
	   }
	}	//THANK YOU SWAMI
	
	//method used to check the balance of a user...This method checks if the current balance is greater than the credit limit or not
	public boolean check_balance(float bal,int c_l)
	{
		if(bal > c_l)
			return true;
		else
			return false;
	}
	
	public int get_duration(int total_dur,int usedtime)
	{
		int dur= total_dur - usedtime;
		if(dur > 0)
			return dur;
		return 0;
	}
	
	public boolean check_time(int t_id) 
	{
		try
		{
			String t_flag= null;
			boolean t_status;
			ResultSet rs = DatabaseConn.executeQuery("SELECT * FROM TIME_SLOT WHERE ts_id="+t_id);
			if(rs.next())
			{
				t_flag = rs.getString("ts_flag");
				if(t_flag.equals("0"))
				{
					t_status= this.check_non_recurring_ts(t_id);
					return t_status;
				}
				else
				{
					t_status= this.check_recurring_ts(t_id);
					return t_status;
				}
			}
			else
			{
				return false;
			}
		}
		catch (SQLException e)
		{
			e.printStackTrace();
		}
		return false;
	}
	
	public boolean check_non_recurring_ts(int t_id)
	{
		try
		{
			String c_min,c_hr,c_dt,c_mon;
			DateTimeFormatter format = DateTimeFormatter.ofPattern("HH:mm");
			LocalDateTime cur_date= LocalDateTime.now();
			int dt= cur_date.getDayOfMonth();
			if(dt < 10)
				c_dt= "0"+dt;
			else
				c_dt= ""+dt;
			int mt= cur_date.getMonthValue();
			if(mt < 10)
				c_mon= "0"+mt;
			else
				c_mon= ""+mt;
			int hr= cur_date.getHour();
			if(hr < 10)
				c_hr= "0" + hr;
			else
				c_hr= "" + hr;
			int min= cur_date.getMinute();
			if(min < 10)
				c_min= "0"+min;
			else
				c_min= ""+min;
			String c_t= ""+c_hr+":"+c_min;
			int year= cur_date.getYear();
			String c_d= ""+year+"-"+c_mon+"-"+c_dt;
			ResultSet rs = DatabaseConn.executeQuery("SELECT * FROM NONRECURRING_TS WHERE ts_id= "+t_id);
			if(rs.next())
			{
				String s_d= rs.getString("start_date");
				String s_t= rs.getString("start_time");
				s_t= s_t.substring(0, s_t.length()-3);
				String e_d= rs.getString("end_date");
				String e_t= rs.getString("end_time");
				e_t= e_t.substring(0, e_t.length()-3);
				LocalTime startTime = LocalTime.parse(s_t, format);
		        LocalTime endTime = LocalTime.parse(e_t, format);
		        LocalTime curTime = LocalTime.parse(c_t, format);
		        LocalDate startDate= LocalDate.parse(s_d);
		        LocalDate endDate= LocalDate.parse(e_d);
		        LocalDate curDate= LocalDate.parse(c_d);
		        if((curDate.equals(startDate) || curDate.isAfter(startDate)) && (curDate.equals(endDate) || curDate.isBefore(endDate)))
				{
					boolean t_val= this.check_timings(startTime, endTime, curTime);
			        if(t_val)
			        {
			        	return true;
			        }
			        else
			        {
			        	return false;
			        }
				}
		        else
		        {
		        	return false;
		        }
			}
			else	//if the result set is empty
				return false;
		}
		catch (SQLException e)
		{
			e.printStackTrace();
		}
		return false;
	}

	public boolean check_recurring_ts(int t_id)
	{
		boolean ret_val= false;
		try
		{
			boolean t_val;
			String c_min,c_hr;
			DateTimeFormatter format = DateTimeFormatter.ofPattern("HH:mm");
			LocalDateTime cur_date= LocalDateTime.now();
			int d= cur_date.getDayOfWeek().getValue();
			if(d == 7)
				d= 0;
			String cur_day = ""+ d;
			int hr= cur_date.getHour();
			if(hr < 10)
				c_hr= "0" + hr;
			else
				c_hr= "" + hr;
			int min= cur_date.getMinute();
			if(min < 10)
				c_min= "0"+min;
			else
				c_min= ""+min;
			String c_t= ""+c_hr+":"+c_min;
			ResultSet rs = DatabaseConn.executeQuery("SELECT * FROM RECURRING_TS WHERE ts_id= "+t_id+" AND weekday= '"+cur_day+"'");
			if(rs == null) //There are no recurring time slots
			{
				return false;
			}
			while(rs.next())
			{
				String s_t= rs.getString("start_time");
				s_t= s_t.substring(0, s_t.length()-3);
				String e_t= rs.getString("end_time");
				e_t= e_t.substring(0, e_t.length()-3);
				LocalTime startTime = LocalTime.parse(s_t, format);
		        LocalTime endTime = LocalTime.parse(e_t, format);
		        LocalTime curTime = LocalTime.parse(c_t, format);
		        t_val= this.check_timings(startTime, endTime, curTime);
		        if(t_val)
		        {
		        	ret_val= true;
		        	return ret_val;
		        }
		        else
		        {
		        	ret_val= false;
		        }
			}
			return ret_val;
		}
		catch (SQLException e)
		{
			e.printStackTrace();
		}
		return false;
	}
	
	public boolean check_timings(LocalTime startTime,LocalTime endTime,LocalTime curTime)
	{
		if (curTime.isBefore(endTime) && curTime.isAfter(startTime)) 
		{
	        return true;
	    }
	    else 
	    {
	        return false;
	    }
	}

	public ArrayList<String> get_balanceamount(String bal)
	{
		ArrayList<String> b_m=new ArrayList<>();
		char[] bnc= bal.toCharArray();
		int flag= 0;
		String rp= "",ps= "";
		for(char c : bnc)
		{
			if(flag == 0)
			{
				if(c == '.')
					flag= 1;
				else
				{
					if(c == '-')
						flag= 1;
					else
					{
						flag= 0;
						rp+= c;	
					}
				}
			}
			else
				ps+= c;
		}
		b_m.add(rp);
		b_m.add(ps);
		return b_m;
	}
	public ArrayList<Float> check_plan(int planid,int calltype) 
	{
		ArrayList<Float> r_l= new ArrayList<>();
		try
		{
			LocalDate curDate= LocalDate.now();
			ResultSet rs = DatabaseConn.executeQuery("SELECT * FROM PLAN WHERE plan_id= "+planid+" AND calltype_id= "+calltype);
			while(rs.next())
			{
				float charge= rs.getFloat("charge_paise");
				int d= rs.getInt("duration_sec");
				float dur= (float) d;
				String s_d = rs.getString("start_date");
				String e_d = rs.getString("end_date");
				LocalDate startDate = LocalDate.parse(s_d);
		        LocalDate endDate = LocalDate.parse(e_d);
		        if((curDate.equals(startDate) || curDate.isAfter(startDate)) && (curDate.equals(endDate) || curDate.isBefore(endDate)))
				{
					r_l.add(charge);
					r_l.add(dur);
					return r_l;
				}
			}
			return null;
		}
		catch (SQLException e)
		{
			e.printStackTrace();
		}
		return null;
	}
	public long getmin(long possible_dur, long avail_dur, long total_dur) 
	{
		ArrayList<Long> l= new ArrayList<>();
		l.add(possible_dur);
		l.add(avail_dur);
		l.add(total_dur);
		long min= l.get(0);
		for(int i= 0;i<= 2 ;i++)
        {
                if(min > l.get(i))
                        min= l.get(i);
        }
		return min;
	}
	
	public String get_rec_path()
	{
		String rec_path= null;
		try
		{
			ResultSet rs5= DatabaseConn.executeQuery("SELECT var_value_assigned FROM GLOBALS WHERE var_name= 'CALL_REC_PATH'");
			if(rs5.next())
			{
					rec_path= rs5.getString(1);
					return rec_path;
			}
		}
		catch(SQLException e)
		{
			e.printStackTrace();
		}
		return rec_path;
	}
	
	public String get_gateway()
	{
		String gd= null;
		try
		{
			ResultSet rs4= DatabaseConn.executeQuery("SELECT var_value_assigned  FROM GLOBALS WHERE var_name= 'GD_ID'");
			if(rs4.next())
			{
				gd= rs4.getString(1);
				return gd;
			}
		}
		catch(SQLException e)
		{
			e.printStackTrace();
		}
		return gd;
	}

	public boolean is_dev_available(int devid)
	{
		LocalDate dt= LocalDate.now();
		try
		{
			String chk_qry= "SELECT final_status FROM USERCALL_DETAILS WHERE start_time like '"+dt+"%' AND (src= '"+devid+"' OR dst= '"+devid+"') AND final_status= 'CALL LIVE'";
			ResultSet r= DatabaseConn.executeQuery(chk_qry);
			if(r.next())
			{
				return false;
			}
			return true;
		}
		catch(SQLException e)
		{
			
		}
		return false;
		
	}

	public boolean check_wl_validity(String start, String end) 
	{
		LocalDate curDate= LocalDate.now();
		LocalDate startDate= LocalDate.parse(start);
		LocalDate endDate= LocalDate.parse(end);
		if((curDate.equals(startDate) || curDate.isAfter(startDate)) && (curDate.equals(endDate) || curDate.isBefore(endDate)))
        	return true;
		return false;
	}
	
	
}