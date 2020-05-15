<?php

/*
 * Copyright 2018 Google LLC All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Bookshelf;

/*
 * Adds all the controllers to $app.  Follows Silex Skeleton pattern.
 */
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Google\Cloud\Storage\Bucket;

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

$app->get('/map', function (Request $request, Response $response) {
    return $this->view->render($response, 'map_location.php');
})->setName('map');

$app->get('/images/add', function (Request $request, Response $response) {
    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Add',
        'image' => array(),
    ]);
});

$app->post('/images/add', function (Request $request, Response $response) {
    $upload = $request->getParsedBody();
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
