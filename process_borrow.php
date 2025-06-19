<?php
require_once 'db.php';
session_start();

// Set JSON response header
header('Content-Type: application/json');

// Check user login status
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

// Handle new book borrow (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['student_id', 'book_id', 'borrow_date', 'return_date'];
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: ' . implode(', ', $missing_fields)
            ]);
            exit;
        }
        
        // Get and sanitize input
        $student_id = intval($_POST['student_id']);
        $book_id = intval($_POST['book_id']);
        $borrow_date = $_POST['borrow_date'];
        $return_date = $_POST['return_date'];
        $status = 'borrowed';
        
        // Check if student exists
        $check_student_sql = "SELECT student_id FROM tblstudent WHERE student_id = ?";
        $check_student_stmt = $conn->prepare($check_student_sql);
        if (!$check_student_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $check_student_stmt->bind_param("i", $student_id);
        if (!$check_student_stmt->execute()) {
            throw new Exception("Execute failed: " . $check_student_stmt->error);
        }
        
        $student_result = $check_student_stmt->get_result();
        if ($student_result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Selected student does not exist in the database'
            ]);
            exit;
        }
        
        // Check if book exists
        $check_book_sql = "SELECT book_id FROM tblbooks WHERE book_id = ?";
        $check_book_stmt = $conn->prepare($check_book_sql);
        if (!$check_book_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $check_book_stmt->bind_param("i", $book_id);
        if (!$check_book_stmt->execute()) {
            throw new Exception("Execute failed: " . $check_book_stmt->error);
        }
        
        $book_result = $check_book_stmt->get_result();
        if ($book_result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Selected book does not exist in the database'
            ]);
            exit;
        }
        
        // Check if book is already borrowed
        $check_sql = "SELECT * FROM tblborrower WHERE book_id = ? AND status = 'borrowed'";
        $check_stmt = $conn->prepare($check_sql);
        if (!$check_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $check_stmt->bind_param("i", $book_id);
        if (!$check_stmt->execute()) {
            throw new Exception("Execute failed: " . $check_stmt->error);
        }
        
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'This book is already borrowed'
            ]);
            exit;
        }
        
        // Insert new borrow record
        $sql = "INSERT INTO tblborrower (student_id, book_id, borrow_date, return_date, status) 
                VALUES (?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("iisss", $student_id, $book_id, $borrow_date, $return_date, $status);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Book borrowed successfully'
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['borrow_id'])) {
        echo json_encode(['success' => false, 'message' => 'Borrow ID is required']);
        exit();
    }

    $borrow_id = filter_var($data['borrow_id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get book_id before deleting
        $sql = "SELECT book_id FROM tblborrower WHERE borrow_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $borrow_id);
        if (!$stmt->execute()) {
            throw new Exception("Error executing query: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $borrow = $result->fetch_assoc();
        
        if (!$borrow) {
            throw new Exception("Borrow record not found");
        }
        
        // Delete the borrow record
        $sql = "DELETE FROM tblborrower WHERE borrow_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $borrow_id);
        if (!$stmt->execute()) {
            throw new Exception("Error deleting record: " . $stmt->error);
        }
        
        if ($stmt->affected_rows === 0) {
            throw new Exception("No record was deleted");
        }
        
        // Update book status to available
        $sql = "UPDATE tblbooks SET status = 'available' WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $borrow['book_id']);
        if (!$stmt->execute()) {
            throw new Exception("Error updating book status: " . $stmt->error);
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Borrow record deleted successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}

// Handle PUT request for updating
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    
    if (!isset($_PUT['borrow_id'])) {
        echo json_encode(['success' => false, 'message' => 'Borrow ID is required']);
        exit();
    }

    $borrow_id = filter_var($_PUT['borrow_id'], FILTER_SANITIZE_NUMBER_INT);
    $student_id = filter_var($_PUT['student_id'], FILTER_SANITIZE_NUMBER_INT);
    $book_id = filter_var($_PUT['book_id'], FILTER_SANITIZE_NUMBER_INT);
    $borrow_date = filter_var($_PUT['borrow_date'], FILTER_SANITIZE_STRING);
    $due_date = filter_var($_PUT['due_date'], FILTER_SANITIZE_STRING);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update borrow record
        $sql = "UPDATE tblborrower SET 
                student_id = ?,
                book_id = ?,
                borrow_date = ?,
                return_date = ?
                WHERE borrow_id = ?";
                
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("iisss", $student_id, $book_id, $borrow_date, $due_date, $borrow_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing update: " . $stmt->error);
        }
        
        if ($stmt->affected_rows === 0) {
            throw new Exception("No record was updated. Borrow ID may not exist.");
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Borrow record updated successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}

// Handle borrow deletion (DELETE request with different parameter)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['borrow_id'])) {
    try {
        $borrow_id = filter_var($_GET['borrow_id'], FILTER_SANITIZE_NUMBER_INT);
        if (!$borrow_id) {
            throw new Exception('Invalid borrow ID');
        }

        // Delete borrow record
        $sql = "DELETE FROM tblborrower WHERE borrow_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt->bind_param("i", $borrow_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Borrow record deleted successfully']);
        } else {
            throw new Exception('Error deleting borrow record: ' . $conn->error);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// Close the database connection
$conn->close();
?> 