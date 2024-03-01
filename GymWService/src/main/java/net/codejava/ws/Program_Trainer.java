package net.codejava.ws;

public class Program_Trainer {
	private String trainers_tiid;
	private String program_pid;
	
	public Program_Trainer() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public Program_Trainer(String trainers_tiid, String program_pid) {
		super();
		this.trainers_tiid = trainers_tiid;
		this.program_pid = program_pid;
	}

	public String getTrainers_tiid() {
		return trainers_tiid;
	}
	public void setTrainers_tiid(String trainers_tiid) {
		this.trainers_tiid = trainers_tiid;
	}
	public String getProgram_pid() {
		return program_pid;
	}
	public void setProgram_pid(String program_pid) {
		this.program_pid = program_pid;
	}

}
