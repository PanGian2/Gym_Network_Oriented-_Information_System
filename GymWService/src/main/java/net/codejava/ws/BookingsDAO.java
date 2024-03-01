package net.codejava.ws;

import java.sql.Connection;
import java.sql.Date;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

public class BookingsDAO {
	
	private static BookingsDAO instance;
	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private BookingsDAO() {
		
	}
	
	public static BookingsDAO getInstance() {
		if (instance==null) {
			instance = new BookingsDAO();
			System.out.println("BookingsDAO instance created");	
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
	
	// Method that returns all bookings
	public List<Bookings> listBookings() {
		Connection dbConn = null;
		ArrayList<Bookings> listOfBookigs = new ArrayList<>();
		try {
			dbConn = createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM bookings");
			ResultSet rs = ps.executeQuery();
			System.out.println(rs);
			int i = 0;
			while (rs.next()) {
				i++;
				Bookings bookings = new Bookings(rs.getString("bookingid"), rs.getString("status"),
						rs.getString("user_userid"), rs.getString("calendar_calendarid"));
				listOfBookigs.add(bookings);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return listOfBookigs;
	}

	//Method that returns a booking based on the given id
	public Bookings findBookings(String id) {
		Connection dbConn=null;
		Bookings bookings = null;
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM bookings WHERE bookingid='"+id+"'");
			 ResultSet rs = ps.executeQuery();
			 if(rs.next()) {
				 bookings = new Bookings(rs.getString("bookingid"), rs.getString("status"), rs.getString("user_userid") , rs.getString("calendar_calendarid"));
			 }
			 else {
				 return null;
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
		}
		return bookings;
	}
	
	//Method that returns bookings based on the user id ordered by date
	public List<Bookings> findBookingsFromUser(String id) {
		Connection dbConn=null;
		ArrayList<Bookings> listOfBookigs = new ArrayList<>();
		try {
			 dbConn=createConnection();
			 System.out.println("Created connection");
			 System.out.println(dbConn);
			 PreparedStatement ps = dbConn.prepareStatement("SELECT bookingid, status, user_userid, calendar_calendarid FROM bookings JOIN calendar ON calendar_calendarid = calendar.calendarid WHERE user_userid='"+id+"' ORDER BY calendar.date DESC");
			 ResultSet rs = ps.executeQuery();
			 while(rs.next()) {
				 Bookings bookings = new Bookings(rs.getString("bookingid"), rs.getString("status"),rs.getString("user_userid"), rs.getString("calendar_calendarid"));
				 listOfBookigs.add(bookings);
			 }
		 } catch(Exception e) {
			 e.printStackTrace();
		}
		return listOfBookigs;
	}
	
	//Method that adds the given booking to the db
	public boolean createBookings(Bookings bookings) {
		Connection dbConn=null;
		String bookingid = bookings.getBookingid();
		String status = bookings.getStatus();
		String calendar_calendarid = bookings.getCalendarid();
		String user_userid = bookings.getUserid();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("INSERT INTO `bookings` VALUES ('"+bookingid+"','"+status+"','"+user_userid+"','"+calendar_calendarid+"')");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the given booking on the db
	public boolean updateBookings(String bookingid, Bookings bookings) {
		Connection dbConn=null;
		String status = bookings.getStatus();
		String calendar_calendarid = bookings.getCalendarid();
		String user_userid = bookings.getUserid();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `bookings` SET `status`='"+status+"',`calendar_calendarid`='"+calendar_calendarid+"',`user_userid`='"+user_userid+"' WHERE `bookingid`='"+bookingid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that deletes the given booking from the db
	public boolean deleteBookings(String bookingid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `bookings` WHERE `bookingid`='"+bookingid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

}
