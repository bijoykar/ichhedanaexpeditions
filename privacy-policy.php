<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = 'Privacy Policy - ' . SITE_NAME;
require_once 'includes/header.php';
?>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
}

.privacy-content {
    max-width: 900px;
    margin: 60px auto;
    padding: 0 20px;
}

.privacy-section {
    margin-bottom: 50px;
}

.privacy-section h2 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 3px solid #667eea;
}

.privacy-section h3 {
    font-size: 24px;
    font-weight: 600;
    color: #374151;
    margin: 30px 0 15px 0;
}

.privacy-section p {
    font-size: 16px;
    line-height: 1.8;
    color: #4b5563;
    margin-bottom: 20px;
}

.privacy-section ul, .privacy-section ol {
    margin: 20px 0;
    padding-left: 30px;
}

.privacy-section li {
    font-size: 16px;
    line-height: 1.8;
    color: #4b5563;
    margin-bottom: 10px;
}

.privacy-section strong {
    color: #1a1a1a;
    font-weight: 600;
}

.highlight-box {
    background: #f3f4f6;
    border-left: 4px solid #667eea;
    padding: 20px 25px;
    margin: 25px 0;
    border-radius: 8px;
}

.contact-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 12px;
    text-align: center;
    margin-top: 60px;
}

.contact-box h3 {
    font-size: 28px;
    margin-bottom: 15px;
}

.contact-box p {
    font-size: 16px;
    margin-bottom: 25px;
    opacity: 0.9;
}

.contact-box a {
    display: inline-block;
    background: white;
    color: #667eea;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.contact-box a:hover {
    transform: translateY(-2px);
}

.last-updated {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 40px;
    font-size: 14px;
    color: #92400e;
}
</style>

<div class="page-header">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us</p>
    </div>
</div>

