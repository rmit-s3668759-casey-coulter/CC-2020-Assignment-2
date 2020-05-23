<?php
# Create a new Silex Application with Twig.  
use Google\Cloud\Samples\AppEngine\GettingStarted\CloudSqlDataModel;
use Google\Cloud\Storage\StorageClient;

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

# Get container
$container = $app->getContainer();

# Register Twig
$container['view'] = function ($container) {
    return new Slim\Views\Twig(__DIR__ . '/../templates');
};

# Cloud Storage bucket
$container['bucket'] = function ($container) {
    # Get the environment storage bucket variable from the app.yaml file
    $bucketName = getenv('GOOGLE_STORAGE_BUCKET');
    $storage = new StorageClient([
        'projectId' => $projectId,
    ]);
    $bucket = $storage->bucket($bucketName);
    # Return the bucket
    return $bucket;
};

# Get the Cloud SQL MySQL connection object
$container['cloudsql'] = function ($container) {
    # Data Model
    # Get the environment database variable from the app.yaml file
    $dbName = getenv('CLOUDSQL_DATABASE_NAME') ?: 'gallery';
    $dbConn = getenv('CLOUDSQL_CONNECTION_NAME');
    $dbUser = getenv('CLOUDSQL_USER');
    $dbPass = getenv('CLOUDSQL_PASSWORD');
    $dsn = "mysql:unix_socket=/cloudsql/${dbConn};dbname=${dbName}";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return new CloudSqlDataModel($pdo);
};

return $app;