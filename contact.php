<?php
$page_title = 'Contact Us';
$meta_description = 'Get in touch with Wings of Desire for wildlife photography tours and expeditions. Contact us for bookings and inquiries.';
require_once __DIR__ . '/includes/header.php';

// Handle form submission
$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Send email
        $to = SITE_EMAIL;
        $email_subject = "Contact Form: " . $subject;
        $email_body = "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone\n\n";
        $email_body .= "Message:\n$message";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        if (mail($to, $email_subject, $email_body, $headers)) {
            $success = true;
        } else {
            $error = 'There was an error sending your message. Please try again.';
        }
    }
}
?>

<!-- Page Header -->
<section class="page-header-modern">
    <div class="header-overlay"></div>
    <div class="container">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h1>Let's Connect</h1>
            <p>We're here to help you plan your perfect wildlife photography adventure</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section contact-section-modern">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info-modern">
                <div class="info-header">
                    <h3>Get In Touch</h3>
                    <p>Ready to embark on an unforgettable journey? Reach out to us and let's make it happen!</p>
                </div>
                
                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Visit Us</h4>
                            <p>Kolkata, West Bengal, India</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Call Us</h4>
                            <p><a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a></p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h4>Email Us</h4>
                            <p><a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></p>
                        </div>
                    </div>
                </div>
                
                <div class="social-section">
                    <h4>Follow Our Journey</h4>
                    <div class="social-links-modern">
                        <a href="#" target="_blank" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" target="_blank" class="social-btn instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" target="_blank" class="social-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" target="_blank" class="social-btn youtube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-modern">
                <div class="form-header">
                    <h3>Send Us A Message</h3>
                    <p>Fill out the form below and we'll get back to you as soon as possible</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success-modern">
                        <div class="alert-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Success!</strong>
                            <p>Thank you for your message! We'll get back to you soon.</p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error-modern">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Error!</strong>
                            <p><?php echo $error; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="modern-form">
                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="name">
                                <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name" class="form-input-modern" required 
                                   placeholder="John Doe"
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-input-modern" required 
                                   placeholder="john@example.com"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="phone">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone" class="form-input-modern" 
                                   placeholder="+91 98765 43210"
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        
                        <div class="form-group-modern">
                            <label for="subject">
                                <i class="fas fa-tag"></i> Subject
                            </label>
                            <input type="text" id="subject" name="subject" class="form-input-modern" 
                                   placeholder="Tour Inquiry"
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group-modern">
                        <label for="message">
                            <i class="fas fa-comment-dots"></i> Message <span class="required">*</span>
                        </label>
                        <textarea id="message" name="message" class="form-textarea-modern" rows="6" required 
                                  placeholder="Tell us about your photography tour interests..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit-modern">
                        <span>Send Message</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section-modern">
    <div class="container-fluid p-0">
        <div class="map-container-modern">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d471218.38540152996!2d88.04952462434658!3d22.67574575782726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f882db4908f667%3A0x43e330e68f6c2cbc!2sKolkata%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1641234567890!5m2!1sen!2sin" 
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<style>
/* Modern Page Header */
.page-header-modern {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    padding: 100px 0 80px;
    position: relative;
    overflow: hidden;
}

.header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.5;
}

.header-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
}

.header-icon {
    font-size: 64px;
    margin-bottom: 20px;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.header-content h1 {
    font-size: 56px;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.header-content p {
    font-size: 20px;
    opacity: 0.95;
}

/* Contact Section Modern */
.contact-section-modern {
    padding: 80px 0;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
}

.contact-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 50px;
    align-items: start;
}

/* Contact Info Modern */
.contact-info-modern {
    position: sticky;
    top: 100px;
}

.info-header h3 {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.info-header p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 30px;
}

.info-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 40px;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateX(10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.info-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.info-content h4 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.info-content p {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.info-content a {
    color: #228B22;
    text-decoration: none;
    transition: color 0.3s ease;
}

.info-content a:hover {
    color: #2F4F4F;
}

/* Social Section */
.social-section {
    padding: 25px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    border-radius: 15px;
    text-align: center;
}

.social-section h4 {
    color: white;
    font-size: 18px;
    margin-bottom: 20px;
}

.social-links-modern {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.social-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-btn:hover {
    background: white;
    transform: translateY(-3px);
}

.social-btn.facebook:hover { color: #1877f2; }
.social-btn.instagram:hover { color: #e4405f; }
.social-btn.twitter:hover { color: #1da1f2; }
.social-btn.youtube:hover { color: #ff0000; }

/* Contact Form Modern */
.contact-form-modern {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.form-header {
    margin-bottom: 30px;
}

.form-header h3 {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.form-header p {
    color: #666;
    font-size: 15px;
}

/* Alert Styles */
.alert-success-modern,
.alert-error-modern {
    display: flex;
    align-items: start;
    gap: 15px;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
}

.alert-success-modern {
    background: #d4edda;
    border-left: 4px solid #28a745;
}

.alert-error-modern {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
}

.alert-icon {
    font-size: 24px;
    padding-top: 2px;
}

.alert-success-modern .alert-icon {
    color: #28a745;
}

.alert-error-modern .alert-icon {
    color: #dc3545;
}

.alert-content strong {
    display: block;
    margin-bottom: 5px;
    font-size: 16px;
}

.alert-content p {
    margin: 0;
    font-size: 14px;
}

/* Form Styles */
.modern-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group-modern {
    display: flex;
    flex-direction: column;
}

.form-group-modern label {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group-modern label i {
    color: #228B22;
}

.form-input-modern,
.form-textarea-modern {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-input-modern:focus,
.form-textarea-modern:focus {
    outline: none;
    border-color: #228B22;
    box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
}

.form-textarea-modern {
    resize: vertical;
    min-height: 150px;
}

.required {
    color: #dc3545;
}

.btn-submit-modern {
    width: 100%;
    padding: 16px 30px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.btn-submit-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102,126,234,0.4);
}

.btn-submit-modern i {
    transition: transform 0.3s ease;
}

.btn-submit-modern:hover i {
    transform: translateX(5px);
}

/* Map Section */
.map-section-modern {
    margin-top: 0;
}

.map-container-modern {
    position: relative;
    overflow: hidden;
}

.map-container-modern iframe {
    display: block;
    filter: grayscale(30%);
    transition: filter 0.3s ease;
}

.map-container-modern:hover iframe {
    filter: grayscale(0%);
}

/* Responsive Design */
@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .contact-info-modern {
        position: static;
    }
    
    .header-content h1 {
        font-size: 42px;
    }
}

@media (max-width: 768px) {
    .page-header-modern {
        padding: 80px 0 60px;
    }
    
    .header-content h1 {
        font-size: 36px;
    }
    
    .header-content p {
        font-size: 16px;
    }
    
    .header-icon {
        font-size: 48px;
    }
    
    .contact-section-modern {
        padding: 60px 0;
    }
    
    .contact-form-modern {
        padding: 30px 20px;
    }
    
    .modern-form .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-header h3 {
        font-size: 24px;
    }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
