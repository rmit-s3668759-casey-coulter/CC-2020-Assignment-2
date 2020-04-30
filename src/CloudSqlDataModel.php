<?php

namespace Google\Cloud\Samples\AppEngine\GettingStarted;

use PDO;

/**
 * Class CloudSql is a wrapper for making calls to a Cloud SQL MySQL database.
 */
class CloudSqlDataModel
{
    private $dsn;
    private $user;
    private $password;

    /**
     * Creates the SQL users table if it doesn't already exist.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $columns = array(
            'id serial PRIMARY KEY ',
            'player_name VARCHAR(255)',
            'summoner_name VARCHAR(255)',
            'image_url VARCHAR(255)',
            'created_by VARCHAR(255)',
            'created_by_id VARCHAR(255)',
        );

        $this->columnNames = array_map(function ($columnDefinition) {
            return explode(' ', $columnDefinition)[0];
        }, $columns);
        $columnText = implode(', ', $columns);

        $this->pdo->query("CREATE TABLE IF NOT EXISTS users ($columnText)");
    }

    /**
     * Throws an exception if $user contains an invalid key.
     *
     * @param $user array
     *
     * @throws \Exception
     */
    private function verifyUser($user)
    {
        if ($invalid = array_diff_key($user, array_flip($this->columnNames))) {
            throw new \Exception(sprintf(
                'unsupported user properties: "%s"',
                implode(', ', $invalid)
            ));
        }
    }

    public function listUsers($limit = 10, $cursor = 0)
    {
        $pdo = $this->pdo;
        $query = 'SELECT * FROM users WHERE id > :cursor ORDER BY id LIMIT :limit';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':cursor', $cursor, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        // Uncomment this while loop to output the results
        // while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        //     var_dump($row);
        // }
        $rows = array();
        $nextCursor = null;
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
            if (count($rows) == $limit) {
                $nextCursor = $row['id'];
                break;
            }
        }

        return ['users' => $rows, 'cursor' => $nextCursor];
    }

    public function create($user, $id = null)
    {
        $this->verifyUser($user);
        if ($id) {
            $user['id'] = $id;
        }
        $names = array_keys($user);
        $placeHolders = array_map(function ($key) {
            return ":$key";
        }, $names);
        $pdo = $this->pdo;
        $sql = sprintf(
            'INSERT INTO users (%s) VALUES (%s)',
            implode(', ', $names),
            implode(', ', $placeHolders)
        );
        $statement = $pdo->prepare($sql);
        $statement->execute($user);
        return $this->pdo->lastInsertId();
    }

    public function read($id)
    {
        $pdo = $this->pdo;
        // [START gae_php_app_cloudsql_query]
        $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // [END gae_php_app_cloudsql_query]
        return $result;
    }

    public function update($user)
    {
        $this->verifyUser($user);
        $assignments = array_map(
            function ($column) {
                return "$column=:$column";
            },
            $this->columnNames
        );
        $assignmentString = implode(',', $assignments);
        $sql = "UPDATE users SET $assignmentString WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $values = array_merge(
            array_fill_keys($this->columnNames, null),
            $user
        );
        return $statement->execute($values);
    }

    public function delete($id)
    {
        $statement = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount();
    }
}
