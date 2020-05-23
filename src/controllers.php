<?php

# Provide google credentials for access to certain features of GCP
namespace Google\Cloud\Samples\Bookshelf;
putenv('/../GOOGLE_APPLICATION_CREDENTIALS=CC-Assignment-2-7372e69e9d4d');
$projectId = 'cc-assignment-2-275700';

# Adds all the controllers to $app.  Follows Silex Skeleton pattern.
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

# Create handling for the default route, setting the default route to home
$app->get('/', function (Request $request, Response $response) {
    return $response->withRedirect('/home');
})->setName('home');

# Create handling for the home route
$app->get('/home', function (Request $request, Response $response) {
    return $this->view->render($response, 'home.html.twig');
});

# Create handling for images route 
# This route handles loading the the images from the database and displaying them
$app->get('/images', function (Request $request, Response $response) {
    $token = (int) $request->getQueryParam('page_token');
    $imageList = $this->cloudsql->listImages(10, $token);

    # Render the page and pass images/next_page_token variables
    return $this->view->render($response, 'list.html.twig', [
        'images' => $imageList['images'],
        'next_page_token' => $imageList['cursor'],
    ]);
})->setName('images');

# Create handling for the translation route
$app->get('/translation', function (Request $request, Response $response) {
    return $this->view->render($response, 'translation.html.twig', [
        'fileName' => array()
    ]);
});

# Create handling for the submit/post route of translation
$app->post('/translation', function (Request $request, Response $response) {
    $translate = $request->getParsedBody();
    $source_file = $translate['translate'];

    # Check if the textbox has content
    if (strlen($source_file) > 0) {
        $text = $source_file;
        # Assign the language to translate to
        $targetLanguage = 'en';

        # Create a new translate object and submit for translation
        $translate = new TranslateClient();
        $result = $translate->translate($text, [
            'target' => $targetLanguage,
        ]);

        # Render the page and pass variables
        return $this->view->render($response, 'translation_results.html.twig', [
            'source' => $source_file,
            'translation' => $result[text],
        ]);
    }
});

# Create handling for the speech_to_text route
$app->get('/speech_to_text', function (Request $request, Response $response) {
    return $this->view->render($response, 'speech_to_text.html.twig', [
        'fileName' => array()
    ]);
});

# Create handling for the submit/post route of speech to text
$app->post('/speech_to_text', function (Request $request, Response $response) {
    # Get the variables from the submitted template
    $fileID = $request->getParsedBody();
    $fileName = $fileID['fileName'];

    # Check if the textbox has content
    if (strlen($fileName) > 0) {
        # The name of the audio file to transcribe
        $audioFile = __DIR__ . '/../test_new.flac';

        # Get contents of a file into a string
        $content = file_get_contents($audioFile);

        # Set string as audio content
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
        $audio_response = $client->recognize($config, $audio);

        # Extract most likely transcription
        foreach ($audio_response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript = $mostLikely->getTranscript();
        }

        $client->close();

        # Render the page and pass variables
        return $this->view->render($response, 'speech_to_text_results.html.twig', [
            'fileName' => $fileName,
            'transcript' => $transcript,
        ]);
    }
    # If content wasn't entered, redirect to the home page
    else {
        return $response->withRedirect("/home");
    }
});

# Create handling for the map route
$app->get('/map', function (Request $request, Response $response) {
    return $this->view->render($response, 'map_location.php');
})->setName('map');

# Create handling for the help route
$app->get('/help', function (Request $request, Response $response) {
    return $this->view->render($response, 'help.html.twig');
})->setName('help');

# Create handling for the ip locations route
$app->get('/iplocations', function (Request $request, Response $response) {
    return $this->view->render($response, 'ip_location.html.twig', [
        'ip_address' => array()
    ]);
});

# Create handling for the submit/post route of ip locations
$app->post('/iplocations', function (Request $request, Response $response) {
    # Get variables from the template
    $ip_address = $request->getParsedBody();
    $geonameID = $ip_address['ip_address'];
        
    # Check if content has been entered
    if (strlen($geonameID) != 0) {
        # Create query for the database
        $query = "SELECT city_name, country_name FROM `cc-assignment-2-275700.IP_GeoLocation.GeoLite2_City_Locations` WHERE geoname_id = {$geonameID}";
        # Search BigQuery
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
            
        $results = array();
        # Loop through the results and put them in an array
        foreach ($queryResults as $row) {
            foreach ($row as $column => $value) {
                if (strpos($row, 'city_name') == false && strpos($column, 'country_name') == false) {
                    array_push($results, $value);
                }
            }
        }

        # Render the page and pass variables
        return $this->view->render($response, 'ip_list.html.twig', [
            'ip' => $geonameID,
            'results' => $results,
        ]);
    }
    # If content wasn't entered, redirect to the home page
    else {
        return $response->withRedirect("/home");
    }
});

