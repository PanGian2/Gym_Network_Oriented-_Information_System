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

@Path("/users")
public class UserResource {
	private UserDAO dao = UserDAO.getInstance();
	
	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response listOfUsers() {
		//Returns all users
		List<User> list = dao.listUsers();
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}
	
	
	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addUser(User user) {
		//Check if all fields have been set
		List<User> list = dao.listUsers();
		UUID id = UUID.randomUUID();
		String userId = id.toString();
		if (user.getName()==null || user.getLast_name()==null || user.getEmail()==null || user.getUsername()==null|| user.getPassword()==null
				|| user.getCountry()==null || user.getCity()==null || user.getAddress()==null || user.getStatus()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (user.getName().isEmpty() || user.getLast_name().isEmpty() || user.getEmail().isEmpty() || user.getUsername().isEmpty()|| user.getPassword().isEmpty()
				|| user.getCountry().isEmpty() || user.getCity().isEmpty() || user.getAddress().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if type has the correct values
		if (!user.getType().equals("simple") && !user.getType().equals("admin") && !user.getType().equals("noRole")) {
			return Response.status(400).entity("User type must be simple or admin or noRole").build();
		}
		//Check if status has the correct values
		if (!user.getStatus().equals("pending") && !user.getStatus().equals("approved")) {
			return Response.status(400).entity("Status must be pending or approved").build();
		}
		//Check if there is already a user with the same username
		for (User u: list) {
			 if (u.getUserId().equals(userId)) {
				id = UUID.randomUUID();
				userId = id.toString();
			 }
			 if (u.getUsername().equals(user.getUsername())) {
				 return Response.status(400).entity("There is already a user with that username").build();
			 }
		}
		//Create user
		user.setCancellation_end_day("NULL");
		user.setUserId(userId);
		if(dao.createUser(user)) {
			return Response.status(200).entity("User was created successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
		
	}
	
	@GET
	@Path("/find/{username}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showUserByUsername(@PathParam("username") String username) {
		//Return user based on the username
		User user = dao.findUserByUsername(username);
			
		if (user==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(user).build();
		}
	}
	
	@GET
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showUser(@PathParam("id") String id) {
		//Return user based on the id
		User user = dao.findUser(id);
			
		if (user==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(user).build();
		}
	}
		
		
	@PUT
	@Path("/{id}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateUser(@PathParam("id") String id, User user) {
		//Check if all fields have been set
		User u = dao.findUser(id);
		List<User> list = dao.listUsers();
		if (user.getName()==null || user.getLast_name()==null || user.getEmail()==null || user.getUsername()==null
				|| user.getCountry()==null || user.getCity()==null || user.getAddress()==null || user.getType()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (user.getName().isEmpty() || user.getLast_name().isEmpty() || user.getEmail().isEmpty() || user.getUsername().isEmpty()
				|| user.getCountry().isEmpty() || user.getCity().isEmpty() || user.getAddress().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if type has the correct values
		if (!user.getType().equals("simple") && !user.getType().equals("admin")) {
			return Response.status(400).entity("User type must be simple or admin").build();
		}
		//Check if the given id exists
		if (u == null) {
			return Response.status(400).entity("No such user").build();
		}
		//Check if there is already a user with the same username
		for (User usr: list) {
			 if (usr.getUsername().equals(user.getUsername()) && !usr.getUserId().equals(id)) {
				 return Response.status(400).entity("There is already a user with that username").build();
			 }
		}
		//Update the user
		user.setPassword(u.getPassword());
		user.setCancellation_end_day(u.getCancellation_end_day());
		user.setStatus(u.getStatus());
		if(dao.updateUser(id, user)) {
			return Response.ok().entity("User updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

	@DELETE
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteUser(@PathParam("id") String id) {
		//Check if the given id exists
		User user = dao.findUser(id);
		if (user == null) {
			return Response.status(400).entity("No such user").build();
		}
		//Delete the announcement
		if(dao.deleteUser(id)) {
			return Response.ok().entity("User deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
		
	}
	
	@PUT
	@Path("/{id}/updateStatus")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateStatus(@PathParam("id") String id, User user) {
		//Check if all fields have been set
		User u = dao.findUser(id);
		if (user.getType()==null || user.getStatus()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if type has the correct values
		if (!user.getType().equals("simple") && !user.getType().equals("admin")) {
			return Response.status(400).entity("User type must be simple or admin").build();
		}
		//Check if status has the correct values
		if (!user.getStatus().equals("pending") && !user.getStatus().equals("approved")) {
			return Response.status(400).entity("Status must be pending or approved").build();
		}
		//Check if the given id exists
		if (u == null) {
			return Response.status(400).entity("No such user").build();
		}
		//Update the user
		if(dao.updateUserStatus(id, user)) {
			return Response.ok().entity("User updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
		
	}
	
	@PUT
	@Path("/{id}/updateCancellations")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateCancellations(@PathParam("id") String id, User user) {
		User u = dao.findUser(id);
		//Check if all fields have been set
		if (user.getCancellation_end_day()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (user.getCancellation_end_day().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if the given id exists
		if (u == null) {
			return Response.status(400).entity("No such user").build();
		}
		//Update the user
		if(dao.updateUserCancellations(id, user)) {
			return Response.ok().entity("User updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
		
	}
	
	@PUT
	@Path("/{id}/updatePassword")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updatePassword(@PathParam("id") String id, User user) {
		//Check if all fields have been set
		User u = dao.findUser(id);
		if (user.getPassword()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (user.getPassword().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if the given id exists
		if (u == null) {
			return Response.status(400).entity("No such user").build();
		}
		//Update the user
		if(dao.updateUserPassword(id, user)) {
			return Response.ok().entity("User updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
		
	}
}
