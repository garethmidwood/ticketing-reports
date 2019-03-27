<?php

require_once('vendor/autoload.php');
include_once('config.example.php');


// these params should be defined in the example.config.php file
$codebaseHQAccount = new GarethMidwood\CodebaseHQ\CodebaseHQAccount($apiUser, $apiKey, $apiHost);

$system = new GarethMidwood\TicketReporting\System\Adapter\CodebaseHQ($codebaseHQAccount);
$period = new GarethMidwood\TicketReporting\System\TimeSession\Period(new DateTime(), new DateTime('-30 days'));

// retrieve the data from the system
$system->populateUserData();
$system->populateProjectData($period, 'creode');
// $system->populateProjectData($period);

// populate the time sessions for each user, based on the projects
$users = $system->users();
$projects = $system->projects();

foreach($projects as $project) {
    $project->populateUserTimeSessions($users);
}


// we'll serialize the data for writing to file
$userData = serialize($system->users());
$projectData = serialize($system->projects());

// write the saved data to file, to be used to generate reports
file_put_contents('reports/data/users.txt', $userData);
file_put_contents('reports/data/all.txt', $projectData);

