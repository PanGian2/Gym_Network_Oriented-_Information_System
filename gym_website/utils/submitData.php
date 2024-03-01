<?php
session_start();
require_once("../interfaces/partials/flash_messages.php");

//Handle post methods from forms
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formType = $_POST["formType"];

    if ($formType === "userUpdate") {
        //Update user request
        $username = $email = $name = $last_name = $country = $city = $address = $cancellations = $type = "";
        $inputsFilled = true;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if username is empty
            $userId = $_POST["id"];
            if (empty(trim($_POST["username"]))) {
                $inputsFilled = false;
            } else {
                $username = trim($_POST["username"]);
            }

            // Check if password is empty
            if (empty(trim($_POST["email"]))) {
                $inputsFilled = false;
            } else {
                $email = trim($_POST["email"]);
            }

            // Check if name is empty
            if (empty(trim($_POST["name"]))) {
                $inputsFilled = false;
            } else {
                $name = trim($_POST["name"]);
            }

            // Check if last_name is empty
            if (empty(trim($_POST["last_name"]))) {
                $inputsFilled = false;
            } else {
                $last_name = trim($_POST["last_name"]);
            }

            // Check if country is empty
            if (empty(trim($_POST["country"]))) {
                $inputsFilled = false;
            } else {
                $country = trim($_POST["country"]);
            }

            // Check if city is empty
            if (empty(trim($_POST["city"]))) {
                $inputsFilled = false;
            } else {
                $city = trim($_POST["city"]);
            }

            // Check if address is empty
            if (empty(trim($_POST["address"]))) {
                $inputsFilled = false;
            } else {
                $address = trim($_POST["address"]);
            }

            $cancellations = $_POST["cancellations"];

            // Check if type is empty
            if (empty(trim($_POST["type"]))) {
                $inputsFilled = false;
            } else {
                $type = trim($_POST["type"]);
            }


            if ($inputsFilled == true) {
                // All the inputs are not empty

                $url = "http://localhost:9999/GymWService/rest/users/" . $userId;

                //Create a json with all the inputs
                $User = array(
                    "username" => $username,
                    "email" => $email,
                    "name" => $name,
                    "last_name" => $last_name,
                    "country" => $country,
                    "city" => $city,
                    "address" => $address,
                    "cancellations" => $cancellations,
                    "type" => $type,

                );
                $jsonUser = json_encode($User);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonUser);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["updateError"] = true;
                    flash("UpdateError", $message, FLASH_ERROR);
                    if (isset($_POST["source"]) && $_POST["source"] == "profile") {
                        //if request was from user profile redirect to this page
                        header("location: ../interfaces/simple_user/user_profile.php");
                    } else {
                        //else redirect to admin show page
                        header("location: ../interfaces/admin_views/users/show.php");
                    }
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["updateSuccess"] = true;
                    flash("updateSuccess", $message, FLASH_SUCCESS);
                    if (isset($_POST["source"]) && $_POST["source"] == "profile") {
                        //if request was from user profile redirect to this page
                        header("location: ../interfaces/simple_user/user_profile.php");
                    } else {
                        //else redirect to admin show page
                        header("location: ../interfaces/admin_views/users/show.php");
                    }
                }
            }
        }
    } elseif ($formType === "userCreate") {
        //Crete user request
        $username = $email = $name = $last_name = $country = $city = $address = $cancellations = $type = "";
        $inputsFilled = true;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if username is empty
            if (empty(trim($_POST["username"]))) {
                $inputsFilled = false;
            } else {
                $username = trim($_POST["username"]);
            }

            // Check if email is empty
            if (empty(trim($_POST["email"]))) {
                $inputsFilled = false;
            } else {
                $email = trim($_POST["email"]);
            }

            // Check if name is empty
            if (empty(trim($_POST["name"]))) {
                $inputsFilled = false;
            } else {
                $name = trim($_POST["name"]);
            }

            // Check if last_name is empty
            if (empty(trim($_POST["last_name"]))) {
                $inputsFilled = false;
            } else {
                $last_name = trim($_POST["last_name"]);
            }

            // Check if country is empty
            if (empty(trim($_POST["country"]))) {
                $inputsFilled = false;
            } else {
                $country = trim($_POST["country"]);
            }

            // Check if city is empty
            if (empty(trim($_POST["city"]))) {
                $inputsFilled = false;
            } else {
                $city = trim($_POST["city"]);
            }

            // Check if address is empty
            if (empty(trim($_POST["address"]))) {
                $inputsFilled = false;
            } else {
                $address = trim($_POST["address"]);
            }

            $cancellations = $_POST["cancellations"];


            // Check if type is empty
            if (empty(trim($_POST["type"]))) {
                $inputsFilled = false;
            } else {
                $type = trim($_POST["type"]);
            }


            if ($inputsFilled == true) {
                //All inputs are filled

                $url = "http://localhost:9999/GymWService/rest/users";

                //Create a json with all the inputs
                $User = array(
                    "username" => $username,
                    "password" => password_hash("gymUser12345", PASSWORD_DEFAULT),
                    "email" => $email,
                    "name" => $name,
                    "last_name" => $last_name,
                    "country" => $country,
                    "city" => $city,
                    "address" => $address,
                    "cancellations" => $cancellations,
                    "type" => $type,
                    "status" => "approved",
                );
                $jsonUser = json_encode($User);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonUser);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode == 200) {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createSuccess"] = true;
                    flash("createSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/users/show.php");
                } else {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createError"] = true;
                    flash("createError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/users/show.php");
                }
            }
        }
    } elseif ($formType === "trainerUpdate") {
        //Update trainer request

        $name = $last_name = $phone_number = $email = "";
        $inputsFilled = true;
        $trainerId = $_POST["id"];

        // Check if name is empty
        if (empty(trim($_POST["name"]))) {
            $inputsFilled = false;
        } else {
            $name = trim($_POST["name"]);
        }
        // Check if last name is empty
        if (empty(trim($_POST["last_name"]))) {
            $inputsFilled = false;
        } else {
            $last_name = trim($_POST["last_name"]);
        }
        // Check if phone is empty
        if (empty(trim($_POST["phone_number"]))) {
            $inputsFilled = false;
        } else {
            $phone_number = trim($_POST["phone_number"]);
        }
        // Check if email is empty
        if (empty(trim($_POST["email"]))) {
            $inputsFilled = false;
        } else {
            $email = trim($_POST["email"]);
        }

        if ($inputsFilled == true) {
            // All the inputs are not empty

            $url = "http://localhost:9999/GymWService/rest/trainers/" . $trainerId;
            //Create a json with all the inputs
            $Trainer = array(
                "name" => $name,
                "last_name" => $last_name,
                "phone_number" => $phone_number,
                "email" => $email
            );
            $jsonTrainer = json_encode($Trainer);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonTrainer);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 200) {
                //All good
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["updateSuccess"] = true;
                flash("updateSuccess", $message, FLASH_SUCCESS);
                header("location: ../interfaces/admin_views/trainers/show.php");
            } else {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["updateError"] = true;
                flash("UpdateError", $message, FLASH_ERROR);
                header("location: ../interfaces/admin_views/trainers/show.php");
            }
        }
    } elseif ($formType === "trainerCreate") {
        //Create trainer request
        $name = $last_name = $phone_number = $email = "";

        $inputsFilled = true;

        // Check if name is empty
        if (empty(trim($_POST["name"]))) {
            $inputsFilled = false;
        } else {
            $name = trim($_POST["name"]);
        }
        // Check if last name is empty
        if (empty(trim($_POST["last_name"]))) {
            $inputsFilled = false;
        } else {
            $last_name = trim($_POST["last_name"]);
        }
        // Check if phone is empty
        if (empty(trim($_POST["phone_number"]))) {
            $inputsFilled = false;
        } else {
            $phone_number = trim($_POST["phone_number"]);
        }
        // Check if email is empty
        if (empty(trim($_POST["email"]))) {
            $inputsFilled = false;
        } else {
            $email = trim($_POST["email"]);
        }


        if ($inputsFilled == true) {
            // All the inputs are not empty

            $url = "http://localhost:9999/GymWService/rest/trainers";
            //Create a json with all the inputs
            $Trainer = array(
                "name" => $name,
                "last_name" => $last_name,
                "phone_number" => $phone_number,
                "email" => $email
            );
            $jsonTrainer = json_encode($Trainer);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonTrainer);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 200) {
                //All good
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["createSuccess"] = true;
                flash("createSuccess", $message, FLASH_SUCCESS);
                header("location: ../interfaces/admin_views/trainers/show.php");
            } else {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["createError"] = true;
                flash("createError", $message, FLASH_ERROR);
                header("location: ../interfaces/admin_views/trainers/show.php");
            }
        }
    } elseif ($formType === "programUpdate") {
        //Update program request
        $img_url = $program_name = $whatdescription = $whydescription = $type = $trainer = $duration = "";

        $inputsFilled = true;
        $pid = $_POST["id"];
        // Check if img is empty
        if (empty(trim($_POST["img_url"]))) {
            $inputsFilled = false;
        } else {
            $img_url = trim($_POST["img_url"]);
        }
        // Check if program name is empty
        if (empty(trim($_POST["program_name"]))) {
            $inputsFilled = false;
        } else {
            $program_name = trim($_POST["program_name"]);
        }
        // Check if what description is empty
        if (empty(trim($_POST["whatdescription"]))) {
            $inputsFilled = false;
        } else {
            $whatdescription = trim($_POST["whatdescription"]);
        }
        // Check if why description is empty
        if (empty(trim($_POST["whydescription"]))) {
            $inputsFilled = false;
        } else {
            $whydescription = trim($_POST["whydescription"]);
        }
        // Check if type is empty
        if (empty(trim($_POST["type"]))) {
            $inputsFilled = false;
        } else {
            $type = trim($_POST["type"]);
        }
        // Check if duration is empty
        if (empty(trim($_POST["duration"]))) {
            $inputsFilled = false;
        } else {
            $duration = trim($_POST["duration"]);
        }

        if ($inputsFilled == true) {
            // All the inputs are not empty

            $url = "http://localhost:9999/GymWService/rest/programs/" . $pid;
            //Create a json with all the inputs
            $Program = array(
                "img_url" => $img_url,
                "program_name" => $program_name,
                "whatdescription" => $whatdescription,
                "whydescription" => $whydescription,
                "type" => $type,
                "duration" => $duration
            );
            $jsonProgram = json_encode($Program);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonProgram);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 200) {
                //All good
                //The returned message is after the GMT in the initial response
                $success_message =  substr($response, strpos($response, "GMT") + 3);
                $noError = true;
                if (isset($_POST['tid'])) {
                    //If there is a tid, then the program is a group program
                    foreach ($_POST['tid'] as $key => $value) {
                        //There can be multiple trainers submitted so iterate
                        $tid = $value;
                        if (isset($_POST["removeTrainer"])) {
                            //If the removeTrainer is submitted then the trainer must be removed from the program
                            if ($_POST["pt_count"] == sizeof($_POST["removeTrainer"])) {
                                //User can't delete all trainers from a group program
                                $noError = false;
                                $_SESSION["updateError"] = true;
                                flash("updateError", "Πρέπει να υπάρχει τουλάχιστον ένας γυμναστής σε αυτό το πρόγραμμα!", FLASH_ERROR);
                                header("location: ../interfaces/admin_views/programs/show.php?id=" . $pid);
                                break;
                            } else {
                                //Iterate through removeTrainer values
                                foreach ($_POST['removeTrainer'] as $key => $value) {
                                    $removeTrainer = $value;
                                    if ($removeTrainer == $tid) {
                                        //Remove trainer
                                        $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $pid . "/trainers/" . $tid;
                                        $curl = curl_init($url);
                                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                                        curl_setopt($curl, CURLOPT_HEADER, true);
                                        $response = curl_exec($curl);
                                        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                        curl_close($curl);
                                        if ($httpcode == 200) {
                                            continue;
                                        } else {
                                            echo $response;
                                        }
                                    }
                                }
                            }
                        } else {
                            //Iterate through the hidden input trainer
                            foreach ($_POST['trainer'] as $key1 => $value1) {
                                $current_trainer = $value1;
                                //If the id of the trainer on the input is not the same as the one in the select
                                //then update proram_trainer
                                if ($current_trainer != $tid && $key == $key1) {
                                    $url = "http://localhost:9999/GymWService/rest/program_trainer/programs/" . $pid . "/trainers/" . $current_trainer;

                                    //Create a json with all the ids of the program and the old trainer
                                    $Program_Trainer = array(
                                        "program_pid" => $pid,
                                        "trainers_tiid" => $tid,
                                    );
                                    $jsonProgram_Trainer = json_encode($Program_Trainer);

                                    $curl = curl_init($url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonProgram_Trainer);
                                    curl_setopt($curl, CURLOPT_HEADER, true);
                                    $response = curl_exec($curl);
                                    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                    if ($httpcode == 200) {
                                        //All went well
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                }
                if (isset($_POST["new_tid"])) {
                    //User wants to add a new trainer to this program
                    foreach ($_POST['new_tid'] as $key => $value) {
                        //For each new trainer add it to program_trainer
                        $tid = $value;
                        $url = "http://localhost:9999/GymWService/rest/program_trainer";

                        //Create the entry as urlencoded form type
                        $Program_Trainer = "program_name=" . $program_name . "&trainers_tiid=" . $tid;

                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $Program_Trainer);
                        curl_setopt($curl, CURLOPT_HEADER, true);
                        $response = curl_exec($curl);
                        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                        if ($httpcode != 200) {
                            //Something went wrong
                            //The returned message is after the GMT in the initial response
                            $message = substr($response, strpos($response, "GMT") + 3);
                            $_SESSION["updateError"] = true;
                            flash("updateError", $message, FLASH_ERROR);
                            header("location: ../interfaces/admin_views/programs/show.php?id=" . $pid);
                        }
                    }
                }
                if ($noError == true) {
                    //Everything went well
                    //The returned message is after the GMT in the initial response
                    $_SESSION["updateSuccess"] = true;
                    flash("updateSuccess", $success_message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/programs/show.php?id=" . $pid);
                }
            } else {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["updateError"] = true;
                flash("updateError", $message, FLASH_ERROR);
                header("location: ../interfaces/admin_views/programs/show.php?id=" . $pid);
            }
        }
    } elseif ($formType === "programCreate") {
        $img_url = $program_name = $whatdescription = $whydescription = $type = $trainer = $duration = "";

        $inputsFilled = true;
        // Check if img is empty
        if (empty(trim($_POST["img_url"]))) {
            $inputsFilled = false;
        } else {
            $img_url = trim($_POST["img_url"]);
        }
        // Check if  name is empty
        if (empty(trim($_POST["program_name"]))) {
            $inputsFilled = false;
        } else {
            $program_name = trim($_POST["program_name"]);
        }
        // Check if what description is empty
        if (empty(trim($_POST["whatdescription"]))) {
            $inputsFilled = false;
        } else {
            $whatdescription = trim($_POST["whatdescription"]);
        }
        // Check if why description is empty
        if (empty(trim($_POST["whydescription"]))) {
            $inputsFilled = false;
        } else {
            $whydescription = trim($_POST["whydescription"]);
        }
        // Check if type is empty
        if (empty(trim($_POST["type"]))) {
            $inputsFilled = false;
        } else {
            $type = trim($_POST["type"]);
        }
        // Check if duration is empty
        if (empty(trim($_POST["duration"]))) {
            $inputsFilled = false;
        } else {
            $duration = trim($_POST["duration"]);
        }

        if ($inputsFilled == true) {
            // All the inputs are not empty

            $url = "http://localhost:9999/GymWService/rest/programs";
            //Create a json with all the inputs
            $Program = array(
                "img_url" => $img_url,
                "program_name" => $program_name,
                "whatdescription" => $whatdescription,
                "whydescription" => $whydescription,
                "type" => $type,
                "duration" => $duration
            );
            $jsonProgram = json_encode($Program);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonProgram);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode == 200) {
                //All good
                //The returned message is after the GMT in the initial response
                $success_message = substr($response, strpos($response, "GMT") + 3);
                if (isset($_POST['tid'])) {
                    //If a tid is submitted then the program is a group one
                    foreach ($_POST['tid'] as $key => $value) {
                        //For each trainer id add an entry on program_trainer
                        $tid = $value;

                        $url = "http://localhost:9999/GymWService/rest/program_trainer";

                        //Create the entry as urlencoded form type
                        $Program_Trainer = "program_name=" . $program_name . "&trainers_tiid=" . $tid;

                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $Program_Trainer);
                        curl_setopt($curl, CURLOPT_HEADER, true);
                        $response = curl_exec($curl);
                        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        if ($httpcode == 200) {
                            continue;
                        }
                    }
                }
                //All good
                //The returned message is after the GMT in the initial response
                $_SESSION["createSuccess"] = true;
                flash("createSuccess", $success_message, FLASH_SUCCESS);
                header("location: ../interfaces/admin_views/programs/index.php");
            } else {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["createError"] = true;
                flash("createError", $message, FLASH_ERROR);
                header("location: ../interfaces/admin_views/programs/index.php");
            }
        }
    } elseif ($formType === "announcementCreate") {
        //Create announcement request

        $title = $content =  "";
        $inputsFilled = true;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Check if title is empty
            if (empty(trim($_POST["title"]))) {
                $inputsFilled = false;
            } else {
                $title = trim($_POST["title"]);
            }

            // Check if content is empty
            if (empty(trim($_POST["content"]))) {
                $inputsFilled = false;
            } else {
                $content = trim($_POST["content"]);
            }


            if ($inputsFilled == true) {
                // All the inputs are not empty
                date_default_timezone_set("Europe/Athens");
                $dateposted = date("Y-m-d"); //get current date
                $url = "http://localhost:9999/GymWService/rest/announcements";

                //Create a json with all the inputs
                $Announcements = array(
                    "title" => $title,
                    "dateposted" => $dateposted,
                    "content" => $content,
                );
                $jsonAnnouncements = json_encode($Announcements);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonAnnouncements);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createError"] = true;
                    flash("createError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/announcements/show.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createSuccess"] = true;
                    flash("createSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/announcements/show.php");
                }
            }
        }
    } elseif ($formType === "announcementUpdate") {
        //Update announcement request
        $title = $content = $dateposted = "";
        $inputsFilled = true;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if title is empty
            $anid = $_POST["id"];
            if (empty(trim($_POST["title"]))) {
                $inputsFilled = false;
            } else {
                $title = trim($_POST["title"]);
            }

            // Check if content is empty
            if (empty(trim($_POST["content"]))) {
                $inputsFilled = false;
            } else {
                $content = trim($_POST["content"]);
            }

            // Check if dateposted is empty
            if (empty(trim($_POST["dateposted"]))) {
                $inputsFilled = false;
            } else {
                $dateposted = trim($_POST["dateposted"]);
            }


            if ($inputsFilled == true) {
                // All the inputs are not empty

                $url = "http://localhost:9999/GymWService/rest/announcements/" . $anid;
                //Create a json with all the inputs
                $Announcements = array(
                    "title" => $title,
                    "dateposted" => $dateposted,
                    "content" => $content,
                );
                $jsonAnnouncements = json_encode($Announcements);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonAnnouncements);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["updateError"] = true;
                    flash("updateError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/announcements/show.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["updateSuccess"] = true;
                    flash("updateSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/announcements/show.php");
                }
            }
        }
    } elseif ($formType === "calendarCreate") {
        //Create calendar request
        $date = $hour = $capacity = $day = "";
        $inputsFilled = true;

        // Check if date is empty
        if (empty(trim($_POST["date"]))) {
            $inputsFilled = false;
        } else {
            $date = trim($_POST["date"]);
        }

        //Check if day is empty
        if (empty(trim($_POST["day"]))) {
            $inputsFilled = false;
        } else {
            $day = trim($_POST["day"]);
        }

        //Check if hour is empty
        if (empty(trim($_POST["hour"]))) {
            $inputsFilled = false;
        } else {
            $hour = trim($_POST["hour"]);
        }

        $capacity = $_POST["capacity"];

        //Get dates for one month, starting from the date that user submitted
        $date = date("Y-m-d", strtotime($day, strtotime($date)));
        $date1 = date("Y-m-d", strtotime('+1week', strtotime($date)));
        $date2 = date("Y-m-d", strtotime('+1week', strtotime($date1)));
        $date3 = date("Y-m-d", strtotime('+1week', strtotime($date2)));


        if ($inputsFilled == true) {
            // All the inputs are not empty

            if (isset($_POST["program_pid"])) {
                //If program_pid is set then the program is a solo one
                $pid = trim($_POST["program_pid"]);
                $url = "http://localhost:9999/GymWService/rest/calendars";

                //Create 4 json with all the inputs
                $Calendar = array(
                    "date" => $date,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "program_pid" => $pid,
                );
                $Calendar1 = array(
                    "date" => $date1,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "program_pid" => $pid,
                );
                $Calendar2 = array(
                    "date" => $date2,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "program_pid" => $pid,
                );
                $Calendar3 = array(
                    "date" => $date3,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "program_pid" => $pid,
                );
                //Create an array with the four calendars and then convert it to json
                $calendarArr = [$Calendar, $Calendar1, $Calendar2, $Calendar3];
                $jsonCalendar = json_encode($calendarArr);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCalendar);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createError"] = true;
                    flash("createError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createSuccess"] = true;
                    flash("createSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                }
            } elseif (isset($_POST["group_program_pid"]) && isset($_POST["tid"])) {
                //If group_program_pid and tid are set then the program is a group one
                $pid = trim($_POST["group_program_pid"]);
                $tid = trim($_POST["tid"]);

                $url = "http://localhost:9999/GymWService/rest/calendars";
                //Create 4 json with all the inputs
                $Calendar = array(
                    "date" => $date,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "g_program_pid" => $pid,
                    "trainer_id" => $tid
                );
                $Calendar1 = array(
                    "date" => $date1,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "g_program_pid" => $pid,
                    "trainer_id" => $tid
                );
                $Calendar2 = array(
                    "date" => $date2,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "g_program_pid" => $pid,
                    "trainer_id" => $tid
                );
                $Calendar3 = array(
                    "date" => $date3,
                    "hour" => $hour,
                    "capacity" => $capacity,
                    "g_program_pid" => $pid,
                    "trainer_id" => $tid
                );
                //Create an array with the four calendars and then convert it to json
                $calendarArr = [$Calendar, $Calendar1, $Calendar2, $Calendar3];
                $jsonCalendar = json_encode($calendarArr);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCalendar);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createError"] = true;
                    flash("createError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createSuccess"] = true;
                    flash("createSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                }
            }
        }
    } elseif ($formType === "calendarUpdate") {
        //Update calendar request
        $calendarid = $_POST["id"];
        $hour = $capacity = "";
        $inputsFilled = true;

        $capacity = $_POST["capacity"];

        if ($inputsFilled == true) {
            if (isset($_POST["hour"])) {
                //If hour is set then the user wants to change the hour too
                $hour = trim($_POST["hour"]);
                $url = "http://localhost:9999/GymWService/rest/calendars/" . $calendarid . "/updateCapacityAndHour";
                //Create a json with all the inputs
                $Calendar = array(
                    "hour" => $hour,
                    "capacity" => $capacity,
                );
                $jsonCalendar = json_encode($Calendar);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCalendar);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createError"] = true;
                    flash("createError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createSuccess"] = true;
                    flash("createSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                }
            } else {
                $url = "http://localhost:9999/GymWService/rest/calendars/" . $calendarid . "/updateCapacityAndHour";
                //Create a json with all the inputs
                $Calendar = array(
                    "capacity" => $capacity,
                );
                $jsonCalendar = json_encode($Calendar);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCalendar);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createError"] = true;
                    flash("createError", $message, FLASH_ERROR);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["createSuccess"] = true;
                    flash("createSuccess", $message, FLASH_SUCCESS);
                    header("location: ../interfaces/admin_views/calendars/index.php");
                }
            }
        }
    } elseif ($formType === "bookingCreate") {
        //Create booking request
        $url = "http://localhost:9999/GymWService/rest/bookings";
        $calendarId =  trim($_POST["calendarid"]);
        $capacity =  $_POST["capacity"];
        $userId =  trim($_POST["userId"]);
        //Create a json with all the inputs
        $Booking = array(
            "calendarid" => $calendarId,
            "userid" => $userId,
            "status" => "Reserved",
        );
        $jsonBooking = json_encode($Booking);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBooking);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpcode != 200) {
            //Something went wrong
            //The returned message is after the GMT in the initial response
            $message = substr($response, strpos($response, "close") + 5);
            $_SESSION["createError"] = true;
            flash("createError", $message, FLASH_ERROR);
            header("location: ../interfaces/simple_user/home.php");
        } else {
            //All good
            $success_message = substr($response, strpos($response, "GMT") + 3);
            $url = "http://localhost:9999/GymWService/rest/calendars/" . $calendarId . "/updateCapacityAndHour";
            //Decrease the capacity of the calendar by one
            $Calendar = array(
                "capacity" => $capacity - 1,
            );
            $jsonCalendar = json_encode($Calendar);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCalendar);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpcode != 200) {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["createError"] = true;
                flash("createError", $message, FLASH_ERROR);
                header("location: ../interfaces/simple_user/home.php");
            } else {
                //All good
                //The returned message is after the GMT in the initial response
                $_SESSION["createSuccess"] = true;
                flash("createSuccess", $success_message, FLASH_SUCCESS);
                header("location: ../interfaces/simple_user/home.php");
            }
        }
    } elseif ($formType === "cancelBooking") {
        //Cance booking request
        $bookingid = trim($_POST["bookingid"]);
        $userid = trim($_POST["userid"]);
        $calendarId = trim($_POST["calendarid"]);
        $capacity = $_POST["capacity"];
        $cancellations = $_POST["cancellations"];

        $url = "http://localhost:9999/GymWService/rest/bookings/" . $bookingid;

        //Create a json with all the inputs
        $Booking = array(
            "userid" => $userid,
            "status" => 'Cancelled',
            "calendarid" => $calendarId,
        );
        $jsonBooking = json_encode($Booking);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBooking);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpcode != 200) {
            //Something went wrong
            //The returned message is after the GMT in the initial response
            $message = substr($response, strpos($response, "GMT") + 3);
            $_SESSION["updateError"] = true;
            flash("updateError", $message, FLASH_ERROR);
            header("location: ../interfaces/simple_user/home.php");
        } else {
            //All good
            //The returned message is after the GMT in the initial response
            $success_message = substr($response, strpos($response, "GMT") + 3);
            $url = "http://localhost:9999/GymWService/rest/users/" . $userid . "/updateCancellations";

            //Increase user's cancellations by 1 and set the cancellation end day
            $Cancel = array(
                "cancellations" => $cancellations + 1,
                "cancellation_end_day" => date("Y-m-d", strtotime("Sunday")),
            );
            $jsonCancel = json_encode($Cancel);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCancel);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpcode != 200) {
                //Something went wrong
                //The returned message is after the GMT in the initial response
                $message = substr($response, strpos($response, "GMT") + 3);
                $_SESSION["updateError"] = true;
                flash("updateError", $message, FLASH_ERROR);
                header("location: ../interfaces/simple_user/home.php");
            } else {
                //All good
                $url = "http://localhost:9999/GymWService/rest/calendars/" . $calendarId . "/updateCapacityAndHour";
                //Increse the calendar's capacity by 1
                $Calendar = array(
                    "capacity" => $capacity + 1,
                );
                $jsonCalendar = json_encode($Calendar);

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonCalendar);
                curl_setopt($curl, CURLOPT_HEADER, true);
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($httpcode != 200) {
                    //Something went wrong
                    //The returned message is after the GMT in the initial response
                    $message = substr($response, strpos($response, "GMT") + 3);
                    $_SESSION["updateError"] = true;
                    flash("updateError", $message, FLASH_ERROR);
                    header("location: ../interfaces/simple_user/home.php");
                } else {
                    //All good
                    //The returned message is after the GMT in the initial response
                    $_SESSION["updateSuccess"] = true;
                    flash("updateSuccess", $success_message, FLASH_SUCCESS);
                    header("location: ../interfaces/simple_user/home.php");
                }
            }
        }
    } elseif ($formType === "passwordUpdate") {
        //Update password request
        $userid = $_POST["id"];
        $password = $_POST["password"];
        $pass1 = $pass2 = $pass3 = "";
        $inputsFilled = true;

        // Check if pass1 is empty
        if (empty(trim($_POST["pass1"]))) {
            $inputsFilled = false;
        } else {
            $pass1 = trim($_POST["pass1"]);
        }
        // Check if pass2 is empty
        if (empty(trim($_POST["pass2"]))) {
            $inputsFilled = false;
        } else {
            $pass2 = trim($_POST["pass2"]);
        }
        // Check if pass3 is empty
        if (empty(trim($_POST["pass3"]))) {
            $inputsFilled = false;
        } else {
            $pass3 = trim($_POST["pass3"]);
        }

        if ($inputsFilled == true) {
            // All the inputs are not empty
            $url = "http://localhost:9999/GymWService/rest/users/" . $userid . "/updatePassword";

            //pass1 verified with user's password
            if (password_verify($pass1, $password)) {
                //pass2 and pass3 are the same
                if ($pass2 === $pass3) {
                    //Create a json with the new hashed password
                    $User = array(
                        "password" => password_hash($pass3, PASSWORD_DEFAULT)
                    );
                    $jsonUser = json_encode($User);

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));;
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonUser);
                    curl_setopt($curl, CURLOPT_HEADER, true);
                    $response = curl_exec($curl);
                    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                    if ($httpcode != 200) {
                        //Something went wrong
                        //The returned message is after the GMT in the initial response
                        $message = substr($response, strpos($response, "GMT") + 3);
                        $_SESSION["updateError"] = true;
                        flash("updateError", $message, FLASH_ERROR);
                        header("location: ../interfaces/simple_user/user_profile.php");
                    } else {
                        //All good
                        //The returned message is after the GMT in the initial response
                        $message = substr($response, strpos($response, "GMT") + 3);
                        $_SESSION["updateSuccess"] = true;
                        flash("updateSuccess", $message, FLASH_SUCCESS);
                        header("location: ../interfaces/simple_user/user_profile.php");
                    }
                    //wrong pass3
                } else {
                    $_SESSION["updateError"] = true;
                    flash("updateError", "Δεν είναι σωστός ο νέος κωδικός!", FLASH_ERROR);
                    header("location: ../interfaces/simple_user/user_profile.php");
                }
                //wrong password
            } else {
                $_SESSION["updateError"] = true;
                flash("updateError", "Λάθος Κωδικός!", FLASH_ERROR);
                header("location: ../interfaces/simple_user/user_profile.php");
            }
        }
    }
}
