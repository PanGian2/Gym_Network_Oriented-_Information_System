<?php
if (isset($_GET['group_program']) && isset($_GET['trainer']) && isset($_GET['date'])) {
    //The user provided the group_program id, the trainer id and a date
    $program = $_GET['group_program'];
    $trainer = $_GET['trainer'];
    $date = $_GET['date'];

    $url = "http://localhost:9999/GymWService/rest/calendars/group_program/" . $program . "/trainer/" . $trainer . "/dates/" . $date . "/hours";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response);
    $now = date("H:i");
    $today = date("Y-m-d");
    $arr = [];
    //If today is prior to calendar date or the current hour is before the calendar hour
    foreach ($data as $post) {
        if ($today < $post->date || ($today == $post->date &&  $post->hour)) {
            //create a json with the calendar's hour, capacity and id
            $Hour = array(
                "hour" => $post->hour,
                "capacity" => $post->capacity,
                "calendarid" => $post->calendarid
            );
            array_push($arr, $Hour);
        }
    }
    //Send a json {results: [array]}
    echo json_encode(['results' => $arr]);
} elseif (isset($_GET['program']) && isset($_GET['date'])) {
    //The user provided the program id and a date
    $program = $_GET['program'];
    $date = $_GET['date'];

    $url = "http://localhost:9999/GymWService/rest/calendars/program/" . $program . "/dates/" . $date . "/hours";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response);
    $now = date("H:i");
    $today = date("Y-m-d");
    $arr = [];
    //If today is prior to calendar date or the current hour is before the calendar hour
    foreach ($data as $post) {
        if ($today < $post->date || ($today == $post->date &&  $post->hour)) {
            //create a json with the calendar's hour, capacity and id
            $Hour = array(
                "hour" => $post->hour,
                "capacity" => $post->capacity,
                "calendarid" => $post->calendarid
            );
            array_push($arr, $Hour);
        }
    }
    //Send a json {results: [array]}
    echo json_encode(['results' => $arr]);
} elseif (isset($_GET['program']) && isset($_GET['pt'])) {
    //The user provided the program id and that is a group program
    $program = $_GET['program'];
    $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $program . "/trainers";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response);
    //Return all the trainers of this program as options
    if ($data == null) {
        echo $data;
    } else {
        echo "<option disabled selected value=''>Επιλέξτε Γυμναστή/στρια</option>";
        foreach ($data as $tr) {
            echo "<option value=", $tr->tiid, ">", $tr->name, " ", $tr->last_name, "</option>";
        }
    }
} elseif (isset($_GET['program'])) {
    //The user provided the program id
    $program = $_GET['program'];

    $url = "http://localhost:9999/GymWService/rest/programs/" . $program;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response);
    //Return the type of the program
    echo $data->type;
} elseif (isset($_GET['group_program']) && isset($_GET['trainer'])) {
    //The user provided the group program id and the trainer id
    $program = $_GET['group_program'];
    $trainer = $_GET['trainer'];

    $url = "http://localhost:9999/GymWService/rest/calendars?group_program=" . $program . "&trainer=" . $trainer;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response);

    $url = "http://localhost:9999/GymWService/rest/programs/" . $program;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $program = json_decode($response);
    $arr = [];
    $dates = [];
    foreach ($data as $post) {
        //Return a json with data needed for the calendar table
        $hour = date("H", strtotime($post->hour)); //hour format
        $h = date("H:i", strtotime($post->hour)); //hour and minutes format
        $due = date("H:i", strtotime('+ ' . $program->duration . ' minutes', strtotime($post->hour))); //adds to hour the duration minutes of program
        $date = date("d-m-Y", strtotime($post->date));
        $day = date("l", strtotime($post->date)); //day format e.g Sunday
        $value = "<p>" . $program->program_name . "</p><p>" . $h . " - " . $due . "</p>"; //the content of the cell
        $Table = array(
            "hour" => $hour,
            "day" => $day,
            "content" => $value,
            "dateFormatted" => $date,
            "dateActual" => $post->date,
        );
        $today = date("Y-m-d");
        $now = date("H:i");
        //If today is prior to calendar date or the current hour is before the calendar hour
        if ($today < $post->date || ($today == $post->date && $now < $h)) {
            array_push($arr, $Table);
        }
    }
    //Return a json {results: [array]}
    echo json_encode(['results' => $arr]);
} elseif (isset($_GET['solo_program'])) {
    //The user provided the program id
    $program = $_GET['solo_program'];

    $url = "http://localhost:9999/GymWService/rest/calendars?program=" . $program;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response);

    $url = "http://localhost:9999/GymWService/rest/programs/" . $program;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $program = json_decode($response);
    $arr = [];
    $dates = [];

    foreach ($data as $post) {
        //Return a json with data needed for the calendar table
        $hour = date("H", strtotime($post->hour));
        $h = date("H:i", strtotime($post->hour));
        $due = date("H:i", strtotime('+ ' . $program->duration . ' minutes', strtotime($post->hour)));
        $date = date("d-m-Y", strtotime($post->date));
        $day = date("l", strtotime($post->date));
        $value = "<p>" . $program->program_name . "</p><p>" . $h . " - " . $due . "</p>";
        $Table = array(
            "hour" => $hour,
            "day" => $day,
            "content" => $value,
            "dateFormatted" => $date,
            "dateActual" => $post->date,
        );
        $today = date("Y-m-d");
        $now = date("H:i");
        //If today is prior to calendar date or the current hour is before the calendar hour
        if ($today < $post->date || ($today == $post->date && $now < $h)) {
            array_push($arr, $Table);
        }
    }
    //Return a json {results: [array]}
    echo json_encode(['results' => $arr]);
}
