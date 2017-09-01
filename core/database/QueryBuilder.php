<?php

class QueryBuilder
{
    protected $con;
    protected $tables = array();

    public function __construct(mysqli $con)
    {
        $this->con = $con;
        $tables = mysqli_query($con, "SHOW tables");

        while ($table = mysqli_fetch_row($tables)) {
            $this->tables[] = $table[0];
        }
    }

    public function selectAll($table, $offset = null, $limit = null)
    {
        if (in_array($table, $this->tables)) {
            
            $query = "SELECT * FROM {$table}";
            if (isset($offset) && isset($limit)) {
                $query .= ' LIMIT ?, ?;';
            }

            $statement = mysqli_prepare(
                $this->con,
                $query
            );

            if (isset($offset) && isset($limit)) {
                $statement->bind_param('ii', $offset, $limit);
            }
            $statement->execute();

            $rows = array();
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $statement->close();

            return $rows;
        }
    }

    public function selectById($table, $id)
    {
        if (in_array($table, $this->tables)) {
            $statement = mysqli_prepare(
                $this->con,
                "SELECT * FROM {$table} WHERE id = ?"
            );
            $statement->bind_param("i", $id);
            $statement->execute();
            return $statement->get_result()->fetch_assoc();
        }
    }
    public function selectFieldsById($table, $fields, $id)
    {
        if (in_array($table, $this->tables)) {
            $fields = implode(', ', $fields);
            $statement = mysqli_prepare(
                $this->con,
                "SELECT {$fields} FROM {$table} WHERE id = ?"
            );
            $statement->bind_param("i", $id);
            $statement->execute();
            return $statement->get_result()->fetch_assoc();
        }
    }

    public function selectWhere($table, $types, $content, $offset = null, $limit = null)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $query = "SELECT * FROM {$table} WHERE";
            $keys = implode("=? AND ", array_keys($content));
            $query .= " {$keys}=?";
            if (isset($offset) && isset($limit)) {
                $query .= ' LIMIT ?, ?;';
            }
            $statement = mysqli_prepare($this->con, $query);
            if (isset($offset) && isset($limit)) {
                $content['offset'] = $offset;                
                $content['limit'] = $limit;
                $types .= 'ii';
            }
            $statement->bind_param($types, ...array_values($content));
            $statement->execute();
            $rows = array();
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $statement->close();

