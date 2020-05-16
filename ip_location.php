<?php

    putenv('GOOGLE_APPLICATION_CREDENTIALS=idoyarkey.json');
    require __DIR__ . '/vendor/autoload.php';

    use Google\Cloud\BigQuery\BigQueryClient;
    use Google\Cloud\Core\ExponentialBackoff;

    $projectId = 'assignment2-275505';

    //if button was pushed
    if(isset($_POST['ip_address_enter'])){

        //if data was entered
        if(strlen($_POST['ip_address']) > 0){

            $geonameID = $_POST['ip_address'];
            $query = "SELECT city_name, country_name FROM `assignment2-275505.IP_GeoLocation.GeoLite2_City_Locations` WHERE geoname_id = {$geonameID}";
            
            //search location

            $bigQuery = new BigQueryClient([
                'projectId' => $projectId,
            ]);
            $jobConfig = $bigQuery->query($query);
            $job = $bigQuery->startQuery($jobConfig);
            
            $backoff = new ExponentialBackoff(10);
            $backoff->execute(function () use ($job) {
                $job->reload();
                if (!$job->isComplete()) {
                    throw new Exception('Job has not yet completed', 500);
                }
            });
            $queryResults = $job->queryResults();
            
            $i = 0;
            foreach ($queryResults as $row) {
                foreach ($row as $column => $value) {
                    printf('%s: %s' . PHP_EOL, $column, json_encode($value));
                }
            }

        }
  }
?>

<html>
<body>

    <form action="" method="post">
    <div>Enter IP Address:<textarea name="ip_address" rows="1" cols="60"></textarea></div>
    <div><input type="submit" value="Enter" name="ip_address_enter"></div>
    </form>

</body>
</html>