<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare the SQL query to fetch user data based on the username
   $sql = "SELECT * FROM login_db WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Binding username to prevent SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
            
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start the session and store user data
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: encoree.html");
        } else {
            echo "<p>Invalid password.</p>";
        }
    } else {
        echo "<p>User not found.</p>";
    }

    // Close the prepared statement
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="login_styles.css">
  <link rel="icon" href="images/favicon.ico" sizes="192x192" type="image/png">
  <style>
    /* Inline styles added for button design consistency */

    /* Adjusted Login Button Styling */
    .button {
      padding: 10px 20px;
      font-size: 16px;
      color: #fff;
      background-color: #333;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 20px;
    }

    .button:hover {
      background-color: #555;
    }

    /* Adjusted Back Button Styling */
    .back-button {
      padding: 10px 20px;
      font-size: 16px;
      color: #fff;
      background-color: #333;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 20px;
    }

    .back-button:hover {
      background-color: #555;
    }
  </style>
</head>
<body>
  <nav>
    <div class="nav__logo">
      <img src="images/logo-white.png" alt="Company Logo" class="logo-white">
    </div>
  </nav>
  
  <div class="container">
    <div class="login form">
      <header>Login</header>
      <form id="loginForm">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        
        <a href="#">Forgot password?</a>
        
        <button type="submit" class="button">Login</button>
      </form>

      <div class="buttons">
        <button class="back-button" onclick="handleBack()">Back</button>
        <div class="register-button">
          <br>
          <span class="signup">
            Don't have an account? <a href="signup.php">Signup</a>
          </span>
        </div>
      </div>
    </div>
  </div>

  <script>
  // Handle the Back button
  function handleBack() {
    window.location.href = 'home.html'; // Always redirect to index.html
  }

  // Add form validation
  const loginForm = document.getElementById('loginForm');
  loginForm.addEventListener('submit', function (event) {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!username || !password) {
      event.preventDefault(); // Prevent form submission
      alert('Please fill up all fields.');
    }
  });
</script>

  </script>
</body>
</html>

