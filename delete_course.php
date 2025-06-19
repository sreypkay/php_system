<?php
session_start();
require_once 'db.php';

// Set header to return JSON response
header('Content-Type: application/json');

try {
    // Check if course ID is provided
    if (!isset($_POST['course_id']) || empty($_POST['course_id'])) {
        throw new Exception("Course ID not provided");
    }

    $course_id = intval($_POST['course_id']);

    if ($course_id <= 0) {
        throw new Exception("Invalid course ID");
    }

    // First check if course exists
    $check_sql = "SELECT course_id FROM tblcourse WHERE course_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    
    if (!$check_stmt) {
        throw new Exception("Error preparing check statement: " . $conn->error);
    }

    $check_stmt->bind_param("i", $course_id);
    
    if (!$check_stmt->execute()) {
        throw new Exception("Error checking course: " . $check_stmt->error);
    }
    
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $check_stmt->close();
        throw new Exception("Course not found");
    }

    $check_stmt->close();

    // Prepare delete statement
    $delete_sql = "DELETE FROM tblcourse WHERE course_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    
    if (!$delete_stmt) {
        throw new Exception("Error preparing delete statement: " . $conn->error);
    }

    // Bind parameters and execute
    $delete_stmt->bind_param("i", $course_id);
    
    if (!$delete_stmt->execute()) {
        throw new Exception("Error executing delete: " . $delete_stmt->error);
    }

    if ($delete_stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    } else {
        throw new Exception("No course was deleted");
    }

    $delete_stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Log error for debugging
    error_log("Delete course error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 