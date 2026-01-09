<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = Database::getInstance()->getConnection();

// Test the current password
$stmt = $db->prepare("SELECT * FROM admin_users WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch();

echo "User found: " . ($user ? "Yes" : "No") . "\n";
if ($user) {
    echo "Username: " . $user['username'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Status: " . $user['status'] . "\n";
    echo "Current hash: " . $user['password'] . "\n\n";
    
    // Test password verification
    $testPassword = 'admin123';
    $verify = password_verify($testPassword, $user['password']);
    echo "Password 'admin123' verification: " . ($verify ? "SUCCESS" : "FAILED") . "\n\n";
    
    if (!$verify) {
        echo "Generating new hash for 'admin123'...\n";
        $newHash = password_hash('admin123', PASSWORD_BCRYPT);
        echo "New hash: " . $newHash . "\n\n";
        
        // Update the password
        $updateStmt = $db->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
        $updateStmt->execute([$newHash]);
        echo "Password updated successfully!\n";
        echo "You can now login with:\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        echo "Password is correct. Login should work.\n";
    }
}
?>
