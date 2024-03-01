package net.codejava.ws;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class ProgramDAO {
	
	private static ProgramDAO instance;

	
	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private ProgramDAO() {
		
	}
	
	public static ProgramDAO getInstance() {
		if (instance==null) {
			instance = new ProgramDAO();
			System.out.println("ProgramDAO instance created");	
		}
		return instance;
	}
	
	//Create connection with db
	public static Connection createConnection() throws Exception {
        Connection con = null;
        try {
            Class.forName(dbClass);
            con = DriverManager.getConnection(dbUrl, dbUser, dbPwd);
            return con;
        } catch (Exception e) {
            throw e;
        } finally {
           return con;
        }
    }
	
	// Method that returns all programs
	public List<Program> listProgram() {
		Connection dbConn = null;
		ArrayList<Program> listOfProgram = new ArrayList<>();
		try {
			dbConn = createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("Select * from program");
			ResultSet rs = ps.executeQuery();
			System.out.println(rs);
			int i = 0;
			while (rs.next()) {
				i++;
				Program program = new Program(rs.getString("pid"), rs.getString("program_name"), rs.getInt("duration"),
						rs.getInt("type"), rs.getString("whatdescription"), rs.getString("whydescription"),
						rs.getString("img_url"));
				listOfProgram.add(program);
			}
		} catch (Exception e) {
			System.out.println("hi");
			e.printStackTrace();
		}
		return listOfProgram;
	}

	//Method that returns a program based on the given id
	public Program findProgram(String id) {
		Connection dbConn=null;
		Program program = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM program WHERE pid='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 program = new Program(rs.getString("pid"), rs.getString("program_name"), 
					 rs.getInt("duration"),rs.getInt("type"), rs.getString("whatdescription"), rs.getString("whydescription"), rs.getString("img_url") );
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 System.out.println("hi");
			 e.printStackTrace();
			 }
		 return program;
		}
	
	//Method that returns a program based on the given name
	public Program findProgramByName(String name) {
		Connection dbConn=null;
		Program program = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM program WHERE program_name='"+name+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 program = new Program(rs.getString("pid"), rs.getString("program_name"), 
					 rs.getInt("duration"),rs.getInt("type"), rs.getString("whatdescription"), rs.getString("whydescription"), rs.getString("img_url") );
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 System.out.println("hi");
			 e.printStackTrace();
			 }
		 return program;
		}

	//Method that adds the given program to the db
	public boolean createProgram(Program program) {
		Connection dbConn=null;
		String pid = program.getPid();
		String program_name = program.getProgram_name();
		int duration = program.getDuration();
		int type = program.getType();
		String whatdescription = program.getWhatdescription();
		String whydescription = program.getWhydescription(); 
		String img_url = program.getImg_url();
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("INSERT INTO `program` VALUES ('"+pid+"','"+program_name+"','"+duration+"','"+type+"','"+whatdescription+"','"+whydescription+"','"+img_url+"')");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

	//Method that updates the given program on the db
	public boolean updateProgram(String pid, Program program) {
		Connection dbConn=null;
		String program_name = program.getProgram_name();
		int duration = program.getDuration();
		int type = program.getType();
		String whatdescription = program.getWhatdescription();
		String whydescription = program.getWhydescription(); 
		String img_url = program.getImg_url();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `program` SET `program_name`='"+program_name+"',`duration`='"+duration+"',`type`='"+type+"',`whatdescription`='"+whatdescription+"',`whydescription`='"+whydescription+"',`img_url`='"+img_url+"' WHERE `pid`='"+pid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

	//Method that deletes the given program from the db
	public boolean deleteProgram(String pid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `program` WHERE `pid`='"+pid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}




}
