package net.codejava.ws;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class TrainersDAO {
	
	private static TrainersDAO instance;
	
	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private TrainersDAO() {
		
	}
	
	public static TrainersDAO getInstance() {
		if (instance==null) {
			instance = new TrainersDAO();
			System.out.println("TrainersDAO instance created");	
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
            throw e;
        } finally {
           return con;
        }
    }
	
	// Method that returns all trainers
	public List<Trainers> listTrainers() {
	 Connection dbConn=null;
	 ArrayList<Trainers> listOfTrainers = new ArrayList<>();
	 try {
		 dbConn=createConnection();
		 System.out.println("Created connection");
		 System.out.println(dbConn);
		 PreparedStatement ps = dbConn.prepareStatement("Select * from trainers");
		 ResultSet rs = ps.executeQuery();
		 System.out.println(rs);
		 int i=0;
		 while(rs.next()) {
			 i++;
			 Trainers trainer = new Trainers (rs.getString("tiid"), rs.getString("name"), 
					 rs.getString("last_name"), rs.getString("email"), rs.getString("phone_number"));
			 
			 listOfTrainers.add(trainer);
		 }
	 } catch(Exception e) {
		e.printStackTrace();
		}
	 return listOfTrainers;
	}

	//Method that returns a trainer based on the given id
	public Trainers findTrainers(String id) {
		Connection dbConn=null;
		Trainers trainers = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM trainers WHERE tiid='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				trainers = new Trainers (rs.getString("tiid"), rs.getString("name"), 
					 rs.getString("last_name"), rs.getString("email"), rs.getString("phone_number"));
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return trainers;
	}

	//Method that adds the given trainer to the db
	public boolean createTrainers(Trainers trainers) {
		Connection dbConn=null;
		String tiid = trainers.getTiid();
		String name = trainers.getName();
		String last_name = trainers.getLast_name();
		String email = trainers.getEmail(); 
		String phone_number = trainers.getPhone_number();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("INSERT INTO `trainers` VALUES ('"+tiid+"','"+name+"','"+last_name+"','"+email+"','"+phone_number+"')");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

	//Method that updates the given trainer on the db
	public boolean updateTrainers(String tiid, Trainers trainers) {
		Connection dbConn=null;
		String name = trainers.getName();
		String last_name = trainers.getLast_name();
		String email = trainers.getEmail(); 
		String phone_number = trainers.getPhone_number();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `trainers` SET `name`='"+name+"',`last_name`='"+last_name+"',`email`='"+email+"',`phone_number`='"+phone_number+"' WHERE `tiid`='"+tiid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

	//Method that deletes the given trainer from the db
	public boolean deleteTrainers(String tiid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `trainers` WHERE `tiid`='"+tiid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

}

