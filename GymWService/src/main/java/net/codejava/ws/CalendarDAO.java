package net.codejava.ws;

import java.sql.Connection;
import java.sql.Date;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.Time;
import java.util.ArrayList;
import java.util.List;

public class CalendarDAO {
	
	private static CalendarDAO instance;
	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private CalendarDAO() {
		
	}
	
	public static CalendarDAO getInstance() {
		if (instance==null) {
			instance = new CalendarDAO();
			System.out.println("CalendarDAO instance created");	
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
	
	// Method that returns all calendars
	public List<Calendar> listCalendar() {
		Connection dbConn = null;
		ArrayList<Calendar> listOfCalendars = new ArrayList<>();
		try {
			dbConn = createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar");
			ResultSet rs = ps.executeQuery();
			System.out.println(rs);
			int i = 0;
			while (rs.next()) {
				i++;
				Calendar calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"),
						rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"),
						rs.getString("program_trainer_program_pid"));
				listOfCalendars.add(calendar);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return listOfCalendars;
	}

	//Method that returns a calendar based on the given id
	public Calendar findCalendar(String id) {
		Connection dbConn=null;
		Calendar calendar = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar WHERE calendarid='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"), rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"), 
						 rs.getString("program_trainer_program_pid"));
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
		}
		return calendar;
	}
	
	//Method that returns calendars based on the group program and trainer id
	public List<Calendar> findCalendarsByGroupIds(String pid, String tid) {
		Connection dbConn=null;
		ArrayList<Calendar> listOfCalendars = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar WHERE program_trainer_program_pid='"+pid+"' AND program_trainer_tiid='"+tid+"'");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Calendar calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"), rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"), 
						 rs.getString("program_trainer_program_pid"));
				 listOfCalendars.add(calendar);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfCalendars;
		}
	
	//Method that returns calendars based on the group program id
	public List<Calendar> findCalendarsByGroupPid(String pid) {
		Connection dbConn=null;
		ArrayList<Calendar> listOfCalendars = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar WHERE program_trainer_program_pid='"+pid+"'");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Calendar calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"), rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"), 
						 rs.getString("program_trainer_program_pid"));
				 listOfCalendars.add(calendar);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfCalendars;
		}
	
	//Method that returns calendars based on the program id
	public List<Calendar> findCalendarsByProgramId(String pid) {
		Connection dbConn=null;
		ArrayList<Calendar> listOfCalendars = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar WHERE program_pid='"+pid+"'");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Calendar calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"), rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"), 
						 rs.getString("program_trainer_program_pid"));
				 listOfCalendars.add(calendar);
				 System.out.println(calendar);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfCalendars;
		}
	
	//Method that returns all calendars based on the program id and the given date
	public List<Calendar> findProgramHours(String pid, Date date) {
		Connection dbConn=null;
		ArrayList<Calendar> listOfCalendars = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar WHERE program_pid='"+pid+"' AND date='"+date+"'");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Calendar calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"), rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"), 
						 rs.getString("program_trainer_program_pid"));
				 listOfCalendars.add(calendar);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfCalendars;
		}
	
	//Method that returns all calendars based on the group program id, the trainer id and the given date
	public List<Calendar> findGroupProgramHours(String pid, String tid,  Date date) {
		Connection dbConn=null;
		ArrayList<Calendar> listOfCalendars = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM calendar WHERE program_trainer_program_pid='"+pid+"' AND program_trainer_tiid='"+tid+"' AND date='"+date+"'");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Calendar calendar = new Calendar(rs.getString("calendarid"), rs.getDate("date"), rs.getString("hour"), rs.getInt("capacity"), rs.getString("program_pid"), rs.getString("program_trainer_tiid"), 
						 rs.getString("program_trainer_program_pid"));
				 listOfCalendars.add(calendar);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
			 }
		 return listOfCalendars;
		}
	
	//Method that adds the given array of calendars to the db
	public boolean createCalendar(List<Calendar> calendars) {
		Connection dbConn = null;

		try {
			dbConn = createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			String statement = "INSERT INTO `calendar` VALUES ";
			int i = 0;
			//For each calendar on the array add it to the db
			for (Calendar calendar : calendars) {
				String calendarid = calendar.getCalendarid();
				Date date = calendar.getDate();
				String hour = calendar.getHour();
				int capacity = calendar.getCapacity();
				//Check if the calendar is for a solo or group program
				if (calendar.getProgram_pid() != null) {
					String program_pid = calendar.getProgram_pid();
					if (i == calendars.size() - 1) {
						statement = statement + "('" + calendarid + "','" + date + "','" + hour + "','" + capacity
								+ "','" + program_pid + "',NULL,NULL) ";
					} else {
						statement = statement + "('" + calendarid + "','" + date + "','" + hour + "','" + capacity
								+ "','" + program_pid + "',NULL,NULL), ";
					}

				} else if (calendar.getG_program_pid() != null) {
					String program_trainer_tiid = calendar.getTrainer_id();
					String program_trainer_pid = calendar.getG_program_pid();
					if (i == calendars.size() - 1) {
						statement = statement + "('" + calendarid + "','" + date + "','" + hour + "','" + capacity
								+ "',NULL,'" + program_trainer_tiid + "','" + program_trainer_pid + "')";
					} else {
						statement = statement + "('" + calendarid + "','" + date + "','" + hour + "','" + capacity
								+ "',NULL,'" + program_trainer_tiid + "','" + program_trainer_pid + "'),";
					}
				}
				i++;
			}
			System.out.println(statement);
			PreparedStatement ps = dbConn.prepareStatement(statement);
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch (Exception e) {
			e.printStackTrace();
			return false;
		}
	}

	//Method that updates the given calendar on the db
	public boolean updateCalendar(String calendarid, Calendar calendar) {
		Connection dbConn=null;
		Date date = calendar.getDate();
		String hour = calendar.getHour();
		int capacity = calendar.getCapacity();
		String program_pid = calendar.getProgram_pid();
		String program_trainer_tiid = calendar.getTrainer_id();
		String program_trainer_pid = calendar.getG_program_pid();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `calendar` SET `date`='"+date+"',`hour`='"+hour+"',`capacity`='"+capacity+"',`program_pid`='"+program_pid+"',`program_trainer_tiid`='"+program_trainer_tiid+"',`program_trainer_program_pid`='"+program_trainer_pid+"' WHERE `calendarid`='"+calendarid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the capacity and/or hour of a given calendar
	public boolean updateCapacityAndHour(String calendarid, Calendar calendar) {
		Connection dbConn=null;
		int capacity = calendar.getCapacity();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			if (calendar.getHour() == null) {
				PreparedStatement ps = dbConn.prepareStatement("UPDATE `calendar` SET `capacity`='"+capacity+"' WHERE `calendarid`='"+calendarid+"'");
				int val = ps.executeUpdate();
				System.out.println("1 row affected");
				
			} else {
				String hour = calendar.getHour();
				PreparedStatement ps = dbConn.prepareStatement("UPDATE `calendar` SET `hour`='"+hour+"',`capacity`='"+capacity+"' WHERE `calendarid`='"+calendarid+"'");
				int val = ps.executeUpdate();
				System.out.println("1 row affected");
			}
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that deletes the given booking from the db
	public boolean deleteCalendar(String calendarid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `calendar` WHERE `calendarid`='"+calendarid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}


}
