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

@Path("/bookings")
public class BookingsResource {
	private BookingsDAO dao = BookingsDAO.getInstance();
	private UserDAO userDao = UserDAO.getInstance();
	private CalendarDAO calendarDao = CalendarDAO.getInstance();
	
	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response listOfBookigs() {
		//Returns all bookings
		List<Bookings> list = dao.listBookings();
		
		if (list.isEmpty()) {
			return Response.noContent().build();
		} else {
			return Response.ok(list).build();
		}
	}

	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addBookings(Bookings bookings) {
		//Check if all fields have been set
		List<Bookings> list = dao.listBookings();
		if (bookings.getStatus()==null || bookings.getUserid()==null || bookings.getCalendarid()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (bookings.getStatus().isEmpty() || bookings.getUserid().isEmpty() || bookings.getCalendarid().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if given calendarid exists
		Calendar calendar = calendarDao.findCalendar(bookings.getCalendarid());
		if (calendar == null) {
			return Response.status(400).entity("No such calendar").build();
		}
		//Check if the capacity is above zero
		if (calendar.getCapacity()==0) {
			return Response.status(400).entity("This time slot for this day is full!").build();
		}
		//Check if given userid exists
		User user = userDao.findUser(bookings.getUserid());
		if (user==null) {
			return Response.status(400).entity("No such user").build();
		}
		//Check if user has more than 2 cancellations
		if (user.getCancellations()==2) {
			return Response.status(400).entity("You are not allowed to do bookings for this week").build();
		}
		//Check if status has the correct values
		if (!bookings.getStatus().equals("Reserved") && !bookings.getStatus().equals("Cancelled") && !bookings.getStatus().equals("Completed")) {
			return Response.status(400).entity("Booking status must be reserved or cancelled").build();
		}
		UUID id = UUID.randomUUID();
		String bookingid = id.toString();
		//Check if there is already a booking with the given id
		for (Bookings u: list) {
			if (u.getBookingid().equals(bookings.getBookingid())) {
				id = UUID.randomUUID();
				bookingid = id.toString();
			}
			if (u.getCalendarid().equals(bookings.getCalendarid()) && (u.getUserid().equals(bookings.getUserid()) && bookings.getStatus() == "Reserved")) {
				return Response.status(400).entity("There is already a booking on this day by this user").build();
			}
		}
		//Create the booking
		bookings.setBookingid(bookingid);
		
		if(dao.createBookings(bookings)) {
			return Response.status(200).entity("Booking created successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@GET
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showBookings(@PathParam("id") String id) {
		//Return booking based on the id
		Bookings bookings = dao.findBookings(id);
			
		if (bookings==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(bookings).build();
		}
	}
		
		
	@PUT
	@Path("/{id}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateBookings(@PathParam("id") String id, Bookings bookings) {
		//Check if all fields have been set
		Bookings u = dao.findBookings(id);
		if (bookings.getStatus()==null || bookings.getUserid()==null || bookings.getCalendarid()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (bookings.getStatus().isEmpty() || bookings.getUserid().isEmpty() || bookings.getCalendarid().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if given calendarid exists
		Calendar calendar = calendarDao.findCalendar(bookings.getCalendarid());
		if (calendar == null) {
			return Response.status(400).entity("No such calendar").build();
		}
		//Check if given userid exists
		User user = userDao.findUser(bookings.getUserid());
		if (user==null) {
			return Response.status(400).entity("No such user").build();
		}
		//Check if status has the correct values
		if (!bookings.getStatus().equals("Reserved") && !bookings.getStatus().equals("Cancelled") && !bookings.getStatus().equals("Completed")) {
			return Response.status(400).entity("Booking status must be reserved or cancelled").build();
		}
		//Check if the given id exists
		if (u == null) {
			return Response.status(400).entity("No such bookings").build();
		}
		
		//Check if user has done the same booking again
		List<Bookings> list = dao.listBookings();
		for (Bookings b: list) {
			if (b.getCalendarid().equals(bookings.getCalendarid()) && b.getUserid().equals(bookings.getUserid()) && !b.getBookingid().equals(id)) {
				return Response.status(400).entity("There is already a booking on this day by this user").build();
			}
		}

		//Update the booking
		if(dao.updateBookings(id, bookings)) {
			return Response.ok().entity("Bookings updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

	@DELETE
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteBookings(@PathParam("id") String id) {
		Bookings bookings = dao.findBookings(id);
		//Check if the given id exists
		if (bookings == null) {
			return Response.status(400).entity("No such bookings").build();
		}

		//Delete the announcement
		if(dao.deleteBookings(id)) {
			return Response.ok().entity("Bookings deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@GET
	@Path("/user/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showBookingsFromUser(@PathParam("id") String id) {
		//Return all bookings made by the user
		List<Bookings> bookings = dao.findBookingsFromUser(id);
			
		if (bookings==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(bookings).build();
		}
	}
	

}
