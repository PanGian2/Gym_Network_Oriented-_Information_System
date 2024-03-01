package net.codejava.ws;

public class Trainers {
	private String tiid;
	private String name;
	private String last_name;
	private String email;
	private String phone_number;
	
	
	
	public Trainers() {
		super();
		// TODO Auto-generated constructor stub
	}


	public Trainers(String tiid, String name, String last_name) {
		super();
		this.tiid = tiid;
		this.name = name;
		this.last_name = last_name;
	}


	public Trainers(String tiid, String name, String last_name, String email, String phone_number) {
		super();
		this.tiid = tiid;
		this.name = name;
		this.last_name = last_name;
		this.email = email;
		this.phone_number = phone_number;
	}
	
	
	public String getTiid() {
		return tiid;
	}
	public void setTiid(String tiid) {
		this.tiid = tiid;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getLast_name() {
		return last_name;
	}
	public void setLast_name(String last_name) {
		this.last_name = last_name;
	}
	public String getEmail() {
		return email;
	}
	public void setEmail(String email) {
		this.email = email;
	}
	public String getPhone_number() {
		return phone_number;
	}
	public void setPhone_number(String phone_number) {
		this.phone_number = phone_number;
	}
	
}
