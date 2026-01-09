<?php
/**
 * Tour Model
 * 
 * @package IchhedanaExpeditions
 */

class Tour extends Model {
    protected $table = 'tours';
    
    /**
     * Get published tours
     */
    public function getPublished($limit = null) {
        return $this->where(['status' => 'published'], 'start_date ASC', $limit);
    }
    
    /**
     * Get featured tours
     */
    public function getFeatured($limit = 6) {
        return $this->where(['status' => 'published', 'featured' => 1], 'display_order ASC, start_date ASC', $limit);
    }
    
    /**
     * Get upcoming tours
     */
    public function getUpcoming($limit = null) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'published' AND start_date >= CURDATE() 
                ORDER BY start_date ASC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get tour by slug
     */
    public function getBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get tours by destination
     */
    public function getByDestination($destinationId, $limit = null) {
        return $this->where(['destination_id' => $destinationId, 'status' => 'published'], 'start_date ASC', $limit);
    }
    
    /**
     * Get tour with destination details
     */
    public function getTourWithDestination($id) {
        $sql = "SELECT t.*, d.name as destination_name, d.region, d.country 
                FROM {$this->table} t
                LEFT JOIN destinations d ON t.destination_id = d.id
                WHERE t.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Search tours
     */
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'published' AND (
                    title LIKE ? OR 
                    short_description LIKE ? OR 
                    photography_highlights LIKE ?
                )
                ORDER BY start_date ASC";
        $searchTerm = "%{$keyword}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}
