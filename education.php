<?php

include "db.php";

$message = "";


/* =========================================================
   GET EMPLOYEE ID
   ========================================================= */

$employee_id = intval(
    $_GET['employee_id'] ?? $_POST['employee_id'] ?? 0
);


/* =========================================================
   DELETE EDUCATION
   ========================================================= */

if (isset($_GET['delete'])) {

    $delete_id = intval($_GET['delete']);

    /* Get certificate file before deleting */
    $sql = "
        SELECT certificate_file
        FROM employee_education
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $file_to_delete = "";

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $file_to_delete =
            $row['certificate_file'];
    }

    $stmt->close();


    /* Delete database record */

    $sql = "
        DELETE FROM employee_education
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $delete_id
    );


    if ($stmt->execute()) {

        /* Delete physical certificate file */

        if (
            $file_to_delete != "" &&
            file_exists($file_to_delete)
        ) {

            unlink($file_to_delete);
        }


        header(
            "Location: education.php?employee_id=" .
            $employee_id
        );

        exit();

    } else {

        $message =
            "Error deleting education record: " .
            $stmt->error;
    }

    $stmt->close();
}


/* =========================================================
   UPDATE EDUCATION
   ========================================================= */

if (isset($_POST['update_education'])) {

    $id = intval($_POST['id']);

    $employee_id =
        intval($_POST['employee_id']);

    $qualification =
        trim($_POST['qualification']);

    $board_university =
        trim($_POST['board_university']);

    $passing_year =
        intval($_POST['passing_year']);

    $percentage =
        floatval($_POST['percentage']);


    /* -----------------------------------------
       GET OLD CERTIFICATE
       ----------------------------------------- */

    $old_file = "";

    $sql = "
        SELECT certificate_file
        FROM employee_education
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    $result =
        $stmt->get_result();


    if ($result->num_rows > 0) {

        $row =
            $result->fetch_assoc();

        $old_file =
            $row['certificate_file'];
    }

    $stmt->close();


    /* -----------------------------------------
       KEEP OLD FILE BY DEFAULT
       ----------------------------------------- */

    $certificate_file =
        $old_file;


    /* -----------------------------------------
       CHECK IF NEW FILE WAS SELECTED
       ----------------------------------------- */

    if (
        isset($_FILES['certificate_file']) &&
        $_FILES['certificate_file']['error']
        == UPLOAD_ERR_OK
    ) {

        $upload_dir =
            "uploads/certificates/";


        /* Create folder if it doesn't exist */

        if (!is_dir($upload_dir)) {

            mkdir(
                $upload_dir,
                0777,
                true
            );
        }


        $original_name =
            basename(
                $_FILES['certificate_file']['name']
            );


        $extension =
            strtolower(
                pathinfo(
                    $original_name,
                    PATHINFO_EXTENSION
                )
            );


        $allowed_extensions = [
            "pdf",
            "jpg",
            "jpeg",
            "png"
        ];


        if (
            !in_array(
                $extension,
                $allowed_extensions
            )
        ) {

            $message =
                "Only PDF, JPG, JPEG and PNG files are allowed.";

        } else {

            $new_file_name =
                time() .
                "_" .
                uniqid() .
                "." .
                $extension;


            $new_file_path =
                $upload_dir .
                $new_file_name;


            if (
                move_uploaded_file(
                    $_FILES['certificate_file']['tmp_name'],
                    $new_file_path
                )
            ) {

                /* Delete old file */

                if (
                    $old_file != "" &&
                    file_exists($old_file)
                ) {

                    unlink($old_file);
                }


                $certificate_file =
                    $new_file_path;

            } else {

                $message =
                    "Error uploading new certificate.";
            }
        }
    }


    /* -----------------------------------------
       UPDATE DATABASE
       ----------------------------------------- */

    if ($message == "") {

        $sql = "
            UPDATE employee_education
            SET
                qualification = ?,
                board_university = ?,
                passing_year = ?,
                percentage = ?,
                certificate_file = ?
            WHERE id = ?
        ";


        $stmt =
            $conn->prepare($sql);


        $stmt->bind_param(
            "ssidsi",
            $qualification,
            $board_university,
            $passing_year,
            $percentage,
            $certificate_file,
            $id
        );


        if ($stmt->execute()) {

            header(
                "Location: education.php?employee_id=" .
                $employee_id
            );

            exit();

        } else {

            $message =
                "Error updating education: " .
                $stmt->error;
        }


        $stmt->close();
    }
}


