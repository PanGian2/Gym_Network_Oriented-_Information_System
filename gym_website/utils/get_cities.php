<?php

if (isset($_GET['country'])) {
    $country = $_GET['country'];

    $url = "https://countriesnow.space/api/v0.1/countries/cities/q?country=" . $country;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $data = json_decode($response, true);
    $cities = $data["data"];
    // Output cities as options for the select element

    foreach ($cities as $city) {
        echo "<option value=", $city, ">", $city, "</option>";
    }
}
