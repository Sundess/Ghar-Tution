<?php
// dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ghar Tution</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Import Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">

            <!-- Navbar Toggler for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- Left Side -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Become Tutor</a>
                    </li>
                </ul>

                <!-- Right Side with Dropdowns -->
                <ul class="navbar-nav">

                    <!-- NRs Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="nrsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            NRs <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="nrsDropdown">
                            <li><a class="dropdown-item" href="#">NRs Option 1</a></li>
                            <li><a class="dropdown-item" href="#">NRs Option 2</a></li>
                        </ul>
                    </li>

                    <!-- English Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="englishDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            English <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="englishDropdown">
                            <li><a class="dropdown-item" href="#">English Option 1</a></li>
                            <li><a class="dropdown-item" href="#">English Option 2</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Below Navbar Section -->
    <div class="secondary-navbar">
        <!-- Left Side: Logo -->
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo"> <!-- Replace with your logo -->
        </div>

        <!-- Right Side: Buttons -->
        <div>
            <a href="register.php" class="btn custom-btn btn-auth">Create Account</a>
            <a href="login.php" class="btn custom-btn-create btn-auth">Sign In</a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero-section">
        <!-- Left Side: Text -->
        <div class="hero-left">
            <h1>Learn from tutors </h1>
            <h1>anytime, anywhere</h1>
            <p>Our mission is to help you to find the best tutors online </p>
            <p>and learn with expert anytime, anywhere.</p>
        </div>

        <!-- Right Side: Image -->
        <div class="hero-right">
            <img src="assets/images/hero-image.png" alt="Hero Image"> <!-- Replace with your image -->
        </div>
    </div>

    <!-- Mission Section -->
    <section class="mission-section">
        <h2>Our Mission</h2>
        <div class="mission-cards">
            <!-- Card 1: Personalized Learning -->
            <div id="left-card" class="card" onclick="window.location.href='aboutus.html';">
                <i class="fas fa-user-graduate fa-3x card-icon "
                    style="color:rgb(249, 218, 218);padding-bottom: 10px;"></i>
                <h3>Personalized Learning</h3>
                <p>Offer tailored educational experiences to each student, ensuring they learn at their own pace and
                    according to their individual needs and strengths.</p>
            </div>

            <!-- Card 2: Affordable Education -->
            <div class="cards highlight-card" onclick="window.location.href='aboutus.html';">
                <i class="fas fa-wallet fa-3x card-icon" style="padding-bottom: 10px;"></i>
                <h3>Affordable Education</h3>
                <p>Provide quality education that is accessible and affordable for all students, breaking down financial
                    barriers to learning opportunities.</p>
            </div>

            <!-- Card 3: Holistic Development -->
            <div id="right-card" class="card" onclick="window.location.href='aboutus.html';">
                <i class="fas fa-brain fa-3x card-icon" style="color:rgb(204, 247, 208);padding-bottom: 10px;"></i>
                <h3>Holistic Development</h3>
                <p>Focus on the all-around development of students by integrating academic learning with life skills,
                    critical thinking, and emotional intelligence.</p>
            </div>
        </div>


    </section>

    <!-- About Us Section -->
    <section class="about-us-section">
        <div class="left-side">
            <img src="assets/images/tuition.png" alt="About Us Image" />
        </div>
        <div class="right-side">
            <h1 class="main-heading">
                <span class="orange-text">About Ghar Tuition</span>
            </h1>
            <h1 class="aboutustext">Empowering Students Through Personalized Learning</h1>
            <p class="subtext">
                At Ghar Tuition, we are committed to providing a personalized and affordable learning experience to
                every student. Our experienced tutors are dedicated to helping students of all levels succeed
                academically and develop essential life skills. Whether it’s through one-on-one tutoring or focused
                group sessions, we aim to empower students to reach their full potential in a supportive and flexible
                environment.
            </p>
            <button class="feature-button"><a href="#">Learn More</a></button>
        </div>

    </section>
    <!-- About Us Section -->
    <section class="about-us-section">
        <!-- Your existing About Us content here -->
    </section>

    <!-- Become a Tutor Section -->
    <section class="become-tutor-section mt-1 mb-5">
        <div class="container">
            <div class="row align-items-center justify-content-center" style="margin-left: 30px; margin-bottom: 110px;">
                <!-- Left Side: Tutor Card -->
                <div class="col-md-5 mb-4">
                    <div class="tutor-card">
                        <div class="text-content">
                            <h2 class="mb-3">Become a Tutor</h2>
                            <p>
                                Instructors from Nepal teach millions of students on Ghar Tuition.
                                We provide the tools and skills to teach what you love.
                            </p>
                            <a href="register.php" class="btn btn-light align-self-start mt-3">Apply Now</a>
                        </div>
                        <div class="image-content">
                            <img src="assets/images/tutor.png" alt="">
                        </div>
                    </div>
                </div>

                

                
                <!-- Right Side: Teaching & Earning Steps -->
                <div class="col-md-6" style="margin-left: 100px;">
                    <h3 class="mb-4">Your teaching & earning steps</h3>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-start mb-2">
                            <div class="badge bg-primary me-3">1</div>
                            <p class="mb-0">Apply to become a tutor</p>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <div class="badge bg-primary me-3">2</div>
                            <p class="mb-0">Build & edit your profile</p>
                        </div>
                        <div class="d-flex align-items-start mb-2">
                            <div class="badge bg-primary me-3">3</div>
                            <p class="mb-0">Interview Process</p>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="badge bg-primary me-3">4</div>
                            <p class="mb-0">Start teaching & earning</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
include 'includes/footer.php';
?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>