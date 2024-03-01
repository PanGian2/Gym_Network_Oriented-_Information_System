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

@Path("/trainers")
public class TrainersResource {
	private TrainersDAO dao = TrainersDAO.getInstance();
	
	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response listOfTrainers() {
		//Returns all bookings
		List<Trainers> list = dao.listTrainers();
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}

	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addTrainers(Trainers trainers) {
		//Check if all fields have been set
		List<Trainers> list = dao.listTrainers();
		UUID id = UUID.randomUUID();
		String tiid = id.toString();
		if (trainers.getName()==null || trainers.getLast_name()==null || trainers.getEmail()==null|| trainers.getPhone_number()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (trainers.getName().isEmpty() || trainers.getLast_name().isEmpty() || trainers.getEmail().isEmpty()|| trainers.getPhone_number().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		for (Trainers t: list) {
			if (t.getTiid().equals(tiid)) {
				id = UUID.randomUUID();
				tiid = id.toString();
			}
		}
		//Create the trainer
		trainers.setTiid(tiid);
		if(dao.createTrainers(trainers)) {
			return Response.status(200).entity("Trainer created successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}

		
	}

	@GET
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showTrainers(@PathParam("id") String id) {
		//Return trainer based on the id
		Trainers trainers = dao.findTrainers(id);
			
		if (trainers==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(trainers).build();
		}
	}

	@PUT
	@Path("/{id}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateTrainers(@PathParam("id") String id, Trainers trainers) {
		//Check if all fields have been set
		Trainers t = dao.findTrainers(id);
		if (trainers.getName()==null || trainers.getLast_name()==null || trainers.getEmail()==null || trainers.getPhone_number()==null) {
			return Response.status(400).entity("All fileds must have a value").build();
		}
		if (trainers.getName().isEmpty() || trainers.getLast_name().isEmpty() || trainers.getEmail().isEmpty()|| trainers.getPhone_number().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if the given id exists
		if (t == null) {
			return Response.status(400).entity("No such trainer").build();
		}
		
		//Update the trainer
		if(dao.updateTrainers(id, trainers)) {
			return Response.ok().entity("Trainer updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

	@DELETE
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteTrainers(@PathParam("id") String id) {
		//Check if the given id exists
		Trainers trainers = dao.findTrainers(id);
		if (trainers == null) {
			return Response.status(400).entity("No such trainer").build();
		}
		//Delete the trainer
		if(dao.deleteTrainers(id)) {
			return Response.ok().entity("Trainer deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

}