<div class="privacy-content">
    <div class="last-updated">
        <strong>Last Updated:</strong> January 10, 2026
    </div>

    <div class="privacy-section">
        <h2>Introduction</h2>
        <p>
            Welcome to <?php echo SITE_NAME; ?>. We respect your privacy and are committed to protecting your personal data. 
            This privacy policy will inform you about how we look after your personal data when you visit our website 
            and tell you about your privacy rights and how the law protects you.
        </p>
        <p>
            This privacy policy applies to information we collect about you when you use our website, book tours, 
            sign up for our newsletter, or otherwise interact with us.
        </p>
    </div>

    <div class="privacy-section">
        <h2>Information We Collect</h2>
        
        <h3>1. Personal Information</h3>
        <p>We may collect the following types of personal information:</p>
        <ul>
            <li><strong>Contact Information:</strong> Name, email address, phone number, postal address</li>
            <li><strong>Booking Information:</strong> Travel dates, tour preferences, number of participants, special requests</li>
            <li><strong>Payment Information:</strong> Credit card details, billing address (processed securely through our payment providers)</li>
            <li><strong>Account Information:</strong> Username, password, profile preferences</li>
            <li><strong>Communication Data:</strong> Records of your correspondence with us via email, phone, or contact forms</li>
        </ul>

        <h3>2. Automatically Collected Information</h3>
        <p>When you visit our website, we automatically collect:</p>
        <ul>
            <li><strong>Technical Data:</strong> IP address, browser type and version, operating system, device information</li>
            <li><strong>Usage Data:</strong> Pages visited, time spent on pages, links clicked, referring website</li>
            <li><strong>Location Data:</strong> General geographic location based on your IP address</li>
        </ul>
    </div>

    <div class="privacy-section">
        <h2>How We Use Your Information</h2>
        <p>We use your personal information for the following purposes:</p>
        <ol>
            <li><strong>To Process Bookings:</strong> Managing your tour reservations, sending confirmations, and providing customer support</li>
            <li><strong>To Communicate:</strong> Sending booking confirmations, updates, travel information, and responding to inquiries</li>
            <li><strong>To Improve Services:</strong> Analyzing usage patterns to enhance our website and tour offerings</li>
            <li><strong>Marketing:</strong> Sending promotional emails about special offers, new tours, and travel tips (with your consent)</li>
            <li><strong>Legal Compliance:</strong> Complying with legal obligations and protecting our legal rights</li>
            <li><strong>Security:</strong> Protecting against fraud, unauthorized access, and other security risks</li>
        </ol>
    </div>

    <div class="privacy-section">
        <h2>Legal Basis for Processing</h2>
        <p>We process your personal data under the following legal bases:</p>
        <ul>
            <li><strong>Contract Performance:</strong> Processing is necessary to fulfill our contract with you when you book a tour</li>
            <li><strong>Consent:</strong> You have given explicit consent for marketing communications or cookies</li>
            <li><strong>Legitimate Interests:</strong> Processing is necessary for our legitimate business interests (improving services, security)</li>
            <li><strong>Legal Obligation:</strong> Processing is required to comply with legal or regulatory requirements</li>
        </ul>
    </div>

    <div class="privacy-section">
        <h2>Sharing Your Information</h2>
        <p>We may share your information with:</p>
        
        <h3>Service Providers</h3>
        <ul>
            <li><strong>Payment Processors:</strong> To process payments securely</li>
            <li><strong>Tour Operators:</strong> Local guides and accommodation providers necessary for your tour</li>
            <li><strong>Email Services:</strong> To send booking confirmations and newsletters</li>
            <li><strong>Website Hosting:</strong> Companies that host our website and databases</li>
        </ul>

        <h3>Legal Requirements</h3>
        <p>We may disclose your information when required by law, court order, or to protect our rights and safety.</p>

        <div class="highlight-box">
            <strong>We do not sell your personal information to third parties.</strong>
        </div>
    </div>

    <div class="privacy-section">
        <h2>Data Security</h2>
        <p>
            We implement appropriate technical and organizational measures to protect your personal data against 
            unauthorized access, alteration, disclosure, or destruction. These measures include:
        </p>
        <ul>
            <li>SSL/TLS encryption for data transmission</li>
            <li>Secure servers with firewall protection</li>
            <li>Access controls and authentication</li>
            <li>Regular security audits and updates</li>
            <li>Employee training on data protection</li>
        </ul>
        <p>
            However, no method of transmission over the internet is 100% secure. While we strive to protect your 
            personal data, we cannot guarantee absolute security.
        </p>
    </div>

    <div class="privacy-section">
        <h2>Data Retention</h2>
        <p>
            We retain your personal data only as long as necessary for the purposes outlined in this policy:
        </p>
        <ul>
            <li><strong>Booking Data:</strong> Retained for 7 years for accounting and legal compliance</li>
            <li><strong>Marketing Data:</strong> Until you unsubscribe or request deletion</li>
            <li><strong>Website Analytics:</strong> Typically retained for 26 months</li>
            <li><strong>Communication Records:</strong> Retained for 3 years for customer service purposes</li>
        </ul>
    </div>

    <div class="privacy-section">
        <h2>Your Privacy Rights</h2>
        <p>Under data protection laws, you have the following rights:</p>
        
        <h3>Access</h3>
        <p>Request a copy of the personal data we hold about you</p>

        <h3>Correction</h3>
        <p>Request correction of inaccurate or incomplete data</p>

        <h3>Deletion</h3>
        <p>Request deletion of your personal data (subject to legal obligations)</p>

        <h3>Restriction</h3>
        <p>Request that we restrict processing of your data</p>

        <h3>Portability</h3>
        <p>Request transfer of your data to another service provider</p>

        <h3>Objection</h3>
        <p>Object to processing of your data for direct marketing</p>

        <h3>Withdraw Consent</h3>
        <p>Withdraw consent at any time where we rely on consent</p>

        <div class="highlight-box">
            <strong>To exercise any of these rights, please contact us using the details at the bottom of this page.</strong>
        </div>
    </div>

    <div class="privacy-section">
        <h2>Cookies</h2>
        <p>
            Our website uses cookies to enhance your browsing experience. Cookies are small text files stored on your 
            device that help us:
        </p>
        <ul>
            <li>Remember your preferences and settings</li>
            <li>Analyze website traffic and user behavior</li>
            <li>Provide personalized content</li>
            <li>Enable social media features</li>
        </ul>
        <p>
            You can control cookies through your browser settings. However, disabling cookies may affect the 
            functionality of our website.
        </p>
    </div>

    <div class="privacy-section">
        <h2>Third-Party Links</h2>
        <p>
            Our website may contain links to third-party websites. We are not responsible for the privacy practices 
            or content of these external sites. We encourage you to review their privacy policies before providing 
            any personal information.
        </p>
    </div>

    <div class="privacy-section">
        <h2>Children's Privacy</h2>
        <p>
            Our services are not directed to individuals under the age of 18. We do not knowingly collect personal 
            information from children. If you believe we have inadvertently collected information from a child, 
            please contact us immediately.
        </p>
    </div>

    <div class="privacy-section">
        <h2>International Data Transfers</h2>
        <p>
            Your information may be transferred to and processed in countries other than your country of residence. 
            We ensure appropriate safeguards are in place to protect your data in accordance with this privacy policy 
            and applicable laws.
        </p>
    </div>

    <div class="privacy-section">
        <h2>Changes to This Policy</h2>
        <p>
            We may update this privacy policy from time to time to reflect changes in our practices or legal requirements. 
            We will notify you of any material changes by posting the new policy on this page and updating the 
            "Last Updated" date. Your continued use of our services after changes constitutes acceptance of the updated policy.
        </p>
    </div>

    <div class="contact-box">
        <h3>Questions About Privacy?</h3>
        <p>
            If you have any questions about this privacy policy or how we handle your personal data, 
            please don't hesitate to contact us.
        </p>
        <a href="<?php echo SITE_URL; ?>/contact.php">
            <i class="fas fa-envelope"></i> Contact Us
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
