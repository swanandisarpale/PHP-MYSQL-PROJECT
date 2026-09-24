<?php

include "../db.php";

header("Content-Type: application/json");

$state_id = intval(
    $_GET['state_id'] ?? 0
);

if ($state_id <= 0) {

    echo json_encode([]);

    exit();

}


$sql = "
    SELECT
        id,
        district_name
    FROM districts
    WHERE state_id = ?
    ORDER BY district_name
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $state_id
);

$stmt->execute();

$result = $stmt->get_result();


$districts = [];


while ($row = $result->fetch_assoc()) {

    $districts[] = $row;

}


echo json_encode($districts);


$stmt->close();

$conn->close();

?>