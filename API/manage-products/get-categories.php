<?php
// File: ../API/manage-products/get-categories.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Use the same connection as your other files
    require_once '../../db.php';
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
    
    // Query to get all categories using MySQLi (same as your other files)
    $result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
    
    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }
    
    // Fetch all categories
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'categories' => $categories,
        'count' => count($categories),
        'debug' => [
            'database_connected' => true,
            'query_executed' => true,
            'categories_found' => count($categories),
            'database_name' => 'gadgets_store'
        ]
    ]);
    
} catch (Exception $e) {
    // Error response
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'debug' => [
            'error_type' => 'Exception',
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'database_name' => 'gadgets_store'
        ]
    ]);
}

// Close connection
if (isset($conn)) {
    $conn->close();
}
?>