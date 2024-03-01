package net.codejava.ws;

import java.sql.Date;

public class Bookings {
	private String bookingid;
	private String status;
	private String userid;
	private String calendarid;

	
	public Bookings() {
		super();
		// TODO Auto-generated constructor stub
	}
	public Bookings(String bookingid, String status, String userid, String calendarid) {
		super();
		this.bookingid = bookingid;
		this.status = status;
		this.calendarid = calendarid;
		this.userid = userid;
	}
	
	public String getBookingid() {
		return bookingid;
	}
	public void setBookingid(String bookingid) {
		this.bookingid = bookingid;
	}
	public String getStatus() {
		return status;
	}
	public void setStatus(String status) {
		this.status = status;
	}
	public String getCalendarid() {
		return calendarid;
	}
	public void setCalendarid(String calendarid) {
		this.calendarid = calendarid;
	}
	public String getUserid() {
		return userid;
	}
	public void setUserid(String userid) {
		this.userid = userid;
	}

}