package net.codejava.ws;

import java.sql.Timestamp;

import com.fasterxml.jackson.annotation.JsonFormat;

public class User {
	private String userId;
	private String username;
	private String password;
	private String email;
	private String name;
	private String last_name;
	private String country;
	private String city;
	private String address;
	private int cancellations;
	private String type;
	private String status;
	private String cancellation_end_day;

	public User(String userId, String username, String password, String email, String name, String last_name,
			String country, String city, String address, int cancellations, String type, String status,
			String cancellation_end_day) {
		super();
		this.userId = userId;
		this.username = username;
		this.password = password;
		this.email = email;
		this.name = name;
		this.last_name = last_name;
		this.country = country;
		this.city = city;
		this.address = address;
		this.cancellations = cancellations;
		this.type = type;
		this.status = status;
		this.cancellation_end_day = cancellation_end_day;
	}


	public User() {
		super();
	}
	


	public String getCancellation_end_day() {
		return cancellation_end_day;
	}


	public void setCancellation_end_day(String cancellation_end_day) {
		this.cancellation_end_day = cancellation_end_day;
	}


	public String getStatus() {
		return status;
	}


	public void setStatus(String status) {
		this.status = status;
	}


	public String getUserId() {
		return userId;
	}

	public void setUserId(String userId) {
		this.userId = userId;
	}

	public String getUsername() {
		return username;
	}

	public void setUsername(String username) {
		this.username = username;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
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

	public String getCountry() {
		return country;
	}

	public void setCountry(String country) {
		this.country = country;
	}

	public String getCity() {
		return city;
	}

	public void setCity(String city) {
		this.city = city;
	}

	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

	public int getCancellations() {
		return cancellations;
	}

	public void setCancellations(int cancellations) {
		this.cancellations = cancellations;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}
	
	
}
