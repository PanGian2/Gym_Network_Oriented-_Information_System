package net.codejava.ws;

import java.util.List;
import java.util.Random;
import java.util.UUID;

import javax.ws.rs.Consumes;
import javax.ws.rs.DELETE;
import javax.ws.rs.FormParam;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.PUT;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;

@Path("/program_trainer")
public class Program_TrainerResource {
	private Program_TrainerDAO dao = Program_TrainerDAO.getInstance();
	private ProgramDAO programDao = ProgramDAO.getInstance();
	
	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response listOfProgram_Trainer() {
		//Returns all program_trainers
		List<Program_Trainer> list = dao.listProgram_Trainer();
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}

	@POST
	@Consumes(MediaType.APPLICATION_FORM_URLENCODED)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addProgram_Trainer(@FormParam("program_name") String program_name, @FormParam("trainers_tiid") String trainer_id) {
		//Check if all fields have been set
		if (program_name==null || trainer_id==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (program_name.isEmpty() || trainer_id.isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if given program exists
		Program program = programDao.findProgramByName(program_name);
		if (program == null) {
			return Response.status(400, "No such program").build();
		}
		//Check if the given program is a group one
		String pid = program.getPid();
		int type = program.getType();
		if (type == 1) {
			return Response.status(400).entity("This program can't have a trainer because it's not a group program").build();
		}
		//Check if the program_trainer already exists
		Program_Trainer program_trainer = new Program_Trainer(trainer_id, pid);
		List<Program_Trainer> list = dao.listProgram_Trainer();
		for (Program_Trainer pt: list) {
			if (pt.getProgram_pid().equals(program_trainer.getProgram_pid()) && pt.getTrainers_tiid().equals(program_trainer.getTrainers_tiid())) {
				return Response.status(400).entity("This group program done by this trainer already exists").build();
			}
		}
		//Create the program_trainer
		if(dao.createProgram_Trainer(program_trainer)) {
			return Response.status(200).build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@GET
	@Path("/programs")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showProgram_Trainer() {
		//Return programs from program_trainer
		List<Program> list = dao.listPrograms();
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}
	
	@GET
	@Path("/programs/{id}/trainers")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showProgram_Trainer(@PathParam("id") String id) {
		//Return all trainers for a specific program
		List<Trainers> list = dao.findTrainersByProgram(id);
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}

	
	
	@PUT
	@Path("/programs/{pid}/trainers/{tid}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateProgram_Trainer(@PathParam("pid") String pid, @PathParam("tid") String tid, Program_Trainer program_trainer) {
		//Check if all fields have been set
		if (program_trainer.getProgram_pid()==null || program_trainer.getTrainers_tiid()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (program_trainer.getProgram_pid().isEmpty() || program_trainer.getTrainers_tiid().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if the given program_trainer already exists
		Program_Trainer pt = dao.findProgram_Trainer(pid,tid);
		if (pt == null) {
			return Response.status(400).entity("No such program_trainer").build();
		}
		
		//In order to update, first delete the current program_trainer and then add the new one
		boolean r = dao.deleteProgram_Trainer(pid, tid);
		if (r) {
			boolean c = dao.createProgram_Trainer(program_trainer);
			if (c) {
				return Response.ok().entity("program_trainer updated successfully").build();
			}
			else {
				return Response.status(500).entity("Something went wrong").build();
			}
		} else {
			return Response.status(500).entity("Something went wrong").build();
		}
	}

	@DELETE
	@Path("/programs/{pid}/trainers/{tid}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteProgram_Trainer(@PathParam("pid") String pid, @PathParam("tid") String tid) {
		//Check if the given program_trainer exists
		Program_Trainer program_trainer = dao.findProgram_Trainer(pid,tid);
		if (program_trainer == null) {
			return Response.status(400).entity("No such program with this trainer").build();
		}

		//Delete the program_trainer
		if(dao.deleteProgram_Trainer(pid,tid)) {
			return Response.ok().entity("User deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@DELETE
	@Path("/programs/{pid}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteProgram(@PathParam("pid") String pid) {
		//Check if the given program_trainer exists
		List<Program_Trainer> program_trainers = dao.findPrograms(pid);
		if (program_trainers == null) {
			return Response.status(400).entity("No such program with this trainer").build();
		}
		//Delete all trainers from the given program
		if(dao.deleteProgramAndAllTrainers(pid)) {
			return Response.ok().entity("Program and trainers deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
}
