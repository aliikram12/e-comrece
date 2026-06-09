<?php
// Database class for reusable database operations

class Database {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Get all records
    public function getAll($table, $where = '', $orderBy = '', $limit = '') {
        $query = "SELECT * FROM $table";
        
        if (!empty($where)) {
            $query .= " WHERE $where";
        }
        
        if (!empty($orderBy)) {
            $query .= " ORDER BY $orderBy";
        }
        
        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }
        
        $result = $this->conn->query($query);
        
        if ($result === false) {
            return array();
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get single record
    public function getOne($table, $where) {
        $query = "SELECT * FROM $table WHERE $where LIMIT 1";
        $result = $this->conn->query($query);
        
        if ($result === false) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    // Insert record
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode("', '", array_values($data));
        
        $query = "INSERT INTO $table ($columns) VALUES ('$values')";
        
        if ($this->conn->query($query) === true) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    // Update record
    public function update($table, $data, $where) {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ', ');
        
        $query = "UPDATE $table SET $set WHERE $where";
        
        return $this->conn->query($query);
    }
    
    // Delete record
    public function delete($table, $where) {
        $query = "DELETE FROM $table WHERE $where";
        return $this->conn->query($query);
    }
    
    // Count records
    public function count($table, $where = '') {
        $query = "SELECT COUNT(*) as count FROM $table";
        
        if (!empty($where)) {
            $query .= " WHERE $where";
        }
        
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    // Execute custom query
    public function query($query) {
        return $this->conn->query($query);
    }
    
    // Get last error
    public function getError() {
        return $this->conn->error;
    }
}
?>
