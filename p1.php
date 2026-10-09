<?php
include 'db.php';


if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM category WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record Deleted Successfully.<br><br>";
    } else {
        echo "Delete Failed.<br><br>";
    }
}



if (isset($_POST['submitBtn'])) {

    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);
	
	if (!preg_match("/^[A-Za-z ]+$/", $name)) {
    echo "Name should contain only letters.";
    exit();
}

    if (empty($name) || empty($mobile) || empty($address)) {

        echo "All fields are required.<br><br>";

    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {

        echo "Mobile number must contain exactly 10 digits.<br><br>";

    } else {

        // Check duplicate mobile
        $check = $conn->prepare("SELECT id FROM register WHERE mobile=?");
        $check->bind_param("s", $mobile);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            echo "Mobile number already exists.<br><br>";

        } else {

            $stmt = $conn->prepare("INSERT INTO register(name,address,mobile,gender) VALUES(?,?,?,?)");

            $stmt->bind_param("ssss", $name, $address, $mobile, $gender);

            if ($stmt->execute()) {

                echo "Record Added Successfully.<br><br>";

            } else {

                echo "Error : " . $conn->error;
            }
        }
    }
}
?>

<html>

<head>
    <title>Registration Form</title>
</head>

<body>

<h2>Registration Form</h2>

<form method="POST">

    <label>Name</label><br>
    <input type="text" name="name" required><br><br>

    <label>Mobile</label><br>
    <input type="text" name="mobile" maxlength="10" required><br><br>

    <label>Gender</label><br>

    <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>

<select name="category">
<?php 
$stmt = $conn->prepare("SELECT * FROM category ORDER BY name ASC");
$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
	?>
	<option value="<?php echo $row["name"];?>"><?php echo $row["name"];?></option>
	
<?php }?>
</select>

<?php 
$stmt = $conn->prepare("SELECT * FROM category ORDER BY name ASC");
$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
	?><input type="checkbox" name="items[]" value="<?php echo htmlspecialchars($row["name"]); ?>">
<br>
	
<?php }?>





    <br><br>

    <label>Address</label><br>
    <textarea name="address" required></textarea>

    <br><br>

    <input type="submit" name="submitBtn" value="Save">
    <input type="reset" value="Reset">

</form>

<hr>

<h2>Registered Users</h2>

<table border="1" cellpadding="8">

<tr>

    <th>Sr.No</th>
    <th>Name</th>
    <th>Mobile</th>
    <th>Gender</th>
    <th>Address</th>
    <th>Action</th>

</tr>

<?php

$stmt = $conn->prepare("SELECT * FROM category ORDER BY name ASC");
$stmt->execute();

$result = $stmt->get_result();

$sr = 1;

while ($row = $result->fetch_assoc()) {

?>

<tr>

    <td><?php echo $sr++; ?></td>

    <td><?php echo htmlspecialchars($row['name']); ?></td>

    <td>


        <a href="?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Delete this record?')">

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