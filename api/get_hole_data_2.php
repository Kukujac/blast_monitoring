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
    $sql = "
        SELECT 
            hole_id,
            actual_elevation,
            actual_easting,
            actual_northing,
            design_elevation,
            planned_elevation,
            dx, dy, dz,
            hole_deviation,
            vx_02, vy_02, in_spec_02,
            vx_04, vy_04, in_spec_04,
            blast_date,
            ST_AsGeoJSON(ST_Transform(geom_actual, 4326)) AS geojson_actual,
            ST_AsGeoJSON(ST_Transform(geom_design, 4326)) AS geojson_design,
            ST_AsGeoJSON(ST_Transform(geom_planned, 4326)) AS geojson_planned
        FROM hole_data_2
        WHERE 1=1
    ";

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
        // Decode geometries
        $actual_geom = $row['geojson_actual'] ? json_decode($row['geojson_actual']) : null;
        $design_geom = $row['geojson_design'] ? json_decode($row['geojson_design']) : null;
        $planned_geom = $row['geojson_planned'] ? json_decode($row['geojson_planned']) : null;

        // Remove geometry JSON strings from properties
        unset($row['geojson_actual'], $row['geojson_design'], $row['geojson_planned']);

        $features[] = [
            "type" => "Feature",
            "properties" => $row,
            "geometry_actual" => $actual_geom,
            "geometry_design" => $design_geom,
            "geometry_planned" => $planned_geom
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
