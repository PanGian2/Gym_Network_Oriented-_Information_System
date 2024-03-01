var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

const alert = document.getElementById("programAlert");
const program = document.getElementById("program");
const select = document.getElementById("trainer");
const date = document.getElementById("dateSelect");
const hour = document.getElementById("hourSelect");
var hours;

//Add event listeners to all selects
program.addEventListener("change", getOption);
select.addEventListener("change", getTrainerOption);
date.addEventListener("change", getDateOption);
hour.addEventListener("change", getHourOption);

//Function to get the option from program's select
function getOption() {

    const button = document.getElementById("nextBtn");
    const prevButton = document.getElementById("prevBtn");
    const trainerAlert = document.getElementById("trainersAlert");
    const programVal = document.getElementById("program").value;
    const trainers = document.getElementById("trainerSelect");
    const select = document.querySelectorAll(".trainer");
    const hour = document.getElementById("hourSelect");
    const capacity = document.getElementById("capacity_val");

    // Send an AJAX request to fetch info for the selected program
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../../utils/get_info.php?program=" + programVal, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //Response is the type of the program
            var response = xhr.response;
            //Group program
            if (response == 2) {
                document.getElementById("program").setAttribute("name", "group_program_pid");
                //Dispaly the second tab which is about trainers
                trainers.style.display = "";
                select.forEach((element) => element.removeAttribute("disabled"));
                // Send an AJAX request to fetch trainers for the selected program
                var request = new XMLHttpRequest();
                request.open("GET", "../../utils/get_info.php?pt=true&program=" + programVal, true);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        const trainers = request.responseText;
                        //There aren't trainers for this programs therefore display aler and disable button
                        if (trainers == "") {
                            trainerAlert.style.display = "";
                            button.setAttribute("disabled", "");
                        } else {
                            //There are trainers for this program
                            trainerAlert.style.display = "none";
                            button.removeAttribute("disabled");
                            button.setAttribute("onclick", "nextPrev(1)");
                            prevButton.setAttribute("onclick", "nextPrev(-1)");
                            //Fill the trainer select with options with the trainers of this program
                            document.getElementById("trainer").innerHTML = request.responseText;
                            hour.innerHTML = "<option selected disabled value=''>Επιλέξτε Ώρα</option>"
                        }

                    }
                };
                request.send();

            } else {
                //The program is solo
                document.getElementById("program").setAttribute("name", "program_pid");
                select.forEach((element) => element.setAttribute("disabled", ""));
                button.removeAttribute("disabled");
                //Go to the last tab and skip the second one
                button.setAttribute("onclick", "nextPrev(2)");
                prevButton.setAttribute("onclick", "nextPrev(-2)");
                // Send an AJAX request to fetch dates for the selected program
                var req = new XMLHttpRequest();
                req.open("GET", "../../utils/get_info.php?solo_program=" + programVal, true);
                req.onreadystatechange = function () {
                    if (req.readyState == 4 && req.status == 200) {
                        const calendars = JSON.parse(req.responseText);
                        var dates = [];
                        hour.innerHTML = "<option selected disabled value=''>Επιλέξτε Ώρα</option>"
                        date.innerHTML = "<option selected disabled value=''>Επιλέξτε Ημερομηνία</option>";
                        capacity.innerText = " ";
                        clearTable();
                        //Populate the table
                        calendars.results.forEach(element => {
                            var hour = element.hour;
                            var day = element.day;
                            var content = element.content;
                            addToTable(hour, day, content);
                            //Poppulate the date select with all the returned dates
                            if (!dates.includes(element.dateActual)) {
                                dates.push(element.dateActual);
                                var option = document.createElement("option");
                                option.value = element.dateActual;
                                option.innerText = element.day + " " + element.dateFormatted;
                                date.appendChild(option);
                            }

                        });

                    }
                };
                req.send();
            }
        }
    };
    xhr.send();
}

//Function to get the option from the trainer's select
function getTrainerOption() {
    const tiid = document.getElementById("trainer").value;
    const programVal = document.getElementById("program").value;
    const date = document.getElementById("dateSelect");
    const hour = document.getElementById("hourSelect");
    const capacity = document.getElementById("capacity_val");

    // Send an AJAX request to fetch dates for the selected program and trainer
    var req = new XMLHttpRequest();
    req.open("GET", "../../utils/get_info.php?trainer=" + tiid + "&group_program=" + programVal, true);
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            const calendars = JSON.parse(req.responseText);
            var dates = [];
            hour.innerHTML = "<option selected disabled value=''>Επιλέξτε Ώρα</option>";
            date.innerHTML = "<option selected disabled value=''>Επιλέξτε Ημερομηνία</option>";
            capacity.innerText = " ";
            clearTable();
            //Populate the calendar table
            calendars.results.forEach(element => {
                var hour = element.hour;
                var day = element.day;
                var content = element.content;

                addToTable(hour, day, content);

                //Poppulate the date select with all the returned dates
                if (!dates.includes(element.dateActual)) {
                    dates.push(element.dateActual);
                    var option = document.createElement("option");
                    option.value = element.dateActual;
                    option.innerText = element.day + " " + element.dateFormatted;
                    date.appendChild(option);
                }

            });

        }
    };
    req.send();
}

