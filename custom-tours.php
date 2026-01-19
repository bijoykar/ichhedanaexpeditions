<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = 'Customised Tours - ' . SITE_NAME;
require_once 'includes/header.php';
?>

<style>
.page-header {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    padding: 80px 0 60px;
    text-align: center;
    color: white;
}

.page-header h1 {
    font-size: 48px;
    font-weight: 700;
    margin: 0 0 15px 0;
}

.page-header p {
    font-size: 18px;
    opacity: 0.9;
    max-width: 700px;
    margin: 0 auto;
}

.custom-content {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
}

.intro-section {
    text-align: center;
    margin-bottom: 60px;
}

.intro-section h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #1a1a1a;
}

.intro-section p {
    font-size: 18px;
    line-height: 1.8;
    color: #4b5563;
    max-width: 800px;
    margin: 0 auto 30px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.feature-card {
    background: white;
    border-radius: 12px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 36px;
    color: white;
}

.feature-card h3 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #1a1a1a;
}

.feature-card p {
    font-size: 16px;
    line-height: 1.7;
    color: #6b7280;
}

.process-section {
    background: #f9fafb;
    padding: 60px 30px;
    border-radius: 12px;
    margin-bottom: 60px;
}

.process-section h2 {
    text-align: center;
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 50px;
    color: #1a1a1a;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    max-width: 1000px;
    margin: 0 auto;
}

.process-step {
    text-align: center;
    position: relative;
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 24px;
    font-weight: 700;
    color: white;
}

.process-step h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #1a1a1a;
}

.process-step p {
    font-size: 15px;
    line-height: 1.6;
    color: #6b7280;
}

.customization-options {
    margin-bottom: 60px;
}

.customization-options h2 {
    text-align: center;
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 40px;
    color: #1a1a1a;
}

.options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.option-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 30px;
    transition: all 0.3s ease;
}

.option-card:hover {
    border-color: #228B22;
    transform: translateY(-3px);
}

.option-card i {
    font-size: 32px;
    color: #228B22;
    margin-bottom: 15px;
}

.option-card h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 12px;
    color: #1a1a1a;
}

.option-card ul {
    list-style: none;
    padding: 0;
}

.option-card ul li {
    font-size: 15px;
    color: #6b7280;
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
}

.option-card ul li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #228B22;
    font-weight: 700;
}

.cta-section {
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    color: white;
    padding: 60px 40px;
    border-radius: 12px;
    text-align: center;
}

.cta-section h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 20px;
}

.cta-section p {
    font-size: 18px;
    margin-bottom: 30px;
    opacity: 0.95;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-button {
    display: inline-block;
    padding: 15px 40px;
    background: white;
    color: #228B22;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.cta-button.secondary {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.cta-button.secondary:hover {
    background: white;
    color: #228B22;
}

.testimonial-section {
    margin: 60px 0;
    padding: 60px 40px;
    background: #f9fafb;
    border-radius: 12px;
}

.testimonial-section h2 {
    text-align: center;
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 40px;
    color: #1a1a1a;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1000px;
    margin: 0 auto;
}

.testimonial-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.testimonial-text {
    font-size: 16px;
    line-height: 1.7;
    color: #4b5563;
    margin-bottom: 20px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #228B22 0%, #2F4F4F 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    font-weight: 700;
}

.author-info h4 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #1a1a1a;
}

.author-info p {
    font-size: 14px;
    margin: 0;
    color: #9ca3af;
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 36px;
    }
    
    .intro-section h2,
    .process-section h2,
    .customization-options h2,
    .cta-section h2,
    .testimonial-section h2 {
        font-size: 28px;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-button {
        width: 100%;
        max-width: 300px;
    }
}
</style>

<div class="page-header">
    <div class="container">
        <h1>Customised Photography Tours</h1>
        <p>Tailor-made experiences designed exclusively for your photography journey</p>
    </div>
</div>

