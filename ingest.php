<?php

require_once('vendor/autoload.php');
include_once('config.example.php');


// these params should be defined in the example.config.php file
$codebaseHQAccount = new GarethMidwood\CodebaseHQ\CodebaseHQAccount(
    $apiUser,
    $apiKey,
    $apiHost
);

$system = new GarethMidwood\TicketReporting\System\Adapter\CodebaseHQ($codebaseHQAccount);

$period = new GarethMidwood\TicketReporting\System\TimeSession\Period(new DateTime(), new DateTime('-30 days'));

$system->populateUserData();
$system->populateProjectData($period, 'creode');


$csvFormatter = new GarethMidwood\TicketReporting\ReportFormat\Csv();

$projectOverviewReport = new GarethMidwood\TicketReporting\Report\Project\Overview($system, $csvFormatter);
$projectOverviewReport->generate('reports/project-overview-report.csv', $period);

$projectTimesReport = new GarethMidwood\TicketReporting\Report\Project\Times($system, $csvFormatter);
$projectTimesReport->generate('reports/project-times-report.csv', $period);

$projectTimesReport = new GarethMidwood\TicketReporting\Report\Project\TicketStatus($system, $csvFormatter);
$projectTimesReport->generate('reports/project-ticketstatus-report.csv', $period);
