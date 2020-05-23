<?php

namespace Google\Cloud\Samples\AppEngine\GettingStarted;
use PDO;

# Custom SQL wrapper for calling CloudSqlDataModel
class CloudSqlDataModel {
    private $dsn;
    private $user;
    private $password;

    # Create images table if it doesn't exist
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        # Columns for the table
        $columns = array(
            'id serial PRIMARY KEY ',
            'title VARCHAR(255)',
            'author VARCHAR(255)',
            'published_date VARCHAR(255)',
            'image_url VARCHAR(255)',
            'description VARCHAR(255)',
            'created_by VARCHAR(255)',
            'created_by_id VARCHAR(255)',
        );

        $this->columnNames = array_map(function ($columnDefinition) {
            return explode(' ', $columnDefinition)[0];
        }, $columns);
        $columnText = implode(', ', $columns);

        $this->pdo->query("CREATE TABLE IF NOT EXISTS images ($columnText)");
    }

    # Check to see if the passed image variable contains a valid key
    private function verifyImage($image) {
        if ($invalid = array_diff_key($image, array_flip($this->columnNames))) {
            throw new \Exception(sprintf(
                'unsupported image properties: "%s"',
                implode(', ', $invalid)
            ));
        }
    }

    # Function for returning a list of images, limiting the result to 10
    public function listImages($limit = 10, $cursor = 0) {
        $pdo = $this->pdo;
        $query = 'SELECT * FROM images WHERE id > :cursor ORDER BY id LIMIT :limit';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':cursor', $cursor, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
 
        $rows = array();
        $nextCursor = null;
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
            if (count($rows) == $limit) {
                $nextCursor = $row['id'];
                break;
            }
        }

        return ['images' => $rows, 'cursor' => $nextCursor];
    }

    # Function used for creating a new image upload in the database
    public function create($image, $id = null) {
        # Check the image variable is valid
        $this->verifyImage($image);
        if ($id) {
            $image['id'] = $id;
        }
        $names = array_keys($image);
        $placeHolders = array_map(function ($key) {
            return ":$key";
        }, $names);
        $pdo = $this->pdo;
        $sql = sprintf(
            'INSERT INTO images (%s) VALUES (%s)',
            implode(', ', $names),
            implode(', ', $placeHolders)
        );
        $statement = $pdo->prepare($sql);
        $statement->execute($image);
        return $this->pdo->lastInsertId();
    }

    # Get an  image that matches the passed id from the database
    public function read($id) {
        $pdo = $this->pdo;
        $statement = $pdo->prepare('SELECT * FROM images WHERE id = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    # Used for updating an image in the database
    public function update($image) {
        # Check the image variable passed is valid
        $this->verifyImage($image);
        $assignments = array_map(
            function ($column) {
                return "$column=:$column";
            },
            $this->columnNames
        );
        $assignmentString = implode(',', $assignments);
        $sql = "UPDATE images SET $assignmentString WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $values = array_merge(
            array_fill_keys($this->columnNames, null),
            $image
        );
        return $statement->execute($values);
    }

    # Used for deleting an image upload from the database
    public function delete($id) {
        $statement = $this->pdo->prepare('DELETE FROM images WHERE id = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount();
    }
}