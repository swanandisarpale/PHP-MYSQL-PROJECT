<?php
session_start();
include("connection/db.php");

$result = mysqli_query($conn, "SELECT * FROM jobs");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="css/manage_jobs.css">
</head>
<body>

<div class="container">

    <h2>Manage Jobs</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Job Title</th>
            <th>Company</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Type</th>
            <th>Experience</th>
            <th>Last Date</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['job_title']; ?></td>
            <td><?php echo $row['company_name']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['salary']; ?></td>
            <td><?php echo $row['job_type']; ?></td>
            <td><?php echo $row['experience']; ?></td>
            <td><?php echo $row['last_date']; ?></td>

            <td>
                <a href="edit_job.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete_job.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>