//Function to get the option from date's select
function getDateOption() {
    const date = document.getElementById("dateSelect").value;
    const hour = document.getElementById("hourSelect");
    const programVal = document.getElementById("program").value;

    //The program is a group
    if (document.getElementById("program").getAttribute("name") == "group_program_pid") {
        const tid = document.getElementById("trainer").value;

        // Send an AJAX request to fetch hours for the selected program, trainer and date
        var req = new XMLHttpRequest();
        req.open("GET", "../../utils/get_info.php?date=" + date + "&group_program=" + programVal + "&trainer=" + tid, true);
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                hour.innerHTML = "<option selected disabled value=''>Επιλέξτε Ώρα</option>"
                hours = JSON.parse(req.responseText);
                //Populate the hour select with the returned hours
                hours.results.forEach(element => {
                    var option = document.createElement("option");
                    option.value = element.hour;
                    option.innerText = element.hour;
                    hour.appendChild(option);
                });
            }
        };
        req.send();
    } else {
        //The program is solo
        //Send an AJAX request to fetch hours for the selected program and date
        var req = new XMLHttpRequest();
        req.open("GET", "../../utils/get_info.php?date=" + date + "&program=" + programVal, true);
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                hour.innerHTML = "<option selected disabled value=''>Επιλέξτε Ώρα</option>"
                hours = JSON.parse(req.responseText);
                //Populate the hour select with the returned hours
                hours.results.forEach(element => {
                    var option = document.createElement("option");
                    option.value = element.hour;
                    option.innerText = element.hour;
                    hour.appendChild(option);
                });
            }
        };
        req.send();
    }

}

//Function to get the option from the hour select
function getHourOption() {
    const button = document.getElementById("nextBtn");
    const capacity_val = document.getElementById("capacity_val");
    const hour = document.getElementById("hourSelect").value;
    const calendarInput = document.getElementById("calendarInput");
    const capacity = document.getElementById("capacity");
    const capAlert = document.getElementById("capacityAlert");

    //For each hour from the getDatesOption()
    hours.results.forEach(element => {
        if (hour == element.hour) {
            capacity_val.innerText = element.capacity;
            //If capacity is 0 display an aler and disable the submit button
            if (element.capacity <= 0) {
                capAlert.style.display = '';
                button.setAttribute("disabled", "");
                calendarInput.removeAttribute("value");
            } else {
                //Put the calendarid to the hidden input and show the remaining capacity
                capAlert.style.display = 'none';
                button.removeAttribute("disabled");
                calendarInput.setAttribute("value", element.calendarid);
                capacity.setAttribute("value", element.capacity)
            }

        }
    })


}

//Function to add some data on a specific cell of the table
function addToTable(row, day, data) {
    const cell = document.getElementById(row).getElementsByClassName(day)[0];
    cell.innerHTML = data;
}

//Function to clear all cells from the table
function clearTable() {
    var table = document.getElementsByClassName('table')[0];

    for (var i = 1; i < table.rows.length; i++) {
        var row = table.rows[i];

        for (var j = 1; j < row.cells.length; j++) {
            var cell = row.cells[j];
            cell.innerText = "";
        }
    }
}


function showTab(n) {
    // This function will display the specified tab of the form 
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    // fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    // run a function that displays the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form :
    if (currentTab >= x.length) {
        //the form gets submitted:
        document.getElementById("regForm").submit();
        return alert("Η Κράτηση έγινε με επιτυχία");
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    if (x[currentTab].getElementsByTagName("input").length != 0) {
        y = x[currentTab].getElementsByTagName("input");
    }
    else {
        y = x[currentTab].getElementsByTagName("select");
    }
    // A loop that checks every input field in the current tab:
    for (i = 0; i < 1; i++) {
        // If a field is empty...
        if (y[i].value == "") {
            // add an "invalid" class to the field:
            y[i].className += " invalid";
            // and set the current valid status to false:
            valid = false;
        }

    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    //add the "active" class to the current step:
    x[n].className += " active";
}
