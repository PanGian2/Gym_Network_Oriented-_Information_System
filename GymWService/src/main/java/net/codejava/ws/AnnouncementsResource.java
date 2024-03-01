package net.codejava.ws;

import java.util.List;
import java.util.UUID;

import javax.ws.rs.Consumes;
import javax.ws.rs.DELETE;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.PUT;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;

@Path("/announcements")
public class AnnouncementsResource {
	private AnnouncementsDAO dao = AnnouncementsDAO.getInstance();
	
	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response listOfAnnouncements() {
		//Returns all announcements
		List<Announcements> list = dao.listAnnouncements();
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}

	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addAnnouncements(Announcements announcements) {
		//Check if there is already an announcement with the given id
		List<Announcements> list = dao.listAnnouncements();
		UUID id = UUID.randomUUID();
		String anid = id.toString();
		for (Announcements u: list) {
			if (u.getAnid().equals(anid)) {
				id = UUID.randomUUID();
				anid = id.toString();
			}
		}
		//Create the announcement
		announcements.setAnid(anid);
		if(dao.createAnnouncements(announcements)) {
			return Response.status(200).entity("Announcement created successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@GET
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showAnnouncements(@PathParam("id") String id) {
		//Return the announcement based on the id
		Announcements announcements = dao.findAnnouncements(id);
			
		if (announcements==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(announcements).build();
		}
	}
		
		
	@PUT
	@Path("/{id}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateAnnouncements(@PathParam("id") String id, Announcements announcements) {
		//Check if the given id exists
		Announcements u = dao.findAnnouncements(id);
		if (u == null) {
			return Response.status(400).entity("No such announcement").build();
		}
		
		//Update the announcement
		if(dao.updateAnnouncements(id, announcements)) {
			return Response.ok().entity("Announcement updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

	@DELETE
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteAnnouncements(@PathParam("id") String id) {
		//Check if the given id exists
		Announcements announcements = dao.findAnnouncements(id);
		if (announcements == null) {
			return Response.status(400).entity("No such announcement").build();
		}

		//Delete the announcement
		if(dao.deleteAnnouncements(id)) {
			return Response.ok().entity("Announcement deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

}