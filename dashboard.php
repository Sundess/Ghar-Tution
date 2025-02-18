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

include 'includes/header.php'
?>

<!-- Hero Section -->
<div class="hero-section">
    <!-- Left Side: Text -->
    <div class="hero-left">
        <h1 class="hero-title">Hello, <?php echo htmlspecialchars($first_name); ?>!</h1>
        <p>Our mission is to help you to find the best tutors online </p>
        <p>and learn with expert anytime, anywhere.</p>
        <!-- Role-Based Actions -->
        <div class="mt-1">
            <?php if ($role == 'parent'): ?>
            <a href="create_post.php" class="btn" style="background-color: #172147; color: white; margin-right:13px; margin-top:15px">Create Tuition
                Post</a>
            <a href="manage_posts.php" class="btn btn-secondary" style="margin-top:15px;">See Your Posts</a>
            <?php elseif ($role == 'tutor'): ?>
            <a href="view_posts.php" class="btn btn-primary" style="background-color: #172147; color: white; ">View
                Tuition Posts</a>
            <a href="my_applications.php" class="btn btn-secondary">My Applications</a>
            <?php elseif ($role == 'admin'): ?>
            <a href="admin_pending_posts.php" class="btn btn-primary"
                style="background-color: #172147; color: white;">Review Pending Posts</a>
            <a href="admin_applications.php" class="btn btn-secondary">View Applications</a>
            <?php endif; ?>
        </div>

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
        <div id="left-card" class="card" onclick="">
            <i class="fas fa-user-graduate fa-3x card-icon " style="color:rgb(249, 218, 218);padding-bottom: 10px;"></i>
            <h3>Personalized Learning</h3>
            <p>Offer tailored educational experiences to each student, ensuring they learn at their own pace and
                according to their individual needs and strengths.</p>
        </div>

        <!-- Card 2: Affordable Education -->
        <div class="cards highlight-card" onclick="">
            <i class="fas fa-wallet fa-3x card-icon" style="padding-bottom: 10px;"></i>
            <h3>Affordable Education</h3>
            <p>Provide quality education that is accessible and affordable for all students, breaking down financial
                barriers to learning opportunities.</p>
        </div>

        <!-- Card 3: Holistic Development -->
        <div id="right-card" class="card" onclick="">
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


<?php if ($role == 'parent'): ?>
<!-- Become a Tutor Section -->
<section class="become-tutor-section mt-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Side: Tutor Card -->
            <div class="col-md-6 mb-4" style="margin-left: -50px;">
                <div class="p-4 d-flex flex-column justify-content-center h-100" style="
                        background: linear-gradient(135deg, #6e47f7 0%, #b66bff 100%);
                        border-radius: 1rem;
                        color: #fff;
                        position: relative;
                    ">
                    <h2 class="mb-3">Become a Tutor</h2>
                    <p>
                        Instructors from Nepal teach millions of students on Ghar Tuition.
                        We provide the tools and skills to teach what you love.
                    </p>
                    <a href="register.php" class="btn btn-light align-self-start mt-3">Apply Now</a>
                </div>
            </div>

            <!-- Right Side: Teaching & Earning Steps -->
            <div class="col-md-6" style="padding-left: -40px;">
                <h3 class="mb-4">Your teaching & earning steps</h3>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-start mb-2">
                        <div class="badge bg-primary me-3" style="margin-right: 10px;">1</div>
                        <p class="mb-0">Apply to become a tutor</p>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <div class="badge bg-primary me-3" style="margin-right: 10px;">2</div>
                        <p class="mb-0">Build & edit your profile</p>
                    </div>
                    <div class="d-flex align-items-start mb-2">
                        <div class="badge bg-primary me-3" style="margin-right: 10px;">3</div>
                        <p class="mb-0">Interview Process</p>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="badge bg-primary me-3" style="margin-right: 10px;">4</div>
                        <p class="mb-0">Start teaching & earning</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<!-- Optionally add alternative content for non-parent users -->
<?php endif; ?>



<?php include 'includes/footer.php'; ?>