            return $rows;
        }
    }

    public function countWhere($table, $types, $content)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $query = "SELECT COUNT(*) AS '0' FROM {$table} WHERE";
            $keys = implode("=? AND ", array_keys($content));
            $query .= " {$keys}=?";
            $statement = mysqli_prepare($this->con, $query);
            $statement->bind_param($types, ...array_values($content));
            $statement->execute();
            $result = $statement->get_result();
            return $result->fetch_assoc()[0];
        }
    }

    public function count(
        string $table,
        array $equalConditions = [],
        array $likeConditions = []
    ) : array
    {
        if (in_array($table, $this->tables)) {
            $query = "SELECT COUNT(*) AS '0' FROM {$table} WHERE";
            
            $keys = implode("=? AND ", array_keys($contentEquals));
            $query .= " {$keys}=?";
            if(count($contentLike) > 0) {
                $keys = implode(" like ? AND ", array_keys($contentLike));
                $query .= " AND {$keys} like ?";
            }
            
            $statement = mysqli_prepare($this->con, $query);
            foreach ($contentLike as $key => $value) {
                $contentLike[$key] = "%{$value}%";
            }
            $statement->bind_param($types, ...array_values(array_merge($contentEquals, $contentLike)));
            $statement->execute();
            $result = $statement->get_result();
            return $result->fetch_assoc()[0];
        }
    }

    public function selectWhereLike($table, $types, $content, $offset = null, $limit = null, $orderBy = null)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $query = "SELECT * FROM {$table} WHERE";
            $keys = implode(" like ? AND ", array_keys($content));
            $query .= " {$keys} like ?";
            if (isset($orderBy)) {
                $query .= " ORDER BY {$orderBy}";
            }
            if (isset($offset) && isset($limit)) {
                $query .= ' LIMIT ?, ?;';
            }
            
            $statement = mysqli_prepare($this->con, $query);
            foreach ($content as $key => $value) {
                $content[$key] = "%{$value}%";
            }
            if (isset($offset) && isset($limit)) {
                $content['offset'] = $offset;                
                $content['limit'] = $limit;
                $types .= 'ii';
            }
            $statement->bind_param($types, ...array_values($content));
            $statement->execute();
            
            $rows = [];
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $statement->close();
            
            return $rows;
        }
    }

    public function selectWhereEqualLike($table, $types,$contentEquals, $contentLike, $offset = null, $limit = null, $orderBy = null)
    {
        if (in_array($table, $this->tables)) {
            $query = "SELECT * FROM {$table} WHERE";

            $keys = implode("=? AND ", array_keys($contentEquals));
            $query .= " {$keys}=?";
            if(count($contentLike) > 0) {
                $keys = implode(" like ? AND ", array_keys($contentLike));
                $query .= " AND {$keys} like ?";
            }
            
            if (isset($orderBy)) {
                $query .= " ORDER BY {$orderBy}";
            }
            if (isset($offset) && isset($limit)) {
                $query .= ' LIMIT ?, ?;';
            }
            
            $statement = mysqli_prepare($this->con, $query);
            foreach ($contentLike as $key => $value) {
                $contentLike[$key] = "%{$value}%";
            }
            if (isset($offset) && isset($limit)) {
                $contentLike['offset'] = $offset;                
                $contentLike['limit'] = $limit;
                $types .= 'ii';
            }
            $statement->bind_param($types, ...array_values(array_merge($contentEquals, $contentLike)));
            $statement->execute();
            $rows = [];
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $statement->close();
            
            return $rows;
        }
    }

    public function countWhereLike($table, $types, $content)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $query = "SELECT COUNT(*) AS '0' FROM {$table} WHERE";
            $keys = implode(" like ? AND ", array_keys($content));
            $query .= " {$keys} like ?";
            $statement = mysqli_prepare($this->con, $query);
            foreach ($content as $key => $value) {
                $content[$key] = "%{$value}%";
            }
            $statement->bind_param($types, ...array_values($content));
            $statement->execute();
            $result = $statement->get_result();
            return $result->fetch_assoc()[0];            
        }
    }

    public function selectFieldsWhere($table, $fields, $types, $content, $offset = null, $limit = null)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $fields = implode(', ', $fields);
            $query = "SELECT {$fields} FROM {$table} WHERE";
            $keys = implode("=? AND ", array_keys($content));
            $query .= " {$keys}=?";
            if (isset($offset) && isset($limit)) {
                $query .= ' LIMIT ?, ?;';
            }
            $statement = mysqli_prepare($this->con, $query);

            if (isset($offset) && isset($limit)) {
                $content['offset'] = $offset;                
                $content['limit'] = $limit;
                $types .= 'ii';
            }
            $statement->bind_param($types, ...array_values($content));
               
            $statement->execute();
            $rows = [];
            $result = $statement->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $statement->close();
            
            return $rows;
        }
    }

    public function insert($table, $types, $content)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $query = "INSERT INTO {$table}";
            $keys = implode(", ", array_keys($content));
            $query .= " ({$keys}) VALUES (?";
            for ($i = 0; $i < count($content) - 1; $i++) {
                $query .= ", ?";
            }
            $query .= ");";

            foreach ($content as $key => $value) {
                $content[$key] = htmlspecialchars($value);
            }

            $statement = mysqli_prepare($this->con, $query);
            $statement->bind_param($types, ...array_values($content));
            $statement->execute();

            return $statement->insert_id;
        }
    }

    public function update($table, $id, $types, $content)
    {
        if (in_array($table, $this->tables) && count($content) > 0) {
            $query = "UPDATE {$table} SET ";
            $keys = implode("=? , ", array_keys($content));
            $query .= " {$keys}=? WHERE id = ?";
            $statement = mysqli_prepare($this->con, $query);

            $content['id'] = & $id;
            $types .= 'i';

            foreach ($content as $key => $value) {
                $content[$key] = htmlspecialchars($value);
            }

            $statement->bind_param($types, ...array_values($content));
            $statement->execute();

            return $statement->insert_id;
        }
    }

    public function deleteById($table, $id)
    {
        if (in_array($table, $this->tables)) {
            $statement = mysqli_prepare(
                $this->con,
                "DELETE FROM {$table} WHERE id = ?"
            );
            $statement->bind_param("i", $id);
            $statement->execute();
        }
    }
}
