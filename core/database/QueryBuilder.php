<?php namespace newsapp\core\database;

/**
 * Class for genetating SQL queries
 */
class QueryBuilder
{
    /**
     * Instance the PDO object tha will be used the connect to the database
     *
     * @var PDO
     */
    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Selects rows from the given table
     *
     * @param string $table table from where the rows will be selected
     * @param array $equalConditions column-value peers for selecting rows where the column is equal to the given value
     * @param array $likeConditions column-value peers for selecting rows where the column contains the given value
     * @param array $columns array of the columns that you want to select from the table
     * @param string|null $orderBy name of the column that you want the rows to be order by
     * @param int $offset number of the row from were you that to start the selection
     * @param int $limit number of rows tha you want to be retrieved
     * @return array array of the selected rows from the table
     */
    public function select(
        string $table,
        array $equalConditions = [],
        array $likeConditions = [],
        array $columns = null,
        ?string $orderBy = null,
        int $offset = null,
        int $limit = null
    ) : array {
        $query = 'SELECT';

        if (count($columns)) {
            $columns = implode(', ', $columns);
            $query .= " {$columns} FROM {$table}";
        } else {
            $query .= " * FROM {$table}";
        }
        
        $condition = '';

        if (count($equalConditions)) {
            foreach (array_keys($equalConditions) as $equalCondition) {
                $condition .= " {$equalCondition} = :{$equalCondition} AND";
            }
        }
        
        if (count($likeConditions)) {
            foreach (array_keys($likeConditions) as $likeCondition) {
                $condition .= " {$likeCondition} LIKE :{$likeCondition} AND";
            }
        }

        if (strlen($condition)) {
            $query .= ' WHERE ' . trim($condition, ' AND');
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
        return  $statement->rowCount() ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];
    }
    
    /**
     * Selects the row with the given id
     *
     * @param string $table table from where the rows will be selected
     * @param int $id id of the row tha you want to be retrieved
     * @param array $columns array of the columns that you want to select ffrom the table
     * @return array array of column-value peers with the information of the selected row
     */
    public function selectById(string $table, int $id, array $columns = null) : array
    {
        $query = 'SELECT';
        
        if (count($columns)) {
            $columns = implode(', ', $columns);
            $query .= " {$columns}";
        } else {
            $query .= ' *';
        }

        $query .= " FROM {$table} WHERE id = :id;";

        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return  $statement->rowCount() ? $statement->fetch(\PDO::FETCH_ASSOC) : [];
    }

    /**
     * Retrieves the number of columns from a table
     *
     * @param string $table name of the table from where the rows will be count
     * @param array $equalConditions column-value peers for selecting rows where the column is equal to the given value
     * @param array $likeConditions column-value peers for selecting rows where the column contains the given value
     * @return int number the rows in the table that fulfill the conditions
     */
    public function count(
        string $table,
        array $equalConditions = [],
        array $likeConditions = []
    ) : int {
        $query = "SELECT COUNT(*) FROM {$table}";
        
        $condition = '';

        if (count($equalConditions)) {
            foreach (array_keys($equalConditions) as $equalCondition) {
                $condition .= " {$equalCondition} = :{$equalCondition} AND";
            }
        }
        
        if (count($likeConditions)) {
            foreach (array_keys($likeConditions) as $likeCondition) {
                $condition .= " {$likeCondition} LIKE :{$likeCondition} AND";
            }
        }

        if (strlen($condition)) {
            $query .= ' WHERE ' . trim($condition, ' AND');
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
        return current($statement->fetch());
    }

    /**
     * Insert a new row in thhe given table
     *
     * @param string $table table where the row will be inserted
     * @param array $content column-value peers with the information tha will be inserted
     * @return int id of the inserted row
     */
    public function insert(string $table, array $content) : int
    {
        if (!count($content)) {
            throw new \Exception('No content');
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

    /**
     * Updates the information from the row where the id is equal to the given id
     *
     * @param string $table table from where the row will be updated
     * @param int $id id of the row tha you want to update
     * @param array $content column-value peers with the information tha will be updated
     * @return bool true if a row was update, otherwise returns false
     */
    public function update(string $table, int $id, array $content) : bool
    {
        if (!count($content)) {
            throw new \Exception('No content');
        }

        $query = "UPDATE {$table} SET";

        foreach (array_keys($content) as $key) {
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

    /**
     * Deletes the row with the given id
     *
     * @param string $table table from where the row will be deleted
     * @param int $id id of the row that you want to delete
     * @return bool true if a row was deleted, otherwise returns false
     */
    public function deleteById(string $table, int $id) : bool
    {
        $statement =$this->pdo->prepare("DELETE FROM {$table} WHERE id = :id;");
        $statement->bindParam(':id', $id);
        return $statement->execute();
    }
}
