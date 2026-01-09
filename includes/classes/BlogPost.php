<?php
/**
 * Blog Post Model
 * 
 * @package IchhedanaExpeditions
 */

class BlogPost extends Model {
    protected $table = 'blog_posts';
    
    /**
     * Get published posts
     */
    public function getPublished($limit = null) {
        return $this->where(['status' => 'published'], 'published_at DESC', $limit);
    }
    
    /**
     * Get featured posts
     */
    public function getFeatured($limit = 3) {
        return $this->where(['status' => 'published', 'featured' => 1], 'published_at DESC', $limit);
    }
    
    /**
     * Get post by slug
     */
    public function getBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get posts by category
     */
    public function getByCategory($category, $limit = null) {
        return $this->where(['category' => $category, 'status' => 'published'], 'published_at DESC', $limit);
    }
    
    /**
     * Get post with author details
     */
    public function getPostWithAuthor($id) {
        $sql = "SELECT p.*, a.full_name as author_name 
                FROM {$this->table} p
                LEFT JOIN admin_users a ON p.author_id = a.id
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Increment views
     */
    public function incrementViews($id) {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Search posts
     */
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'published' AND (
                    title LIKE ? OR 
                    excerpt LIKE ? OR 
                    content LIKE ?
                )
                ORDER BY published_at DESC";
        $searchTerm = "%{$keyword}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all categories with post count
     */
    public function getCategories() {
        $sql = "SELECT category, COUNT(*) as count 
                FROM {$this->table} 
                WHERE status = 'published' AND category IS NOT NULL AND category != ''
                GROUP BY category 
                ORDER BY category ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
