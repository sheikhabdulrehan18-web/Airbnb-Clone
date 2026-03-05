<?php
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
 
$property_result = $conn->query("SELECT * FROM properties WHERE id = $id");
if ($property_result->num_rows == 0) {
    die("Property not found!");
}
$property = $property_result->fetch_assoc();
 
$booking_success = false;
$total_price = 0;
$nights = 0;
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve'])) {
    $c_in = $_POST['check_in'];
    $c_out = $_POST['check_out'];
    $user_id = $_SESSION['user_id'];
 
    $date1 = new DateTime($c_in);
    $date2 = new DateTime($c_out);
    $interval = $date1->diff($date2);
    $nights = $interval->days;
 
    if ($nights <= 0) {
        $error = "Check-out date must be after check-in date!";
    } else {
        $total_price = $nights * $property['price_per_night'];
        $sql = "INSERT INTO bookings (user_id, property_id, check_in, check_out, total_price) 
                VALUES ('$user_id', '$id', '$c_in', '$c_out', '$total_price')";
        if ($conn->query($sql)) {
            $booking_success = true;
        } else {
            $error = "Booking failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book - <?= htmlspecialchars($property['title']) ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .booking-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 3rem;
        }
        .property-hero {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 20px;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .booking-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
 
    <nav class="glass-panel" style="background: white;">
        <a href="index.php" class="logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor"><path d="M16 1c2.008 0 3.463.963 4.751 3.269l.533 1.025c1.954 3.83 6.114 12.54 7.1 14.836l.145.353c.667 1.591.91 2.472.96 3.396l.01.415.001.228c0 4.062-2.877 6.478-6.357 6.478-2.224 0-4.556-1.258-6.709-3.386l-.257-.26-.172-.179h-.011l-.176.185c-2.044 2.1-4.393 3.405-6.701 3.405-3.48 0-6.358-2.416-6.358-6.478l.012-.766c.03-.93.212-1.637.892-3.269l.181-.423c1.236-2.73 5.37-11.234 7.101-14.836l.533-1.025C12.537 1.963 13.992 1 16 1zm0 2c-1.232 0-2.31 1.05-3.376 3.111l-.478.919c-1.897 3.636-6.024 12.115-7.143 14.597l-.234.542c-.524 1.258-.696 1.815-.742 2.504l-.01.373c0 2.96 2.051 4.478 4.358 4.478 1.636 0 3.396-.921 5.304-2.871l.343-.362.355.361c1.93 1.942 3.712 2.872 5.304 2.872 2.307 0 4.356-1.518 4.356-4.478l-.013-.574c-.035-.615-.175-1.11-.699-2.361l-.234-.542c-1.12-2.482-5.246-10.96-7.143-14.597l-.478-.919C18.31 4.05 17.232 3 16 3zm0 13.5c1.933 0 3.5 1.567 3.5 3.5s-1.567 3.5-3.5 3.5-3.5-1.567-3.5-3.5 1.567-3.5 3.5-3.5zm0 2c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5z"></path></svg>
            <span>airbnb</span>
        </a>
        <div class="nav-links">
             <a href="#">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></a>
             <a href="logout.php">Logout</a>
        </div>
    </nav>
 
    <main class="container">
        <?php if($booking_success): ?>
            <div class="stroke-card fade-in" style="padding: 3rem; text-align: center;">
                <h1 style="color: #27ae60; margin-bottom: 1rem;">Booking Confirmed!</h1>
                <p style="font-size: 1.2rem;">Congratulations, your stay at <strong><?= htmlspecialchars($property['title']) ?></strong> has been reserved.</p>
                <p style="margin-top: 1rem; color: var(--text-muted);">Total Paid: $<?= number_format($total_price, 2) ?> for <?= $nights ?> nights.</p>
                <a href="index.php" class="btn-primary" style="display:inline-block; margin-top:2rem; text-decoration:none;">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="booking-grid">
                <div>
                    <img src="<?= $property['image'] ?>" alt="<?= htmlspecialchars($property['title']) ?>" class="property-hero fade-in">
                    <h1 style="font-size: 2.2rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($property['title']) ?></h1>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1.5rem;">Located in <?= htmlspecialchars($property['location']) ?></p>
                    <div style="border-top: 1px solid var(--border-stroke); padding-top: 2rem;">
                        <h3>Description</h3>
                        <p style="margin-top: 1rem; color: var(--text-main); line-height: 1.8;"><?= nl2br(htmlspecialchars($property['description'])) ?></p>
                    </div>
                </div>
 
                <div>
                    <div class="stroke-card fade-in" style="padding: 2rem; position: sticky; top: 120px;">
                        <h2 style="margin-bottom: 1.5rem;">$<?= $property['price_per_night'] ?> <span style="font-weight: 400; font-size: 1rem; color: var(--text-muted);">night</span></h2>
 
                        <?php if(isset($error)): ?>
                            <p style="color: var(--primary); margin-bottom: 1rem;"><?= $error ?></p>
                        <?php endif; ?>
 
                        <form method="POST">
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                                <div class="form-group" style="flex: 1;">
                                    <label>CHECK-IN</label>
                                    <input type="date" name="check_in" value="<?= $check_in ?>" required>
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label>CHECKOUT</label>
                                    <input type="date" name="check_out" value="<?= $check_out ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>GUESTS</label>
                                <input type="number" value="1" min="1" max="10">
                            </div>
 
                            <button type="submit" name="reserve" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">Reserve</button>
                            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--text-muted);">You won't be charged yet</p>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
 
    <footer style="padding: 4rem 5%; background: #eee; text-align: center; margin-top: 4rem;">
        <p>&copy; 2024 Airbnb Clone. Built with Pure PHP by Senior Dev.</p>
    </footer>
 
</body>
</html>
 
