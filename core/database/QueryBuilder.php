<?php

class QueryBuilder
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function select(
        string $table,
        array $equalConditions = [],
        array $likeConditions = [],
        array $fields = null,
        string $orderBy = null,
        int $offset = null,
        int $limit = null
    ) : array
    {
        $query = 'SELECT';

        if (count($fields)) {
            $fields = implode(', ', $fields);
            $query .= " {$fields} FROM {$table}";
        } else {
            $query .= " * FROM {$table}";            
        }
        
        $condition = '';

        if (count($equalConditions)) {
            foreach (array_keys($equalConditions) as $equalCondition)
            {
                $condition .= " {$equalCondition} = :{$equalCondition} AND";
            }
        }
        
        if (count($likeConditions)) {
            foreach (array_keys($likeConditions) as $likeCondition)
            {
                $condition .= " {$likeCondition} LIKE :{$likeCondition} AND";
            }
        }

        if (strlen($condition)) {
            $query .= " WHERE " . trim($condition, ' AND');
        }
        
        if (!is_null($orderBy)) {
            $query .= " ORDER BY {$orderBy}";
        }

        if (!is_null($offset) && !is_null($limit)) {
            $query .= " LIMIT {$offset}, {$limit}";
        }
        
        $query .= ';';

        $statement = $this->pdo->prepare($query);
        if (count($equalConditions)) {
            foreach (array_keys($equalConditions) as $key) {
                if (gettype($equalConditions[$key]) === 'string') {
                    $equalConditions[$key] = htmlspecialchars($equalConditions[$key]);
                    $statement->bindParam(":{$key}", $equalConditions[$key]);
                } else {
                    $statement->bindParam(":{$key}", $equalConditions[$key]);
                }
                
            }
        }
        
        if (count($likeConditions)) {
            foreach (array_keys($likeConditions) as $key) {
                if (gettype($likeConditions[$key]) === 'string') {
                    $likeConditions[$key] = '%' . htmlspecialchars($likeConditions[$key]) . '%';
                    $statement->bindParam(":{$key}", $likeConditions[$key]);
                } else {
                    $statement->bindParam(":{$key}", $likeConditions[$key]);
                }
            }
        }

        $statement->execute();
        
        return  $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectById(string $table, int $id, array $fields = null) : array
    {
        if (is_null($fields)) {
            return $this->select($table, [ 'id' => $id ], []);        
        }
        return  $this->select($table, [ 'id' => $id ], [], $fields);
    }

    public function count(
        string $table,
        array $equalConditions = [],
        array $likeConditions = []
    ) : array
    {
        $query = "SELECT COUNT(*) FROM {$table}";
        
        $condition = '';

        if (count($equalConditions)) {
            foreach (array_keys($equalConditions) as $equalCondition)
            {
                $condition .= " {$equalCondition} = :{$equalCondition} AND";
            }
        }
        
        if (count($likeConditions)) {
            foreach (array_keys($likeConditions) as $likeCondition)
            {
                $condition .= " {$likeCondition} LIKE :{$likeCondition} AND";
            }
        }

        if (strlen($condition)) {
            $query .= " WHERE " . trim($condition, ' AND');
        }
        
        $query .= ';';

        $statement = $this->pdo->prepare($query);
        if (count($equalConditions)) {
            foreach (array_keys($equalConditions) as $key) {
                if (gettype($equalConditions[$key]) === 'string') {
                    $equalConditions[$key] = htmlspecialchars($equalConditions[$key]);
                    $statement->bindParam(":{$key}", $equalConditions[$key]);
                } else {
                    $statement->bindParam(":{$key}", $equalConditions[$key]);
                }
                
            }
        }
        
        if (count($likeConditions)) {
            foreach (array_keys($likeConditions) as $key) {
                if (gettype($likeConditions[$key]) === 'string') {
                    $likeConditions[$key] = '%' . htmlspecialchars($likeConditions[$key]) . '%';
                    $statement->bindParam(":{$key}", $likeConditions[$key]);
                } else {
                    $statement->bindParam(":{$key}", $likeConditions[$key]);
                }
            }
        }

        $statement->execute();
        
        return  $statement->fetchAll(PDO::FETCH_NUM);
    }


    public function insert(string $table, array $content) : int
    {
        if (!count($content)) {
            throw new NoContentException();
        }

        $query = "INSERT INTO {$table} (";
        $params = '';

        foreach (array_keys($content) as $key) {
            $query .= "{$key}, ";
            $params .= " :{$key}, ";
        }

        $query = trim($query, ', ') . ') VALUES (' . trim($params, ', ') . ');';

        $statement = $this->pdo->prepare($query);
        
        foreach (array_keys($content) as $key) {
            if (gettype($content[$key]) === 'string') {
                $content[$key] = htmlspecialchars($content[$key]);
                $statement->bindParam(":{$key}", $content[$key]);
            } else {
                $statement->bindParam(":{$key}", $content[$key]);
            }
        }

        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function update(string $table, int $id, array $content) : bool
    { 
        if (!count($content)) {
            throw new NoContentException();
        }

        $query = "UPDATE {$table} SET";

        foreach (array_keys($content) as $key)
        {
            $query .= " {$key} = :{$key},";
        }

        $query = trim($query, ',');
        $query .= ' WHERE id = :id';
        $statement = $this->pdo->prepare($query);

        foreach (array_keys($content) as $key) {
            if (gettype($content[$key]) === 'string') {
                $content[$key] = htmlspecialchars($content[$key]);
                $statement->bindParam(":{$key}", $content[$key]);
            } else {
                $statement->bindParam(":{$key}", $content[$key]);
            }
        }

        $statement->bindParam(':id', $id);
        return $statement->execute();
    }

    public function deleteById(string $table, int $id) : bool 
    {
        $statement =$this->pdo->prepare("DELETE FROM {$table} WHERE id = :id;");
        $statement->bindParam(':id', $id);
        return $statement->execute();
    }
}
