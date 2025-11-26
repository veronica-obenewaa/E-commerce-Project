<?php
require_once __DIR__ . '/../settings/core.php';
include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Med-ePharma</title>
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
        .about-hero {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .about-hero::before {
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
        
        .about-hero::after {
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
        
        .about-hero-content {
            position: relative;
            z-index: 2;
        }
        
        .about-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }
        
        .about-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
        }
        
        /* Main Content Sections */
        .content-section {
            padding: 80px 0;
            position: relative;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .section-title i {
            color: #10b981;
            -webkit-text-fill-color: unset;
        }
        
        .section-subtitle {
            font-size: 1.3rem;
            color: #374151;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .section-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        
        /* Feature Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            border-left: 4px solid #10b981;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
        
        .feature-card-icon {
            font-size: 2.5rem;
            color: #10b981;
            margin-bottom: 1rem;
        }
        
        .feature-card h3 {
            color: #1f2937;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .feature-card p {
            color: #6b7280;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        /* Values Section */
        .values-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            margin-top: 3rem;
        }
        
        .value-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #f0fdf4 100%);
            padding: 2.5rem;
            border-radius: 12px;
            border: 2px solid #dbeafe;
            text-align: center;
        }
        
        .value-card h4 {
            color: #059669;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }
        
        .value-card p {
            color: #6b7280;
            line-height: 1.6;
        }
        
        /* Statistics Section */
        .stats-section {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 60px 0;
            margin: 60px 0;
            border-radius: 12px;
        }
        
        .stat-box {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 500;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            margin-top: 3rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
            transform: translateX(-50%);
        }
        
        .timeline-item {
            margin-bottom: 3rem;
            width: 48%;
        }
        
        .timeline-item:nth-child(odd) {
            margin-left: 0;
            text-align: right;
            padding-right: 2rem;
        }
        
        .timeline-item:nth-child(even) {
            margin-left: 52%;
            text-align: left;
            padding-left: 2rem;
        }
        
        .timeline-content {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .timeline-year {
            color: #10b981;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .timeline-title {
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .timeline-description {
            color: #6b7280;
            font-size: 0.95rem;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
            padding: 60px 0;
            border-radius: 12px;
            text-align: center;
            margin-top: 60px;
        }
        
        .cta-section h2 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 1.5rem;
            font-weight: 800;
        }
        
        .cta-section p {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-cta {
            padding: 1rem 2rem;
            font-weight: 700;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn-cta-primary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        
        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
            color: white;
        }
        
        .btn-cta-secondary {
            background: white;
            color: #059669;
            border: 2px solid #059669;
        }
        
        .btn-cta-secondary:hover {
            background: #f0fdf4;
            color: #059669;
            transform: translateY(-2px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .about-hero h1 {
                font-size: 2rem;
            }
            
            .about-hero p {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .section-text {
                font-size: 1rem;
            }
            
            .timeline::before {
                left: 20px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 60px !important;
                padding-right: 0 !important;
                text-align: left !important;
                margin-left: 0 !important;
            }
            
            .content-section {
                padding: 50px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="about-hero-content">
                <h1><i class="fas fa-info-circle"></i> About Med-ePharma</h1>
                <p>Revolutionizing healthcare access in Ghana through innovative technology and trusted partnerships.</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- General Description Section -->
        <section class="content-section">
            <h2 class="section-title">
                <i class="fas fa-hospital"></i> Who We Are
            </h2>
            <div class="section-text">
                <p>
                    Med-ePharma is a Ghana-based e-commerce platform designed to revolutionize access to pharmaceutical products and telehealth services. The platform enables licensed pharmaceutical companies to onboard and sell medications directly to customers through a secure and user-friendly digital marketplace.
                </p>
                <p>
                    We are committed to bridging the critical gaps in Ghana's healthcare system by providing a seamless connection between patients, certified pharmaceutical suppliers, and licensed physicians through our integrated platform.
                </p>
            </div>

            <!-- Key Features -->
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-card-icon">
                        <i class="fas fa-check-shield"></i>
                    </div>
                    <h3>Verified & Certified</h3>
                    <p>All pharmaceutical companies and medications on our platform are thoroughly vetted and certified to ensure authenticity and safety.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Secure Platform</h3>
                    <p>Enterprise-grade security measures protect your personal data and ensure safe transactions on our platform.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Expert Support</h3>
                    <p>Our team of healthcare professionals is available to provide guidance and support whenever you need it.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3>Telehealth Services</h3>
                    <p>Connect with licensed physicians for consultations, prescription services, and medical advice from the comfort of your home.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h3>Wide Medication Range</h3>
                    <p>Access a comprehensive catalog of genuine, affordable medications from trusted pharmaceutical companies.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card-icon">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Reliable and swift delivery services to get your medications to you when you need them most.</p>
                </div>
            </div>
        </section>

        <!-- Mission & Vision Section -->
        <section class="content-section">
            <div class="values-container">
                <div class="value-card">
                    <h4>
                        <i class="fas fa-bullseye"></i> Our Mission
                    </h4>
                    <p>
                        The mission of Med-ePharma is to leverage technology to bridge the critical gaps in Ghana's healthcare system by connecting patients directly to certified pharmaceutical suppliers and licensed physicians through a secure, user-friendly, and reliable e-commerce and telemedicine platform.
                    </p>
                </div>
                <div class="value-card">
                    <h4>
                        <i class="fas fa-star"></i> Our Vision
                    </h4>
                    <p>
                        The vision of Med-ePharma is to become Ghana's leading integrated digital health platform, making quality healthcare and genuine medications accessible to every individual, irrespective of their location or physical ability.
                    </p>
                </div>
                <div class="value-card">
                    <h4>
                        <i class="fas fa-heart"></i> Our Values
                    </h4>
                    <p>
                        We are committed to integrity, accessibility, innovation, and excellence in everything we do. We believe in putting our customers' health and wellbeing at the center of our operations.
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Licensed Physicians</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number">50,000+</div>
                        <div class="stat-label">Medications Available</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number">1,000+</div>
                        <div class="stat-label">Pharmaceutical Companies</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Journey Section -->
        <section class="content-section">
            <h2 class="section-title">
                <i class="fas fa-road"></i> Our Journey
            </h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2024</div>
                        <div class="timeline-title">Platform Launch</div>
                        <div class="timeline-description">Med-ePharma officially launches with full e-commerce and telemedicine capabilities.</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2024</div>
                        <div class="timeline-title">Community Expansion</div>
                        <div class="timeline-description">We expand our network of verified pharmaceutical partners and licensed physicians across Ghana.</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <div class="timeline-year">2025</div>
                        <div class="timeline-title">Innovation</div>
                        <div class="timeline-description">Launching advanced features to enhance user experience and healthcare accessibility.</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <h2>Ready to Experience Quality Healthcare?</h2>
            <p>Join thousands of customers who trust Med-ePharma for their pharmaceutical and healthcare needs.</p>
            <div class="cta-buttons">
                <a href="all_product.php" class="btn-cta btn-cta-primary">
                    <i class="fas fa-shopping-cart"></i> Browse Medications
                </a>
                <a href="book_consultation.php" class="btn-cta btn-cta-primary">
                    <i class="fas fa-video"></i> Book Consultation
                </a>
                <a href="contact_us.php" class="btn-cta btn-cta-secondary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
