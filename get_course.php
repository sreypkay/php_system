<?php
session_start();
require_once 'db.php';

// Set header to return JSON response
header('Content-Type: application/json');

try {
    // Get course ID from URL
    $course_id = isset($_GET['id']) ? $_GET['id'] : die(json_encode([
        'success' => false,
        'message' => 'Course ID not provided'
    ]));

    // Prepare and execute query
    $sql = "SELECT * FROM tblcourse WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if ($course) {
        echo json_encode([
            'success' => true,
            'course' => $course
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Course not found'
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 