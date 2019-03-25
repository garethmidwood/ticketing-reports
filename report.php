<?php

require_once('vendor/autoload.php');
include_once('config.example.php');

// load data from storage
$userData = file_get_contents('reports/data/users.txt');
$projectData = file_get_contents('reports/data/projects.txt');

// unserialize data to retrieve collections
$users = unserialize($userData);
$projects = unserialize($projectData);


$csvFormatter = new GarethMidwood\TicketReporting\ReportFormat\Csv();
$period =   new GarethMidwood\TicketReporting\System\TimeSession\Period(
                new DateTime(),
                new DateTime('-7 days')
            );


$projectOverviewReport =    new GarethMidwood\TicketReporting\Report\Project\Overview(
                                $users,
                                $projects,
                                $csvFormatter
                            );
$projectOverviewReport->generate('reports/generated/project-overview-report.csv', $period);


$projectTimesReport =   new GarethMidwood\TicketReporting\Report\Project\Times(
                            $users,
                            $projects,
                            $csvFormatter
                        );
$projectTimesReport->generate('reports/generated/project-times-report.csv', $period);


$projectTimesReport =   new GarethMidwood\TicketReporting\Report\Project\TicketStatus(
                            $users,
                            $projects,
                            $csvFormatter
                        );
$projectTimesReport->generate('reports/generated/project-ticketstatus-report.csv', $period);
