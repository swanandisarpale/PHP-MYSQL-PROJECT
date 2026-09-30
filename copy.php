<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_id = $_POST["employee_id"];
    $full_name = $_POST["full_name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $pincode = $_POST["pincode"];
    $emergency_name = $_POST["emergency_name"];
    $emergency_mobile = $_POST["emergency_mobile"];
    $department = $_POST["department"];
    $designation = $_POST["designation"];
    $joining_date = $_POST["joining_date"];
    $employment_type = $_POST["employment_type"];
    $status = $_POST["status"];

    $sql = "INSERT INTO employees
    (
        employee_id,
        full_name,
        mobile,
        email,
        dob,
        gender,
        address,
        city,
        state,
        pincode,
        emergency_name,
        emergency_mobile,
        department,
        designation,
        joining_date,
        employment_type,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssssssssssssss",
        $employee_id,
        $full_name,
        $mobile,
        $email,
        $dob,
        $gender,
        $address,
        $city,
        $state,
        $pincode,
        $emergency_name,
        $emergency_mobile,
        $department,
        $designation,
        $joining_date,
        $employment_type,
        $status
    );

    if ($stmt->execute()) {
        $message = "Employee added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee Personal Information</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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

        @media(max-width: 700px) {

            .form-row {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: 1;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>Employee Personal Information</h1>

    <p class="subtitle">
        Employee Registration Form
    </p>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <form method="POST" onsubmit="return validateForm()">

        <!-- PERSONAL INFORMATION -->

        <h2 class="section-title">
            Personal Information
        </h2>

        <div class="form-row">

            <div class="form-group">

                <label>Employee ID</label>

                <input
                    type="text"
                    name="employee_id"
                    id="employee_id"
                    placeholder="Example: EMP001"
                    required
                >

            </div>


            <div class="form-group">

                <label>Full Name</label>

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

                <label>Mobile Number</label>

                <input
                    type="tel"
                    name="mobile"
                    id="mobile"
                    placeholder="Enter mobile number"
                    maxlength="10"
                    required
                >

            </div>


            <div class="form-group">

                <label>Email Address</label>

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

                <label>Date of Birth</label>

                <input
                    type="date"
                    name="dob"
                    required
                >

            </div>


            <div class="form-group">

                <label>Gender</label>

                <select name="gender" required>

                    <option value="">Select Gender</option>

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

                <label>Address</label>

                <textarea
                    name="address"
                    placeholder="Enter complete address"
                    required
                ></textarea>

            </div>

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>City</label>

                <input
                    type="text"
                    name="city"
                    placeholder="Enter city"
                >

            </div>


            <div class="form-group">

                <label>State</label>

                <input
                    type="text"
                    name="state"
                    placeholder="Enter state"
                >

            </div>

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>Pincode</label>

                <input
                    type="text"
                    name="pincode"
                    maxlength="6"
                    placeholder="Enter pincode"
                >

            </div>

        </div>


        <!-- EMERGENCY INFORMATION -->

        <h2 class="section-title">
            Emergency Contact
        </h2>


        <div class="form-row">

            <div class="form-group">

                <label>Emergency Contact Name</label>

                <input
                    type="text"
                    name="emergency_name"
                    placeholder="Enter contact name"
                >

            </div>


            <div class="form-group">

                <label>Emergency Contact Number</label>

                <input
                    type="tel"
                    name="emergency_mobile"
                    maxlength="10"
                    placeholder="Enter contact number"
                >

            </div>

        </div>


        <!-- EMPLOYMENT INFORMATION -->

        <h2 class="section-title">
            Employment Information
        </h2>


        <div class="form-row">

            <div class="form-group">

                <label>Department</label>

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

                <label>Designation</label>

                <input
                    type="text"
                    name="designation"
                    placeholder="Example: Software Developer"
                >

            </div>

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>Joining Date</label>

                <input
                    type="date"
                    name="joining_date"
                >

            </div>


            <div class="form-group">

                <label>Employment Type</label>

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

        </div>


        <div class="form-row">

            <div class="form-group">

                <label>Employee Status</label>

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


        <div class="button-container">

            <button type="submit">
                Save Employee
            </button>

        </div>

    </form>

</div>


<script>

function validateForm() {

    let mobile = document.getElementById("mobile").value;

    let employeeId = document.getElementById("employee_id").value;

    if (!/^[0-9]{10}$/.test(mobile)) {

        alert("Please enter a valid 10-digit mobile number.");

        return false;

    }

    if (employeeId.trim() === "") {

        alert("Employee ID is required.");

        return false;

    }

    return true;
}

</script>

</body>

</html>