<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    require_once '../config/supabase.php';
    
    $conn = getDbConnection();

    // Query pit boundaries - adjust table name as needed
    $sql = "SELECT *, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson FROM pit_boundaries";
    $rs = $conn->query($sql);

    $features = [];

    while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
        $properties = $row;
        unset($properties['geom'], $properties['geojson']);

        $features[] = [
            "type" => "Feature",
            "geometry" => json_decode($row['geojson']),
            "properties" => $properties
        ];
    }

    echo json_encode([
        "type" => "FeatureCollection",
        "features" => $features
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
