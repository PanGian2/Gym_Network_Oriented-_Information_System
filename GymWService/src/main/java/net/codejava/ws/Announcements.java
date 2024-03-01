package net.codejava.ws;

import java.sql.Date;

import com.fasterxml.jackson.annotation.JsonFormat;

public class Announcements {
	private String anid;
	private String title;
	private String content;
	@JsonFormat(shape = JsonFormat.Shape.STRING, pattern = "yyyy-MM-dd", timezone="Europe/Athens")
	private Date dateposted;
	
	public Announcements() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public Announcements(String anid, String title, String content, Date dateposted) {
		super();
		this.anid = anid;
		this.title = title;
		this.content = content;
		this.dateposted = dateposted;
	}
	
	public String getAnid() {
		return anid;
	}
	public void setAnid(String anid) {
		this.anid = anid;
	}
	public String getTitle() {
		return title;
	}
	public void setTitle(String title) {
		this.title = title;
	}
	public String getContent() {
		return content;
	}
	public void setContent(String content) {
		this.content = content;
	}
	public Date getDateposted() {
		return dateposted;
	}
	public void setDateposted(Date dateposted) {
		this.dateposted = dateposted;
	}

	
}