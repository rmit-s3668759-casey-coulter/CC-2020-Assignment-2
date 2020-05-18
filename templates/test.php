// putenv('GOOGLE_APPLICATION_CREDENTIALS=CC-Assignment-2-7372e69e9d4d');
  // require __DIR__ . '/vendor/autoload.php';

  // use Google\Cloud\BigQuery\BigQueryClient;
  // use Google\Cloud\Core\ExponentialBackoff;

  // $projectId = 'cc-assignment-2-275700';

  // echo "test1";

  // //if button was pushed
  // if (isset($_POST['ip_address_enter'])) {
  //   echo "test3";
  //   //if data was entered
  //   if (strlen($_POST['ip_address']) > 0) {
  //     $geonameID = $_POST['ip_address'];
  //     $query = "SELECT city_name, country_name FROM `cc-assignment-2-275700.IP_GeoLocation.GeoLite2_City_Locations` WHERE geoname_id = {$geonameID}";
          
  //     //search location
  //     $bigQuery = new BigQueryClient([
  //       'projectId' => $projectId
  //     ]);
  //     $jobConfig = $bigQuery->query($query);
  //     $job = $bigQuery->startQuery($jobConfig);
          
  //     $backoff = new ExponentialBackoff(10);
  //     $backoff->execute(function () use ($job) {
  //       $job->reload();
  //       if (!$job->isComplete()) {
  //         throw new Exception('Job has not yet completed', 500);
  //       }
  //     });
  //     $queryResults = $job->queryResults();
          
  //     $i = 0;
  //     foreach ($queryResults as $row) {
  //       foreach ($row as $column => $value) {
  //         printf('%s: %s' . PHP_EOL, $column, json_encode($value));
  //       }
  //     }
  //   }
  //   else {
  //     echo "Error:  Please enter a IP address and submit again";
  //   }
  // }

  // echo "test2";

  <!-- <!DOCTYPE html>
<html>
  <head>
    <title>Cloud Computing - Assignment 2</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
  </head>
  <body>
    <div class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <div class="navbar-brand">App Name</div>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="/images">Image Gallery</a></li>
          <li><a href="/map">Map</a></li>
          <li><a href="/iplocations">IP Locations</a></li>
        </ul>
      </div>
    </div>

    <div class="container">
      <h3>IP Address Finder</h3>
      <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="ip">Enter IP Address without the fullstops</label>
            <input type="text" name="ip_address" id="ip_address" class="form-control"/>

            <button id="ip_address_enter" name="ip_address_enter" class="btn btn-success">Submit</button>
          </div>
      </form>
    </div>
  </body>
</html> -->