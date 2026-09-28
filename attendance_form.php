<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="attendance_form.css">
    <title>Employee Attendance Form</title>

</head>

<body>

<h2>Employee Attendance Form</h2>

<form method="post" action="attendance.php">

    <label>Employee Name</label><br>

    <input
        type="text"
        name="employee_name"
        id="employee_name"
        required
    >

    <br><br>


    <label>Date</label><br>

    <input
        type="date"
        name="date"
        id="date"
        required
    >

    <br><br>


    <label>Department</label><br>

    <select
        name="department"
        id="department"
        required
    >

        <option value="">Select Department</option>

        <option value="IT">
            IT
        </option>

        <option value="HR">
            HR
        </option>

        <option value="Finance">
            Finance
        </option>

        <option value="Marketing">
            Marketing
        </option>

    </select>

    <br><br>


    <label>Attendance</label><br>

    <input
        type="radio"
        name="attendance"
        value="Present"
        required
    >

    Present


    <input
        type="radio"
        name="attendance"
        value="Absent"
    >

    Absent


    <input
        type="radio"
        name="attendance"
        value="Half Day"
    >

    Half Day

    <br><br>


    <label>Check-in Time</label><br>

    <input
        type="time"
        name="check_in"
        id="check_in"
    >

    <br><br>


    <label>Check-out Time</label><br>

    <input
        type="time"
        name="check_out"
        id="check_out"
    >

    <br><br>


    <input
        type="submit"
        name="submitbtn"
        value="Save"
    >

    <input
        type="reset"
        value="Reset"
    >

</form>

<br><br>

<a href="attendance_list.php">
    View Attendance List
</a>

</body>

</html>