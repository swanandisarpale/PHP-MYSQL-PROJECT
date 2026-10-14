<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Job Portal</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add_job.php">➕ Add Job</a>
    <a href="manage_jobs.php">📋 Manage Jobs</a>
    <a href="view_applicants.php">👥 View Applicants</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">

    <div class="header">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['admin']; ?></p>
    </div>

    <div class="cards">

        <div class="card">
            <h2>20</h2>
            <p>Total Jobs</p>
        </div>

        <div class="card">
            <h2>45</h2>
            <p>Total Applicants</p>
        </div>

        <div class="card">
            <h2>12</h2>
            <p>Companies</p>
        </div>

        <div class="card">
            <h2>5</h2>
            <p>Active Jobs</p>
        </div>

    </div>

</div>

</body>
</html>