/* =========================================================
   ADD EDUCATION
   ========================================================= */

if (isset($_POST['add_education'])) {

    $employee_id =
        intval($_POST['employee_id']);

    $qualification =
        trim($_POST['qualification']);

    $board_university =
        trim($_POST['board_university']);

    $passing_year =
        intval($_POST['passing_year']);

    $percentage =
        floatval($_POST['percentage']);


    /* Certificate is OPTIONAL */

    $certificate_file = "";


    /* -----------------------------------------
       CHECK IF FILE WAS UPLOADED
       ----------------------------------------- */

    if (
        isset($_FILES['certificate_file']) &&
        $_FILES['certificate_file']['error']
        == UPLOAD_ERR_OK
    ) {

        $upload_dir =
            "uploads/certificates/";


        /* Create folder */

        if (!is_dir($upload_dir)) {

            mkdir(
                $upload_dir,
                0777,
                true
            );
        }


        $original_name =
            basename(
                $_FILES['certificate_file']['name']
            );


        $extension =
            strtolower(
                pathinfo(
                    $original_name,
                    PATHINFO_EXTENSION
                )
            );


        $allowed_extensions = [
            "pdf",
            "jpg",
            "jpeg",
            "png"
        ];


        if (
            !in_array(
                $extension,
                $allowed_extensions
            )
        ) {

            $message =
                "Only PDF, JPG, JPEG and PNG files are allowed.";
        }


        if ($message == "") {

            $new_file_name =
                time() .
                "_" .
                uniqid() .
                "." .
                $extension;


            $new_file_path =
                $upload_dir .
                $new_file_name;


            if (
                move_uploaded_file(
                    $_FILES['certificate_file']['tmp_name'],
                    $new_file_path
                )
            ) {

                $certificate_file =
                    $new_file_path;

            } else {

                $message =
                    "Error uploading certificate.";
            }
        }
    }


    /* -----------------------------------------
       INSERT INTO DATABASE
       ----------------------------------------- */

    if (
        $message == "" &&
        $employee_id > 0 &&
        $qualification != ""
    ) {

        $sql = "
            INSERT INTO employee_education
            (
                employee_id,
                qualification,
                board_university,
                passing_year,
                percentage,
                certificate_file
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";


        $stmt =
            $conn->prepare($sql);


        $stmt->bind_param(
            "issids",
            $employee_id,
            $qualification,
            $board_university,
            $passing_year,
            $percentage,
            $certificate_file
        );


        if ($stmt->execute()) {

            /*
             * Refresh page with employee ID.
             * This makes Update/Delete appear immediately.
             */

            header(
                "Location: education.php?employee_id=" .
                $employee_id
            );

            exit();

        } else {

            $message =
                "Error adding education: " .
                $stmt->error;
        }


        $stmt->close();
    }
}


/* =========================================================
   GET EMPLOYEE INFORMATION
   ========================================================= */

$employee_name = "";
$employee_code = "";


if ($employee_id > 0) {

    $sql = "
        SELECT
            id,
            employee_id,
            full_name
        FROM employees
        WHERE id = ?
    ";


    $stmt =
        $conn->prepare($sql);


    $stmt->bind_param(
        "i",
        $employee_id
    );


    $stmt->execute();


    $result =
        $stmt->get_result();


    if ($result->num_rows > 0) {

        $employee =
            $result->fetch_assoc();


        $employee_code =
            $employee['employee_id'];


        $employee_name =
            $employee['full_name'];

    } else {

        $message =
            "Employee not found.";
    }


    $stmt->close();
}


/* =========================================================
   EDIT EDUCATION
   ========================================================= */

$edit_id = "";
$edit_qualification = "";
$edit_board = "";
$edit_year = "";
$edit_percentage = "";
$edit_file = "";


if (isset($_GET['edit'])) {

    $edit_id =
        intval($_GET['edit']);


    $sql = "
        SELECT
            qualification,
            board_university,
            passing_year,
            percentage,
            certificate_file,
            employee_id
        FROM employee_education
        WHERE id = ?
    ";


    $stmt =
        $conn->prepare($sql);


    $stmt->bind_param(
        "i",
        $edit_id
    );


    $stmt->execute();


    $result =
        $stmt->get_result();


    if ($result->num_rows > 0) {

        $row =
            $result->fetch_assoc();


        $edit_qualification =
            $row['qualification'];


        $edit_board =
            $row['board_university'];


        $edit_year =
            $row['passing_year'];


        $edit_percentage =
            $row['percentage'];


        $edit_file =
            $row['certificate_file'];


        $employee_id =
            $row['employee_id'];
    }


    $stmt->close();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
<link rel="stylesheet" href="education.css">

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Employee Education</title>


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

    box-shadow:
        0 4px 15px
        rgba(0, 0, 0, 0.1);
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


.employee-box {

    background: #eef4ff;

    padding: 15px;

    border-radius: 6px;

    margin-bottom: 25px;

    line-height: 1.8;
}


.section-title {

    margin-top: 25px;

    margin-bottom: 15px;

    padding-bottom: 8px;

    border-bottom:
        2px solid #ddd;

    color: #333;
}


.form-row {

    display: grid;

    grid-template-columns:
        1fr 1fr;

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
select {

    padding: 11px;

    border:
        1px solid #ccc;

    border-radius: 5px;

    font-size: 15px;

    width: 100%;
}


input:focus,
select:focus {

    outline: none;

    border-color: #2563eb;
}


input[type="file"] {

    background: #fafafa;
}


.button-container {

    margin-top: 20px;
}


button {

    padding:
        12px 30px;

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


table {

    width: 100%;

    border-collapse: collapse;

    margin-top: 20px;
}


th,
td {

    border:
        1px solid #ddd;

    padding: 10px;

    text-align: left;
}


th {

    background: #f1f1f1;
}


.update-link {

    color: #2563eb;

    text-decoration: none;

    font-weight: bold;
}


.delete-link {

    color: red;

    text-decoration: none;

    font-weight: bold;
}


.view-link {

    color: #16a34a;

    text-decoration: none;
}


.cancel-link {

    margin-left: 15px;

    color: #555;

    text-decoration: none;
}


@media (max-width: 700px) {

    .form-row {

        grid-template-columns: 1fr;
    }

    table {

        font-size: 13px;
    }

}

</style>

</head>


<body>


<div class="container">


<h1>
    Employee Education
</h1>


<p class="subtitle">
    Add Employee Educational Details
</p>


<?php if ($message != "") { ?>

<div class="message">

    <?php

    echo htmlspecialchars(
        $message
    );

    ?>

</div>

<?php } ?>


<!-- =====================================================
     EMPLOYEE INFORMATION
     ===================================================== -->

<?php if ($employee_id > 0) { ?>

<div class="employee-box">

    <strong>
        Employee ID:
    </strong>

    <?php

    echo htmlspecialchars(
        $employee_code
    );

    ?>

    <br>


    <strong>
        Employee Name:
    </strong>

    <?php

    echo htmlspecialchars(
        $employee_name
    );

    ?>

</div>

<?php } else { ?>

<div class="message">

    Employee ID is missing.

    Please open this page using:

    <br><br>

    <strong>
        education.php?employee_id=EMPLOYEE_DATABASE_ID
    </strong>

</div>

<?php } ?>


<!-- =====================================================
     EDUCATION FORM
     ===================================================== -->

<h2 class="section-title">

<?php

if ($edit_id != "") {

    echo "Update Education";

} else {

    echo "Add Education";

}

?>

</h2>


<form
    method="POST"
    enctype="multipart/form-data"
>


<!-- Employee ID -->

<input
    type="hidden"
    name="employee_id"
    value="<?php

        echo $employee_id;

    ?>"
>


<!-- Edit ID -->

<?php if ($edit_id != "") { ?>

<input
    type="hidden"
    name="id"
    value="<?php

        echo $edit_id;

    ?>"
>

<?php } ?>


<!-- Qualification + Board -->

<div class="form-row">


<div class="form-group">

<label>
    Qualification
</label>


<select
    name="qualification"
    required
>

<option value="">
    Select Qualification
</option>


<option
    value="10th"

    <?php

    if (
        $edit_qualification
        == "10th"
    ) {

        echo "selected";
    }

    ?>
>

    10th

</option>


<option
    value="12th"

    <?php

    if (
        $edit_qualification
        == "12th"
    ) {

        echo "selected";
    }

    ?>
>

    12th

</option>


<option
    value="Degree"

    <?php

    if (
        $edit_qualification
        == "Degree"
    ) {

        echo "selected";
    }

    ?>
>

    Degree

</option>


</select>

</div>


<div class="form-group">

<label>
    Board / University
</label>


<input
    type="text"
    name="board_university"
    placeholder="Enter Board / University"

    value="<?php

        echo htmlspecialchars(
            $edit_board
        );

    ?>"
>

</div>


</div>


<!-- Year + Percentage -->

<div class="form-row">


<div class="form-group">

<label>
    Passing Year
</label>


<input
    type="number"
    name="passing_year"
    min="1950"
    max="2100"
    placeholder="Example: 2024"

    value="<?php

        echo htmlspecialchars(
            $edit_year
        );

    ?>"
>

</div>


<div class="form-group">

<label>
    Percentage
</label>


<input
    type="number"
    name="percentage"
    step="0.01"
    min="0"
    max="100"
    placeholder="Example: 85.50"

    value="<?php

        echo htmlspecialchars(
            $edit_percentage
        );

    ?>"
>

</div>


</div>


<!-- Certificate -->

<div class="form-row">


<div class="form-group">

<label>
    Certificate
    <span
        style="font-weight:normal;"
    >
        (Optional)
    </span>
</label>


<input
    type="file"
    name="certificate_file"
    accept=".pdf,.jpg,.jpeg,.png"
>


<?php if ($edit_file != "") { ?>

<br>

<a
    class="view-link"
    href="<?php

        echo htmlspecialchars(
            $edit_file
        );

    ?>"
    target="_blank"
>

    View Existing Certificate

</a>

<?php } ?>


</div>

</div>


<!-- Buttons -->

<div class="button-container">


<?php if ($edit_id != "") { ?>


<button
    type="submit"
    name="update_education"
>

    Update Education

</button>


<a
    class="cancel-link"
    href="education.php?employee_id=<?php

        echo $employee_id;

    ?>"
>

    Cancel

</a>


<?php } else { ?>


<button
    type="submit"
    name="add_education"
>

    Save Education

</button>


<?php } ?>


</div>


</form>


<!-- =====================================================
     EDUCATION LIST
     ===================================================== -->

<h2 class="section-title">

    Education List

</h2>


<table>

<tr>

    <th>
        Sr.No
    </th>

    <th>
        Qualification
    </th>

    <th>
        Board / University
    </th>

    <th>
        Passing Year
    </th>

    <th>
        Percentage
    </th>

    <th>
        Certificate
    </th>

    <th>
        Action
    </th>

</tr>


<?php

if ($employee_id > 0) {


    $sql = "
        SELECT
            id,
            qualification,
            board_university,
            passing_year,
            percentage,
            certificate_file
        FROM employee_education
        WHERE employee_id = ?
        ORDER BY id DESC
    ";


    $stmt =
        $conn->prepare($sql);


    $stmt->bind_param(
        "i",
        $employee_id
    );


    $stmt->execute();


    $result =
        $stmt->get_result();


    $sr = 1;


    if ($result->num_rows > 0) {


        while (
            $row =
            $result->fetch_assoc()
        ) {

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
        $row['qualification']
    );

    ?>

</td>


<td>

    <?php

    echo htmlspecialchars(
        $row['board_university']
    );

    ?>

</td>


<td>

    <?php

    echo htmlspecialchars(
        $row['passing_year']
    );

    ?>

</td>


<td>

    <?php

    echo htmlspecialchars(
        $row['percentage']
    );

    ?>

    %

</td>


<td>

<?php

if (
    !empty(
        $row['certificate_file']
    )
) {

?>

<a
    class="view-link"
    href="<?php

        echo htmlspecialchars(
            $row['certificate_file']
        );

    ?>"
    target="_blank"
>

    View Certificate

</a>

<?php

} else {

    echo "Not Uploaded";

}

?>

</td>


<td>


<!-- UPDATE -->

<a
    class="update-link"
    href="education.php?employee_id=<?php
        echo $employee_id;
    ?>&edit=<?php
        echo $row['id'];
    ?>"
>

    Update

</a>


&nbsp; | &nbsp;


<!-- DELETE -->

<a
    class="delete-link"
    href="education.php?employee_id=<?php
        echo $employee_id;
    ?>&delete=<?php
        echo $row['id'];
    ?>"

    onclick="return confirm(
        'Are you sure you want to delete this education record?'
    );"
>

    Delete

</a>


</td>


</tr>


<?php

        }


    } else {

?>


<tr>

    <td
        colspan="7"
        style="text-align:center;"
    >

        No education records found.

    </td>

</tr>


<?php

    }


    $stmt->close();

}

?>


</table>


</div>


</body>

</html>