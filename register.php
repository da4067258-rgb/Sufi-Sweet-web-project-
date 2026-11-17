
<?php
require_once "config/db.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form data
    $name = trim($_POST["full_name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";
    $city = trim($_POST["city"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $terms = isset($_POST["terms"]);

    // Validation
    if (
        $name === "" || $phone === "" ||
        $email === "" || $city === "" ||
        $password === ""
    ) {
        $message = "Please fill all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";

    } elseif (!preg_match('/^[0-9+\- ]{10,15}$/', $phone)) {
        $message = "Please enter a valid phone number.";

    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";

    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";

    } elseif (!$terms) {
        $message = "Please accept the Terms and Privacy Policy.";

    } else {

        // Check duplicate email
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "This email is already registered.";

        } else {

            // Secure password hashing
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert new user
            $stmt = $conn->prepare(
                "INSERT INTO users
                (full_name, phone, email, password, city, address)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $phone,
                $email,
                $hashedPassword,
                $city,
                $address
            ]);

            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register | Sufi Sweets</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="CSS/style.css">

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-sufi sticky-top shadow-sm">

<div class="container">

<a class="navbar-brand" href="index.html">
🍬 Sufi <span>Sweets</span>
</a>

<button class="navbar-toggler" type="button"
data-bs-toggle="collapse" data-bs-target="#mainNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="mainNav">

<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

<li class="nav-item">
<a class="nav-link" href="index.html">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="product.php">Shop</a>
</li>

<li class="nav-item">
<a class="nav-link" href="about.html">About</a>
</li>

<li class="nav-item">
<a class="nav-link" href="contact.php">Contact</a>
</li>

<li class="nav-item">
<a class="nav-link" href="dashboard.php">Dashboard</a>
</li>

<li class="nav-item">
<a class="nav-link" href="cart.html">
Cart <span class="badge rounded-pill bg-warning text-dark cart-count">0</span>
</a>
</li>

<li class="nav-item ms-lg-2">
<a class="btn btn-sufi btn-sm px-3" href="login.php">Login</a>
</li>

<li class="nav-item">
<a class="btn btn-outline-light btn-sm px-3 active" href="register.php">Register</a>
</li>

</ul>
</div>
</div>
</nav>


<!-- REGISTRATION FORM -->
<main>

<section class="auth-wrap">

<div class="container">

<div class="auth-card" style="max-width:640px;">

<div class="card card-sufi">

<div class="card-body p-4 p-md-5">

<div class="text-center mb-4">

<div class="display-5">🍬</div>

<h2 class="section-title mt-2 mb-1">
Create an Account
</h2>

<p class="text-muted small mb-0">
Join Sufi Sweets and start ordering today
</p>

</div>


<?php if ($message !== ""): ?>

<div class="alert alert-danger">
<?= htmlspecialchars($message) ?>
</div>

<?php endif; ?>


<form method="POST"
action="register.php"
id="registerForm"
class="needs-validation"
data-server="true"
novalidate>

<div class="row g-3">

<!-- FULL NAME -->
<div class="col-md-6">

<label for="regName" class="form-label">
Full Name <span class="text-danger">*</span>
</label>

<input type="text"
class="form-control"
id="regName"
name="full_name"
required>

<div class="invalid-feedback">
Please enter your full name.
</div>

</div>


<!-- PHONE -->
<div class="col-md-6">

<label for="regPhone" class="form-label">
Phone Number <span class="text-danger">*</span>
</label>

<input type="tel"
class="form-control"
id="regPhone"
name="phone"
pattern="[0-9+\- ]{10,15}"
required>

<div class="invalid-feedback">
Enter a valid phone number.
</div>

</div>


<!-- EMAIL -->
<div class="col-12">

<label for="regEmail" class="form-label">
Email Address <span class="text-danger">*</span>
</label>

<input type="email"
class="form-control"
id="regEmail"
name="email"
required>

<div class="invalid-feedback">
Please enter a valid email address.
</div>

</div>


<!-- PASSWORD -->
<div class="col-md-6">

<label for="regPassword" class="form-label">
Password <span class="text-danger">*</span>
</label>

<input type="password"
class="form-control"
id="regPassword"
name="password"
minlength="6"
required>

<div class="invalid-feedback">
Password must be at least 6 characters.
</div>

</div>


<!-- CONFIRM PASSWORD -->
<div class="col-md-6">

<label for="regConfirm" class="form-label">
Confirm Password <span class="text-danger">*</span>
</label>

<input type="password"
class="form-control"
id="regConfirm"
name="confirm_password"
minlength="6"
required>

<div class="invalid-feedback">
Passwords do not match.
</div>

</div>


<!-- CITY -->
<div class="col-md-6">

<label for="regCity" class="form-label">
City <span class="text-danger">*</span>
</label>

<select class="form-select"
id="regCity"
name="city"
required>

<option value="" selected disabled>
Choose city…
</option>

<option value="Lahore">Lahore</option>
<option value="Karachi">Karachi</option>
<option value="Islamabad">Islamabad</option>
<option value="Rawalpindi">Rawalpindi</option>
<option value="Faisalabad">Faisalabad</option>
<option value="Multan">Multan</option>

</select>

<div class="invalid-feedback">
Please select your city.
</div>

</div>


<!-- ADDRESS -->
<div class="col-md-6">

<label for="regAddress" class="form-label">
Address
</label>

<input type="text"
class="form-control"
id="regAddress"
name="address"
placeholder="Street, area">

</div>

</div>


<!-- TERMS -->
<div class="form-check mt-4">

<input class="form-check-input"
type="checkbox"
id="regTerms"
name="terms"
value="1"
required>

<label class="form-check-label small"
for="regTerms">

I agree to the Terms of Service and Privacy Policy
<span class="text-danger">*</span>

</label>

<div class="invalid-feedback">
You must accept the terms to continue.
</div>

</div>


<!-- SUBMIT -->
<div class="d-grid mt-3">

<button class="btn btn-sufi btn-lg"
type="submit">

Create Account

</button>

</div>


<p class="text-center small mt-3 mb-0">

Already have an account?

<a href="login.php"
class="fw-semibold text-decoration-none">

Login

</a>

</p>

</form>

</div>
</div>
</div>
</div>
</section>
</main>


<!-- FOOTER -->
<footer class="footer-sufi pt-5 pb-3 mt-4">

<div class="container">

<div class="row g-4">

<div class="col-lg-4 col-md-6">

<h5 class="mb-3">🍬 Sufi Sweets</h5>

<p class="small">
Traditional sweets and dairy products made with pure desi ghee since 1994.
</p>

</div>


<div class="col-lg-2 col-md-6">

<h6 class="mb-3">Quick Links</h6>

<ul class="list-unstyled small d-grid gap-2">

<li><a href="index.html">Home</a></li>
<li><a href="product.php">Shop</a></li>
<li><a href="about.html">About Us</a></li>
<li><a href="contact.php">Contact</a></li>

</ul>

</div>


<div class="col-lg-3 col-md-6">

<h6 class="mb-3">My Account</h6>

<ul class="list-unstyled small d-grid gap-2">

<li><a href="login.php">Login</a></li>
<li><a href="cart.html">Shopping Cart</a></li>
<li><a href="dashboard.php">Dashboard</a></li>

</ul>

</div>


<div class="col-lg-3 col-md-6">

<h6 class="mb-3">Contact Us</h6>

<ul class="list-unstyled small d-grid gap-2">

<li>📍 12-B, Food Street, Gulberg Lahore</li>
<li>📞 +92 3161701236</li>
<li>✉️ info@sufisweets.com</li>

</ul>

</div>

</div>

<hr class="border-light opacity-25 mt-4">

<p class="text-center small mb-0">
© 2026 Sufi Sweets. All rights reserved.
</p>

</div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="JS/main.js"></script>

</body>
</html>
