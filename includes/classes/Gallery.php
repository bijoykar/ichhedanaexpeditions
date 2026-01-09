<?php
/**
 * Gallery Model
 * 
 * @package IchhedanaExpeditions
 */

class Gallery extends Model {
    protected $table = 'gallery';
    
    /**
     * Get published images
     */
    public function getPublished($limit = null) {
        return $this->where(['status' => 'published'], 'display_order ASC, created_at DESC', $limit);
    }
    
    /**
     * Get featured images
     */
    public function getFeatured($limit = 8) {
        return $this->where(['status' => 'published', 'featured' => 1], 'display_order ASC', $limit);
    }
    
    /**
     * Get images by category
     */
    public function getByCategory($category, $limit = null) {
        return $this->where(['category' => $category, 'status' => 'published'], 'display_order ASC, created_at DESC', $limit);
    }
    
    /**
     * Get images by destination
     */
    public function getByDestination($destinationId, $limit = null) {
        return $this->where(['destination_id' => $destinationId, 'status' => 'published'], 'display_order ASC', $limit);
    }
    
    /**
     * Get images by tour
     */
    public function getByTour($tourId, $limit = null) {
        return $this->where(['tour_id' => $tourId, 'status' => 'published'], 'display_order ASC', $limit);
    }
    
    /**
     * Get all categories
     */
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM {$this->table} 
                WHERE status = 'published' AND category IS NOT NULL AND category != ''
                ORDER BY category ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
