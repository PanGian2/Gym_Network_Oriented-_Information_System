package net.codejava.ws;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.List;

public class UserDAO {
	
	private static UserDAO instance;

	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private UserDAO() {
		
	}
	
	public static UserDAO getInstance() {
		if (instance==null) {
			instance = new UserDAO();
			System.out.println("UserDAO instance created");	
		}
		return instance;
	}
	
	//Create connection with db
	public static Connection createConnection() throws Exception {
        Connection con = null;
        try {
            Class.forName(dbClass);
            con = DriverManager.getConnection(dbUrl, dbUser, dbPwd);
            System.out.println("Connection successful");
            return con;
        } catch (Exception e) {
        	System.out.println(e);
            throw e;
        } finally {
           return con;
        }
    }
	
	// Method that returns all users
	public List<User> listUsers() {
	 Connection dbConn=null;
	ArrayList<User> listOfUsers = new ArrayList<>();
	try {
		 dbConn=createConnection();
		 System.out.println("Created connection");
		 System.out.println(dbConn);
		 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM user");
		 ResultSet rs = ps.executeQuery();
		 System.out.println(rs);
		 int i=0;
		 while(rs.next()) {
			 i++;
			 User user = new User(rs.getString("userId"), rs.getString("username"), rs.getString("password"), rs.getString("email"), rs.getString("name"), 
					 rs.getString("last_name"), rs.getString("country"), rs.getString("city"), rs.getString("address"), rs.getInt("cancellations"), rs.getString("type"), rs.getString("status"), rs.getString("cancellation_end_day"));
			 listOfUsers.add(user);
		 }
	 } catch(Exception e) {
		 e.printStackTrace();
		 }
	 return listOfUsers;
	}
	
	//Method that returns a user based on the given id
	public User findUser(String id) {
		Connection dbConn=null;
		User user = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM user WHERE userid='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 user = new User(rs.getString("userId"), rs.getString("username"), rs.getString("password"), rs.getString("email"), rs.getString("name"), 
						 rs.getString("last_name"), rs.getString("country"), rs.getString("city"), rs.getString("address"), rs.getInt("cancellations"), rs.getString("type"), rs.getString("status"), rs.getString("cancellation_end_day"));
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 System.out.println("hi");
			 e.printStackTrace();
			 }
		 return user;
		}
	
	//Method that returns a user based on the given username
	public User findUserByUsername(String username) {
		Connection dbConn=null;
		User user = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM user WHERE username='"+username+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 user = new User(rs.getString("userId"), rs.getString("username"), rs.getString("password"), rs.getString("email"), rs.getString("name"), 
						 rs.getString("last_name"), rs.getString("country"), rs.getString("city"), rs.getString("address"), rs.getInt("cancellations"), rs.getString("type"), rs.getString("status"), rs.getString("cancellation_end_day"));
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return user;
		}
	
	//Method that adds the given user to the db
	public boolean createUser(User user) {
		Connection dbConn=null;
		String userId = user.getUserId();
		String username = user.getUsername();
		String password = user.getPassword();
		String email = user.getEmail();
		String name = user.getEmail();
		String last_name = user.getLast_name();
		String country = user.getCountry();
		String city = user.getCity();
		String address = user.getAddress(); 
		int cancellations = user.getCancellations();
		String type = user.getType();
		String status= user.getStatus();
		String cancellation_end_day = user.getCancellation_end_day();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("INSERT INTO `user` VALUES ('"+userId+"','"+username+"','"+password+"','"+email+"','"+name+"','"+last_name+"','"+country+"','"+city+"','"+address+"','"+cancellations+"','"+type+"','"+status+"','"+cancellation_end_day+"')");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the given user on the db
	public boolean updateUser(String userId, User user) {
		Connection dbConn=null;
		String username = user.getUsername();
		String password = user.getPassword();
		String email = user.getEmail();
		String name = user.getEmail();
		String last_name = user.getLast_name();
		String country = user.getCountry();
		String city = user.getCity();
		String address = user.getAddress(); 
		int cancellations = user.getCancellations();
		String type = user.getType();
		String status= user.getStatus();
		String cancellation_end_day = user.getCancellation_end_day();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `user` SET `username`='"+username+"',`password`='"+password+"',`email`='"+email+"',`name`='"+name+"',`last_name`='"+last_name+"',`country`='"+country+"',`city`='"+city+"',`address`='"+address+"',`cancellations`='"+cancellations+"',`type`='"+type+"',`status`='"+status+"',`cancellation_end_day`='"+cancellation_end_day+"' WHERE `userid`='"+userId+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the cancellations and cancellation end day of the given user
	public boolean updateUserCancellations(String userId, User user) {
		Connection dbConn=null;
		int cancellations = user.getCancellations();
		String cancellation_end_day = user.getCancellation_end_day();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `user` SET `cancellations`='"+cancellations+"',`cancellation_end_day`='"+cancellation_end_day+"' WHERE `userid`='"+userId+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the status and type of the given user
	public boolean updateUserStatus(String userId, User user) {
		Connection dbConn=null;
		String type = user.getType();
		String status= user.getStatus();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `user` SET `type`='"+type+"',`status`='"+status+"' WHERE `userid`='"+userId+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the password of the given user
	public boolean updateUserPassword(String userId, User user) {
		Connection dbConn=null;
		String password = user.getPassword();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `user` SET `password`='"+password+"' WHERE `userid`='"+userId+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that deletes the given user from the db
	public boolean deleteUser(String userId) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `user` WHERE `userid`='"+userId+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
}
