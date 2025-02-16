<?php
// dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// Fetch user details (e.g., first name) for the greeting
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT first_name, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$first_name = $user ? $user['first_name'] : 'User';
$role       = $user ? $user['role'] : 'parent';

include 'includes/header.php';
?>


<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Section: Greeting & CTA -->
            <div class="col-md-6 text-left">
                <h1 class="hero-title">Hello, <?php echo htmlspecialchars($first_name); ?>!</h1>
                <p class="hero-subtext">
                    Our mission is to help you find the best tutors online and learn with expert guidance anytime,
                    anywhere.
                </p>
                <!-- "Search for Tutor" Button -->
                <a href="view_posts.php" class="btn btn-primary search-tutor-btn">Search for Tutor</a>
            </div>

            <!-- Right Section: Image -->
            <div class="col-md-6 text-center">
                <!-- Replace 'tutor-student.png' with your own hero image -->
                <img src="assets/images/tutor-student.png" alt="Tutor and Student" class="img-fluid hero-img">
            </div>
        </div>
    </div>
</div>

<!-- Browse Top Courses Section -->
<div class="container mt-5">
    <h2 class="section-title">Browse Top Courses</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="course-card">
                <h5>SEE</h5>
                <p>12,323 Courses</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="course-card">
                <h5>BLE</h5>
                <p>8,232 Courses</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="course-card">
                <h5>IT & Software</h5>
                <p>2,232 Courses</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="course-card">
                <h5>Accounts</h5>
                <p>1,232 Courses</p>
            </div>
        </div>
    </div>
</div>

<!-- Top Tutors of the Month Section -->
<div class="container mt-5">
    <h2 class="section-title">Top Tutors of the Month</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="tutor-card">
                <img src="assets/images/tutor1.jpg" alt="Tutor 1">
                <h5>Tutor Name 1</h5>
                <p>Expert in Math & Science</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tutor-card">
                <img src="assets/images/tutor2.jpg" alt="Tutor 2">
                <h5>Tutor Name 2</h5>
                <p>Expert in Language & Arts</p>
            </div>
        </div>
    </div>
</div>

<!-- Tutors Near You Section -->
<div class="container mt-5">
    <h2 class="section-title">Tutors Near You</h2>
    <div class="row">
        <!-- Example tutor card placeholders -->
        <div class="col-md-3">
            <div class="tutor-card">
                <img src="assets/images/tutor3.jpg" alt="Tutor 3">
                <h5>Tutor Name 3</h5>
                <p>Near your location</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tutor-card">
                <img src="assets/images/tutor4.jpg" alt="Tutor 4">
                <h5>Tutor Name 4</h5>
                <p>Near your location</p>
            </div>
        </div>
    </div>
</div>

<!-- Become a Tutor & Steps -->
<div class="container mt-5 tutor-steps-section">
    <div class="row">
        <!-- Left Column: Become a Tutor -->
        <div class="col-md-6 mb-3">
            <div class="become-tutor-card">
                <h3>Become a Tutor</h3>
                <p>Instructors from Nepal reach millions of students on Ghar Tuition. We provide the tools and skills to
                    teach what you love.</p>
                <button class="become-tutor-btn">Apply Now</button>
            </div>
        </div>
        <!-- Right Column: Teaching & Earning Steps -->
        <div class="col-md-6">
            <h4>Your teaching & earning steps</h4>
            <div class="teaching-steps">
                <ul>
                    <li>Apply to become a tutor</li>
                    <li>Build & edit your profile</li>
                    <li>Interview Process</li>
                    <li>Start teaching & earning</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Stats Section -->
<div class="bottom-stats-section mt-5">
    <h2>Start learning with the best tutors anytime anywhere.</h2>
    <div class="stats-cards">
        <div class="stats-card">
            <h3>6.3k</h3>
            <span>Online Students</span>
        </div>
        <div class="stats-card">
            <h3>26k</h3>
            <span>Certified Tutors</span>
        </div>
        <div class="stats-card">
            <h3>99.9%</h3>
            <span>Success Rate</span>
        </div>
    </div>
</div>

<!-- Role-Based Actions -->
<div class="container mt-5 text-center">
    <?php if ($role == 'parent'): ?>
    <a href="create_post.php" class="btn btn-primary">Create Tuition Post</a>
    <a href="manage_posts.php" class="btn btn-secondary">Manage My Posts</a>
    <?php elseif ($role == 'tutor'): ?>
    <a href="view_posts.php" class="btn btn-primary">View Tuition Posts</a>
    <a href="my_applications.php" class="btn btn-secondary">My Applications</a>
    <?php elseif ($role == 'admin'): ?>
    <a href="admin_pending_posts.php" class="btn btn-primary">Review Pending Posts</a>
    <a href="admin_applications.php" class="btn btn-secondary">View Applications</a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>