<div class="custom-content">
    <div class="intro-section">
        <h2>Your Vision, Our Expertise</h2>
        <p>
            Every photographer has a unique vision and style. That's why we offer fully customised photography 
            tours that are designed around your specific interests, skill level, and creative goals. Whether 
            you're chasing landscapes, wildlife, cultural experiences, or adventure photography, we'll create 
            the perfect itinerary just for you.
        </p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3>Flexible Itineraries</h3>
            <p>Choose your destinations, duration, and pace. We adapt to your schedule and preferences, not the other way around.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Private Groups</h3>
            <p>Travel with your own group of friends, family, or fellow photographers. Perfect for photography clubs and workshops.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-camera"></i>
            </div>
            <h3>Expert Guidance</h3>
            <p>Work with experienced photography guides who know the best locations, lighting conditions, and local culture.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-star"></i>
            </div>
            <h3>Premium Experiences</h3>
            <p>Access exclusive locations, private viewpoints, and unique opportunities not available on standard tours.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-hotel"></i>
            </div>
            <h3>Custom Accommodation</h3>
            <p>Choose from budget-friendly to luxury stays based on your preferences and comfort requirements.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3>Any Season</h3>
            <p>Plan your tour for any time of year to capture specific seasonal photography like monsoons, festivals, or wildlife.</p>
        </div>
    </div>

    <div class="process-section">
        <h2>How It Works</h2>
        <div class="process-steps">
            <div class="process-step">
                <div class="step-number">1</div>
                <h3>Share Your Vision</h3>
                <p>Tell us about your photography interests, skill level, preferred destinations, and dates.</p>
            </div>
            
            <div class="process-step">
                <div class="step-number">2</div>
                <h3>Get Custom Proposal</h3>
                <p>We'll create a detailed itinerary with locations, timings, costs, and photography opportunities.</p>
            </div>
            
            <div class="process-step">
                <div class="step-number">3</div>
                <h3>Refine Details</h3>
                <p>Work with us to adjust the plan until it perfectly matches your expectations and budget.</p>
            </div>
            
            <div class="process-step">
                <div class="step-number">4</div>
                <h3>Book & Shoot</h3>
                <p>Confirm your booking and get ready for an unforgettable photography adventure!</p>
            </div>
        </div>
    </div>

    <div class="customization-options">
        <h2>Customize Every Aspect</h2>
        <div class="options-grid">
            <div class="option-card">
                <i class="fas fa-mountain"></i>
                <h3>Destinations</h3>
                <ul>
                    <li>Himalayan landscapes</li>
                    <li>Desert vistas</li>
                    <li>Coastal scenery</li>
                    <li>Wildlife sanctuaries</li>
                    <li>Cultural heritage sites</li>
                    <li>Urban photography</li>
                </ul>
            </div>
            
            <div class="option-card">
                <i class="fas fa-clock"></i>
                <h3>Duration</h3>
                <ul>
                    <li>Weekend getaways (2-3 days)</li>
                    <li>Week-long tours (7-10 days)</li>
                    <li>Extended expeditions (15+ days)</li>
                    <li>Multi-destination trips</li>
                    <li>Seasonal campaigns</li>
                </ul>
            </div>
            
            <div class="option-card">
                <i class="fas fa-palette"></i>
                <h3>Photography Focus</h3>
                <ul>
                    <li>Landscape photography</li>
                    <li>Wildlife & birds</li>
                    <li>Street & documentary</li>
                    <li>Cultural portraits</li>
                    <li>Astrophotography</li>
                    <li>Macro & nature</li>
                </ul>
            </div>
            
            <div class="option-card">
                <i class="fas fa-user-graduate"></i>
                <h3>Skill Level</h3>
                <ul>
                    <li>Beginner-friendly</li>
                    <li>Intermediate challenges</li>
                    <li>Advanced techniques</li>
                    <li>Workshop-style learning</li>
                    <li>Professional mentoring</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="testimonial-section">
        <h2>What Our Clients Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <p class="testimonial-text">
                    "The custom tour exceeded all expectations. Every location was perfect for golden hour 
                    shooting, and our guide knew exactly when and where to be for the best shots."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">RK</div>
                    <div class="author-info">
                        <h4>Rajesh Kumar</h4>
                        <p>Landscape Photographer</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">
                    "Planning our photography club trip was effortless. They handled everything and created 
                    an itinerary that catered to both beginners and experienced photographers in our group."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">PS</div>
                    <div class="author-info">
                        <h4>Priya Sharma</h4>
                        <p>Photography Club President</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">
                    "The flexibility to change plans based on weather and lighting conditions made all the 
                    difference. We got incredible shots that wouldn't have been possible on a fixed schedule."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">AM</div>
                    <div class="author-info">
                        <h4>Amit Mehta</h4>
                        <p>Wildlife Photographer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-section">
        <h2>Ready to Create Your Perfect Tour?</h2>
        <p>Let's design a photography adventure that captures your vision and exceeds your expectations.</p>
        <div class="cta-buttons">
            <a href="<?php echo SITE_URL; ?>/contact.php?inquiry=custom-tour" class="cta-button">
                <i class="fas fa-paper-plane"></i> Request Custom Itinerary
            </a>
            <a href="<?php echo SITE_URL; ?>/tours.php" class="cta-button secondary">
                <i class="fas fa-images"></i> View Sample Tours
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
