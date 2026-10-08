<?php

include "db.php";

$message = "";


/* =====================================================
   DELETE EMPLOYEE
===================================================== */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    if ($id > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM employees WHERE id = ?"
        );

        if ($stmt) {

            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $message = "Employee deleted successfully!";
            } else {
                $message = "Delete Error: " . $stmt->error;
            }

            $stmt->close();

        } else {

            $message = "Delete Error: " . $conn->error;

        }
    }
}


/* =====================================================
   ADD EMPLOYEE
===================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ==============================
    // PERSONAL INFORMATION
    // ==============================

    $employee_id = trim($_POST["employee_id"] ?? "");
    $full_name = trim($_POST["full_name"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $dob = $_POST["dob"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $address = trim($_POST["address"] ?? "");


    // ==============================
    // LOCATION
    // ==============================

    $state_id = intval($_POST["state"] ?? 0);
    $district_id = intval($_POST["district"] ?? 0);
    $city_id = intval($_POST["city"] ?? 0);

    $pincode = trim($_POST["pincode"] ?? "");


    // ==============================
    // EMERGENCY CONTACT
    // ==============================

    $emergency_name =
        trim($_POST["emergency_name"] ?? "");

    $emergency_mobile =
        trim($_POST["emergency_mobile"] ?? "");


    // ==============================
    // EMPLOYMENT INFORMATION
    // ==============================

    $department =
        $_POST["department"] ?? "";

    $designation =
        trim($_POST["designation"] ?? "");

    $joining_date =
        $_POST["joining_date"] ?? "";

    // BASIC SALARY
    $basic_salary =
        $_POST["basic_salary"] ?? "";

    $employment_type =
        $_POST["employment_type"] ?? "";

    $status =
        $_POST["status"] ?? "Active";


    // ==============================
    // INSERT EMPLOYEE
    // ==============================

    $sql = "INSERT INTO employees
    (
        `employee_id`,
        `full_name`,
        `mobile`,
        `email`,
        `dob`,
        `gender`,
        `address`,
        `state`,
        `district`,
        `city`,
        `pincode`,
        `emergency_name`,
        `emergency_mobile`,
        `department`,
        `designation`,
        `joining_date`,
        `basic_salary`,
        `employment_type`,
        `status`
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        $message =
            "Database error: " . $conn->error;

    } else {

        $stmt->bind_param(
            "sssssssiiissssssssd",

            $employee_id,
            $full_name,
            $mobile,
            $email,
            $dob,
            $gender,
            $address,

            $state_id,
            $district_id,
            $city_id,

            $pincode,

            $emergency_name,
            $emergency_mobile,

            $department,
            $designation,
            $joining_date,

            $basic_salary,

            $employment_type,
            $status
        );


        if ($stmt->execute()) {

            $message =
                "Employee added successfully!";

        } else {

            $message =
                "Error: " . $stmt->error;

        }


        $stmt->close();
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>
<link rel="stylesheet" href="employee.css">
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Employee Registration</title>


    <!-- <style>
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        body {
            font-family: Arial, sans-serif;
            background: #ff0000;
            padding: 30px;
        }


        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.1);
        }


        h1 {
            text-align: center;
            margin-bottom: 10px;
        }


        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }


        .section-title {
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ddd;
            color: #333;
        }


        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 18px;
        }


        .form-group {
            display: flex;
            flex-direction: column;
        }


        label {
            margin-bottom: 7px;
            font-weight: bold;
        }


        input,
        select,
        textarea {
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            width: 100%;
        }


        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
        }


        select:disabled {
            background: #f1f1f1;
            cursor: not-allowed;
        }


        textarea {
            min-height: 90px;
            resize: vertical;
        }


        .full {
            grid-column: 1 / 3;
        }


        .button-container {
            text-align: center;
            margin-top: 30px;
        }


        button {
            padding: 12px 35px;
            border: none;
            border-radius: 5px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }


        button:hover {
            background: #1d4ed8;
        }


        .message {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }


        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }


        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 1100px;
        }


        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }


        th {
            background: #f3f4f6;
        }


        .action a {
            text-decoration: none;
            margin-right: 5px;
        }


        @media (max-width: 700px) {

            .form-row {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: 1;
            }

        }

    </style> -->

</head>


<body>


<div class="container">


    <h1>Employee Personal Information</h1>


    <p class="subtitle">
        Employee Registration Form
    </p>


    <?php if ($message != "") { ?>

        <div class="message">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php } ?>


    <form
        method="POST"
        onsubmit="return validateForm()"
    >


        <!-- ================================= -->
        <!-- PERSONAL INFORMATION -->
        <!-- ================================= -->

        <h2 class="section-title">
            Personal Information
        </h2>


        <div class="form-row">


            <div class="form-group">

                <label>
                    Employee ID
                </label>

                <input
                    type="text"
                    name="employee_id"
                    id="employee_id"
                    placeholder="Example: EMP001"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="full_name"
                    id="full_name"
                    placeholder="Enter full name"
                    required
                >

            </div>


        </div>


        <div class="form-row">


            <div class="form-group">

                <label>
                    Mobile Number
                </label>

                <input
                    type="tel"
                    name="mobile"
                    id="mobile"
                    placeholder="Enter 10-digit mobile number"
                    maxlength="10"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="example@gmail.com"
                    required
                >

            </div>


        </div>


        <div class="form-row">


            <div class="form-group">

                <label>
                    Date of Birth
                </label>

                <input
                    type="date"
                    name="dob"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Gender
                </label>

                <select
                    name="gender"
                    required
                >

                    <option value="">
                        Select Gender
                    </option>

                    <option value="Male">
                        Male
                    </option>

                    <option value="Female">
                        Female
                    </option>

                    <option value="Other">
                        Other
                    </option>

                </select>

            </div>


        </div>


        <div class="form-row">


            <div class="form-group full">

                <label>
                    Address
                </label>

                <textarea
                    name="address"
                    placeholder="Enter complete address"
                    required
                ></textarea>

            </div>


        </div>


        <!-- ================================= -->
        <!-- LOCATION -->
        <!-- ================================= -->

        <h2 class="section-title">
            Location Information
        </h2>


        <div class="form-row">


            <!-- STATE -->

            <div class="form-group">

                <label>
                    State
                </label>

                <select
                    name="state"
                    id="state"
                    required
                >

                    <option value="">
                        Select State
                    </option>

                </select>

            </div>


            <!-- DISTRICT -->

            <div class="form-group">

                <label>
                    District
                </label>

                <select
                    name="district"
                    id="district"
                    required
                    disabled
                >

                    <option value="">
                        Select District
                    </option>

                </select>

            </div>


        </div>


        <div class="form-row">


            <!-- CITY -->

            <div class="form-group">

                <label>
                    City
                </label>

                <select
                    name="city"
                    id="city"
                    required
                    disabled
                >

                    <option value="">
                        Select City
                    </option>

                </select>

            </div>


            <!-- PINCODE -->

            <div class="form-group">

                <label>
                    Pincode
                </label>

                <input
                    type="text"
                    name="pincode"
                    id="pincode"
                    maxlength="6"
                    placeholder="Enter pincode"
                >

            </div>


        </div>


        <!-- ================================= -->
        <!-- EMERGENCY CONTACT -->
        <!-- ================================= -->

        <h2 class="section-title">
            Emergency Contact
        </h2>


        <div class="form-row">


            <div class="form-group">

                <label>
                    Emergency Contact Name
                </label>

                <input
                    type="text"
                    name="emergency_name"
                    placeholder="Enter contact name"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Emergency Contact Number
                </label>

                <input
                    type="tel"
                    name="emergency_mobile"
                    id="emergency_mobile"
                    maxlength="10"
                    placeholder="Enter 10-digit number"
                    required
                >

            </div>


        </div>


        <!-- ================================= -->
        <!-- EMPLOYMENT INFORMATION -->
        <!-- ================================= -->

        <h2 class="section-title">
            Employment Information
        </h2>


        <div class="form-row">


            <div class="form-group">

                <label>
                    Department
                </label>

                <select name="department">

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

                    <option value="Sales">
                        Sales
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Designation
                </label>

                <input
                    type="text"
                    name="designation"
                    placeholder="Example: Software Developer"
                >

            </div>


        </div>


        <!-- ================================= -->
        <!-- JOINING DATE + BASIC SALARY -->
        <!-- ================================= -->

        <div class="form-row">


            <div class="form-group">

                <label>
                    Joining Date
                </label>

                <input
                    type="date"
                    name="joining_date"
                >

            </div>


            <div class="form-group">

                <label>
                    Basic Salary
                </label>

                <input
                    type="number"
                    name="basic_salary"
                    step="0.01"
                    min="0"
                    placeholder="Enter basic salary"
                >

            </div>


        </div>


        <!-- ================================= -->
        <!-- EMPLOYMENT TYPE -->
        <!-- ================================= -->

        <div class="form-row">


            <div class="form-group">

                <label>
                    Employment Type
                </label>

                <select name="employment_type">

                    <option value="">
                        Select Type
                    </option>

                    <option value="Full-Time">
                        Full-Time
                    </option>

                    <option value="Part-Time">
                        Part-Time
                    </option>

                    <option value="Intern">
                        Intern
                    </option>

                    <option value="Contract">
                        Contract
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Employee Status
                </label>

                <select name="status">

                    <option value="Active">
                        Active
                    </option>

                    <option value="Inactive">
                        Inactive
                    </option>

                </select>

            </div>


        </div>


        <!-- ================================= -->
        <!-- SAVE -->
        <!-- ================================= -->

        <div class="button-container">

            <button type="submit">
                Save Employee
            </button>

        </div>


    </form>


    <!-- ================================= -->
    <!-- EMPLOYEE LIST -->
    <!-- ================================= -->

    <h2 class="section-title">
        Employee List
    </h2>


    <div class="table-container">

        <table>

            <tr>

                <th>Sr.No</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Gender</th>
                <th>State</th>
                <th>District</th>
                <th>City</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Basic Salary</th>
                <th>Status</th>
                <th>Action</th>

            </tr>


            <?php

            $sql = "SELECT

                        employees.id,
                        employees.employee_id,
                        employees.full_name,
                        employees.mobile,
                        employees.email,
                        employees.gender,
                        employees.department,
                        employees.designation,
                        employees.basic_salary,
                        employees.status,

                        states.state_name,

                        districts.district_name,

                        cities.city_name

                    FROM employees

                    LEFT JOIN states
                    ON employees.state = states.id

                    LEFT JOIN districts
                    ON employees.district = districts.id

                    LEFT JOIN cities
                    ON employees.city = cities.id

                    ORDER BY employees.id DESC";


            $result = $conn->query($sql);


            if (!$result) {

                echo "<tr>";

                echo "<td colspan='14'>";

                echo "SQL Error: "
                    . htmlspecialchars($conn->error);

                echo "</td>";

                echo "</tr>";

            } else {

                $sr = 1;

                while (
                    $employee = $result->fetch_assoc()
                ) {

            ?>

                <tr>

                    <td>
                        <?php echo $sr++; ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["employee_id"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["full_name"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["mobile"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["email"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["gender"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["state_name"] ?? ""
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["district_name"] ?? ""
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["city_name"] ?? ""
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["department"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["designation"]
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["basic_salary"] ?? ""
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $employee["status"]
                        );
                        ?>
                    </td>


                    <td class="action">


                        <!-- DELETE -->

                        <a
                            href="?delete=<?php
                                echo $employee["id"];
                            ?>"
                            onclick="return confirm(
                                'Are you sure you want to delete this employee?'
                            );"
                        >
                            Delete
                        </a>


                        <!-- EDUCATION -->

                        <a
                            href="education.php?employee_id=<?php
                                echo $employee["id"];
                            ?>"
                            onclick="return confirm(
                                'Are you sure you want to see education details?'
                            );"
                        >
                            Education
                        </a>


                        <!-- ATTENDANCE -->

                        <a
                            href="attendance_form.php?employee_id=<?php
                                echo $employee['employee_id'];
                            ?>"
                            onclick="return confirm(
                                'Are you sure you want to see attendance details?'
                            );"
                        >
                            Attendance
                        </a>


                        <!-- SALARY -->

                        <a
                            href="salary.php?employee_id=<?php
                                echo $employee['employee_id'];
                            ?>"
                        >
                            Salary
                        </a>


                    </td>

                </tr>

            <?php

                }

            }

            ?>

        </table>

    </div>


</div>


<!-- ================================= -->
<!-- EMPLOYEE JAVASCRIPT -->
<!-- ================================= -->

<script src="js/employee.js"></script>


<script>

function validateForm() {

    const mobile =
        document
            .getElementById("mobile")
            .value
            .trim();


    const emergencyMobile =
        document
            .getElementById("emergency_mobile")
            .value
            .trim();


    const employeeId =
        document
            .getElementById("employee_id")
            .value
            .trim();


    const pincode =
        document
            .getElementById("pincode")
            .value
            .trim();


    const state =
        document
            .getElementById("state")
            .value;


    const district =
        document
            .getElementById("district")
            .value;


    const city =
        document
            .getElementById("city")
            .value;


    // Employee ID

    if (employeeId === "") {

        alert(
            "Employee ID is required."
        );

        return false;
    }


    // Mobile

    if (!/^[0-9]{10}$/.test(mobile)) {

        alert(
            "Please enter a valid 10-digit mobile number."
        );

        return false;
    }


    // Emergency Mobile

    if (!/^[0-9]{10}$/.test(emergencyMobile)) {

        alert(
            "Please enter a valid 10-digit emergency number."
        );

        return false;
    }


    // State

    if (state === "") {

        alert(
            "Please select a state."
        );

        return false;
    }


    // District

    if (district === "") {

        alert(
            "Please select a district."
        );

        return false;
    }


    // City

    if (city === "") {

        alert(
            "Please select a city."
        );

        return false;
    }


    // Pincode

    if (
        pincode !== "" &&
        !/^[0-9]{6}$/.test(pincode)
    ) {

        alert(
            "Please enter a valid 6-digit pincode."
        );

        return false;
    }


    return true;

}

</script>


</body>

</html>