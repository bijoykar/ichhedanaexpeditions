<?php
$admin_user = getLoggedInUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-panel">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><?php echo SITE_NAME; ?></h2>
                <p>Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="<?php echo ADMIN_URL; ?>/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    
                    <li class="nav-section">Content Management</li>
                    <li><a href="<?php echo ADMIN_URL; ?>/tours.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tours.php' ? 'active' : ''; ?>">
                        <i class="fas fa-route"></i> Tours
                    </a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>/destinations.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'destinations.php' ? 'active' : ''; ?>">
                        <i class="fas fa-map-marked-alt"></i> Destinations
                    </a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>/blog-posts.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'blog-posts.php' ? 'active' : ''; ?>">
                        <i class="fas fa-blog"></i> Blog Posts
                    </a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>/gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
                        <i class="fas fa-images"></i> Gallery
                    </a></li>
                    
                    <li class="nav-section">Customer Management</li>
                    <li><a href="<?php echo ADMIN_URL; ?>/reviews.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>">
                        <i class="fas fa-star"></i> Reviews
                    </a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>/messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i> Messages
                    </a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>/bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                        <i class="fas fa-ticket-alt"></i> Bookings
                    </a></li>
                    
                    <li class="nav-section">Settings</li>
                    <li><a href="<?php echo ADMIN_URL; ?>/settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> Site Settings
                    </a></li>
                    <li><a href="<?php echo ADMIN_URL; ?>/users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Admin Users
                    </a></li>
                </ul>
            </nav>
        </aside>
        
        <div class="admin-main">
            <header class="admin-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="header-right">
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-sm btn-outline">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                    
                    <div class="admin-user-menu">
                        <button class="user-menu-toggle">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($admin_user['full_name']); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown">
                            <a href="<?php echo ADMIN_URL; ?>/profile.php"><i class="fas fa-user"></i> Profile</a>
                            <a href="<?php echo ADMIN_URL; ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </header>
            
            <?php
            $flash = getFlashMessage();
            if ($flash):
            ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible">
                <?php echo $flash['message']; ?>
                <button class="alert-close">&times;</button>
            </div>
            <?php endif; ?>
