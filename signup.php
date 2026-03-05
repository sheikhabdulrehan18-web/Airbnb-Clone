<?php
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
 
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            header("Location: index.php");
            exit;
        } else {
            $error = "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Airbnb Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: #fdfdfd;">
 
    <nav class="glass-panel" style="background: white;">
        <a href="index.php" class="logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor"><path d="M16 1c2.008 0 3.463.963 4.751 3.269l.533 1.025c1.954 3.83 6.114 12.54 7.1 14.836l.145.353c.667 1.591.91 2.472.96 3.396l.01.415.001.228c0 4.062-2.877 6.478-6.357 6.478-2.224 0-4.556-1.258-6.709-3.386l-.257-.26-.172-.179h-.011l-.176.185c-2.044 2.1-4.393 3.405-6.701 3.405-3.48 0-6.358-2.416-6.358-6.478l.012-.766c.03-.93.212-1.637.892-3.269l.181-.423c1.236-2.73 5.37-11.234 7.101-14.836l.533-1.025C12.537 1.963 13.992 1 16 1zm0 2c-1.232 0-2.31 1.05-3.376 3.111l-.478.919c-1.897 3.636-6.024 12.115-7.143 14.597l-.234.542c-.524 1.258-.696 1.815-.742 2.504l-.01.373c0 2.96 2.051 4.478 4.358 4.478 1.636 0 3.396-.921 5.304-2.871l.343-.362.355.361c1.93 1.942 3.712 2.872 5.304 2.872 2.307 0 4.356-1.518 4.356-4.478l-.013-.574c-.035-.615-.175-1.11-.699-2.361l-.234-.542c-1.12-2.482-5.246-10.96-7.143-14.597l-.478-.919C18.31 4.05 17.232 3 16 3zm0 13.5c1.933 0 3.5 1.567 3.5 3.5s-1.567 3.5-3.5 3.5-3.5-1.567-3.5-3.5 1.567-3.5 3.5-3.5zm0 2c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5z"></path></svg>
            <span>airbnb</span>
        </a>
    </nav>
 
    <div class="auth-container stroke-card fade-in">
        <h2 style="margin-bottom: 2rem; text-align: center;">Join the community</h2>
 
        <?php if(isset($error)): ?>
            <p style="color: var(--primary); margin-bottom: 1rem; text-align: center;"><?= $error ?></p>
        <?php endif; ?>
 
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min 6 characters" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Create Account</button>
        </form>
 
        <p style="margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Log in</a>
        </p>
    </div>
 
</body>
</html>
 
