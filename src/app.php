<?php
/**
 * Create a new Silex Application with Twig.  Configure it for debugging.
 * Follows Silex Skeleton pattern.
 */
use Google\Cloud\Samples\AppEngine\GettingStarted\CloudSqlDataModel;
// [START gae_php_app_storage_client_import]
use Google\Cloud\Storage\StorageClient;

// [END gae_php_app_storage_client_import]

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

// Get container
$container = $app->getContainer();

// Register Twig
$container['view'] = function ($container) {
    return new Slim\Views\Twig(__DIR__ . '/../templates');
};

// Cloud Storage bucket
$container['bucket'] = function ($container) {
    $bucketName = getenv('GOOGLE_STORAGE_BUCKET');
    // [START gae_php_app_storage_client_setup]
    // Your Google Cloud Storage bucket name and Project ID can be configured
    // however fits your application best.
    // $projectId = 'YOUR_PROJECT_ID';
    // $bucketName = 'YOUR_BUCKET_NAME';
    $storage = new StorageClient([
        'projectId' => $projectId,
    ]);
    $bucket = $storage->bucket($bucketName);
    // [END gae_php_app_storage_client_setup]
    return $bucket;
};

// Get the Cloud SQL MySQL connection object
$container['cloudsql'] = function ($container) {
    // Data Model
    $dbName = getenv('CLOUDSQL_DATABASE_NAME') ?: 'user-instance';
    $dbConn = getenv('CLOUDSQL_CONNECTION_NAME');
    $dbUser = getenv('CLOUDSQL_USER');
    $dbPass = getenv('CLOUDSQL_PASSWORD');
    // [START gae_php_app_cloudsql_client_setup]
    // Fill the variables below to match your Cloud SQL configuration.
    $dbConn = 'cc-riot-api:australia-southeast1:user-instance';
    $dbName = 'user-data';
    $dbUser = 'root';
    $dbPass = 'yesac12345';
    $dsn = "mysql:unix_socket=/cloudsql/${dbConn};dbname=${dbName}";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    // [END gae_php_app_cloudsql_client_setup]
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return new CloudSqlDataModel($pdo);
};

return $app;