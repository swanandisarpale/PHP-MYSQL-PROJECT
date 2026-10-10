<?php

include "db.php";

$message = "";

/* =====================================================
   GET EMPLOYEE ID
===================================================== */

$employee_id = 0;

if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']);
}

if (isset($_POST['employee_id'])) {
    $employee_id = intval($_POST['employee_id']);
}

if ($employee_id <= 0) {
    die("Employee ID is missing.");
}


/* =====================================================
   GET EMPLOYEE DETAILS
===================================================== */

$employee_name = "";
$employee_code = "";
$basic_salary = 0;
$joining_date = "";

$stmt = $conn->prepare("
    SELECT
        id,
        employee_id,
        full_name,
        basic_salary,
        joining_date
    FROM employees
    WHERE id = ?
");

$stmt->bind_param("i", $employee_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $employee = $result->fetch_assoc();

    $employee_code = $employee['employee_id'];
    $employee_name = $employee['full_name'];
    $basic_salary = $employee['basic_salary'];
    $joining_date = $employee['joining_date'];

} else {

    die("Employee not found.");

}

$stmt->close();


/* =====================================================
   DELETE SALARY
===================================================== */

if (isset($_GET['delete'])) {

    $salary_id = intval($_GET['delete']);

    $stmt = $conn->prepare("
        DELETE FROM salary
        WHERE id = ?
        AND employee_id = ?
    ");

    $stmt->bind_param(
        "ii",
        $salary_id,
        $employee_id
    );

    if ($stmt->execute()) {

        header(
            "Location: salary.php?employee_id=" . $employee_id
        );

        exit();

    } else {

        $message = "Error deleting salary.";

    }

    $stmt->close();
}


/* =====================================================
   UPDATE SALARY
===================================================== */

if (isset($_POST['update_salary'])) {

    $salary_id = intval($_POST['salary_id']);

    $salary_date = $_POST['salary_date'];

    $amount = $_POST['amount'];

    $remark = trim($_POST['remark']);

    $payment_mode = $_POST['payment_mode'];


    $sql = "
        UPDATE salary
        SET
            salary_date = ?,
            amount = ?,
            remark = ?,
            payment_mode = ?
        WHERE id = ?
        AND employee_id = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        $message = "Database Error: " . $conn->error;

    } else {

        $stmt->bind_param(
            "sdssii",
            $salary_date,
            $amount,
            $remark,
            $payment_mode,
            $salary_id,
            $employee_id
        );


        if ($stmt->execute()) {

            header(
                "Location: salary.php?employee_id=" . $employee_id
            );

            exit();

        } else {

            $message =
                "Error updating salary: " .
                $stmt->error;
        }

        $stmt->close();
    }
}


/* =====================================================
   ADD SALARY
===================================================== */

if (isset($_POST['add_salary'])) {

    $salary_date = $_POST['salary_date'];

    $amount = $_POST['amount'];

    $remark = trim($_POST['remark']);

    $payment_mode = $_POST['payment_mode'];


    if (
        $salary_date != "" &&
        $amount != "" &&
        $payment_mode != ""
    ) {

        $sql = "
            INSERT INTO salary
            (
                employee_id,
                salary_date,
                amount,
                remark,
                payment_mode
            )
            VALUES (?, ?, ?, ?, ?)
        ";


        $stmt = $conn->prepare($sql);


        if (!$stmt) {

            $message =
                "Database Error: " .
                $conn->error;

        } else {

            $stmt->bind_param(
                "isdss",
                $employee_id,
                $salary_date,
                $amount,
                $remark,
                $payment_mode
            );


            if ($stmt->execute()) {

                header(
                    "Location: salary.php?employee_id=" .
                    $employee_id
                );

                exit();

            } else {

                $message =
                    "Error adding salary: " .
                    $stmt->error;
            }


            $stmt->close();
        }

    } else {

        $message =
            "Please fill Date, Amount and Payment Mode.";
    }
}


/* =====================================================
   GET SALARY FOR UPDATE
===================================================== */

$edit_id = 0;

$edit_date = "";

$edit_amount = "";

$edit_remark = "";

$edit_payment_mode = "";


if (isset($_GET['edit'])) {

    $edit_id = intval($_GET['edit']);


    $stmt = $conn->prepare("
        SELECT
            id,
            salary_date,
            amount,
            remark,
            payment_mode
        FROM salary
        WHERE id = ?
        AND employee_id = ?
    ");


    $stmt->bind_param(
        "ii",
        $edit_id,
        $employee_id
    );


    $stmt->execute();


    $result = $stmt->get_result();


    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();


        $edit_date =
            $row['salary_date'];

        $edit_amount =
            $row['amount'];

        $edit_remark =
            $row['remark'];

        $edit_payment_mode =
            $row['payment_mode'];
    }


    $stmt->close();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
<link rel="stylesheet" href="salary.css
">
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Employee Salary</title>

</head>


<body>


<h2>Employee Salary</h2>


<?php if ($message != "") { ?>

    <p>

        <?php
        echo htmlspecialchars($message);
        ?>

    </p>

<?php } ?>


<!-- =====================================================
     EMPLOYEE INFORMATION
===================================================== -->

<h3>Employee Information</h3>


<table border="1"
       cellpadding="10"
       cellspacing="0">

    <tr>

        <th>Employee ID</th>

        <td>
            <?php
            echo htmlspecialchars($employee_code);
            ?>
        </td>

    </tr>


    <tr>

        <th>Employee Name</th>

        <td>
            <?php
            echo htmlspecialchars($employee_name);
            ?>
        </td>

    </tr>


    <tr>

        <th>Joining Date</th>

        <td>

            <?php

            if ($joining_date != "") {

                echo htmlspecialchars($joining_date);

            } else {

                echo "-";

            }

            ?>

        </td>

    </tr>


    <tr>

        <th>Basic Salary</th>

        <td>

            ₹ <?php

            echo number_format(
                (float)$basic_salary,
                2
            );

            ?>

        </td>

    </tr>

</table>


<br>


<!-- =====================================================
     SALARY FORM
===================================================== -->

<h3>

<?php

if ($edit_id > 0) {

    echo "Update Salary";

} else {

    echo "Add Salary";

}

?>

</h3>


<form method="POST">


    <!-- EMPLOYEE DATABASE ID -->

    <input
        type="hidden"
        name="employee_id"
        value="<?php
            echo $employee_id;
        ?>"
    >


    <?php if ($edit_id > 0) { ?>

        <input
            type="hidden"
            name="salary_id"
            value="<?php
                echo $edit_id;
            ?>"
        >

    <?php } ?>


    <!-- DATE -->

    <label>

        Salary Date

    </label>

    <br>


    <input
        type="date"
        name="salary_date"
        value="<?php
            echo htmlspecialchars($edit_date);
        ?>"
        required
    >


    <br>
    <br>


    <!-- AMOUNT -->

    <label>

        Amount

    </label>

    <br>


    <input
        type="number"
        name="amount"
        step="0.01"
        placeholder="Enter salary amount"
        value="<?php
            echo htmlspecialchars($edit_amount);
        ?>"
        required
    >


    <br>
    <br>


    <!-- REMARK -->

    <label>

        Remark

    </label>

    <br>


    <textarea
        name="remark"
        placeholder="Enter remark"
        rows="4"
        cols="40"
    ><?php
        echo htmlspecialchars($edit_remark);
    ?></textarea>


    <br>
    <br>


    <!-- PAYMENT MODE -->

    <label>

        Payment Mode

    </label>

    <br>


    <select
        name="payment_mode"
        required
    >

        <option value="">
            Select Payment Mode
        </option>


        <option
            value="Cash"
            <?php
            if ($edit_payment_mode == "Cash") {
                echo "selected";
            }
            ?>
        >
            Cash
        </option>


        <option
            value="QR"
            <?php
            if ($edit_payment_mode == "QR") {
                echo "selected";
            }
            ?>
        >
            QR
        </option>


        <option
            value="Cheque"
            <?php
            if ($edit_payment_mode == "Cheque") {
                echo "selected";
            }
            ?>
        >
            Cheque
        </option>


        <option
            value="RTGS"
            <?php
            if ($edit_payment_mode == "RTGS") {
                echo "selected";
            }
            ?>
        >
            RTGS
        </option>

    </select>


    <br>
    <br>


    <?php if ($edit_id > 0) { ?>

        <button
            type="submit"
            name="update_salary"
        >
            Update Salary
        </button>


        <a
            href="salary.php?employee_id=<?php
                echo $employee_id;
            ?>"
        >
            Cancel
        </a>

    <?php } else { ?>

        <button
            type="submit"
            name="add_salary"
        >
            Save Salary
        </button>

    <?php } ?>

</form>


<br>
<br>


<!-- =====================================================
     SALARY TABLE
===================================================== -->

<h3>Salary List</h3>


<table
    border="1"
    cellpadding="10"
    cellspacing="0"
>


    <tr>

        <th>Sr.No</th>

        <th>Salary Date</th>

        <th>Month</th>

        <th>Amount</th>

        <th>Remark</th>

        <th>Payment Mode</th>

        <th>Action</th>

    </tr>


<?php

$sql = "
    SELECT
        id,
        salary_date,
        amount,
        remark,
        payment_mode
    FROM salary
    WHERE employee_id = ?
    ORDER BY salary_date DESC, id DESC
";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "i",
    $employee_id
);


$stmt->execute();


$result = $stmt->get_result();


$sr = 1;


while ($show = $result->fetch_assoc()) {

?>


    <tr>


        <td>

            <?php
            echo $sr++;
            ?>

        </td>


        <td>

            <?php
            echo htmlspecialchars(
                $show['salary_date']
            );
            ?>

        </td>


        <!-- MONTH -->

        <td>

            <?php

            echo date(
                "F Y",
                strtotime($show['salary_date'])
            );

            ?>

        </td>


        <td>

            ₹ <?php

            echo number_format(
                (float)$show['amount'],
                2
            );

            ?>

        </td>


        <td>

            <?php

            echo htmlspecialchars(
                $show['remark']
            );

            ?>

        </td>


        <td>

            <?php

            echo htmlspecialchars(
                $show['payment_mode']
            );

            ?>

        </td>


        <td>


            <!-- UPDATE -->

            <a
                href="salary.php?employee_id=<?php
                    echo $employee_id;
                ?>&edit=<?php
                    echo $show['id'];
                ?>"
            >

                Update

            </a>


            |


            <!-- DELETE -->

            <a
                href="salary.php?employee_id=<?php
                    echo $employee_id;
                ?>&delete=<?php
                    echo $show['id'];
                ?>"
                onclick="return confirm(
                    'Are you sure you want to delete this salary record?'
                );"
            >

                Delete

            </a>


            |


            <!-- SALARY SLIP PDF -->

            <a
                href="salary_slip.php?employee_id=<?php
                    echo $employee_id;
                ?>&salary_id=<?php
                    echo $show['id'];
                ?>"
                target="_blank"
            >

                Salary Slip PDF

            </a>


        </td>


    </tr>


<?php

}


$stmt->close();

?>


</table>


<br>


<a href="employee.php">

    Back to Employee List

</a>


</body>

</html>