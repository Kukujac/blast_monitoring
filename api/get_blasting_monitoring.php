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

    // Get date parameters (if provided)
    $dateFrom = $_GET['date_from'] ?? null;
    $dateTo = $_GET['date_to'] ?? null;

    // Base query
    $sql = "SELECT *, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson FROM blasting_monitoring WHERE 1=1";
    
    // Add date filters if provided
    $params = [];
    if ($dateFrom) {
        $sql .= " AND blast_date >= :date_from";
        $params[':date_from'] = $dateFrom;
    }
    if ($dateTo) {
        $sql .= " AND blast_date <= :date_to";
        $params[':date_to'] = $dateTo;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $features = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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