# Create handling for 'GET' adding images
$app->get('/images/add', function (Request $request, Response $response) {
    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Add',
        'image' => array(),
    ]);
});

# Create handling for 'POST' adding an image
$app->post('/images/add', function (Request $request, Response $response) {
    # Get data from the template
    $upload = $request->getParsedBody();
    # Create a new reCaptcha variable with our site key
    $recaptcha = new ReCaptcha('6Lf2i_cUAAAAAOHxt-gbhMb52ogTC-u2HhbQVijr');
    # Upload the reCaptcha from the template to reCaptcha
    $resp = $recaptcha->verify($upload['g-recaptcha-response']);

    # If the reCaptcha fails, redirect to home. If it passes, upload the image
    if (!$resp->isSuccess()) {
        return $response->withRedirect("/home");
    }
    else {
        # Remove the reCaptcha from the array
        unset($upload['g-recaptcha-response']);
        $files = $request->getUploadedFiles();
        # Check size of the image uplaoded
        if ($files['image']->getSize()) {
            # Store the uploaded files in a Cloud Storage object
            $image = $files['image'];
            # Push the image to the cloud bucket
            $object = $this->bucket->upload($image->getStream(), [
                'metadata' => ['contentType' => $image->getClientMediaType()],
                'predefinedAcl' => 'publicRead',
            ]);
            $upload['image_url'] = $object->info()['mediaLink'];
        }
        # Create the entry in the database with image URL
        $id = $this->cloudsql->create($upload);

        # Redirect to the newly created image
        return $response->withRedirect("/images/$id");
    }
});

# Create handling for viewing a particular image route
$app->get('/images/{id}', function (Request $request, Response $response, $args) {
    # Get the image from the database
    $image = $this->cloudsql->read($args['id']);
    # Check if the image exists, redirect with error page if it doesn't
    if (!$image) {
        return $response->withStatus(404);
    }
    return $this->view->render($response, 'view.html.twig', ['image' => $image]);
});

# Create handling for 'GET' editing a particular image
$app->get('/images/{id}/edit', function (Request $request, Response $response, $args) {
    # Get the image from the database
    $image = $this->cloudsql->read($args['id']);
    # Check if the image exists, redirect with error page if it doesn't
    if (!$image) {
        return $response->withStatus(404);
    }

    # Render the page and pass variables
    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Edit',
        'image' => $image,
    ]);
});

# Create handling for editing an image with 'POST'
$app->post('/images/{id}/edit', function (Request $request, Response $response, $args) {
    # Get variables from the database
    $upload = $request->getParsedBody();
    # Create a new reCaptcha variable with our site key
    $recaptcha = new ReCaptcha('6Lf2i_cUAAAAAOHxt-gbhMb52ogTC-u2HhbQVijr');
    # Upload the reCaptcha from the template to reCaptcha
    $resp = $recaptcha->verify($upload['g-recaptcha-response']);

    # If the reCaptcha fails, redirect to home. If it passes, upload the image
    if (!$resp->isSuccess()) {
        return $response->withRedirect("/home");
    }
    else {
        # Check the image to be edited exists
        if (!$this->cloudsql->read($args['id'])) {
            return $response->withStatus(404);
        }
        # Remove the reCaptcha from the array
        unset($upload['g-recaptcha-response']);
        $upload['id'] = $args['id'];
        $files = $request->getUploadedFiles();
        if ($files['image']->getSize()) {
            $image = $files['image'];
            $bucket = $this->bucket;
            $imageStream = $image->getStream();
            $imageContentType = $image->getClientMediaType();
            $object = $bucket->upload($imageStream, [
                'metadata' => ['contentType' => $imageContentType],
                'predefinedAcl' => 'publicRead',
            ]);
            $imageUrl = $object->info()['mediaLink'];
            $upload['image_url'] = $imageUrl;
        }
        if ($this->cloudsql->update($upload)) {
            return $response->withRedirect("/images/$args[id]");
        }

        return new Response('Could not update image');
    }
});

# Create handling for 'POST' of deleting an image
$app->post('/images/{id}/delete', function (Request $request, Response $response, $args) {
    # Get image from the database
    $image = $this->cloudsql->read($args['id']);
    # Check the image exists, if not, redirect to 404
    if ($image) {
        $this->cloudsql->delete($args['id']);
        # Check the image URL exists
        if (!empty($image['image_url'])) {
            $objectName = parse_url(basename($image['image_url']), PHP_URL_PATH);
            # Remove the image from the cloud bucket
            $bucket = $this->bucket;
            $object = $bucket->object($objectName);
            $object->delete();
        }
        return $response->withRedirect('/images');
    }

    return $response->withStatus(404);
});