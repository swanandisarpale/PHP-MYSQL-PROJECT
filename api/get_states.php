<?php

include "../db.php";

header("Content-Type: application/json");

$sql = "SELECT id, state_name
        FROM states
        ORDER BY state_name";

$result = $conn->query($sql);

$states = [];

while ($row = $result->fetch_assoc()) {

    $states[] = [
        "id" => $row["id"],
        "state_name" => $row["state_name"]
    ];

}

echo json_encode($states);

?>