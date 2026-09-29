<?php

include "db.php";

?>

<!DOCTYPE html>
<html>

<head>

    <title>Attendance List</title>

    <style>

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            width: 350px;
            border-radius: 8px;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }

    </style>

</head>


<body>


<h2>Employee Attendance List</h2>


<a href="attendance_form.php">
    Add New Attendance
</a>

<br><br>


<table border="1">

    <tr>

        <th>Sr.No</th>

        <th>City Name</th>

        <th>State</th>

    </tr>


<?php


$sql = "select cities.id, cities.city_name, states.state_name from cities left join states on cities.state_id = states.id";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

$sr = 1;

while ($show = $result->fetch_assoc()) {
?>

    <tr>

        <td>
            <?php echo $sr++; ?>
        </td>

        <td>
            <?php echo htmlspecialchars($show['city_name']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($show['state_name']); ?>
        </td>

    </tr>

<?php
}
?>

</table>


<!-- ==================== UPDATE MODAL ==================== -->



<script>


function updateFunction(
    id,
    employee_name,
    date,
    department,
    attendance,
    check_in,
    check_out
) {


    document.getElementById(
        "myModal"
    ).style.display = "flex";


    document.getElementById(
        "id1"
    ).value = id;


    document.getElementById(
        "employee_name1"
    ).value = employee_name;


    document.getElementById(
        "date1"
    ).value = date;


    document.getElementById(
        "department1"
    ).value = department;


    document.getElementById(
        "check_in1"
    ).value = check_in;


    document.getElementById(
        "check_out1"
    ).value = check_out;


    if (attendance == "Present") {

        document.getElementById(
            "present1"
        ).checked = true;

    }

    else if (attendance == "Absent") {

        document.getElementById(
            "absent1"
        ).checked = true;

    }

    else if (attendance == "Half Day") {

        document.getElementById(
            "halfday1"
        ).checked = true;

    }

}


function closeModal() {

    document.getElementById(
        "myModal"
    ).style.display = "none";

}


</script>


</body>

</html>