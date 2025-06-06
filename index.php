<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #222, #575757);
            font-family: monospace;
            min-height: 80vh;
            margin: 0;
            padding: 0;
            color: white;
        }
        #vanta-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .card {
            background-color: rgba(0, 0, 0, 0.95);
            border-radius: 16px;
            margin-top: 20vh;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }
        .form-label {
            font-weight: 300;
        }
        .form-control {
            padding: 0.4rem 0.6rem;
            font-size: 0.9rem;
        }
        a {
            color: #4fd1c5;
        }
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        @keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
         }
}
    </style>
</head>
<body>

<div id="vanta-bg"></div>

<div class="container">
    <div class="card mx-auto p-4 text-white fade-in-up" style="max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
            <div class="mt-3 text-center">
                Don't have an account? <a href="register.php">Register</a>
            </div>
        </form>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.0/p5.min.js"></script>
<script src="vanta.net.min.js"></script>
<script>
VANTA.NET({
  el: "#vanta-bg",
  mouseControls: true,
  touchControls: true,
  gyroControls: false,
  minHeight: 200.00,
  minWidth: 200.00,
  scale: 1.00,
  scaleMobile: 1.00,
  color: 0xff0000,
  backgroundColor: 0x0,
  points: 20.00,
  spacing: 14.00
});
</script>

</body>
</html>
