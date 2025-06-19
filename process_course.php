<?php
session_start();
require_once 'db.php';

// Set header to return JSON response
header('Content-Type: application/json');

try {
    // Get form data
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $description = $_POST['description'];
    
    // Check if this is an update or insert
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $course_id = $_POST['course_id'];
        
        // Update existing course
        $sql = "UPDATE tblcourse SET course_name = ?, course_code = ?, description = ? WHERE course_id = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param("sssi", $course_name, $course_code, $description, $course_id);
    } else {
        // Insert new course
        $sql = "INSERT INTO tblcourse (course_name, course_code, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param("sss", $course_name, $course_code, $description);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => isset($_POST['action']) ? 'Course updated successfully' : 'Course added successfully'
        ]);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
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