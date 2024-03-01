package net.codejava.ws;

import java.sql.Date;
import java.sql.Time;

import com.fasterxml.jackson.annotation.JsonFormat;

public class Calendar {
	
	
	
	private String calendarid;
	@JsonFormat(shape = JsonFormat.Shape.STRING, pattern = "yyyy-MM-dd", timezone="Europe/Athens")
	private Date date;
	private String hour;
	private int capacity;
	private String trainer_id;
	private String program_pid;
	private String g_program_pid;
	
	public Calendar() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public Calendar(String calendarid, Date date, String hour, int capacity, String program_pid, String trainer_id, 
			String g_program_pid) {
		super();
		this.calendarid = calendarid;
		this.date = date;
		this.hour = hour;
		this.capacity = capacity;
		this.trainer_id = trainer_id;
		this.program_pid = program_pid;
		this.g_program_pid = g_program_pid;
	}

	public String getG_program_pid() {
		return g_program_pid;
	}

	public void setG_program_pid(String g_program_pid) {
		this.g_program_pid = g_program_pid;
	}

	public String getCalendarid() {
		return calendarid;
	}
	public void setCalendarid(String calendarid) {
		this.calendarid = calendarid;
	}
	public Date getDate() {
		return date;
	}
	public void setDate(Date date) {
		this.date = date;
	}
	public String getHour() {
		return hour;
	}
	public void setHour(String hour) {
		this.hour = hour;
	}
	public int getCapacity() {
		return capacity;
	}
	public void setCapacity(int capacity) {
		this.capacity = capacity;
	}
	public String getTrainer_id() {
		return trainer_id;
	}
	public void setTrainer_id(String trainer_id) {
		this.trainer_id = trainer_id;
	}
	public String getProgram_pid() {
		return program_pid;
	}
	public void setProgram_pid(String program_pid) {
		this.program_pid = program_pid;
	}
	
}