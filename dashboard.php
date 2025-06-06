<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, photo, firstname, middlename, lastname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = htmlspecialchars($user['username']);
$firstname = htmlspecialchars($user['firstname']);
$photo = htmlspecialchars($user['photo']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: monospace;
        }
        #vanta-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s ease-out forwards;
        }

        @keyframes fadeInUp {
        to {
        opacity: 1;
        transform: translateY(0);
        }
}
    </style>
</head>
<body class="bg-light">

<div id="vanta-bg"></div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white">
  <div class="container">
    <a class="navbar-brand" href="#">Dashboard</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5 text-white fade-in-up">
    <div class="text-center">
        <h1>Welcome, <?= $firstname ?>!</h1>

        <?php if ($photo && file_exists($photo)): ?>
            <img src="<?= $photo ?>" alt="Profile photo of <?= $firstname ?>" class="rounded-circle" style="max-width: 150px; height: auto; margin-top: 20px;">
        <?php else: ?>
            <p>No profile photo uploaded.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.0/p5.min.js"></script>
<script src="vanta.dots.min.js"></script>
<script>
VANTA.DOTS({
  el: "#vanta-bg",
  mouseControls: true,
  touchControls: true,
  gyroControls: false,
  minHeight: 200.00,
  minWidth: 200.00,
  scale: 1.00,
  scaleMobile: 1.00,
  color: 0xff000a,
  backgroundColor: 0x0,
  size: 3.60,
  spacing: 45.00,
  showLines: false
});
</script>

</body>
</html>
