<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("<p>Passwords do not match.</p>");
    }

    // Check for duplicate username or email
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        die("<p>Username or email is already taken. Please try another.</p>");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, phone_number, gender, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $phone_number, $gender);

    // Execute the statement and check if registration is successful
    if ($stmt->execute()) {
        echo "<p>Registration successful! You can now <a href='login.php'>login</a>.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="signup_styles.css">
</head>
<body>
  <div class="container">
    <div class="title">Registration</div>
    <div class="content">
      <form action="signup.php" method="POST"> <!-- Corrected action -->
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="name" placeholder="Enter your name" required>
          </div>
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="username" placeholder="Enter your username" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="phone_number" placeholder="Enter your number" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
          </div>
        </div>
        <div class="gender-details">
          <span class="gender-title">Gender</span>
          <div class="category">
            <label>
              <input type="radio" name="gender" value="Male" required>
              <span>Male</span>
            </label>
            <label>
              <input type="radio" name="gender" value="Female" required>
              <span>Female</span>
            </label>
            <label>
              <input type="radio" name="gender" value="Prefer not to say" required>
              <span>Prefer not to say</span>
            </label>
          </div>
        </div>
        <div class="button">
          <input type="submit" value="Register">
        </div>
      </form>
      <div class="login-link">
        <p>Already have an account? <a href="login.php">Login</a></p>
      </div>
    </div>
  </div>
</body>
</html>
