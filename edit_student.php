<?php
require_once 'session_check.php';
require('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['student_id'])) {
            throw new Exception('Student ID is required');
        }

        $student_id = $_POST['student_id'];
        $firstname = $_POST['firstName'] ?? '';
        $lastname = $_POST['lastName'] ?? '';
        $email = $_POST['email'] ?? '';
        $course_id = $_POST['courseId'] ?? null;

        // Start with base SQL without photo
        $sql = "UPDATE tblstudent SET 
                firstname = ?,
                lastname = ?,
                email = ?,
                course_id = ?";
        
        $params = [$firstname, $lastname, $email, $course_id];
        $types = "sssi"; // string, string, string, integer

        // Handle photo upload if provided
        if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
            $target_dir = "uploads/students/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;

            // Validate image
            if (!getimagesize($_FILES["photo"]["tmp_name"])) {
                throw new Exception("File is not an image");
            }

            // Check file size (5MB max)
            if ($_FILES["photo"]["size"] > 5000000) {
                throw new Exception("File is too large. Maximum size is 5MB");
            }

            // Allow certain file formats
            if ($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
                throw new Exception("Only JPG, JPEG & PNG files are allowed");
            }

            // Upload file
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $sql .= ", photo = ?";
                $params[] = $target_file;
                $types .= "s";
            } else {
                throw new Exception("Failed to upload file");
            }
        }

        // Add WHERE clause
        $sql .= " WHERE student_id = ?";
        $params[] = $student_id;
        $types .= "s";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made']);
        }

        $stmt->close();

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (!isset($_GET['student_id'])) {
            throw new Exception('Student ID is required');
        }

        $student_id = $_GET['student_id'];

        $sql = "SELECT s.*, c.course_name 
                FROM tblstudent s 
                LEFT JOIN tblcourse c ON s.course_id = c.course_id 
                WHERE s.student_id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $student_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $student]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Student not found']);
        }

        $stmt->close();

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

$conn->close();
?> 