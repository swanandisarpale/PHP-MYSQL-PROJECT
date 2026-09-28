<?php

include "db_emp.php";

?>

<!DOCTYPE html>
<html>

<head>

    <title>Attendance List</title>

    <!-- <style>

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

    </style> -->

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

        <th>Employee Name</th>

        <th>Date</th>

        <th>Department</th>

        <th>Attendance</th>

        <th>Check-in</th>

        <th>Check-out</th>

        <th>Action</th>

    </tr>


<?php

$sql = "SELECT * FROM attendance";

$result = $conn->query($sql);

$sr = 1;


while ($show = $result->fetch_assoc()) {

?>

    <tr>

        <td>

            <?php echo $sr++; ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['employee_name']
            );
            ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['date']
            );
            ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['department']
            );
            ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['attendance']
            );
            ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['check_in']
            );
            ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['check_out']
            );
            ?>

        </td>


        <td>

            <a
                href="attendance.php?delete=<?php echo $show['id']; ?>"
                onclick="return confirm('Are you sure you want to delete this record?')"
            >
                Delete
            </a>


            <button
                onclick="updateFunction(
                    '<?php echo $show['id']; ?>',
                    '<?php echo htmlspecialchars($show['employee_name'], ENT_QUOTES); ?>',
                    '<?php echo $show['date']; ?>',
                    '<?php echo $show['department']; ?>',
                    '<?php echo $show['attendance']; ?>',
                    '<?php echo $show['check_in']; ?>',
                    '<?php echo $show['check_out']; ?>'
                )"
            >

                Update

            </button>

        </td>

    </tr>

<?php

}

?>

</table>


<!-- ==================== UPDATE MODAL ==================== -->


<div id="myModal" class="modal">

    <div class="modal-content">


        <span
            class="close"
            onclick="closeModal()"
        >

            &times;

        </span>


        <h2>Update Attendance</h2>


        <form
            method="post"
            action="attendance.php"
        >


            <input
                type="hidden"
                name="id1"
                id="id1"
            >


            <label>Employee Name</label><br>

            <input
                type="text"
                name="employee_name1"
                id="employee_name1"
                required
            >

            <br><br>


            <label>Date</label><br>

            <input
                type="date"
                name="date1"
                id="date1"
                required
            >

            <br><br>


            <label>Department</label><br>

            <select
                name="department1"
                id="department1"
                required
            >

                <option value="">
                    Select Department
                </option>

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
                name="attendance1"
                id="present1"
                value="Present"
                required
            >

            Present


            <input
                type="radio"
                name="attendance1"
                id="absent1"
                value="Absent"
            >

            Absent


            <input
                type="radio"
                name="attendance1"
                id="halfday1"
                value="Half Day"
            >

            Half Day

            <br><br>


            <label>Check-in Time</label><br>

            <input
                type="time"
                name="check_in1"
                id="check_in1"
            >

            <br><br>


            <label>Check-out Time</label><br>

            <input
                type="time"
                name="check_out1"
                id="check_out1"
            >

            <br><br>


            <input
                type="submit"
                name="updatebtn"
                value="Update"
            >

        </form>


        <br>


        <button onclick="closeModal()">

            Close

        </button>


    </div>

</div>


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