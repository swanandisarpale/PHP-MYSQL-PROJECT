<?php
session_start();
include("connection/db.php");

if(isset($_POST['add_job']))
{
    $title = $_POST['job_title'];
    $company = $_POST['company_name'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $type = $_POST['job_type'];
    $experience = $_POST['experience'];
    $last_date = $_POST['last_date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO jobs(job_title, company_name, location, salary, job_type, experience, last_date, description)
            VALUES('$title','$company','$location','$salary','$type','$experience','$last_date','$description')";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>alert('Job Added Successfully');</script>";
    }
    else
    {
        echo "<script>alert('Error Adding Job');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Job</title>
    <link rel="stylesheet" href="css/add_job.css">
</head>
<body>

<div class="container">
    <h2>Add New Job</h2>

    <form method="POST">

        <input type="text" name="job_title" placeholder="Job Title" required>

        <input type="text" name="company_name" placeholder="Company Name" required>

        <input type="text" name="location" placeholder="Location" required>

        <input type="text" name="salary" placeholder="Salary" required>

        <select name="job_type" required>
            <option value="">Select Job Type</option>
            <option>Full Time</option>
            <option>Part Time</option>
            <option>Internship</option>
        </select>

        <input type="text" name="experience" placeholder="Experience" required>

        <input type="date" name="last_date" required>

        <textarea name="description" placeholder="Job Description" rows="5" required></textarea>

        <button type="submit" name="add_job">Add Job</button>

    </form>

</div>

</body>
</html>