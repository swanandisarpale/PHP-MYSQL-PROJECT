<?php

include "../db.php";

header("Content-Type: application/json");

$district_id = intval($_GET['district_id'] ?? 0);

if ($district_id <= 0) {
    echo json_encode([]);
    exit();
}

$sql = "
    SELECT
        id,
        city_name
    FROM cities
    WHERE district_id = ?
    ORDER BY city_name ASC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([]);
    exit();
}

$stmt->bind_param("i", $district_id);

$stmt->execute();

$result = $stmt->get_result();

$cities = [];

while ($row = $result->fetch_assoc()) {

    $cities[] = [
        "id" => $row["id"],
        "city_name" => $row["city_name"]
    ];

}

echo json_encode($cities);

$stmt->close();
$conn->close();

?>