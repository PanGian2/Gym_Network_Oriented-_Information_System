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

@Path("/programs")
public class ProgramResource {
	private ProgramDAO dao = ProgramDAO.getInstance();
	
	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response listOfProgram() {
		//Returns all programs
		List<Program> list = dao.listProgram();
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}


	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addProgram(Program program) {
		//Check if all fields have been set
		List<Program> list = dao.listProgram();
		UUID id = UUID.randomUUID();
		String pid = id.toString();
		if (program.getProgram_name()==null || program.getWhatdescription()==null || program.getWhydescription()==null || program.getImg_url()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (program.getProgram_name().isEmpty() || program.getWhatdescription().isEmpty() || program.getWhydescription().isEmpty() || program.getImg_url().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if duration is above zero
		if(program.getDuration() <= 0) {
			return Response.status(400).entity("The duration must be a positive integer").build();
		}
		//Check if type has correct values
		if (program.getType()!=1 && program.getType()!=2) {
			return Response.status(400).entity("Program type must be 1 or 2").build();
		}
		
		//Check if there is already a program with the same name
		for (Program p: list) {
			if (p.getPid().equals(pid)) {
				id = UUID.randomUUID();
				pid = id.toString();
			}
			
			if (p.getProgram_name().equals(program.getProgram_name())) {
				return Response.status(400).entity("There is already a program with that name").build();
			}
		
		}
		
		//Create the program
		program.setPid(pid);
		if(dao.createProgram(program)) {
			return Response.status(200).entity("Program created successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@GET
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showProgram(@PathParam("id") String id) {
		//Return program based on the id
		Program program = dao.findProgram(id);
			
		if (program==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(program).build();
		}
	}

	@PUT
	@Path("/{id}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateProgram(@PathParam("id") String id, Program program) {
		//Check if all fields have been set
		Program p = dao.findProgram(id);
		if (program.getProgram_name()==null || program.getWhatdescription()==null || program.getWhydescription()==null || program.getImg_url()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (program.getProgram_name().isEmpty() || program.getWhatdescription().isEmpty() || program.getWhydescription().isEmpty() || program.getImg_url().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if duration is above zero
		if(program.getDuration() <= 0) {
			return Response.status(400).entity("The duration must be a positive integer").build();
		}
		//Check if type has correct values
		if (program.getType()!=1 && program.getType()!=2) {
			return Response.status(400).entity("Program type must be 1 or 2").build();
		}
		
		//Check if the given id exists
		if (p == null) {
			return Response.status(400).entity("No such program").build();
		}
		//Check if there is already a program with the same name
		if (p.getProgram_name().equals(program.getProgram_name()) && !p.getPid().equals(id)) {
			return Response.status(400).entity("There is already a program with that name").build();
		}
		
		//Update the program
		if(dao.updateProgram(id, program)) {
			return Response.ok().entity("Program updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}


	@DELETE
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteProgram(@PathParam("id") String id) {
		//Check if the given id exists
		Program program = dao.findProgram(id);
		if (program == null) {
			return Response.status(400).entity("No such program").build();
		}
		//Delete the program
		if(dao.deleteProgram(id)) {
			return Response.ok().entity("Program deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
	}



}
