<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Check maintenance mode
if (MAINTENANCE_MODE && !isLoggedIn()) {
    die('<h1>Maintenance Mode</h1><p>' . MAINTENANCE_MESSAGE . '</p>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta_description ?? DEFAULT_META_DESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords ?? DEFAULT_META_KEYWORDS; ?>">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo ASSETS_URL; ?>/images/favicon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    
    <?php if (isset($extra_css)): ?>
        <?php echo $extra_css; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Modern Header -->
    <header class="modern-site-header">
        <div class="modern-top-bar">
            <div class="container">
                <div class="modern-top-bar-content">
                    <div class="modern-contact-info">
                        <a href="tel:<?php echo SITE_PHONE; ?>" class="contact-link">
                            <i class="fas fa-phone-alt"></i> 
                            <span><?php echo SITE_PHONE; ?></span>
                        </a>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" class="contact-link">
                            <i class="fas fa-envelope"></i> 
                            <span><?php echo SITE_EMAIL; ?></span>
                        </a>
                    </div>
                    <div class="modern-social-links">
                        <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" title="Facebook" class="social-icon facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo FACEBOOK_GROUP_URL; ?>" target="_blank" title="Facebook Group" class="social-icon group">
                            <i class="fas fa-users"></i>
                        </a>
                        <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" title="Instagram" class="social-icon instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="modern-main-nav">
            <div class="container">
                <div class="modern-nav-wrapper">
                    <div class="modern-logo">
                        <a href="<?php echo SITE_URL; ?>">
                            <img src="https://ichhedanaexpeditions.com/usercontent/1776478732.png" alt="<?php echo SITE_NAME; ?>">
                        </a>
                    </div>
                    
                    <button class="modern-mobile-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    
                    <ul class="modern-nav-menu" id="navMenu">
                        <li><a href="<?php echo SITE_URL; ?>" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i><span>Home</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/tours.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'tours.php') ? 'active' : ''; ?>">
                            <i class="fas fa-map-marked-alt"></i><span>Tours</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/destinations.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'destinations.php') ? 'active' : ''; ?>">
                            <i class="fas fa-mountain"></i><span>Destinations</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/gallery.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : ''; ?>">
                            <i class="fas fa-images"></i><span>Gallery</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blogs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'blogs.php') ? 'active' : ''; ?>">
                            <i class="fas fa-blog"></i><span>Blog</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/reviews.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'reviews.php') ? 'active' : ''; ?>">
                            <i class="fas fa-star"></i><span>Reviews</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle"></i><span>About</span>
                        </a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php' || basename($_SERVER['PHP_SELF']) == 'contact-form.php') ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i><span>Contact</span>
                        </a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="mobile-menu-backdrop" id="mobileBackdrop"></div>
    </header>
    
    <style>
    /* Prevent horizontal overflow on mobile */
    html, body {
        overflow-x: hidden;
        max-width: 100%;
        padding-top: 0;
        margin: 0;
    }
    
    /* Add padding to prevent content from hiding behind fixed header */
    body > *:first-child:not(.modern-site-header) {
        padding-top: 90px;
    }
    
    * {
        box-sizing: border-box;
    }
    
    /* Modern Header Styles */
    .modern-site-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .modern-site-header.scrolled {
        box-shadow: 0 4px 30px rgba(0,0,0,0.12);
    }
    
    /* Modern Top Bar */
    .modern-top-bar {
        display: none;
        background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
        padding: 12px 0;
        font-size: 13px;
    }
    
    .modern-top-bar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modern-contact-info {
        display: flex;
        gap: 25px;
        align-items: center;
    }
    
    .contact-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 6px 12px;
        border-radius: 6px;
    }
    
    .contact-link:hover {
        color: rgba(255,255,255,1);
        transform: translateY(-2px);
    }
    
    .contact-link i {
        font-size: 14px;
    }
    
    .modern-social-links {
        display: flex;
        gap: 12px;
    }
    
    .social-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .social-icon:hover {
        transform: translateY(-3px) scale(1.1);
    }
    
    .social-icon.facebook:hover {
        background: #1877f2;
        color: white;
    }
    
    .social-icon.instagram:hover {
        background: #E4405F;
        color: white;
    }
    
    .social-icon.group:hover {
        background: #228B22;
        color: white;
    }
    
    /* Modern Main Nav */
    .modern-main-nav {
        padding: 18px 0;
        background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    }
    
    .modern-nav-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modern-logo a {
        display: block;
        transition: transform 0.3s ease;
    }
    
    .modern-logo a:hover {
        transform: scale(1.05);
    }
    
    .modern-logo img {
        height: 65px;
        width: auto;
        display: block;
        filter: brightness(0) saturate(100%) invert(100%);
        transition: filter 0.3s ease;
    }
    
    .modern-logo a:hover img {
        filter: brightness(0) saturate(100%) invert(100%) opacity(0.8);
    }
    
    .modern-nav-menu {
        display: flex;
        gap: 5px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .modern-nav-menu li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 18px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 15px;
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
        text-transform: uppercase;
    }
    
    .modern-nav-menu li a i {
        font-size: 16px;
        color: rgba(255,255,255,0.9);
        transition: all 0.3s ease;
    }
    
    .modern-nav-menu li a:hover {
        color: white;
        transform: translateY(-2px);
    }
    
    .modern-nav-menu li a.active {
        background: rgba(255,255,255,0.25);
        color: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .modern-nav-menu li a.active i {
        color: white;
    }
    
    .modern-mobile-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .modern-mobile-toggle:hover {
        transform: scale(1.05);
    }
    
    .modern-mobile-toggle span {
        display: block;
        width: 28px;
        height: 3px;
        background: white;
        margin: 5px 0;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    .modern-mobile-toggle.active span:nth-child(1) {
        transform: rotate(45deg) translate(8px, 8px);
    }
    
    .modern-mobile-toggle.active span:nth-child(2) {
        opacity: 0;
    }
    
    .modern-mobile-toggle.active span:nth-child(3) {
        transform: rotate(-45deg) translate(8px, -8px);
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .modern-nav-menu li a {
            padding: 10px 14px;
            font-size: 14px;
        }
        
        .modern-nav-menu li a i {
            font-size: 14px;
        }
    }
    
    @media (max-width: 992px) {
        /* Prevent horizontal overflow */
        .container {
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .modern-top-bar-content,
        .modern-nav-wrapper {
            max-width: 100%;
            overflow: hidden;
        }
        
        .modern-mobile-toggle {
            display: block;
            position: relative;
            z-index: 10003;
        }
        
        .modern-nav-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: #ffffff;
            flex-direction: column;
            padding: 80px 20px 20px 20px;
            box-shadow: 3px 0 15px rgba(0,0,0,0.2);
            transition: left 0.3s ease-in-out;
            overflow-y: auto;
            z-index: 10002;
        }
        
        .modern-nav-menu.menu-open {
            left: 0;
        }
        
        .modern-nav-menu li {
            width: 100%;
            margin: 5px 0;
        }
        
        .modern-nav-menu li a {
            width: 100%;
            padding: 15px 20px;
            border-radius: 8px;
            background: #f8f9fa;
            color: #333;
            font-size: 16px;
        }
        
        .modern-nav-menu li a:hover {
            color: #228B22;
            font-weight: 600;
        }
        
        .modern-nav-menu li a:hover i {
            color: white;
        }
        
        .modern-nav-menu li a.active {
            background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
            color: white;
        }
        
        .modern-nav-menu li a i {
            color: #228B22;
        }
        
        .modern-nav-menu li a.active i {
            color: white;
        }
        
        .mobile-menu-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            z-index: 10001;
        }
        
        .mobile-menu-backdrop.backdrop-open {
            opacity: 1;
            visibility: visible;
        }
    }
    
    @media (max-width: 576px) {
        .modern-contact-info {
            gap: 10px;
        }
        
        .modern-social-links {
            gap: 8px;
        }
        
        .social-icon {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
        
        .modern-logo img {
            height: 50px;
        }
    }
    </style>
    
    <script>
    (function() {
        'use strict';
        
        // Wait for DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMobileMenu);
        } else {
            initMobileMenu();
        }
        
        function initMobileMenu() {
            const menuBtn = document.getElementById('mobileMenuToggle');
            const menu = document.getElementById('navMenu');
            const backdrop = document.getElementById('mobileBackdrop');
            
            if (!menuBtn || !menu || !backdrop) {
                console.warn('Mobile menu elements not found');
                return;
            }
            
            // Toggle menu
            menuBtn.onclick = function() {
                const isOpen = menu.classList.contains('menu-open');
                
                if (isOpen) {
                    closeMenu();
                } else {
                    openMenu();
                }
            };
            
            // Close on backdrop click
            backdrop.onclick = closeMenu;
            
            // Close on menu link click
            menu.querySelectorAll('a').forEach(function(link) {
                link.onclick = closeMenu;
            });
            
            function openMenu() {
                menu.classList.add('menu-open');
                backdrop.classList.add('backdrop-open');
                menuBtn.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMenu() {
                menu.classList.remove('menu-open');
                backdrop.classList.remove('backdrop-open');
                menuBtn.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    })();
    
    // Add scrolled class to header
    let lastScroll = 0;
    const header = document.querySelector('.modern-site-header');
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });
    </script>
    
    <?php
    // Display flash messages
    $flash = getFlashMessage();
    if ($flash):
    ?>
    <div class="flash-message <?php echo $flash['type']; ?>">
        <div class="container">
            <p><?php echo $flash['message']; ?></p>
            <button class="close-flash">&times;</button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="main-content">
