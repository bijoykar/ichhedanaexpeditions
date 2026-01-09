<?php
/**
 * Destination Model
 * 
 * @package IchhedanaExpeditions
 */

class Destination extends Model {
    protected $table = 'destinations';
    
    /**
     * Get published destinations
     */
    public function getPublished($limit = null) {
        return $this->where(['status' => 'published'], 'display_order ASC, name ASC', $limit);
    }
    
    /**
     * Get featured destinations
     */
    public function getFeatured($limit = 4) {
        return $this->where(['status' => 'published', 'featured' => 1], 'display_order ASC', $limit);
    }
    
    /**
     * Get destination by slug
     */
    public function getBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get destinations by country
     */
    public function getByCountry($country) {
        return $this->where(['country' => $country, 'status' => 'published'], 'name ASC');
    }
    
    /**
     * Get destination with tour count
     */
    public function getWithTourCount() {
        $sql = "SELECT d.*, COUNT(t.id) as tour_count 
                FROM {$this->table} d
                LEFT JOIN tours t ON d.id = t.destination_id AND t.status = 'published'
                WHERE d.status = 'published'
                GROUP BY d.id
                ORDER BY d.display_order ASC, d.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
