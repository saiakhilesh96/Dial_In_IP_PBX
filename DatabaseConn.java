import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.*;  
import java.io.*;  

public class DatabaseConn 
{
	private static Connection conn;
    private static String url,user,password;
    static
    {
        if(conn == null) 
        {
                try 
                {
                	FileReader reader;
            		reader = new FileReader("src/db.properties");
            		Properties p=new Properties();  
        			p.load(reader);
        			url= p.getProperty("url");
        			user= p.getProperty("user");
        			password= p.getProperty("password");
                    //url= "jdbc:mysql://localhost/phone";
                    Class.forName("com.mysql.jdbc.Driver");
                    //conn= DriverManager.getConnection(url,"root","sairam");
                    conn= DriverManager.getConnection(url,user,password);
                }
                catch (ClassNotFoundException| IOException| SQLException ex) 
                {
                	System.out.println("logger exception");
                    Logger.getLogger(DatabaseConn.class.getName()).log(Level.SEVERE, null, ex);
                }
        }
    }
    
    public static ResultSet executeQuery(String statement)
    {
    	try 
    	{
    		PreparedStatement p_stat= conn.prepareStatement(statement);
			ResultSet res_set= p_stat.executeQuery();
			return res_set;
		}
    	catch (SQLException e) 
    	{
    		System.out.println("The exception for execute query is : "+e);
    		e.printStackTrace();
		}
		return null;
    }
    
    public static void executeUpdate(String update_query)
    {
    	try
    	{
    		PreparedStatement p_stmt= conn.prepareStatement(update_query);
    		p_stmt.executeUpdate(update_query);
    	}
    	catch (SQLException e)
    	{
    		System.out.println("The exception for update query is : "+e);
    		e.printStackTrace();
    	}
    }
}