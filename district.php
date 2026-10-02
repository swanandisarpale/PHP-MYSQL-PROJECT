<?php

include "db.php";

$message = "";


/* ================= DELETE ================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    // Delete cities belonging to district
    $stmt = $conn->prepare(
        "DELETE FROM cities WHERE district_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();


    // Delete district
    $stmt = $conn->prepare(
        "DELETE FROM districts WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: district.php");
        exit();

    } else {

        echo "Error deleting district!";

    }

    $stmt->close();
}



/* ================= UPDATE ================= */

if (isset($_POST['update_district'])) {

    $id = intval($_POST['id']);

    $district_name = trim(
        $_POST['district_name']
    );

    $state_id = intval(
        $_POST['state_id']
    );


    $sql = "
        UPDATE districts

        SET
            district_name = ?,
            state_id = ?

        WHERE id = ?
    ";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sii",
        $district_name,
        $state_id,
        $id
    );


    if ($stmt->execute()) {

        header("Location: district.php");
        exit();

    } else {

        echo "Error updating district!";

    }

    $stmt->close();
}



/* ================= ADD ================= */

if (isset($_POST['add_district'])) {

    $district_name = trim(
        $_POST['district_name']
    );

    $state_id = intval(
        $_POST['state_id']
    );


    if (
        $district_name != ""
        &&
        $state_id > 0
    ) {

        $sql = "
            INSERT INTO districts
            (
                district_name,
                state_id
            )

            VALUES (?, ?)
        ";


        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "si",
            $district_name,
            $state_id
        );


        if ($stmt->execute()) {

            $message =
                "District added successfully!";

        } else {

            $message =
                "Error adding district!";

        }

        $stmt->close();

    } else {

        $message =
            "Please select state and enter district.";

    }

}



/* ================= GET DISTRICT FOR UPDATE ================= */

$edit_id = "";
$edit_district = "";
$edit_state = "";


if (isset($_GET['edit'])) {

    $edit_id = intval(
        $_GET['edit']
    );


    $sql = "
        SELECT
            district_name,
            state_id

        FROM districts

        WHERE id = ?
    ";


    $stmt = $conn->prepare($sql);

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


        $edit_district =
            $row['district_name'];

        $edit_state =
            $row['state_id'];

    }

    $stmt->close();

}

?>

<!DOCTYPE html>

<html>

<head>

    <title>District Management</title>

</head>


<body>


<h2>

<?php

if ($edit_id != "") {

    echo "Update District";

} else {

    echo "Add District";

}

?>

</h2>


<?php

if ($message != "") {

    echo "<p>"
        . htmlspecialchars($message)
        . "</p>";

}

?>


<!-- ================= FORM ================= -->

<form method="POST">


<?php

if ($edit_id != "") {

?>

    <input
        type="hidden"
        name="id"
        value="<?php echo $edit_id; ?>"
    >

<?php

}

?>


<!-- ================= STATE ================= -->

<label>
    State
</label>

<br>


<select
    name="state_id"
    id="state"
    required
>

    <option value="">
        Select State
    </option>


    <?php

    $state_sql = "
        SELECT
            id,
            state_name

        FROM states

        ORDER BY state_name
    ";


    $state_result =
        $conn->query($state_sql);


    while (
        $state =
        $state_result->fetch_assoc()
    ) {

    ?>

        <option
            value="<?php
                echo $state['id'];
            ?>"

            <?php

            if (
                $edit_state ==
                $state['id']
            ) {

                echo "selected";

            }

            ?>
        >

            <?php

            echo htmlspecialchars(
                $state['state_name']
            );

            ?>

        </option>

    <?php

    }

    ?>

</select>


<br>
<br>


<!-- ================= DISTRICT ================= -->

<label>
    District
</label>

<br>


<input
    type="text"
    name="district_name"
    placeholder="Enter District Name"
    value="<?php
        echo htmlspecialchars(
            $edit_district
        );
    ?>"
    required
>


<br>
<br>


<!-- ================= BUTTON ================= -->

<?php

if ($edit_id != "") {

?>

    <button
        type="submit"
        name="update_district"
    >
        Update District
    </button>


    <a href="district.php">
        Cancel
    </a>

<?php

} else {

?>

    <button
        type="submit"
        name="add_district"
    >
        Save District
    </button>

<?php

}

?>


</form>


<br>
<br>


<!-- ================= DISTRICT TABLE ================= -->

<h2>
    District List
</h2>


<table
    border="1"
    cellpadding="10"
    cellspacing="0"
>


<tr>

    <th>
        Sr.No
    </th>

    <th>
        State
    </th>

    <th>
        District
    </th>

    <th>
        Action
    </th>

</tr>


<?php


$sql = "

    SELECT

        districts.id,

        districts.district_name,

        states.state_name

    FROM districts

    LEFT JOIN states

    ON districts.state_id =
       states.id

    ORDER BY districts.id DESC

";


$result =
    $conn->query($sql);


$sr = 1;


while (
    $show =
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
            $show['state_name']
        );

        ?>

    </td>


    <td>

        <?php

        echo htmlspecialchars(
            $show['district_name']
        );

        ?>

    </td>


    <td>

        <a
            href="district.php?edit=<?php
                echo $show['id'];
            ?>"
        >
            Update
        </a>


        |


        <a
            href="district.php?delete=<?php
                echo $show['id'];
            ?>"

            onclick="
                return confirm(
                    'Are you sure you want to delete this district?'
                );
            "
        >
            Delete
        </a>

    </td>

</tr>


<?php

}

?>


</table>


</body>

</html>