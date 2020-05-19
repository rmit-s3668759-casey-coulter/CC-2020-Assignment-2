<?php

namespace Google\Cloud\Samples\Bookshelf;
putenv('/../GOOGLE_APPLICATION_CREDENTIALS=CC-Assignment-2-7372e69e9d4d');
$projectId = 'cc-assignment-2-275700';

/*
 * Adds all the controllers to $app.  Follows Silex Skeleton pattern.
 */
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Google\Cloud\Storage\Bucket;
use ReCaptcha\ReCaptcha;
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
use Google\Cloud\Translate\TranslateClient;


$app->get('/', function (Request $request, Response $response) {
    return $response->withRedirect('/images');
})->setName('home');

$app->get('/images', function (Request $request, Response $response) {
    $token = (int) $request->getQueryParam('page_token');
    $imageList = $this->cloudsql->listImages(10, $token);

    return $this->view->render($response, 'list.html.twig', [
        'images' => $imageList['images'],
        'next_page_token' => $imageList['cursor'],
    ]);
})->setName('images');

$app->get('/translation', function (Request $request, Response $response) {
    return $this->view->render($response, 'translation.html.twig', [
        'fileName' => array()
    ]);
});

$app->post('/translation', function (Request $request, Response $response) {

    //if data was entered
    if(strlen($_POST['translate']) > 0){

        /** Uncomment and populate these variables in your code */
        $text = $_POST['translate'];
        $targetLanguage = 'en';  // Language to translate to

        $translate = new TranslateClient();
        $result = $translate->translate($text, [
            'target' => $targetLanguage,
        ]);

        $results = array();
        array_push($results,$result[source]);
        array_push($results,$result[text]);

        return $this->view->render($response, 'translation_results.html.twig', [
            'translate' => $translate,
            'results' => $results,
        ]);

    }

});

$app->get('/speech_to_text', function (Request $request, Response $response) {
    return $this->view->render($response, 'speech_to_text.html.twig', [
        'fileName' => array()
    ]);
});

$app->post('/speech_to_text', function (Request $request, Response $response) {
    if (strlen($fileName) > 0) {

        # The name of the audio file to transcribe
        $audioFile = __DIR__ . '/../test_new.flac';

        # get contents of a file into a string
        $content = file_get_contents($audioFile);

        # set string as audio content
        $audio = (new RecognitionAudio())
            ->setContent($content);

        # The audio file's encoding, sample rate and language
        $config = new RecognitionConfig([
            'encoding' => AudioEncoding::FLAC,
            'sample_rate_hertz' => 48000,
            'language_code' => 'en-US'
        ]);

        # Instantiates a client
        $client = new SpeechClient();

        # Detects speech in the audio file
        $response = $client->recognize($config, $audio);

        # Print most likely transcription
        $results = array();
        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript = $mostLikely->getTranscript();
            array_push($results,$transcript);
        }

        $client->close();

        return $this->view->render($response, 'speech_to_text_results.html.twig', [
            'fileName' => $fileName,
            'results' => $results,
        ]);
    }
    else {
        return $response->withRedirect("/images");
    }
});

$app->get('/map', function (Request $request, Response $response) {
    return $this->view->render($response, 'map_location.php');
})->setName('map');

$app->get('/iplocations', function (Request $request, Response $response) {
    return $this->view->render($response, 'ip_location.html.twig', [
        'ip_address' => array()
    ]);
});

