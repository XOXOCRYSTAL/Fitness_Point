<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: home.html");
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login_styles.css">
  <style>

    #username, #password {
      width: 100%;
      height: 50px;
      padding: 0 15px;
      font-size: 16px;
      margin-bottom: 1.5rem;
      border: 1px solid #ddd;
      border-radius: 6px;
      outline: none;
      transition: 0.3s;
    }
  

    #username:focus {
      border-color: #555;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    #password:focus {
      border-color: #555;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

     #username a {
      font-size: 16px;
      color: #1f1f1f;
      text-decoration: none;
      margin-bottom: 1rem;
      display: inline-block;
    }
    #password a {
      font-size: 16px;
      color: #1f1f1f;
      text-decoration: none;
      margin-bottom: 1rem;
      display: inline-block;
    }

    #username a:hover {
      text-decoration: underline;
    }

    #password a:hover {
      text-decoration: underline;
    }


    /* Adjusted Login Button Styling */
    
  </style>
</head>
<body>
  <div class="container">
    <form action="login.php" method="POST"> <!-- Corrected action -->
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter your username" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
      <button type="submit" class="button">Login</button>
    </form>
    <div>
      <p>Don't have an account? <a href="signup.php">Signup</a></p>
    </div>
  </div>
</body>
</html>
