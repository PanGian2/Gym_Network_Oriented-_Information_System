package net.codejava.ws;

import java.util.Date;
import java.sql.Time;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.List;
import java.util.Map;
import java.util.UUID;

import javax.ws.rs.Consumes;
import javax.ws.rs.DELETE;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.PUT;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.QueryParam;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.MultivaluedMap;
import javax.ws.rs.core.Response;
import javax.ws.rs.core.UriInfo;

@Path("/calendars")
public class CalendarResource {
	private CalendarDAO dao = CalendarDAO.getInstance();
	private ProgramDAO programDao = ProgramDAO.getInstance();
	private Program_TrainerDAO program_trainerDao = Program_TrainerDAO.getInstance();

	@GET
	@Produces(MediaType.APPLICATION_JSON)
	public Response getCalendar(@Context UriInfo uriInfo) {
		//Check if there are queries
		MultivaluedMap<String,String> queries =  uriInfo.getQueryParameters();
		if (!queries.isEmpty()) {
			if (queries.containsKey("program")) {
				//There is a program query
				//Return all calendars of solo programs based on query value
				List<String> pid = queries.get("program");
				List<Calendar> list = dao.findCalendarsByProgramId(pid.get(0));
				if (list==null) {
					return Response.noContent().build();
				} else {
					return Response.ok(list).build();
				}
			} else if (queries.containsKey("trainer") && queries.containsKey("group_program")) {
				//There are a group_program and a trainer query
				//Return all calendars of group programs based on the query values
				List<String> tid = queries.get("trainer");
				List<String> pid = queries.get("group_program");
				List<Calendar> list = dao.findCalendarsByGroupIds(pid.get(0),tid.get(0));
				
				if (list==null) {
					return Response.noContent().build();
				} else {
					return Response.ok(list).build();
				}
			} else if (queries.containsKey("group_program")) {
				//There is a group_program
				//Return all calendars of group programs based on the query value
				List<String> pid = queries.get("group_program");
				List<Calendar> list = dao.findCalendarsByGroupPid(pid.get(0));
				if (list==null) {
					return Response.noContent().build();
				} else {
					return Response.ok(list).build();
				}
			}
			else {
				return Response.noContent().build();
			}
		} else {
			//Return all calendars
			List<Calendar> list = dao.listCalendar();
			
			if (list.isEmpty()) {
				return Response.noContent().build();
			} else {
				return Response.ok(list).build();
			}
		}
	}
	
