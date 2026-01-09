<?php
/**
 * Base Model Class
 * 
 * @package IchhedanaExpeditions
 */

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Find record by ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find record by column value
     */
    public function findBy($column, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetch();
    }
    
    /**
     * Get all records
     */
    public function all($orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get records with conditions
     */
    public function where($conditions, $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $placeholders = [];
        $values = [];
        
        foreach ($conditions as $column => $value) {
            $placeholders[] = "{$column} = ?";
            $values[] = $value;
        }
        
        $sql .= implode(' AND ', $placeholders);
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $stmt->fetchAll();
    }
    
    /**
     * Insert new record
     */
    public function insert($data) {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($data), '?');
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        $columns = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $columns[] = "{$column} = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $columns) . 
               " WHERE {$this->primaryKey} = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Count records
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $placeholders = [];
            $values = [];
            
            foreach ($conditions as $column => $value) {
                $placeholders[] = "{$column} = ?";
                $values[] = $value;
            }
            
            $sql .= implode(' AND ', $placeholders);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
        } else {
            $stmt = $this->db->query($sql);
        }
        
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Paginate records
     */
    public function paginate($page = 1, $perPage = ITEMS_PER_PAGE, $conditions = [], $orderBy = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        $values = [];
        
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $placeholders = [];
            
            foreach ($conditions as $column => $value) {
                $placeholders[] = "{$column} = ?";
                $values[] = $value;
            }
            
            $sql .= implode(' AND ', $placeholders);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $this->count($conditions),
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($this->count($conditions) / $perPage)
        ];
    }
}
