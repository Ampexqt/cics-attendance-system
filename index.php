<?php $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ZPPSU CICS Student Access & Attendance System</title>
    <link rel="stylesheet" href="frontend/assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  </head>

  <body>
    <!-- HEADER -->
    <header class="header">
      <div class="header-logo">
        <img
          src="https://uploadthingy.s3.us-west-1.amazonaws.com/h5rtnYfu5NzN7nEjkomYz5/ZPPSU-LOGO.jpg"
          alt="ZPPSU Logo"
          class="logo-img"
        />
        <div class="header-title">
          ZAMBOANGA PENINSULA POLYTECHNIC STATE UNIVERSITY
        </div>
      </div>

      <div class="header-nav">
        <nav class="nav">
          <a href="#" class="nav-link">Home</a>
          <a href="#features" class="nav-link">Features</a>
          <a href="#about" class="nav-link">About</a>
          <a href="#contact" class="nav-link">Contact</a>
        </nav>
        <a href="login.php" class="btn-login">Login</a>
      </div>
    </header>

    <!-- HERO -->
    <main class="hero">
      <div class="hero-circle hero-circle-1"></div>
      <div class="hero-circle hero-circle-2"></div>

      <div class="hero-container">
        <div class="hero-content">
          <div class="hero-badge">
            <span>ZPPSU College of Information and Computing Sciences</span>
          </div>
          <h1 class="hero-title">
            CICS Students Access and Attendance System
          </h1>
          <h2 class="hero-subtitle">
            With Automated Parental Notification
          </h2>
          <p class="hero-description">
            A secure and efficient system designed for the College of Information and Computing Sciences (CICS) at ZPPSU. It streamlines
            student attendance tracking, provides real-time monitoring for instructors and administrators, and ensures parents are automatically informed.
          </p>
          <div class="hero-buttons">
            <button class="btn btn-primary">
              Get Started →
            </button>
            <button class="btn btn-secondary">
              Learn More
            </button>
          </div>
        </div>

        <div class="hero-image">
          <div class="hero-image-bg"></div>
          <div class="hero-image-wrapper">
            <img
              src="https://uploadthingy.s3.us-west-1.amazonaws.com/qHYtTa1uNrpFjc66NgGcuM/ZPPUS-CICS_LOGO.jpg"
              alt="CICS Logo"
              class="hero-img"
            />
          </div>
        </div>
      </div>
    </main>

    <!-- FEATURES -->
    <section id="features" class="features-section">
      <div class="container">
        <div class="features-header">
          <h2 class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L9.568 3z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
            </svg>
            Key Features
          </h2>
          <div class="section-divider"></div>
          <p class="section-description">
            Our system provides comprehensive tools for monitoring student access and attendance with automated notifications.
          </p>
        </div>

        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
              </svg>
            </div>
            <h3 class="feature-title">Accurate Attendance Tracking</h3>
            <p class="feature-description">Start and end class sessions with campus GPS restriction to ensure authentic attendance logs.</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
              </svg>
            </div>
            <h3 class="feature-title">Smart Notifications</h3>
            <p class="feature-description">Parents receive automated email summaries of student attendance daily (Present, Absent, Late).</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
              </svg>
            </div>
            <h3 class="feature-title">Flexible Management Tools</h3>
            <p class="feature-description">Admins manage students and instructors, approve requests, and generate reports.</p>
          </div>

          <div class="feature-card">
            <div class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
              </svg>
            </div>
            <h3 class="feature-title">Comprehensive Reports & Analytics</h3>
            <p class="feature-description">Instructors and admins can monitor absences/lateness and export reports to Excel.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="about-section">
      <div class="container">
        <div class="about-content">
          <div class="about-image">
            <div class="about-image-border"></div>
            <div class="about-image-wrapper">
              <div class="about-grid">
                <div class="about-grid-item">
                  <img src="https://uploadthingy.s3.us-west-1.amazonaws.com/h5rtnYfu5NzN7nEjkomYz5/ZPPSU-LOGO.jpg" alt="ZPPSU Logo" />
                </div>
                <div class="about-grid-item">
                  <img src="https://uploadthingy.s3.us-west-1.amazonaws.com/qHYtTa1uNrpFjc66NgGcuM/ZPPUS-CICS_LOGO.jpg" alt="CICS Logo" />
                </div>
                <div class="about-grid-item about-grid-item-span">
                  <h4 class="about-grid-title">ZPPSU CICS</h4>
                  <p class="about-grid-subtitle">College of Information and Computing Sciences</p>
                </div>
              </div>
            </div>
          </div>

          <div class="about-text">
            <h2 class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
              </svg>
              About the System
            </h2>
            <div class="section-divider"></div>
            <p class="about-description">
              The CICS Student Access and Attendance System with Automated Parental Notification is a centralized platform designed to strengthen accountability
              and transparency in student attendance.
            </p>
            <h3 class="about-benefits-title">Key Benefits</h3>
            <ul class="benefits-list">
              <li class="benefit-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Real-time attendance monitoring
              </li>
              <li class="benefit-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Automated parental notifications
              </li>
              <li class="benefit-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Secure login (one device per student)
              </li>
              <li class="benefit-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Approval workflow for changes
              </li>
              <li class="benefit-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Exportable reports & analytics
              </li>
            </ul>
            <button class="btn btn-primary">
              Learn More About CICS
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact" class="footer">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-col">
            <h3 class="footer-title">ZPPSU CICS</h3>
            <p class="footer-description">
              Empowering education with technology-driven attendance solutions.
            </p>
            <div class="footer-logos">
              <img src="https://uploadthingy.s3.us-west-1.amazonaws.com/h5rtnYfu5NzN7nEjkomYz5/ZPPSU-LOGO.jpg" alt="ZPPSU Logo" />
              <img src="https://uploadthingy.s3.us-west-1.amazonaws.com/qHYtTa1uNrpFjc66NgGcuM/ZPPUS-CICS_LOGO.jpg" alt="CICS Logo" />
            </div>
          </div>

          <div class="footer-col">
            <h3 class="footer-title">Quick Links</h3>
            <ul class="footer-links">
              <li><a href="#">Home</a></li>
              <li><a href="#features">Features</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
              <li><a href="#">Login</a></li>
            </ul>
          </div>

          <div class="footer-col">
            <h3 class="footer-title">Contact Information</h3>
            <ul class="contact-list">
              <li class="contact-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                </svg>
                <span>Zamboanga Peninsula Polytechnic State University, CICS</span>
              </li>
              <li class="contact-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
                <span>+63 (XXX) XXX XXXX</span>
              </li>
              <li class="contact-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <span>support@zppsu.edu</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2025 ZPPSU College of Information and Computing Sciences. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </body>
</html>
