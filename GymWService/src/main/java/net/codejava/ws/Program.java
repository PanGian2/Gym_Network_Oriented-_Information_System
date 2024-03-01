package net.codejava.ws;

public class Program {
	private String pid;
	private String program_name;
	private int duration;
	private int type;
	private String whatdescription;
	private String whydescription;
	private String img_url;
	
	public Program(String pid, String program_name, int duration, int type, String whatdescription,
			String whydescription, String img_url) {
		super();
		this.pid = pid;
		this.program_name = program_name;
		this.duration = duration;
		this.type = type;
		this.whatdescription = whatdescription;
		this.whydescription = whydescription;
		this.img_url = img_url;
	}
	
	public Program(String pid, String program_name) {
		super();
		this.pid = pid;
		this.program_name = program_name;
	}

	public Program() {
		super();
		// TODO Auto-generated constructor stub
	}

	public String getPid() {
		return pid;
	}
	public void setPid(String pid) {
		this.pid = pid;
	}
	public String getProgram_name() {
		return program_name;
	}
	public void setProgram_name(String program_name) {
		this.program_name = program_name;
	}
	public int getDuration() {
		return duration;
	}
	public void setDuration(int duration) {
		this.duration = duration;
	}
	public int getType() {
		return type;
	}
	public void setType(int type) {
		this.type = type;
	}
	public String getWhatdescription() {
		return whatdescription;
	}
	public void setWhatdescription(String whatdescription) {
		this.whatdescription = whatdescription;
	}
	public String getWhydescription() {
		return whydescription;
	}
	public void setWhydescription(String whydescription) {
		this.whydescription = whydescription;
	}
	public String getImg_url() {
		return img_url;
	}
	public void setImg_url(String img_url) {
		this.img_url = img_url;
	}
		
}
