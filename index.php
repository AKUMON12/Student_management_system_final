<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "MrSpecific" && $password === "kalimot ko") {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="styles.css">
</head>


<body class="index-page">
    <div class="container">
        <!-- Form Section -->
        <div class="form-section">
            <h1>Welcome Greatest Designer</h1>
            <p>Please enter your login details below</p>

            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <div class="remember-me">
                    <input type="checkbox" id="remember-me" name="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>

                <button type="submit">Sign in</button>
            </form>

            <p class="copyright">@mrspecific2024</p>

            <!-- Error Message -->
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
            
        </div>

        <!-- Image Section -->
        <div class="image-section">
            <img class="cropped-gif" src="logo_student_system/animted.gif" alt="Howard's Student File Manager">
        </div>

    </div>

    <video autoplay muted loop id="background-video">
    <source src="logo_student_system/background.mp4" type="video/mp4">
</body>


</html>
