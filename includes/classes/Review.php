<?php
/**
 * Review Model
 * 
 * @package IchhedanaExpeditions
 */

class Review extends Model {
    protected $table = 'reviews';
    
    /**
     * Get approved reviews
     */
    public function getApproved($limit = null) {
        return $this->where(['status' => 'approved'], 'created_at DESC', $limit);
    }
    
    /**
     * Get featured reviews
     */
    public function getFeatured($limit = 3) {
        return $this->where(['status' => 'approved', 'featured' => 1], 'display_order ASC', $limit);
    }
    
    /**
     * Get reviews by tour
     */
    public function getByTour($tourId, $limit = null) {
        return $this->where(['tour_id' => $tourId, 'status' => 'approved'], 'created_at DESC', $limit);
    }
    
    /**
     * Get reviews with tour details
     */
    public function getReviewsWithTour() {
        $sql = "SELECT r.*, t.title as tour_title 
                FROM {$this->table} r
                LEFT JOIN tours t ON r.tour_id = t.id
                WHERE r.status = 'approved'
                ORDER BY r.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get average rating
     */
    public function getAverageRating($tourId = null) {
        $sql = "SELECT AVG(rating) as avg_rating FROM {$this->table} WHERE status = 'approved'";
        $params = [];
        
        if ($tourId) {
            $sql .= " AND tour_id = ?";
            $params[] = $tourId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return round($result['avg_rating'], 1);
    }
    
    /**
     * Create a new review
     */
    public function create($data) {
        // Set default values
        $data['status'] = $data['status'] ?? 'pending';
        $data['featured'] = $data['featured'] ?? 0;
        $data['display_order'] = $data['display_order'] ?? 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // Insert and return the ID
        return $this->insert($data);
    }
}
