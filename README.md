# ticketing-reports
A system that generates reports from ticketing systems

# What does it do?
Very simply, it generates timeboxed reports of tickets, times, projects and people from a ticketing system. A variety of report formats are available for generation.

# How does it work?
This package is designed to use existing PHP libraries for ticketing systems. For each system an adapter layer is required to translate the systems API output into something normalised that can be used for report generation.


# Usage
## Example
The `ingest.php` script in this repository serves as an example of how to generate reports.

Some further explanation is made below, in this example we're using [CodebaseHQ](https://www.codebasehq.com/) as our ticketing system

```php
// Create an instance of the API library for the ticket system
$codebaseHQAccount = new GarethMidwood\CodebaseHQ\CodebaseHQAccount(
    $apiUser,
    $apiKey,
    $apiHost
);

// Create an instance of the adapter for this system
$system = new GarethMidwood\TicketReporting\System\Adapter\CodebaseHQ($codebaseHQAccount);

// Set the period you want to report for (this may not be required, it depends on the report you're running)
$period = new GarethMidwood\TicketReporting\System\TimeSession\Period(new DateTime(), new DateTime('-7 days'));

// Populates the data for the system
$system->populateUserData();
// in this case we're just pulling data for a single project called 'creode'
$system->populateProjectData($period, 'creode');

// Choose the format you would like your report providing in
// Other formats can be created, as long as they implement the GarethMidwood\TicketReporting\ReportFormat\ReportFormat interface
$csvFormatter = new GarethMidwood\TicketReporting\ReportFormat\Csv();

// Create the report you want to generate
$projectOverviewReport = new GarethMidwood\TicketReporting\Report\ProjectOverview($system, $csvFormatter);
// and then generate it, the first parameter is the file to be output and can be a relative or absolute path
$projectOverviewReport->generate('reports/project-overview-report.csv', $period);
```

# Contributing
## Adding a new system
New systems must implement the `GarethMidwood\TicketReporting\System` interface, that is the only requirement.

Once you've created a new system you can use it to generate a report, just like you would with any other system

```php
$system = new You\Own\System\Class();
$csvFormatter = new GarethMidwood\TicketReporting\ReportFormat\Csv();

$projectOverviewReport = new GarethMidwood\TicketReporting\Report\ProjectOverview($system, $csvFormatter);
$projectOverviewReport->generate('reports/project-overview-report.csv', $period);
```

## Adding a new report formatter
New formatters must implement the `GarethMidwood\TicketReporting\ReportFormat\ReportFormat` interface, that is the only requirement.


## Sharing your class(es)
If you do create a class for a system or a report format then please share it!
The best route to do this is to add it to packagist with `garethmidwood/ticketing-reports` as a dependency.



