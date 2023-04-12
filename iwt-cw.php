<?php

// Read the query string parameters
$file = isset($_GET["file"]) ? $_GET["file"] : "";
$year = isset($_GET["year"]) ? $_GET["year"] : "";
$yearOp = isset($_GET["yearOp"]) ? $_GET["yearOp"] : "";
$tournament = isset($_GET["tournament"]) ? $_GET["tournament"] : "";
$winner = isset($_GET["winner"]) ? $_GET["winner"] : "";
$runnerUp = isset($_GET["runnerUp"]) ? $_GET["runnerUp"] : "";

// Check for any errors in query string parameters
if (empty($file) || empty($year) || empty($yearOp) || empty($tournament)) {
  $error = array("error" => "Missing required parameter(s)");
  echo json_encode($error);
  exit();
}

// Load contents of the file specified by the "file" parameter into a string
$data = file_get_contents($file);

// Decode the JSON string into an array
$results = json_decode($data, true);


// Filter all the results based on the "year" and "yearOp" parameters
if ($yearOp == "=") {
    $filtered_array = array_filter($results, function($value) use ($year) {
        return $value['year'] == $year;
    });
} else if ($yearOp == "<") { // Comparison operator is "<" or 'less than', filter the results to include only those with a year less than the specified year
    $filtered_array = array_filter($results, function($value) use ($year) {
        return $value['year'] < $year;
    });
} else if ($yearOp == ">") { // Comparison operator is ">" or 'greater than', filter the results to include only those with a year less than the specified year
    $filtered_array = array_filter($results, function($value) use ($year) {
        return $value['year'] > $year;
    });
}

// Filter results based on the "tournament" parameter
if ($tournament == "Any") {
    $tournament_filtered_array = $filtered_array;
} else {
    $tournament_filtered_array = array_filter($filtered_array, function($value) use ($tournament) {
        return $value['tournament'] == $tournament;
    });
}

// Filter results based on the "winner" parameter
function filter_by_winner($data, $winner = '') {
    $filtered_data = array();
    foreach ($data as $item) {
        if (empty($winner) || strpos(strtolower($item['winner']), strtolower($winner)) !== false) {
            $filtered_data[] = $item;
        }
    }
    return $filtered_data;
}

// Filter results based on the "runnerUp" parameter
function filter_by_runnerUp($data, $runnerUp = '') {
    $filtered_data = array();
    foreach ($data as $item) {
        if (empty($runnerUp) || strpos(strtolower($item['runner-up']), strtolower($runnerUp)) !== false) {
            $filtered_data[] = $item;
        }
    }
    return $filtered_data;
}


// Filter all results by winner
$results = filter_by_winner($tournament_filtered_array, $winner);

// Filter all results by runner-up
$results = filter_by_runnerUp($results, $runnerUp);

// Encode all results as a JSON array and output it
echo json_encode(array_values($results));
