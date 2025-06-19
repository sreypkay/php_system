<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'session_check.php';
require_once 'config/config.php';

header('Content-Type: application/json');

// Log the request
error_log("Received request for student details. GET params: " . print_r($_GET, true));

if (!isset($_GET['student_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Student ID is required']);
    exit;
}

$student_id = $_GET['student_id'];
error_log("Processing request for student_id: " . $student_id);

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get student details including course name and active borrows count
    $query = "SELECT s.*, c.course_name,
              (SELECT COUNT(*) FROM tblborrower b 
               WHERE b.student_id = s.student_id 
               AND b.status = 'borrowed') as active_borrows
              FROM tblstudent s
              LEFT JOIN tblcourse c ON s.course_id = c.course_id
              WHERE s.student_id = :student_id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();

    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        error_log("No student found with ID: " . $student_id);
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        exit;
    }

    // Ensure photo path is complete
    if ($student['photo'] && !str_starts_with($student['photo'], 'http')) {
        $student['photo'] = 'uploads/' . $student['photo'];
    }

    // Format dates
    $student['created_at'] = date('Y-m-d H:i:s', strtotime($student['created_at']));
    
    // Handle null values
    $student['email'] = $student['email'] ?? '';
    $student['phone'] = $student['phone'] ?? '';
    $student['address'] = $student['address'] ?? '';
    
    error_log("Sending student data: " . print_r($student, true));
    echo json_encode($student);

} catch(PDOException $e) {
    error_log("Error in get_student_details.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 