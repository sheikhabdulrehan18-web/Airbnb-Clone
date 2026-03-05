<?php
include 'db.php';
 
$location = isset($_GET['location']) ? mysqli_real_escape_string($conn, $_GET['location']) : '';
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
 
$sql = "SELECT * FROM properties";
if (!empty($location)) {
    $sql .= " WHERE location LIKE '%$location%' OR title LIKE '%$location%'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - Airbnb Clone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .filter-header {
            padding: 2rem 5%;
            border-bottom: 1px solid var(--border-stroke);
            background: white;
            position: sticky;
            top: 80px;
            z-index: 900;
        }
    </style>
</head>
<body>
 
    <nav class="glass-panel" style="background: white; border-bottom: 1px solid var(--border-stroke);">
        <a href="index.php" class="logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor"><path d="M16 1c2.008 0 3.463.963 4.751 3.269l.533 1.025c1.954 3.83 6.114 12.54 7.1 14.836l.145.353c.667 1.591.91 2.472.96 3.396l.01.415.001.228c0 4.062-2.877 6.478-6.357 6.478-2.224 0-4.556-1.258-6.709-3.386l-.257-.26-.172-.179h-.011l-.176.185c-2.044 2.1-4.393 3.405-6.701 3.405-3.48 0-6.358-2.416-6.358-6.478l.012-.766c.03-.93.212-1.637.892-3.269l.181-.423c1.236-2.73 5.37-11.234 7.101-14.836l.533-1.025C12.537 1.963 13.992 1 16 1zm0 2c-1.232 0-2.31 1.05-3.376 3.111l-.478.919c-1.897 3.636-6.024 12.115-7.143 14.597l-.234.542c-.524 1.258-.696 1.815-.742 2.504l-.01.373c0 2.96 2.051 4.478 4.358 4.478 1.636 0 3.396-.921 5.304-2.871l.343-.362.355.361c1.93 1.942 3.712 2.872 5.304 2.872 2.307 0 4.356-1.518 4.356-4.478l-.013-.574c-.035-.615-.175-1.11-.699-2.361l-.234-.542c-1.12-2.482-5.246-10.96-7.143-14.597l-.478-.919C18.31 4.05 17.232 3 16 3zm0 13.5c1.933 0 3.5 1.567 3.5 3.5s-1.567 3.5-3.5 3.5-3.5-1.567-3.5-3.5 1.567-3.5 3.5-3.5zm0 2c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5z"></path></svg>
            <span>airbnb</span>
        </a>
        <div class="nav-links">
            <a href="properties.php">Places to stay</a>
            <a href="#">Experiences</a>
        </div>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="#">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php" class="btn-primary">Sign up</a>
            <?php endif; ?>
        </div>
    </nav>
 
    <div class="filter-header">
        <div class="container" style="margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 1.1rem; font-weight: 500;">
                Showing results for <span style="color: var(--primary);"><?= !empty($location) ? htmlspecialchars($location) : 'All Locations' ?></span>
            </p>
            <div style="display: flex; gap: 1rem;">
                <button class="stroke-card" style="padding: 0.5rem 1rem; border-radius: 30px; cursor: pointer; background: white;">Price</button>
                <button class="stroke-card" style="padding: 0.5rem 1rem; border-radius: 30px; cursor: pointer; background: white;">Type of place</button>
            </div>
        </div>
    </div>
 
    <main class="container">
        <div class="grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $search_params = !empty($check_in) ? "&check_in=$check_in&check_out=$check_out" : "";
                    echo '
                    <div class="stroke-card fade-in">
                        <img src="'.$row['image'].'" alt="'.$row['title'].'" class="card-img">
                        <div class="card-content">
                            <h3 class="card-title">'.$row['title'].'</h3>
                            <p class="card-location">'.$row['location'].'</p>
                            <p class="card-price">$'.$row['price_per_night'].' <span>night</span></p>
                            <a href="book.php?id='.$row['id'].$search_params.'" class="btn-primary" style="display:inline-block; margin-top:1rem; text-decoration:none;">Reserve Room</a>
                        </div>
                    </div>';
                }
            } else {
                echo "<h3>No properties found match your criteria.</h3>";
            }
            ?>
        </div>
    </main>
 
    <footer style="padding: 4rem 5%; background: #eee; text-align: center; margin-top: 4rem;">
        <p>&copy; 2024 Airbnb Clone. Built with Pure PHP by Senior Dev.</p>
    </footer>
 
</body>
</html>
 
