<?php

include "db_std.php";

if (isset($_POST['submitbtn1'])) {

    $id = $_POST['id1'];
    $name = $_POST['name1'];
    $mobile = $_POST['mobile1'];
    $gender = $_POST['gender1'];
    $address = $_POST['address1'];

    if($id == "")
        {
    $stmt = $conn->prepare(
        "INSERT INTO students (name, mobile, gender, address)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssss", $name, $mobile, $gender, $address);

        }
        else
            {
    $stmt = $conn->prepare(
        "update students set name = ?, mobile = ?, gender = ?, address = ? where id = ?"
    );

    $stmt->bind_param("ssssi", $name, $mobile, $gender, $address, $id);

            }

    if ($stmt->execute()) {
        echo "Data saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>


<!-- ///deleting//// -->
<?php

include "db_std.php";


// DELETE
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    $stmt->execute();

    header("Location: form.php");
    exit;
}

?>






<!Doctype html>
<html>
<head>
    <title>student form</title>
<head>
     <style>
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            width: 300px;
            border-radius: 8px;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
    <body>
        <h2>student form</h2>

        <Form method="post" action="form.php">

            
             <input type="text" name="id" id="id" style="display:none;">
                 <label>Name</label><br>

                    <input type="text" name="name" id="name" list="studentNames" required>

                    <datalist id="studentNames">

                    <?php

                    $stmt = $conn->prepare("SELECT name FROM students");
                    $stmt->execute();

                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                    ?>

                        <option value="<?php echo htmlspecialchars($row['name']); ?>">

                    <?php
                    }
                    ?>

                    </datalist>

                    <br><br>


        
             <label>mobile</label><br>
            <input type="text" name="mobile" id="mobile" maxlength="10" required><br><br>
              
            <label> gender </label><br>
            <input type="radio" name="gender" id="male" value="male" required>
            male

            <input type="radio" name="gender" id="female" value="female" required>
            female

            <br><br>

              <label>Address</label><br>
              <textarea name="address" id="address" required></textarea>

             <br><br>
            <input type="submit" name="submitbtn" value="save">
            <input type="reset" value="reset">
           </form>

           <br><br>

           <h2> student list <h2>
            <table border="1">

            <tr>
                <th>sr.no</th>
                <th>name</th>
                <th>mobile</th>
                <th>gender</th>
                <th>address</th>
                <th>action</th>
            </tr>


            <?php 
            $sql="SELECT * FROM STUDENTS";
            $result=$conn->query($sql);
            $sr=1;

            while($show=$result->fetch_assoc()){
                ?>
                <tr>
                     <td><?php echo $sr++; ?></td>

                        <td><?php echo htmlspecialchars($show['name']); ?></td>

                        <td><?php echo htmlspecialchars($show['mobile']); ?></td>

                        <td><?php echo htmlspecialchars($show['gender']); ?></td>

                        <td><?php echo htmlspecialchars($show['address']); ?></td>

                        <td>
                            <a href="form.php?id=<?php echo $show['id']; ?>">
                                Delete
                            </a>
                            <button onclick="myFunction('<?php echo $show['id'];?>', '<?php echo $show['name']; ?>', '<?php echo $show['mobile']; ?>', '<?php echo $show['gender']; ?>', '<?php echo $show['address']; ?>')">Update</button>
                        </td>

                    </tr>

                <?php
                }
                ?>

                </table>


<div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>

            <Form method="post" action="form.php">

            
             <input type="text" name="id1" id="id1" style="display:none;">
                 <label>Name</label><br>

                    <input type="text" name="name1" id="name1" list="studentNames" required>

                    <datalist id="studentNames">

                    <?php

                    $stmt = $conn->prepare("SELECT name FROM students");
                    $stmt->execute();

                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                    ?>

                        <option value="<?php echo htmlspecialchars($row['name']); ?>">

                    <?php
                    }
                    ?>

                    </datalist>

                    <br><br>


        
             <label>mobile</label><br>
            <input type="text" name="mobile1" id="mobile1" maxlength="10" required><br><br>
              
            <label> gender </label><br>
            <input type="radio" name="gender1" id="male1" value="male" required>
            male

            <input type="radio" name="gender1" id="female1" value="female" required>
            female

            <br><br>

              <label>Address</label><br>
              <textarea name="address1" id="address1" required></textarea>

             <br><br>
            <input type="submit" name="submitbtn1" value="Update">
           </form>

            <button onclick="closeModal()">Close</button>
        </div>
    </div>



<script>
    function myFunction(id, name, mobile, gender, address)
    {
        
        ////////////////////////////////////////with same form//////////////////////
        /*document.getElementById("id").value = id;
        document.getElementById("name").value = name;
        document.getElementById("mobile").value = mobile;
        if(gender == "female")
        {

        document.getElementById("female").checked = true;
        }
        else{

        document.getElementById("male").checked = true;
        }
        document.getElementById("address").value = address;*/
        ////////////////////////////////////////Modal Example//////////////////////

        document.getElementById("myModal").style.display = "flex";
        document.getElementById("id1").value = id;
        document.getElementById("name1").value = name;
        document.getElementById("mobile1").value = mobile;
        if(gender == "female1")
        {

        document.getElementById("female1").checked = true;
        }
        else{

        document.getElementById("male1").checked = true;
        }
        document.getElementById("address1").value = address;
    }


    function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

    </script>

    
                </body>
                </html>

                        


                                
