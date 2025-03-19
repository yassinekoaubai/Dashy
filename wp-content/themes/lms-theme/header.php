<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri() . '/assets/images/Logo-Dashy.svg' ?>">

  <title><?php bloginfo('name') ?> | <?php bloginfo('description') ?></title>

  <?php wp_head() ?>

</head>
<body>

  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>

  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="index.html" class="logo">
              <h4>Dashy <img src="../lms-theme/assets/images/logo-icon.png" alt=""></h4>
            </a>
            <ul class="nav">
              <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
              <li class="scroll-to-section"><a href="#features">À Propos </a></li>
              <li class="scroll-to-section"><a href="#about">About Us</a></li>
              <li class="scroll-to-section"><a href="#services">Services</a></li>
              <li class="scroll-to-section"><a href="#portfolio">Portfolio</a></li>
              <li class="scroll-to-section"><a href="#contact">Contact Us</a></li>
              <li class="scroll-to-section">
                <div class="main-blue-button"><a href="#contact">Get Started</a></div>
              </li>
            </ul>
            <a class='menu-trigger'>
              <span>Menu</span>
            </a>
          </nav>
        </div>
      </div>
    </div>
  </header>