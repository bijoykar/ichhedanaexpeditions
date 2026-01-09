    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-column">
                        <h3><?php echo SITE_NAME; ?></h3>
                        <p><?php echo DEFAULT_META_DESCRIPTION; ?></p>
                        <div class="social-links">
                            <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo FACEBOOK_GROUP_URL; ?>" target="_blank" title="Facebook Group"><i class="fas fa-users"></i></a>
                            <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/tours.php">Photography Tours</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/custom-tours.php">Customised Tours</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/destinations.php">Destinations</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/about.php">About Us</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Explore</h4>
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>/gallery.php">Gallery</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/blogs.php">Blog Posts</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/reviews.php">Customer Reviews</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact Us</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/privacy-policy.php">Privacy Policy</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h4>Contact Us</h4>
                        <ul class="contact-list">
                            <li>
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong><?php echo SITE_PHONE; ?></strong>
                                    <small>Round the clock support</small>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong><?php echo SITE_EMAIL; ?></strong>
                                    <small>For any questions</small>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Kolkata, India</strong>
                                    <small>Office Location</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. | 
                <a href="<?php echo SITE_URL; ?>/privacy-policy.php">Privacy Policy</a> | 
                <a href="<?php echo SITE_URL; ?>/sitemap.php">Sitemap</a></p>
            </div>
        </div>
    </footer>
    
    <!-- Contact Modal -->
    <div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Contact Us</h2>
            <form id="contactForm" class="contact-form">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Your Name *" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Your Email *" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Your Phone">
                </div>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Subject">
                </div>
                <div class="form-group">
                    <textarea name="message" rows="5" placeholder="Your Message *" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
            <div id="contactFormMessage"></div>
        </div>
    </div>
    
    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" title="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
    
    <?php if (isset($extra_js)): ?>
        <?php echo $extra_js; ?>
    <?php endif; ?>
</body>
</html>
