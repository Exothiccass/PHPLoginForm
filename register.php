<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = basename($_FILES["photo"]["name"]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileType, $allowedTypes)) {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            $newFileName = uniqid('user_', true) . '.' . $fileType;
            $targetFilePath = $targetDir . $newFileName;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photoPath = $targetFilePath;
            } else {
                $error = "Sorry, there was an error uploading your photo.";
            }
        }
    } else {
        $photoPath = null;
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO users (firstname, middlename, lastname, email, username, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $email, $username, $password, $photoPath);

        if ($stmt->execute()) {
            $success = "Registration successful. <a href='login.php' class='alert-link'>Login here</a>.";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: monospace;
            overflow-x: hidden;
        }

        #vanta-bg {
            width: 100%;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 16px;
            margin-top: 10vh;
        }

        .form-label {
            font-weight: 300;
        }

        .form-control {
            padding: 0.2rem 0.4rem;
            font-size: 0.875rem;
        }

        .container {
            position: relative;
            z-index: 1;
        }
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
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

<div class="container mt-5">
    <div class="card mx-auto p-4 text-white fade-in-up" style="max-width: 500px;">
        <h2 class="text-center mb-4">Register</h2>

            <?php if (isset($success)) : ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstname" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middlename" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastname" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)" />
                </div>
                <div class="mb-3 text-center">
                    <img id="photoPreview" src="#" alt="Photo Preview" style="max-width: 100%; display: none; border-radius: 8px;" />
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <div class="mt-3 text-center">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('photoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "";
            preview.style.display = "none";
        }
    }
    </script>

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
