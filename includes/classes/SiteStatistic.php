<?php
/**
 * Site Statistics Model
 * 
 * @package IchhedanaExpeditions
 */

class SiteStatistic extends Model {
    protected $table = 'site_statistics';
    
    /**
     * Get all statistics ordered by display order
     */
    public function getAllStats() {
        return $this->all('display_order ASC');
    }
    
    /**
     * Get statistic by key
     */
    public function getByKey($key) {
        return $this->findBy('stat_key', $key);
    }
    
    /**
     * Update statistic value
     */
    public function updateStatValue($key, $value) {
        $sql = "UPDATE {$this->table} SET stat_value = ? WHERE stat_key = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$value, $key]);
    }
}
