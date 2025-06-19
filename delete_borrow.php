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
    if ($borrow_id <= 0) {
        http_response_code(400);
        throw new Exception('Invalid borrow ID');
    }

    $check_sql = "SELECT borrow_id FROM tblborrower WHERE borrow_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $borrow_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows === 0) {
        http_response_code(404);
        throw new Exception('Borrow record not found');
    }

    $delete_sql = "DELETE FROM tblborrower WHERE borrow_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $borrow_id);
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Borrow record deleted successfully']);
    } else {
        http_response_code(500);
        throw new Exception('Failed to delete record: ' . $delete_stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 