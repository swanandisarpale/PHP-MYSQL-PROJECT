
<?php
include("connection/db.php");

if(isset($_POST['admin_name']))
{
    $admin_name = $_POST['admin_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    $sql = "INSERT INTO admin(admin_name, username, email, phone, role, password)
            VALUES('$admin_name','$username','$email','$phone','$role','$password')";

    if(mysqli_query($conn, $sql))
    {
        echo "<script>
                alert('Registration Successful');
                window.location='admin_login.php';
              </script>";
    }
    else
    {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>

    <link rel="stylesheet" href="css/admin_register.css">
</head>
<body>

<div class="container">

    <h2>Admin Registration</h2>

    <form id="adminForm" action="admin_register.php" method="POST">

        <div class="input-group">
            <label>Admin Name</label>
            <input type="text" name="admin_name" required>
        </div>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" name="phone" required>
        </div>

        <div class="input-group">
            <label>Role</label>

            <select name="role">
                <option>Super Admin</option>
                <option>HR Admin</option>
                <option>Recruitment Admin</option>
            </select>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" id="confirmPassword" required>
        </div>

        <button type="submit">Create Account</button>

        <p>
            Already have an account?
            <a href="admin_login.html">Login</a>
        </p>

    </form>

</div>

<script src="js/admin_register.js"></script>

</body>
</html>