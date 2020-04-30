<?php
/*
 * Adds all the controllers to $app.  Follows Silex Skeleton pattern.
 */
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Google\Cloud\Storage\Bucket;

$app->get('/', function (Request $request, Response $response) {
    return $response->withRedirect('/users');
})->setName('home');

$app->get('/users', function (Request $request, Response $response) {
    $token = (int) $request->getQueryParam('page_token');
    $userList = $this->cloudsql->listUsers(10, $token);

    return $this->view->render($response, 'list.html.twig', [
        'users' => $userList['users'],
        'next_page_token' => $userList['cursor'],
    ]);
})->setName('users');

$app->get('/users/add', function (Request $request, Response $response) {
    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Add',
        'user' => array(),
    ]);
});

$app->post('/users/add', function (Request $request, Response $response) {
    $user = $request->getParsedBody();
    $files = $request->getUploadedFiles();
    if ($files['image']->getSize()) {
        // Store the uploaded files in a Cloud Storage object.
        $image = $files['image'];
        $object = $this->bucket->upload($image->getStream(), [
            'metadata' => ['contentType' => $image->getClientMediaType()],
            'predefinedAcl' => 'publicRead',
        ]);
        $user['image_url'] = $object->info()['mediaLink'];
    }
    $id = $this->cloudsql->create($user);

    return $response->withRedirect("/users/$id");
});

$app->get('/users/{id}', function (Request $request, Response $response, $args) {
    $user = $this->cloudsql->read($args['id']);
    if (!$user) {
        return $response->withStatus(404);
    }
    return $this->view->render($response, 'view.html.twig', ['user' => $user]);
});

$app->get('/users/{id}/edit', function (Request $request, Response $response, $args) {
    $user = $this->cloudsql->read($args['id']);
    if (!$user) {
        return $response->withStatus(404);
    }

    return $this->view->render($response, 'form.html.twig', [
        'action' => 'Edit',
        'user' => $user,
    ]);
});

$app->post('/users/{id}/edit', function (Request $request, Response $response, $args) {
    if (!$this->cloudsql->read($args['id'])) {
        return $response->withStatus(404);
    }
    $user = $request->getParsedBody();
    $user['id'] = $args['id'];
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
        $user['image_url'] = $imageUrl;
    }
    if ($this->cloudsql->update($user)) {
        return $response->withRedirect("/users/$args[id]");
    }

    return new Response('Could not update user');
});

$app->post('/users/{id}/delete', function (Request $request, Response $response, $args) {
    $user = $this->cloudsql->read($args['id']);
    if ($user) {
        $this->cloudsql->delete($args['id']);
        if (!empty($user['image_url'])) {
            $objectName = parse_url(basename($user['image_url']), PHP_URL_PATH);
            $bucket = $this->bucket;
            // get bucket name from image
            // [START gae_php_app_delete_image]
            $object = $bucket->object($objectName);
            $object->delete();
            // [END gae_php_app_delete_image]
        }
        return $response->withRedirect('/users');
    }

    return $response->withStatus(404);
});
