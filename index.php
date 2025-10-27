<?php
session_start();
include 'db.php';

// Handle registration
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        $message = "Registration successful! Please login now.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $message = "Invalid Email or Password!";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Single Page Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (!isset($_SESSION['username'])): ?>
<div class="container">
    <div class="form-box">
        <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn" onclick="showLogin()">Login</button>
            <button type="button" class="toggle-btn" onclick="showRegister()">Register</button>
        </div>

        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

        <!-- Login Form -->
        <form id="login" class="input-group" method="POST">
            <input type="email" class="input-field" name="email" placeholder="Email" required>
            <input type="password" class="input-field" name="password" placeholder="Password" required>
            <button type="submit" class="submit-btn" name="login">Login</button>
        </form>

        <!-- Register Form -->
        <form id="register" class="input-group" method="POST">
            <input type="text" class="input-field" name="username" placeholder="Username" required>
            <input type="email" class="input-field" name="email" placeholder="Email" required>
            <input type="password" class="input-field" name="password" placeholder="Password" required>
            <button type="submit" class="submit-btn" name="register">Register</button>
        </form>
    </div>
</div>

<script>
var loginForm = document.getElementById("login");
var registerForm = document.getElementById("register");
var btn = document.getElementById("btn");

function showRegister() {
    loginForm.style.left = "-400px";
    registerForm.style.left = "50px";
    btn.style.left = "110px";
}
function showLogin() {
    loginForm.style.left = "50px";
    registerForm.style.left = "450px";
    btn.style.left = "0";
}
</script>

<?php else: ?>
<div class="welcome">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
    <a href="?logout" class="logout-btn">Logout</a>
</div>
<?php endif; ?>
</body>
</html>