$app->post('/iplocations', function (Request $request, Response $response) {
    $ip_address = $request->getParsedBody();
    $geonameID = $ip_address['ip_address'];
        
    if (strlen($geonameID) != 0) {
        $query = "SELECT city_name, country_name FROM `cc-assignment-2-275700.IP_GeoLocation.GeoLite2_City_Locations` WHERE geoname_id = {$geonameID}";
        //search location
        $bigQuery = new BigQueryClient([
            'projectId' => $projectId
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
        $results = array();
        foreach ($queryResults as $row) {
            foreach ($row as $column => $value) {
                if (strpos($row, 'city_name') == false && strpos($column, 'country_name') == false) {
                    array_push($results, $value);
                }
            }
        }
        return $this->view->render($response, 'ip_list.html.twig', [
            'ip' => $geonameID,
            'results' => $results,
        ]);
    }
    else {
        return $response->withRedirect("/images");
    }
});

$app->get('/images/add', function (Request $request, Response $response) {
    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Add',
        'image' => array(),
    ]);
});

$app->post('/images/add', function (Request $request, Response $response) {
    $upload = $request->getParsedBody();
    $recaptcha = new ReCaptcha('6Lf2i_cUAAAAAOHxt-gbhMb52ogTC-u2HhbQVijr');
    $resp = $recaptcha->verify($upload['g-recaptcha-response']);

    if (!$resp->isSuccess()) {
        return $response->withRedirect("/images");
    }
    else {
        unset($upload['g-recaptcha-response']);
        $files = $request->getUploadedFiles();
        if ($files['image']->getSize()) {
            // Store the uploaded files in a Cloud Storage object.
            $image = $files['image'];
            $object = $this->bucket->upload($image->getStream(), [
                'metadata' => ['contentType' => $image->getClientMediaType()],
                'predefinedAcl' => 'publicRead',
            ]);
            $upload['image_url'] = $object->info()['mediaLink'];
        }
        $id = $this->cloudsql->create($upload);

        return $response->withRedirect("/images/$id");
    }
});

$app->get('/images/{id}', function (Request $request, Response $response, $args) {
    $image = $this->cloudsql->read($args['id']);
    if (!$image) {
        return $response->withStatus(404);
    }
    return $this->view->render($response, 'view.html.twig', ['image' => $image]);
});

$app->get('/images/{id}/edit', function (Request $request, Response $response, $args) {
    $image = $this->cloudsql->read($args['id']);
    if (!$image) {
        return $response->withStatus(404);
    }

    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Edit',
        'image' => $image,
    ]);
});

$app->post('/images/{id}/edit', function (Request $request, Response $response, $args) {
    if (!$this->cloudsql->read($args['id'])) {
        return $response->withStatus(404);
    }
    $upload = $request->getParsedBody();
    $upload['id'] = $args['id'];
    $files = $request->getUploadedFiles();
    if ($files['image']->getSize()) {
        $image = $files['image'];
        $bucket = $this->bucket;
        $imageStream = $image->getStream();
        $imageContentType = $image->getClientMediaType();
        // [START gae_php_app_upload_image]
        // Set your own image file path and content type below to upload an
        // image to Cloud Storage.
        // $imageStream = fopen('/path/to/your_image.jpg', 'r');
        // $imageContentType = 'image/jpg';
        $object = $bucket->upload($imageStream, [
            'metadata' => ['contentType' => $imageContentType],
            'predefinedAcl' => 'publicRead',
        ]);
        $imageUrl = $object->info()['mediaLink'];
        // [END gae_php_app_upload_image]
        $upload['image_url'] = $imageUrl;
    }
    if ($this->cloudsql->update($upload)) {
        return $response->withRedirect("/images/$args[id]");
    }

    return new Response('Could not update image');
});

$app->post('/images/{id}/delete', function (Request $request, Response $response, $args) {
    $image = $this->cloudsql->read($args['id']);
    if ($image) {
        $this->cloudsql->delete($args['id']);
        if (!empty($image['image_url'])) {
            $objectName = parse_url(basename($image['image_url']), PHP_URL_PATH);
            $bucket = $this->bucket;
            // get bucket name from image
            // [START gae_php_app_delete_image]
            $object = $bucket->object($objectName);
            $object->delete();
            // [END gae_php_app_delete_image]
        }
        return $response->withRedirect('/images');
    }

    return $response->withStatus(404);
});
