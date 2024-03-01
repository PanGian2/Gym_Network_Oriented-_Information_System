package net.codejava.ws;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

import javax.ws.rs.PathParam;

public class Program_TrainerDAO {
	
	private static Program_TrainerDAO instance;
	
	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private Program_TrainerDAO() {
		
	}
	
	public static Program_TrainerDAO getInstance() {
		if (instance==null) {
			instance = new Program_TrainerDAO();
			System.out.println("Program_TrainerDAO instance created");	
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
	
	// Method that returns all Program_Trainers
	public List<Program_Trainer> listProgram_Trainer() {
	 Connection dbConn=null;
	 ArrayList<Program_Trainer> listOfProgram_Trainer = new ArrayList<>();
	try {
		 dbConn=createConnection();
		 System.out.println("Created connection");
		 System.out.println(dbConn);
		 PreparedStatement ps = dbConn.prepareStatement("Select * from program_trainer");
		 ResultSet rs = ps.executeQuery();
		 int i=0;
		 while(rs.next()) {
			 i++;
			 Program_Trainer program_trainer = new Program_Trainer(rs.getString("trainers_tiid"), rs.getString("program_pid"));
			 listOfProgram_Trainer.add(program_trainer);
		 }
	 } catch(Exception e) {
		e.printStackTrace();
		}
	 return listOfProgram_Trainer;
 	}
	
	//Method that returns all programs name and program_pid once
	public List<Program> listPrograms() {
		 Connection dbConn=null;
		 ArrayList<Program> listOfPrograms = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT DISTINCT program_pid, program_name FROM `program_trainer` JOIN program ON program_pid = program.pid;");
			 ResultSet rs = ps.executeQuery();
			 int i=0;
			 while(rs.next()) {
				 i++;
				 Program program = new Program(rs.getString("program_pid"), rs.getString("program_name"));
				 listOfPrograms.add(program);
			 }
		 } catch(Exception e) {
			e.printStackTrace();
			}
		 return listOfPrograms;
	 	}


	//Method that returns a program_trainer based on the given ids
	public Program_Trainer findProgram_Trainer(String pid, String tid) {
		Connection dbConn=null;
		Program_Trainer program_trainer = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM program_trainer WHERE program_pid='"+pid+"' AND trainers_tiid='"+tid+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 program_trainer = new Program_Trainer(rs.getString("trainers_tiid"), rs.getString("program_pid"));
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return program_trainer;
	}
	
	//Method that returns a booking based on the given program id
	public List<Program_Trainer> findPrograms(String id) {
		Connection dbConn=null;
		ArrayList<Program_Trainer> listOfPrograms = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM `program_trainer` WHERE program_pid ='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Program_Trainer program_trainer = new Program_Trainer(rs.getString("trainers_tiid"), rs.getString("program_pid"));
				 listOfPrograms.add(program_trainer);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfPrograms;
	}
	
	//Method that returns trainers based on the given program id
	public List<Trainers> findTrainersByProgram(String id) {
		Connection dbConn=null;
		ArrayList<Trainers> listOfTrainers = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT DISTINCT trainers_tiid, name, last_name FROM `program_trainer` JOIN trainers ON trainers_tiid = trainers.tiid WHERE program_trainer.program_pid ='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 while (rs.next()) {
				 Trainers program_trainer = new Trainers(rs.getString("trainers_tiid"), rs.getString("name"), rs.getString("last_name"));
				 listOfTrainers.add(program_trainer);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfTrainers;
	}

	//Method that adds the given program_trainer to the db
	public boolean createProgram_Trainer(Program_Trainer program_trainer) {
		Connection dbConn=null;
		String program_pid = program_trainer.getProgram_pid();
		String trainers_tiid = program_trainer.getTrainers_tiid();
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("INSERT INTO `program_trainer` VALUES ('"+trainers_tiid+"','"+program_pid+"')");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

	//Method that updates the given program_trainer on the db
	public boolean updateProgram_Trainer(String program_pid, String trainer_tiid, Program_Trainer program_trainer) {
		Connection dbConn=null;
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `program_trainer` SET `trainers_tiid`='"+trainer_tiid+"',`program_pid`='"+program_pid+"' WHERE `trainers_tiid`='"+trainer_tiid+"' AND `program_pid`='"+program_pid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

	//Method that deletes the given program_trainer from the db
	public boolean deleteProgram_Trainer(String program_pid, String trainer_tid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `program_trainer` WHERE `trainers_tiid`='"+trainer_tid+"' AND `program_pid`='"+program_pid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that deletes every program_trainer from the db based on the given program id
	public boolean deleteProgramAndAllTrainers(String program_pid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `program_trainer` WHERE `program_pid`='"+program_pid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
}
