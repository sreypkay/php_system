<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';
session_start();
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Invalid request method');
    }

    $borrow_id = isset($_POST['borrow_id']) ? intval($_POST['borrow_id']) : 0;
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    $borrow_date = isset($_POST['borrow_date']) ? date('Y-m-d', strtotime($_POST['borrow_date'])) : '';
    $return_date = isset($_POST['return_date']) ? date('Y-m-d', strtotime($_POST['return_date'])) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if (!$borrow_id || !$student_id || !$book_id || !$course_id || !$borrow_date || !$return_date || !$status) {
        http_response_code(400);
        throw new Exception('All fields are required');
    }

    $check_sql = "SELECT borrow_id FROM tblborrower WHERE borrow_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $borrow_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows === 0) {
        http_response_code(404);
        throw new Exception('Borrow record not found');
    }

    $sql = "UPDATE tblborrower 
            SET student_id = ?, book_id = ?, course_id = ?, borrow_date = ?, return_date = ?, status = ? 
            WHERE borrow_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisssi", $student_id, $book_id, $course_id, $borrow_date, $return_date, $status, $borrow_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Borrow record updated successfully']);
    } else {
        http_response_code(500);
        throw new Exception('Failed to update record: ' . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 