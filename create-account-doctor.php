<?php
session_start();

// Unset all the server-side variables
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Import database connection
include("connection1.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_SESSION['personal']['fname'];
    $lname = $_SESSION['personal']['lname'];
    $name = $fname . " " . $lname;
    $email = $_POST['newemail'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['cpassword'];

    if ($newpassword == $cpassword) {
        // Check if email already exists in doctor table
        $sql_check_email = "SELECT * FROM doctor WHERE docemail = ?";
        $stmt_check_email = $database->prepare($sql_check_email);
        if ($stmt_check_email === false) {
            die('Prepare failed: ' . htmlspecialchars($database->error));
        }
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if ($result_check_email->num_rows > 0) {
            $error = '<label for="prompter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        } else {
            // Insert new doctor into doctor table
            $sql_insert_doctor = "INSERT INTO doctor (docemail, docname, docpassword) VALUES (?, ?, ?)";
            $stmt_insert_doctor = $database->prepare($sql_insert_doctor);
            if ($stmt_insert_doctor === false) {
                die('Prepare failed: ' . htmlspecialchars($database->error));
            }
            $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);
            $stmt_insert_doctor->bind_param("sss", $email, $name, $hashed_password);

            if ($stmt_insert_doctor->execute()) {
                // Successfully signed up
                $_SESSION["user"] = $email;
                $_SESSION["usertype"] = "doctor";
                $_SESSION["username"] = $fname;

                header('Location: doctor_dashboard.php');
                exit();
            } else {
                $error = '<label for="prompter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Error inserting into doctor table.</label>';
            }
        }
    } else {
        $error = '<label for="prompter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Please reconfirm your password.</label>';
    }
} else {
    $error = '<label for="prompter" class="form-label"></label>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">
    <title>Create Account</title>
    <style>
        .container {
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
    <center>
        <div class="container">
            <table border="0" style="width: 69%;">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Let's Get Started</p>
                        <p class="sub-text">It's Okay, Now Create User Account.</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td" colspan="2">
                            <label for="newemail" class="form-label">Email: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="newpassword" class="form-label">Create New Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="cpassword" class="form-label">Confirm Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php echo $error ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                    </td>
                    <td>
                        <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                        <a href="login-doctor.php" class="hover-link1 non-style-link">Login</a>
                        <br><br><br>
                    </td>
                </tr>
                    </form>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>
