<?php
require_once 'session_check.php';
require('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    if (!isset($_POST['student_id'])) {
        throw new Exception('Student ID is required');
    }

    $student_id = $_POST['student_id'];

    // First check if student has any active borrows
    $check_sql = "SELECT COUNT(*) as active_borrows 
                  FROM tblborrower 
                  WHERE student_id = ? AND return_date IS NULL";
    
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $check_stmt->bind_param("s", $student_id);
    
    if (!$check_stmt->execute()) {
        throw new Exception("Execute failed: " . $check_stmt->error);
    }

    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['active_borrows'] > 0) {
        throw new Exception("Cannot delete student with active borrows. Please ensure all books are returned first.");
    }

    $check_stmt->close();

    // Get the photo path before deleting
    $photo_sql = "SELECT photo FROM tblstudent WHERE student_id = ?";
    $photo_stmt = $conn->prepare($photo_sql);
    $photo_stmt->bind_param("s", $student_id);
    $photo_stmt->execute();
    $photo_result = $photo_stmt->get_result();
    $photo_row = $photo_result->fetch_assoc();
    $photo_path = $photo_row['photo'] ?? null;
    $photo_stmt->close();

    // Begin transaction
    $conn->begin_transaction();

    // Delete from tblborrower first (historical records)
    $delete_borrows = "DELETE FROM tblborrower WHERE student_id = ?";
    $stmt1 = $conn->prepare($delete_borrows);
    $stmt1->bind_param("s", $student_id);
    $stmt1->execute();
    $stmt1->close();

    // Then delete the student
    $delete_student = "DELETE FROM tblstudent WHERE student_id = ?";
    $stmt2 = $conn->prepare($delete_student);
    $stmt2->bind_param("s", $student_id);
    $stmt2->execute();

    if ($stmt2->affected_rows > 0) {
        // Commit the transaction
        $conn->commit();

        // Delete the photo file if it exists
        if ($photo_path && file_exists($photo_path)) {
            unlink($photo_path);
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Student deleted successfully'
        ]);
    } else {
        throw new Exception("Student not found");
    }

    $stmt2->close();

} catch (Exception $e) {
    // Rollback the transaction if there was an error
    if ($conn->connect_error === null) {
        $conn->rollback();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?> 