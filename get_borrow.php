<?php
require_once 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

// Check if borrow_id is provided
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Borrow ID is required']);
    exit();
}

$borrow_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Fetch borrow details
$sql = "SELECT *, 
        CONCAT(s.firstname, ' ', s.lastname) as student_name,
        bk.title as book_title,
        DATE_FORMAT(b.borrow_date, '%Y-%m-%d') as borrow_date,
        DATE_FORMAT(b.return_date, '%Y-%m-%d') as due_date
        FROM tblborrower b
        INNER JOIN tblstudent s ON b.student_id = s.student_id
        INNER JOIN tblbooks bk ON b.book_id = bk.book_id
        WHERE b.borrow_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$stmt->bind_param("i", $borrow_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Borrow record not found']);
    exit();
}

$borrow = $result->fetch_assoc();
echo json_encode([
    'success' => true,
    'borrow' => [
        'borrow_id' => $borrow['borrow_id'],
        'student_id' => $borrow['student_id'],
        'book_id' => $borrow['book_id'],
        'course_id' => $borrow['course_id'],
        'borrow_date' => $borrow['borrow_date'],
        'return_date' => $borrow['return_date'],
        'status' => $borrow['status'],
    ]
]);
?> 