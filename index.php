<?php
# Index file to allow us to run MVC

require_once __DIR__ . '/vendor/autoload.php';

# Load the application code.
$app = require __DIR__ . '/src/app.php';
require __DIR__ . '/src/controllers.php';

# Bootstrap the slim framework to handle the request.
$app->run();