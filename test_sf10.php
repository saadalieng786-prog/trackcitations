<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SalesForce;
use App\Integrations\Salesforce\SalesforceService;

$salesForce = SalesForce::first();

$sf = new SalesforceService(
    $salesForce->sf_instance_url,
    $salesForce->sf_access_token
);

$date = '2024-01-01T00:00:00.000+0000';
$query = "SELECT Id, CreatedDate, Title FROM ContentVersion WHERE CreatedDate > {$date} AND IsLatest = true ORDER BY CreatedDate ASC LIMIT 5";
$result = $sf->apiCall('/services/data/v58.0/query', ['q' => $query]);

echo "Oldest ContentVersion records after {$date}:\n";
foreach($result['records'] as $record) {
    echo "{$record['CreatedDate']} - {$record['Title']} (ID: {$record['Id']})\n";
}

$query2 = "SELECT Id, CreatedDate, Name FROM Attachment WHERE CreatedDate > {$date} ORDER BY CreatedDate ASC LIMIT 5";
$result2 = $sf->apiCall('/services/data/v58.0/query', ['q' => $query2]);

echo "\nOldest Attachment records after {$date}:\n";
foreach($result2['records'] as $record) {
    echo "{$record['CreatedDate']} - {$record['Name']} (ID: {$record['Id']})\n";
}