	@POST
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response addCalendar(List<Calendar> calendars) {
		//Check if all fields have been set
		List<Calendar> list = dao.listCalendar();
		for (Calendar calendar: calendars) {
			if (calendar.getDate()==null || calendar.getHour()==null) {
				return Response.status(400).entity("All fields must have a value").build();
			}
			if (calendar.getHour().isEmpty()) {
				return Response.status(400).entity("All fields must have a value").build();
			}
			
			//Capacity must be a positive integer
			if (calendar.getCapacity() < 0) {
				return Response.status(400).entity("Capacity must be a positive integer").build();
			}
			
			//Check if the given hour has the correct format
			try {
				Date hour = new SimpleDateFormat("HH:mm").parse(calendar.getHour());
		        Date from = new SimpleDateFormat("HH:mm").parse("10:00");
		        Date to = new SimpleDateFormat("HH:mm").parse("22:00");
				if (hour.before(from) || hour.after(to)) {
					return Response.status(400).entity("All programs must be done from 10:00 to 22:00").build();
				}
			} catch (ParseException e) {
				return Response.status(400).entity("Invalid time").build();
			}
			//There must be either a pair of group_program_pid and a trainer_tid or only program_pid. Make the appropriate checks
			if (calendar.getProgram_pid()!=null && calendar.getG_program_pid()!=null && calendar.getTrainer_id()!=null) {
				if (!calendar.getProgram_pid().isEmpty() && !calendar.getG_program_pid().isEmpty() && !calendar.getTrainer_id().isEmpty()) {
					return Response.status(400).entity("A calendar must have only an associated program id or a combined group_program id and a trainer id").build();
				}
				if (calendar.getG_program_pid().isEmpty() && !calendar.getTrainer_id().isEmpty()) {
					return Response.status(400).entity("A trainer must be included in case of group program").build();
				}
				if (!calendar.getG_program_pid().isEmpty() && calendar.getTrainer_id().isEmpty()) {
					return Response.status(400).entity("A group_program must be included in case of a trainer existing in the calendar").build();
				}
			} else if (calendar.getProgram_pid() != null && calendar.getG_program_pid()!=null && calendar.getTrainer_id()==null) {
				return Response.status(400).entity("A calendar must have only an associated program id or a combined group_program id and a trainer id").build();
			} else if (calendar.getProgram_pid() != null && calendar.getG_program_pid()==null && calendar.getTrainer_id()!=null) {
				return Response.status(400).entity("A calendar must have only an associated program id or a combined group_program id and a trainer id").build();
			} else if (calendar.getProgram_pid() == null && calendar.getG_program_pid()==null && calendar.getTrainer_id()!=null) {
				return Response.status(400).entity("A trainer must be included in case of group program").build();
			} else if (calendar.getProgram_pid() == null && calendar.getG_program_pid()!=null && calendar.getTrainer_id()==null) {
				return Response.status(400).entity("A group_program must be included in case of a trainer existing in the calendar").build();
			} else if (calendar.getProgram_pid() != null && calendar.getG_program_pid()==null && calendar.getTrainer_id()==null) {
				//Check the type of the given program
				if (!calendar.getProgram_pid().isEmpty()) {
					Program program = programDao.findProgram(calendar.getProgram_pid());
					if (program.getType() == 2) {
						return Response.status(400).entity("The associated program is a group program").build();
					}
					UUID id = UUID.randomUUID();
					String calendarid = id.toString();
					//Check if there is already a calendar at the same hour and day
					for (Calendar u: list) {
						if (u.getCalendarid().equals(calendarid)) {
							id = UUID.randomUUID();
							calendarid = id.toString();
						}
						if (u.getDate().equals(calendar.getDate()) && u.getHour().equals(calendar.getHour()) && u.getProgram_pid().equals(calendar.getProgram_pid()) && !calendar.getProgram_pid().isEmpty()) {
							return Response.status(400).entity("There is already a calendar at this day and hour for this program").build();
						}
					}
					calendar.setCalendarid(calendarid);
				} else {
					return Response.status(400).entity("Program Id must be filled").build();
				}
			} else if (calendar.getProgram_pid() == null && calendar.getG_program_pid()!=null && calendar.getTrainer_id()!=null) {
				//Check the type of the given program
				if (!calendar.getG_program_pid().isEmpty() && !calendar.getTrainer_id().isEmpty()) {
					Program program = programDao.findProgram(calendar.getG_program_pid());
					if (program.getType() == 1) {
						return Response.status(400).entity("The associated program is not a group program").build();
					}
					//Check if the given program and trainer are correct
					Program_Trainer pt = program_trainerDao.findProgram_Trainer(calendar.getG_program_pid(), calendar.getTrainer_id());
					if (pt == null) {
						return Response.status(400).entity("There isn't a combination of this group program with this trainer").build();
					}
					UUID id = UUID.randomUUID();
					String calendarid = id.toString();
					//Check if there is already a calendar at the same hour and day
					for (Calendar u: list) {
						if (u.getCalendarid().equals(calendarid)) {
							id = UUID.randomUUID();
							calendarid = id.toString();
						}
						if (u.getDate().equals(calendar.getDate()) && u.getHour().equals(calendar.getHour()) && u.getG_program_pid().equals(calendar.getG_program_pid()) 
								&& u.getTrainer_id().equals(calendar.getTrainer_id()) && !calendar.getG_program_pid().isEmpty()) {
							return Response.status(400).entity("There is already a calendar at this day and hour for this group program").build();
						}
					}
					calendar.setCalendarid(calendarid);
				}
			}
		}
		//Create the calendar
		if(dao.createCalendar(calendars)) {
			return Response.status(200).entity("The calendar was created successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	@GET
	@Path("/program/{id}/dates/{date}/hours")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showHours(@PathParam("id") String id, @PathParam("date") java.sql.Date date) {
		//Return all calendars based on the program id and the given date
		List<Calendar> calendar = dao.findProgramHours(id, date);
			
		if (calendar==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(calendar).build();
		}
	}
	
	@GET
	@Path("/group_program/{pid}/trainer/{tid}/dates/{date}/hours")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showHours(@PathParam("pid") String pid, @PathParam("date") java.sql.Date date, @PathParam("tid") String tid) {
		//Return all calendars based on the group program id, the trainer id and the given date
		List<Calendar> calendar = dao.findGroupProgramHours(pid, tid, date);
			
		if (calendar==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(calendar).build();
		}
	}
	
	@GET
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response showCalendar(@PathParam("id") String id) {
		//Return booking based on the id
		Calendar calendar = dao.findCalendar(id);
			
		if (calendar==null) {
			return Response.noContent().build();
		} else {
			return Response.ok(calendar).build();
		}
	}
		
		
	@PUT
	@Path("/{id}")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateCalendar(@PathParam("id") String id, Calendar calendar) {
		//Check if all fields have been set
		Calendar u = dao.findCalendar(id);

		if (calendar.getDate()==null || calendar.getHour()==null) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		if (calendar.getHour().isEmpty()) {
			return Response.status(400).entity("All fields must have a value").build();
		}
		//Check if capacity is above 0
		if (calendar.getCapacity() < 0) {
			return Response.status(400).entity("Capacity must be a positive integer").build();
		}
		//Check if the given hour has the correct format
		try {
			Date hour = new SimpleDateFormat("HH:mm").parse(calendar.getHour());
	        Date from = new SimpleDateFormat("HH:mm").parse("10:00");
	        Date to = new SimpleDateFormat("HH:mm").parse("22:00");
			if (hour.before(from) || hour.after(to)) {
				return Response.status(400).entity("All programs must be done from 10:00 to 22:00").build();
			}
		} catch (ParseException e) {
			return Response.status(400).entity("Invalid time").build();
		}
		
		//There must be either a pair of group_program_pid and a trainer_tid or only program_pid. Make the appropriate checks
		if (!calendar.getProgram_pid().isEmpty() && !calendar.getG_program_pid().isEmpty() && !calendar.getTrainer_id().isEmpty()) {
			return Response.status(400).entity("A calendar must have only an associated program id or a combined group_program id and a trainer id").build();
		}
		if (calendar.getG_program_pid().isEmpty() && !calendar.getTrainer_id().isEmpty()) {
			return Response.status(400).entity("A trainer must be included in case of group program").build();
		}
		if (!calendar.getG_program_pid().isEmpty() && calendar.getTrainer_id().isEmpty()) {
			return Response.status(400).entity("A group_program must be included in case of a trainer existing in the calendar").build();
		}
		//Check the type of the given program
		if (!calendar.getProgram_pid().isEmpty()) {
			Program program = programDao.findProgram(calendar.getProgram_pid());
			if (program.getType() == 2) {
				return Response.status(400).entity("The associated program is a group program").build();
			}
		}
		//Check the type of the given program
		if (!calendar.getG_program_pid().isEmpty()) {
			Program program = programDao.findProgram(calendar.getG_program_pid());
			if (program.getType() == 1) {
				return Response.status(400).entity("The associated program is not a group program").build();
			}
			//Check if the given program and trainer are correct
			Program_Trainer pt = program_trainerDao.findProgram_Trainer(calendar.getG_program_pid(), calendar.getTrainer_id());
			if (pt == null) {
				return Response.status(400).entity("There isn't a combination of this group program with this trainer").build();
			}
		}
		//Check if the given id exists
		if (u == null) {
			return Response.status(400, "No such calendar").build();
		}
		List<Calendar> list = dao.listCalendar(); 
		//Check if there is already a calendar at the same hour and day
		for (Calendar c: list) {
			if (c.getDate().equals(calendar.getDate()) && c.getHour().equals(calendar.getHour()) && c.getProgram_pid().equals(calendar.getProgram_pid()) && !calendar.getProgram_pid().isEmpty() 
					&& !c.equals(id)) {
				return Response.status(400).entity("There is already a calendar at this day and hour for this program").build();
			}
			if (u.getDate().equals(calendar.getDate()) && u.getHour().equals(calendar.getHour()) && u.getG_program_pid().equals(calendar.getG_program_pid()) 
					&& u.getTrainer_id().equals(calendar.getTrainer_id()) && !calendar.getG_program_pid().isEmpty() ) {
				return Response.status(400).entity("There is already a calendar at this day and hour for this group program").build();
			}
		}
		//Update the calendar
		if(dao.updateCalendar(id, calendar)) {
			return Response.ok().entity("Calendar updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}

	@DELETE
	@Path("/{id}")
	@Produces(MediaType.APPLICATION_JSON)
	public Response deleteCalendar(@PathParam("id") String id) {
		//Check if the given id exists
		Calendar calendar = dao.findCalendar(id);
		if (calendar == null) {
			return Response.status(400).entity("No such calendar").build();
		}
		//Delete the calendar
		if(dao.deleteCalendar(id)) {
			return Response.ok().entity("Calendar deleted successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
	}
	
	
	@PUT
	@Path("/{id}/updateCapacityAndHour")
	@Consumes(MediaType.APPLICATION_JSON)
	@Produces(MediaType.APPLICATION_JSON)
	public Response updateCalendarCapacityAndHour(@PathParam("id") String id, Calendar calendar) {
		Calendar u = dao.findCalendar(id);
		
		//Capacity must be a positive integer
		if (calendar.getCapacity() < 0) {
			return Response.status(400).entity("Capacity must be a positive integer").build();
		}
		//Check if the given id exists 
		if (u == null) {
			return Response.status(400, "No such calendar").build();
		}
		//If hour is given, check if it is in the correct format
		if (calendar.getHour() != null) {
			try {
				Date hour = new SimpleDateFormat("HH:mm").parse(calendar.getHour());
				Date from = new SimpleDateFormat("HH:mm").parse("10:00");
				Date to = new SimpleDateFormat("HH:mm").parse("22:00");
				if (hour.before(from) || hour.after(to)) {
					return Response.status(400).entity("All programs must be done from 10:00 to 22:00").build();
				}
			} catch (ParseException e) {
				return Response.status(400).entity("Invalid time").build();
			}
			//Check if there is already a calendar at the same hour and day
			List<Calendar> list = dao.listCalendar();
			for (Calendar c : list) {
				if (u.getProgram_pid() != null) {
					if (c.getDate().equals(u.getDate()) && c.getHour().equals(calendar.getHour())
							&& c.getProgram_pid().equals(u.getProgram_pid()) && !c.getCalendarid().equals(id)) 
					{
						return Response.status(400).entity("There is already a calendar at this day and hour for this program").build();
					}
				} else if (u.getG_program_pid() != null) {
					if (c.getDate().equals(u.getDate()) && c.getHour().equals(calendar.getHour())
							&& c.getG_program_pid().equals(u.getG_program_pid())
							&& c.getTrainer_id().equals(u.getTrainer_id()) && !c.getCalendarid().equals(id)) 
					{
						return Response.status(400).entity("There is already a calendar at this day and hour for this group program").build();
					}
				}
			}
		}
		//Update the calendar
		if(dao.updateCapacityAndHour(id, calendar)) {
			return Response.ok().entity("Calendar updated successfully").build();
		}
		else {
			return Response.status(500).entity("Something went wrong").build();
		}
		
		
		
		
		

		
		
	}

}
