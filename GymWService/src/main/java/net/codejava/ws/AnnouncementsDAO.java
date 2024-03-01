package net.codejava.ws;

import java.sql.Connection;
import java.sql.Date;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.List;

public class AnnouncementsDAO {
	
	private static AnnouncementsDAO instance;
	
	public static String dbClass = "com.mysql.jdbc.Driver";
	private static String dbName= "gym_db";
	public static String dbUrl = "jdbc:mysql://localhost:3306/"+dbName;
	public static String dbUser = "root";
	public static String dbPwd = "";
	
	private AnnouncementsDAO() {
		
	}
    public static AnnouncementsDAO getInstance() {
		if (instance==null) {
			instance = new AnnouncementsDAO();
			System.out.println("AnnouncementsDAO instance created");	
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
	
	//Method that returns all announcements ordered by dateposted
	public List<Announcements> listAnnouncements() {
	 Connection dbConn=null;
	ArrayList<Announcements> listOfAnnouncements = new ArrayList<>();
	try {
		 dbConn=createConnection();
		 System.out.println("Created connection");
		 System.out.println(dbConn);
		 PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM Announcements ORDER BY dateposted DESC");
		 ResultSet rs = ps.executeQuery();
		 System.out.println(rs);
		 int i=0;
		 while(rs.next()) {
			 i++;
			 Announcements announcements = new Announcements(rs.getString("anid"), rs.getString("title"), rs.getString("content"), rs.getDate("dateposted"));
			 listOfAnnouncements.add(announcements);
		 }
	 } catch(Exception e) {
		System.out.println("hi");
		e.printStackTrace();
		}
	 return listOfAnnouncements;
 }

	// Method that returns an announcement based on the given id
	public Announcements findAnnouncements(String id) {
		Connection dbConn = null;
		Announcements announcements = null;
		try {
			dbConn = createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("SELECT * FROM announcements WHERE anid='" + id + "'");
			ResultSet rs = ps.executeQuery();
			if (rs.next()) {
				announcements = new Announcements(rs.getString("anid"), rs.getString("title"), rs.getString("content"),
						rs.getDate("dateposted"));
			} else {
				return null;
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return announcements;
	}
	
	//Method that adds the given announcement to the db
	public boolean createAnnouncements(Announcements announcements) {
		Connection dbConn=null;
		String anid = announcements.getAnid();
		String title = announcements.getTitle();
		String content = announcements.getContent();
		Date dateposted = announcements.getDateposted();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			System.out.println(dbConn);
			PreparedStatement ps = dbConn.prepareStatement("INSERT INTO `announcements` VALUES ('"+anid+"','"+title+"','"+content+"','"+dateposted+"')");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that updates the given announcement on the db
	public boolean updateAnnouncements(String anid, Announcements announcements) {
		Connection dbConn=null;
		String title = announcements.getTitle();
		String content = announcements.getContent();
		Date dateposted = announcements.getDateposted();
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("UPDATE `announcements` SET `title`='"+title+"',`content`='"+content+"',`dateposted`='"+dateposted+"' WHERE `anid`='"+anid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}
	
	//Method that deletes the given announcement from the db
	public boolean deleteAnnouncements(String anid) {
		Connection dbConn=null;
		
		try {
			dbConn=createConnection();
			System.out.println("Created connection");
			PreparedStatement ps = dbConn.prepareStatement("DELETE FROM `announcements` WHERE `anid`='"+anid+"'");
			int val = ps.executeUpdate();
			System.out.println("1 row affected");
			return true;
		} catch(Exception e) {
			 e.printStackTrace();
			 return false;
		}
	}

}
