<?php
session_start();

// Database connection
include("connection1.php");

// Function to sanitize inputs
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Doctor login functionality
if(isset($_POST['doctorLogin'])) {
    $docemail = sanitize_input($_POST['docemail']);
    $docpassword = sanitize_input($_POST['docpassword']);

    // Check if doctor exists in database
    $query = "SELECT * FROM doctors WHERE docemail = '$docemail' LIMIT 1";
    $result = $database->query($query);

    if ($result->num_rows == 1) {
        $doctor = $result->fetch_assoc();
        if (password_verify($docpassword, $doctor['docpassword'])) {
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_email'] = $doctor['docemail'];
            $_SESSION['doctor_name'] = $doctor['docname'];
            header('Location: doctor_dashboard.php'); // Redirect to doctor dashboard or profile page
            exit();
        } else {
            $login_error = "Invalid email or password";
        }
    } else {
        $login_error = "Account not found";
    }
}

// Doctor sign-up functionality
if(isset($_POST['doctorSignup'])) {
    $docname = sanitize_input($_POST['docname']);
    $docemail = sanitize_input($_POST['docemail']);
    $docpassword = password_hash(sanitize_input($_POST['docpassword']), PASSWORD_DEFAULT);

    // Check if doctor email already exists
    $check_query = "SELECT * FROM doctors WHERE docemail = '$docemail' LIMIT 1";
    $check_result = $database->query($check_query);

    if ($check_result->num_rows > 0) {
        $signup_error = "Doctor already registered with this email";
    } else {
        // Insert new doctor into database
        $insert_query = "INSERT INTO doctors (docname, docemail, docpassword) VALUES ('$docname', '$docemail', '$docpassword')";
        if ($database->query($insert_query) === TRUE) {
            $signup_success = "Doctor registered successfully";
        } else {
            $signup_error = "Error: " . $database->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login & Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            display: inline-block;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-group .error-message {
            color: #ff0000;
            margin-top: 5px;
            text-align: left;
        }

        .signup-link {
            margin-top: 10px;
            text-align: center;
        }

        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Doctor Login</h2>

        <!-- Doctor Login Form -->
        <form action="login-doctor.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="docemail">Email:</label>
                <input type="email" id="docemail" name="docemail" required>
            </div>

            <div class="form-group">
                <label for="docpassword">Password:</label>
                <input type="password" id="docpassword" name="docpassword" required>
            </div>

            <button type="submit" name="doctorLogin">Doctor Login</button>
            <?php if(isset($login_error)) { ?>
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php } ?>
        </form>

        <div class="signup-link">
            <p>Don't have an account? <a href="docter-signup.php">Sign Up</a></p>
        </div>

        <hr>

 
        
    </div>
</body>
</html>
