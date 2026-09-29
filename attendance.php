<?php

include "db_emp.php";


// ==================== DELETE ====================//

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare(
        "DELETE FROM attendance WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: attendance_list.php");
        exit;

    } else {

        echo "Error: " . $stmt->error;

    }
}


// ==================== UPDATE ====================

if (isset($_POST['updatebtn'])) {

    $id = $_POST['id1'];
    $employee_name = $_POST['employee_name1'];
    $date = $_POST['date1'];
    $department = $_POST['department1'];
    $attendance = $_POST['attendance1'];
    $check_in = $_POST['check_in1'];
    $check_out = $_POST['check_out1'];

    $stmt = $conn->prepare(
        "UPDATE attendance
         SET employee_name = ?,
             date = ?,
             department = ?,
             attendance = ?,
             check_in = ?,
             check_out = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssssssi",
        $employee_name,
        $date,
        $department,
        $attendance,
        $check_in,
        $check_out,
        $id
    );

    if ($stmt->execute()) {

        header("Location: attendance_list.php");
        exit;

    } else {

        echo "Error: " . $stmt->error;

    }
}


// ==================== SAVE ====================

if (isset($_POST['submitbtn'])) {

    $employee_name = $_POST['employee_name'];
    $date = $_POST['date'];
    $department = $_POST['department'];
    $attendance = $_POST['attendance'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $stmt = $conn->prepare(
        "INSERT INTO attendance
        (employee_name, date, department, attendance, check_in, check_out)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssss",
        $employee_name,
        $date,
        $department,
        $attendance,
        $check_in,
        $check_out
    );

    if ($stmt->execute()) {

        header("Location: attendance_list.php");
        exit;

    } else {

        echo "Error: " . $stmt->error;

    }
}

?>