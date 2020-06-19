/*
 ***********************************************AUM SRI SAI RAM*****************************************************************
 */
import java.sql.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class Mappings 
{
	private Map<Integer,Integer> d_map= new HashMap<>(); 
	private Map<Integer,Integer> u_map= new HashMap<>(); 
	private Map<Integer,String> w_l_map= new HashMap<>();
	public ArrayList<Integer> u_grp= new ArrayList<>();
	
	public Mappings()
	{
	}
	
	public void set_device_map()
	{
		//System.out.println("CLASS: Mappings_______________METHOD: set_device_map");
		try
		{
			ResultSet rs = DatabaseConn.executeQuery("SELECT * FROM DEVICE_GROUP");
			if(rs == null)
			{
				return;
			}
			while(rs.next())	//retrieve the sip_device and its respective group from DEVICE_GROUP
			{
				int sdg_id = rs.getInt("sdg_id");
				int sd_id = rs.getInt("sd_id");
				d_map.put(sd_id, sdg_id);
			}
		}
				
		catch (SQLException e)
		{
			e.printStackTrace();
		}
	}
	
	public Map<Integer, Integer> get_device_map()
	{
		this.set_device_map();
		return d_map;
	}
	
	public void set_user_map()
	{
		try
		{
			ResultSet rs = DatabaseConn.executeQuery("SELECT ug_id,pg_id FROM USER_GROUP");
			if(rs == null)
			{
				return;
			}
			while(rs.next())	
			{
				int ug_id = rs.getInt("ug_id");
				int pg_id = rs.getInt("pg_id");
				u_map.put(ug_id, pg_id);
			}
		}
				
		catch (SQLException e)
		{
			e.printStackTrace();
		}
	}
	
	public ArrayList<Integer> getgroups(int u)
	{
		this.set_user_map();
		u_grp.add(u);
		int p= u_map.get(u);
		if(p == u)
		{
			return u_grp;
		}
		return getgroups(p);
	}	
	
	public void  set_whitelist()
	{
		try
		{
			ResultSet rs = DatabaseConn.executeQuery("SELECT ug_id,whitelist_exemption FROM USER_GROUP");
			if(rs == null)
			{
				System.err.println("The USER_GROUP is empty");
				return;
			}
			while(rs.next())	
			{
				int ug_id = rs.getInt("ug_id");
				String wl_id = rs.getString("whitelist_exemption");
				w_l_map.put(ug_id, wl_id);
			}
		}
				
		catch (SQLException e)
		{
			//System.out.println("sql or class exception: ");
			e.printStackTrace();
		
		}
	}
	
	public int get_whitelist_status(int u_grp)
	{
		this.set_whitelist();
		String v= w_l_map.get(u_grp);
		int val= Integer.parseInt(v);
		return val;
	}
}