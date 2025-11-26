<?php
require_once __DIR__ . '/../settings/core.php';
include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #f7f9fc;
        }
        
        /* Hero Section */
        .contact-hero {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .contact-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }
        
        .contact-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }
        
        .contact-hero-content {
            position: relative;
            z-index: 2;
        }
        
        .contact-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .contact-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
        }
        
        /* Main Content */
        .content-wrapper {
            padding: 80px 0;
        }
        
        /* Contact Info Section */
        .contact-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 60px;
        }
        
        .contact-info-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            text-align: center;
            border-top: 4px solid #10b981;
            transition: all 0.3s ease;
        }
        
        .contact-info-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
        
        .contact-info-icon {
            font-size: 2.5rem;
            color: #10b981;
            margin-bottom: 1rem;
        }
        
        .contact-info-card h3 {
            color: #1f2937;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .contact-info-card p {
            color: #6b7280;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        .contact-info-card a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .contact-info-card a:hover {
            color: #059669;
            text-decoration: underline;
        }
        
        /* Contact Form Section */
        .contact-form-section {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .form-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .form-subtitle {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control,
        .form-select,
        textarea.form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            outline: none;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
            color: white;
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .form-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            display: none;
        }
        
        .form-message.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
            display: block;
        }
        
        .form-message.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            display: block;
        }
        
        /* Social Links */
        .social-section {
            text-align: center;
            padding: 40px 0;
            margin-top: 40px;
            border-top: 2px solid #e5e7eb;
        }
        
        .social-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 2rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        
        .social-link {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0fdf4 0%, #f0fdf4 100%);
            border: 2px solid #10b981;
            border-radius: 50%;
            color: #10b981;
            text-decoration: none;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Office Hours */
        .office-hours {
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
            padding: 2rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .hours-title {
            color: #059669;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .hours-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            color: #6b7280;
            border-bottom: 1px solid rgba(107, 114, 128, 0.2);
        }
        
        .hours-item:last-child {
            border-bottom: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .contact-hero h1 {
                font-size: 2rem;
            }
            
            .contact-hero p {
                font-size: 1rem;
            }
            
            .contact-form-section {
                padding: 2rem 1rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
            
            .content-wrapper {
                padding: 40px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="contact-hero-content">
                <h1><i class="fas fa-envelope"></i> Contact Us</h1>
                <p>Get in touch with our team. We're here to help and answer any questions you may have.</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container content-wrapper">
        <!-- Contact Info Cards -->
        <div class="contact-info-grid">
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Office Address</h3>
                <p>
                    Med-ePharma Ghana<br>
                    Accra, Ghana<br>
                    <a href="https://maps.google.com" target="_blank">View on Map</a>
                </p>
            </div>

            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Phone</h3>
                <p>
                    <a href="tel:+233500000000">+233 (500) 000-000</a><br>
                    <small>Available Monday - Friday, 9am - 5pm GMT</small>
                </p>
            </div>

            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p>
                    <a href="mailto:support@medepharma.com">support@medepharma.com</a><br>
                    <a href="mailto:info@medepharma.com">info@medepharma.com</a>
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <section class="contact-form-section">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h2 class="form-title">Send us a Message</h2>
                    <p class="form-subtitle">Fill out the form below and we'll get back to you as soon as possible.</p>

                    <form id="contactForm" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Full Name *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i> Phone Number
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-tag"></i> Subject *
                                </label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="support">Customer Support</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="complaints">Complaints</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">
                                <i class="fas fa-comment"></i> Message *
                            </label>
                            <textarea class="form-control" id="message" name="message" required placeholder="Please provide details about your inquiry..."></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </div>

                        <div id="formMessage" class="form-message"></div>
                    </form>

                    <!-- Office Hours -->
                    <div class="office-hours">
                        <div class="hours-title">
                            <i class="fas fa-clock"></i> Office Hours
                        </div>
                        <div class="hours-item">
                            <span>Monday - Friday:</span>
                            <strong>9:00 AM - 5:00 PM (GMT)</strong>
                        </div>
                        <div class="hours-item">
                            <span>Saturday:</span>
                            <strong>10:00 AM - 2:00 PM (GMT)</strong>
                        </div>
                        <div class="hours-item">
                            <span>Sunday:</span>
                            <strong>Closed</strong>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="social-section">
                        <div class="social-title">Follow Us</div>
                        <div class="social-links">
                            <a href="#" class="social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageDiv = document.getElementById('formMessage');
            
            // Get form data
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            // Validate form
            if (!name || !email || !subject || !message) {
                messageDiv.textContent = 'Please fill in all required fields.';
                messageDiv.className = 'form-message error';
                return;
            }
            
            // In a real application, you would send this data to a server
            // For now, we'll just show a success message
            messageDiv.innerHTML = `
                <i class="fas fa-check-circle"></i> 
                Thank you for your message! We'll get back to you soon.
            `;
            messageDiv.className = 'form-message success';
            
            // Reset form
            this.reset();
            
            // Clear message after 5 seconds
            setTimeout(() => {
                messageDiv.className = 'form-message';
            }, 5000);
        });
    </script>
</body>
</html>
