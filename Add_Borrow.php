<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and sanitize input
        $student_id = trim($_POST['student_id']);
        $book_id = intval($_POST['book_id']);
        $borrow_date = date('Y-m-d', strtotime($_POST['borrow_date']));
        $return_date = date('Y-m-d', strtotime($_POST['return_date']));
        $status = 'borrowed';

        // Check if this student already has this book borrowed
        $check_sql = "SELECT borrow_id FROM tblborrower 
                     WHERE student_id = ? AND book_id = ? AND status = 'borrowed'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $student_id, $book_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception("This student has already borrowed this book");
        }

        // Check if book is already borrowed by another student
        $check_sql = "SELECT borrow_id FROM tblborrower 
                     WHERE book_id = ? AND status = 'borrowed'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $book_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception("This book is already borrowed by another student");
        }
        
        // Insert new borrow record
        $sql = "INSERT INTO tblborrower (student_id, book_id, borrow_date, return_date, status) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $student_id, $book_id, $borrow_date, $return_date, $status);
        
        if ($stmt->execute()) {
            // Get the newly inserted borrow record
            $borrow_id = $stmt->insert_id;
            $select_sql = "SELECT *, s.firstname, s.lastname, bk.title, bk.author, c.course_name
                          FROM tblborrower b
                          JOIN tblstudent s ON b.student_id = s.student_id
                          JOIN tblbooks bk ON b.book_id = bk.book_id
                          LEFT JOIN tblcourse c ON s.course_id = c.course_id
                          WHERE b.borrow_id = ?";
            $select_stmt = $conn->prepare($select_sql);
            $select_stmt->bind_param("i", $borrow_id);
            $select_stmt->execute();
            $result = $select_stmt->get_result();
            $new_record = $result->fetch_assoc();
            
            // Format dates
            $new_record['borrow_date'] = date('Y-m-d', strtotime($new_record['borrow_date']));
            $new_record['return_date'] = date('Y-m-d', strtotime($new_record['return_date']));
            
            echo json_encode([
                'success' => true, 
                'message' => 'Book borrowed successfully',
                'record' => $new_record
            ]);
        } else {
            throw new Exception("Error adding borrow record: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

$book_sql = "SELECT * FROM tblbooks b 
             WHERE b.book_id NOT IN (
                 SELECT book_id FROM tblborrower 
                 WHERE status = 'borrowed'
             )
             ORDER BY b.title";
?> 