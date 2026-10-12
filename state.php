<?php

include "db.php";

$message = "";


/* ================= DELETE STATE ================= */

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    /*
        First check if cities are using this state
    */

    $check_sql = "SELECT COUNT(*) AS total
                  FROM cities
                  WHERE state_id = ?";

    $check_stmt = $conn->prepare($check_sql);

    $check_stmt->bind_param("i", $id);

    $check_stmt->execute();

    $check_result = $check_stmt->get_result();

    $check = $check_result->fetch_assoc();

    $check_stmt->close();


    if ($check['total'] > 0) {

        $message = "This state cannot be deleted because cities are assigned to it.";

    } else {

        $sql = "DELETE FROM states WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);


        if ($stmt->execute()) {

            header("Location: state.php");
            exit();

        } else {

            $message = "Error deleting state!";

        }

        $stmt->close();

    }
}


/* ================= UPDATE STATE ================= */

if (isset($_POST['update_state'])) {

    $id = $_POST['id'];

    $state_name = trim($_POST['state_name']);


    if ($state_name != "") {

        $sql = "UPDATE states
                SET state_name = ?
                WHERE id = ?";


        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "si",
            $state_name,
            $id
        );


        if ($stmt->execute()) {

            header("Location: state.php");
            exit();

        } else {

            $message = "Error updating state!";

        }

        $stmt->close();

    } else {

        $message = "State name cannot be empty.";

    }
}


/* ================= ADD STATE ================= */

if (isset($_POST['add_state'])) {

    $state_name = trim($_POST['state_name']);


    if ($state_name != "") {

        $sql = "INSERT INTO states (state_name)
                VALUES (?)";


        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "s",
            $state_name
        );


        if ($stmt->execute()) {

            header("Location: state.php");
            exit();

        } else {

            $message = "Error adding state!";

        }

        $stmt->close();

    } else {

        $message = "Please enter state name.";

    }
}


/* ================= GET STATE FOR UPDATE ================= */

$edit_id = "";
$edit_state = "";


if (isset($_GET['edit'])) {

    $edit_id = $_GET['edit'];


    $sql = "SELECT state_name
            FROM states
            WHERE id = ?";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $edit_id
    );

    $stmt->execute();


    $result = $stmt->get_result();


    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $edit_state = $row['state_name'];

    }


    $stmt->close();
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>State Management</title>

</head>


<body>


<h2>

<?php

if ($edit_id != "") {

    echo "Update State";

} else {

    echo "Add State";

}

?>

</h2>


<?php

if ($message != "") {

    echo "<p>";

    echo htmlspecialchars($message);

    echo "</p>";

}

?>


<!-- ================= STATE FORM ================= -->

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


<label>State Name</label>


<input
    type="text"
    name="state_name"
    placeholder="Enter state name"
    value="<?php echo htmlspecialchars($edit_state); ?>"
    required
>


<br><br>


<?php

if ($edit_id != "") {

?>

    <button
        type="submit"
        name="update_state"
    >

        Update State

    </button>


    <a href="state.php">

        Cancel

    </a>


<?php

} else {

?>

    <button
        type="submit"
        name="add_state"
    >

        Save State

    </button>

<?php

}

?>


</form>


<br><br>


<!-- ================= STATE + CITY TABLE ================= -->

<h2>State List</h2>


<table
    border="1"
    cellpadding="10"
    cellspacing="0"
>


<tr>

    <th>Sr.No</th>

    <th>State Name</th>

    <th>Cities</th>

    <th>Action</th>

</tr>


<?php


/*
    Get all states
*/

$sql = "SELECT id, state_name
        FROM states
        ORDER BY id DESC";


$result = $conn->query($sql);


if (!$result) {

    die(
        "SQL Error: " .
        $conn->error
    );

}


$sr = 1;


while ($show = $result->fetch_assoc()) {

?>


<tr>


    <!-- SR NO -->

    <td>

        <?php

        echo $sr++;

        ?>

    </td>


    <!-- STATE NAME -->

    <td>

        <?php

        echo htmlspecialchars(
            $show['state_name']
        );

        ?>

    </td>


    <!-- CITIES -->

    <td>


        <?php


        $state_id = $show['id'];


        $city_sql = "SELECT city_name
                     FROM cities
                     WHERE state_id = ?
                     ORDER BY city_name";


        $city_stmt = $conn->prepare($city_sql);

        $city_stmt->bind_param(
            "i",
            $state_id
        );

        $city_stmt->execute();


        $city_result = $city_stmt->get_result();


        if ($city_result->num_rows > 0) {

            $cities = [];


            while ($city = $city_result->fetch_assoc()) {

                $cities[] = htmlspecialchars(
                    $city['city_name']
                );

            }


            echo implode(", ", $cities);

        } else {

            echo "No cities added";

        }


        $city_stmt->close();

        ?>


    </td>


    <!-- ACTION -->

    <td>


        <a
            href="state.php?edit=<?php echo $show['id']; ?>"
        >

            Update

        </a>


        |


        <a
            href="state.php?delete=<?php echo $show['id']; ?>"
            onclick="return confirm('Are you sure you want to delete this state?');"
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