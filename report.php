<?php

require_once('vendor/autoload.php');
include_once('config.example.php');

// load data from storage
$userData = file_get_contents('reports/data/users.txt');
$projectData = file_get_contents('reports/data/all.txt');

// unserialize data to retrieve collections
$users = unserialize($userData);
$projects = unserialize($projectData);


$period =   new GarethMidwood\TicketReporting\System\TimeSession\Period(
                new DateTime(),
                new DateTime('-14 days')
            );

$reportData = new GarethMidwood\TicketReporting\Report\Data($users, $projects, $period);

$projectOverviewReport = new GarethMidwood\TicketReporting\Report\User\Overview($reportData);
$projectOverviewReport->generate('reports/generated/user-overview-report.html');

$projectOverviewReport = new GarethMidwood\TicketReporting\Report\Project\Overview($reportData);
$projectOverviewReport->generate('reports/generated/project-overview-report.html');

$projectTimesReport = new GarethMidwood\TicketReporting\Report\Project\Times($reportData);
$projectTimesReport->generate('reports/generated/project-times-report.html');

$projectTimesReport = new GarethMidwood\TicketReporting\Report\Project\TicketStatus($reportData);
$projectTimesReport->generate('reports/generated/project-ticketstatus-report.html');
