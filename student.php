<?php
require_once 'session_check.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("db.php");
    
    try {
        // File upload handling
        $target_dir = "uploads/students/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is actual image
        if(getimagesize($_FILES["photo"]["tmp_name"]) === false) {
            throw new Exception("File is not an image.");
        }
        
        // Check file size (5MB max)
        if ($_FILES["photo"]["size"] > 5000000) {
            throw new Exception("File is too large. Maximum size is 5MB.");
        }
        
        // Allow certain file formats
        if($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
            throw new Exception("Only JPG, JPEG & PNG files are allowed.");
        }
        
        // Upload file
        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            throw new Exception("Failed to upload file.");
        }
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO tblstudent (student_id, firstname, lastname, course_id, photo, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $student_id = $_POST['studentId'];
        $firstname = $_POST['firstName'];
        $lastname = $_POST['lastName'];
        $course_id = $_POST['courseId'];
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        $stmt->bind_param("sssissss", 
            $student_id,
            $firstname,
            $lastname,
            $course_id,
            $target_file,
            $email,
            $phone,
            $address
        );
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Student added successfully!');
                    window.location.href = 'student.php';
                  </script>";
        } else {
            throw new Exception("Error inserting record: " . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href = 'student.php';
              </script>";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once 'session_check.php';
    ?>
    <title>Student Management</title>
    <?php include 'components/head.php'; ?>
    <style>
        /* Base styles for full width layout */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Main container styles */
        .main-container {
            margin-left: 16rem;  /* Match sidebar width */
            min-height: 100vh;
            padding: 2rem;
            width: calc(100% - 16rem);  /* Full width minus sidebar */
            box-sizing: border-box;
            background-color: #f3f4f6;
        }

        /* Ensure sidebar is fixed */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 16rem;
            height: 100vh;
            background-color: #ffffff;
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 300px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #9333ea;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7e22ce;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }

        .floating-add-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #4f46e5;
            color: white;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.2s;
        }

        .floating-add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th,
        .students-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .students-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .students-table tr:hover {
            background-color: #f9fafb;
        }

        .student-photo {
            width: 60px;                   /* Standard size for table view */
            height: 60px;
            border-radius: 50%;            /* Circle shape for a modern profile look */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Softer, more elevated shadow */
            border: 2px solid #fff;        /* Adds contrast if used on a colored background */
            background-color: #f0f0f0;     /* Light gray background in case image doesn't load */
        }

        .student-details-photo {
            width: 120px;                  /* Larger size for detail view */
            height: 120px;
            border-radius: 8px;            /* Slightly rounded corners for detail view */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Consistent shadow with student-photo */
            border: 1px solid #e5e7eb;     /* Subtle border */
            background-color: #f0f0f0;     /* Consistent background with student-photo */
        }

        .photo-container {
            flex: 0 0 120px;              /* Match the width of student-details-photo */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .course-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        .page-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .page-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            background-color: white;
            color: #374151;
            cursor: pointer;
        }

        .page-btn:hover {
            background-color: #f9fafb;
        }

        .page-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        /* Modal and Form Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 2rem 1rem;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
            position: relative;
            margin: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            margin: -0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .close-btn:hover {
            color: #111827;
            background-color: #f3f4f6;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #111827;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #111827;
            background-color: #ffffff;
            border-color: #4f46e5;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.25);
        }

        select.form-control {
            padding-right: 2rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Current Photo Preview */
        #currentPhotoPreview {
            margin-top: 1rem;
        }

        #currentPhoto {
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 0.25rem;
            background-color: #f9fafb;
        }

        /* Form Submit Button */
        form .btn-primary {
            width: 100%;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            font-size: 1rem;
        }

        /* Modal Animation */
        .modal.active {
            display: flex;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .modal-content {
                padding: 1.5rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .modal {
                padding: 1rem;
            }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 300px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #9333ea;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7e22ce;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }

        .floating-add-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #4f46e5;
            color: white;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.2s;
        }

        .floating-add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th,
        .students-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .students-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .students-table tr:hover {
            background-color: #f9fafb;
        }

        .student-photo {
            width: 60px;                   /* Standard size for table view */
            height: 60px;
            border-radius: 50%;            /* Circle shape for a modern profile look */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Softer, more elevated shadow */
            border: 2px solid #fff;        /* Adds contrast if used on a colored background */
            background-color: #f0f0f0;     /* Light gray background in case image doesn't load */
        }

        .student-details-photo {
            width: 120px;                  /* Larger size for detail view */
            height: 120px;
            border-radius: 8px;            /* Slightly rounded corners for detail view */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Consistent shadow with student-photo */
            border: 1px solid #e5e7eb;     /* Subtle border */
            background-color: #f0f0f0;     /* Consistent background with student-photo */
        }

        .photo-container {
            flex: 0 0 120px;              /* Match the width of student-details-photo */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .course-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        .page-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .page-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            background-color: white;
            color: #374151;
            cursor: pointer;
        }

        .page-btn:hover {
            background-color: #f9fafb;
        }

        .page-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        /* Additional styles for the enhanced table */
        .student-name {
            font-weight: 500;
            color: #111827;
        }

        .student-email {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .phone-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .phone-link:hover {
            text-decoration: underline;
        }

        .address {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-borrowed {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-available {
            background-color: #dcfce7;
            color: #16a34a;
        }

        /* Make the table more compact but still readable */
        .students-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        /* Add hover effect to the entire row */
        .students-table tr:hover {
            background-color: #f8fafc;
        }

        /* Style for the action buttons container */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }

        /* Make buttons smaller in the table */
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .student-details {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .student-photo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .student-details-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e5e7eb;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-item label {
            font-weight: 600;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .detail-item span {
            color: #111827;
            font-size: 1rem;
        }

        @media (max-width: 640px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .btn-info {
            background: none;
            color: #0dcaf0;
            border: 1.5px solid #0dcaf0;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }
        .btn-info:hover {
            background: #0dcaf011;
            color: #0a8ca0;
        }

        .btn-primary {
            background: none;
            color: #3730a3;
            border: 1.5px solid #3730a3;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }
        .btn-primary:hover {
            background: #3730a311;
            color: #1e1b4b;
        }

        .btn-danger {
            background: none;
            color: #dc3545;
            border: 1.5px solid #dc3545;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }
        .btn-danger:hover {
            background: #dc354511;
            color: #a71d2a;
        }
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    
    <div class="main-container">
        <div class="page-header">
            <h1 class="text-2xl font-semibold text-gray-900">Student Management</h1>
            <div class="header-actions">
                <input type="text" placeholder="Search students..." class="search-input">
                <button class="btn btn-primary" style="  background-color:rgb(25, 80, 231); color: #fff; border: none;"onclick="openAddStudentModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                    </svg>
                    Add New Student
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Borrowing Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require("db.php");
                    
                    $sql = "SELECT *, c.course_name,
                           (SELECT COUNT(*) FROM tblborrower b 
                            WHERE b.student_id = s.student_id AND b.return_date IS NULL) as active_borrows
                           FROM tblstudent s 
                           LEFT JOIN tblcourse c ON s.course_id = c.course_id 
                           ORDER BY s.student_id";
                    
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Determine borrowing status
                            $borrowStatus = $row['active_borrows'] > 0 ? 
                                          '<span class="status-badge status-borrowed">Currently Borrowing (' . $row['active_borrows'] . ')</span>' : 
                                          '<span class="status-badge status-available">No Active Borrows</span>';

                            echo "<tr>";
                            echo "<td><img src='" . htmlspecialchars($row['photo']) . "' alt='Student photo' class='student-photo'></td>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>
                                    <div class='student-name'>" . 
                                        htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . 
                                    "</div>
                                    <div class='student-email'>" . 
                                        htmlspecialchars($row['email']) . 
                                    "</div>
                                  </td>";
                            echo "<td><span class='course-badge'>" . htmlspecialchars($row['course_name']) . "</span></td>";
                            echo "<td>" . $borrowStatus . "</td>";
                            echo "<td class='actions'>
                                    <button onclick='viewDetails(\"" . htmlspecialchars($row['student_id']) . "\")' class='btn btn-info btn-sm'>
                                        <span style='font-size:1.1em;'>👁️</span> View
                                    </button>
                                    <button onclick='editStudent(\"" . htmlspecialchars($row['student_id']) . "\")' class='btn btn-primary btn-sm'>
                                        <span style='font-size:1.1em;'>✏️</span> Edit
                                    </button>
                                    <button onclick='deleteStudent(\"" . htmlspecialchars($row['student_id']) . "\")' class='btn btn-danger btn-sm'>
                                        <span style='font-size:1.1em;'>🗑️</span> Delete
                                    </button>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4'>No students found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Floating Add Button -->
        
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Student</h2>
                <button class="close-btn" onclick="closeAddStudentModal()">&times;</button>
            </div>
            <form id="addStudentForm" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="studentId">Student ID</label>
                    <input type="text" id="studentId" name="studentId" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="courseId">Course</label>
                    <select id="courseId" name="courseId" class="form-control" required>
                        <option value="">Select Course</option>
                        <?php
                        $courseSql = "SELECT course_id, course_name FROM tblcourse ORDER BY course_name";
                        $courseResult = $conn->query($courseSql);
                        if ($courseResult && $courseResult->num_rows > 0) {
                            while($course = $courseResult->fetch_assoc()) {
                                echo "<option value='" . $course['course_id'] . "'>" . 
                                     htmlspecialchars($course['course_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Student</button>
            </form>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Student</h2>
                <button class="close-btn" onclick="closeEditStudentModal()">&times;</button>
            </div>
            <form id="editStudentForm" method="post" enctype="multipart/form-data">
                <input type="hidden" id="editStudentId" name="student_id">
                <div class="form-group">
                    <label for="editFirstName">First Name</label>
                    <input type="text" id="editFirstName" name="firstName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editLastName">Last Name</label>
                    <input type="text" id="editLastName" name="lastName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" id="editEmail" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="editCourseId">Course</label>
                    <select id="editCourseId" name="courseId" class="form-control" required>
                        <option value="">Select Course</option>
                        <?php
                        $courseSql = "SELECT course_id, course_name FROM tblcourse ORDER BY course_name";
                        $courseResult = $conn->query($courseSql);
                        if ($courseResult && $courseResult->num_rows > 0) {
                            while($course = $courseResult->fetch_assoc()) {
                                echo "<option value='" . $course['course_id'] . "'>" . 
                                     htmlspecialchars($course['course_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editPhoto">Photo (leave empty to keep current photo)</label>
                    <input type="file" id="editPhoto" name="photo" class="form-control" accept="image/*">
                    <div id="currentPhotoPreview" class="mt-2">
                        <img id="currentPhoto" src="" alt="Current photo" style="max-width: 100px; display: none;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
<?php 
 include 'components/top_bar.php';
?>
    <!-- View Details Modal -->
    <div id="viewDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Student Details</h2>
                <button class="close-btn" onclick="closeViewDetailsModal()">&times;</button>
            </div>
            <div class="student-details">
                <div class="photo-container">
                    <img id="detailsPhoto" src="" alt="Student photo" class="student-details-photo">
                </div>
                <div class="details-container">
                    <div class="detail-item">
                        <label>Student ID</label>
                        <span id="detailsStudentId"></span>
                    </div>
                    <div class="detail-item">
                        <label>Full Name</label>
                        <span id="detailsFullName"></span>
                    </div>
                    <div class="detail-item">
                        <label>Course</label>
                        <span id="detailsCourse"></span>
                    </div>
                    <div class="detail-item">
                        <label>Email</label>
                        <span id="detailsEmail"></span>
                    </div>
                    <div class="detail-item">
                        <label>Phone</label>
                        <span id="detailsPhone"></span>
                    </div>
                    <div class="detail-item">
                        <label>Address</label>
                        <span id="detailsAddress"></span>
                    </div>
                    <div class="detail-item">
                        <label>Registered Date</label>
                        <span id="detailsCreatedAt"></span>
                    </div>
                    <div class="detail-item">
                        <label>Borrowing Status</label>
                        <span id="detailsBorrowStatus"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.students-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });

        // Modal functions
        function openAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        function editStudent(studentId) {
            // Fetch student details
            fetch(`edit_student.php?student_id=${studentId}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || `HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success || !data.data) {
                        throw new Error('Failed to load student data');
                    }

                    const student = data.data;
                    
                    // Populate the form
                    document.getElementById('editStudentId').value = student.student_id;
                    document.getElementById('editFirstName').value = student.firstname;
                    document.getElementById('editLastName').value = student.lastname;
                    document.getElementById('editEmail').value = student.email || '';
                    document.getElementById('editCourseId').value = student.course_id;

                    // Show current photo
                    const currentPhoto = document.getElementById('currentPhoto');
                    if (student.photo) {
                        currentPhoto.src = student.photo;
                        currentPhoto.style.display = 'block';
                    } else {
                        currentPhoto.style.display = 'none';
                    }

                    // Show the modal
                    document.getElementById('editStudentModal').classList.add('active');
                    document.body.classList.add('modal-open');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load student details: ' + error.message);
                });
        }

        function closeEditStudentModal() {
            document.getElementById('editStudentModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        function viewDetails(studentId) {
            fetch(`get_student_details.php?student_id=${studentId}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || `HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) {
                        throw new Error('No data received from server');
                    }
                    
                    // Populate the modal with student details
                    document.getElementById('detailsPhoto').src = data.photo;
                    document.getElementById('detailsStudentId').textContent = data.student_id;
                    document.getElementById('detailsFullName').textContent = `${data.firstname} ${data.lastname}`;
                    document.getElementById('detailsCourse').textContent = data.course_name;
                    document.getElementById('detailsEmail').textContent = data.email || 'Not provided';
                    document.getElementById('detailsPhone').textContent = data.phone || 'Not provided';
                    document.getElementById('detailsAddress').textContent = data.address || 'Not provided';
                    document.getElementById('detailsCreatedAt').textContent = new Date(data.created_at).toLocaleDateString();
                    document.getElementById('detailsBorrowStatus').textContent = 
                        data.active_borrows > 0 ? 
                        `Currently Borrowing (${data.active_borrows} items)` : 
                        'No Active Borrows';
                    
                    // Show the modal
                    document.getElementById('viewDetailsModal').classList.add('active');
                    document.body.classList.add('modal-open');
                })
                .catch(error => {
                    console.error('Error details:', error);
                    alert('Failed to load student details: ' + error.message);
                });
        }

        function closeViewDetailsModal() {
            document.getElementById('viewDetailsModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Handle edit form submission
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);

            fetch('edit_student.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Student updated successfully');
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to update student');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update student: ' + error.message);
            });
        });

        function deleteStudent(studentId) {
            if (!confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
                return;
            }

            const formData = new FormData();
            formData.append('student_id', studentId);

            fetch('delete_student.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Student deleted successfully');
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to delete student');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete student: ' + error.message);
            });
        }

        // Preview image before upload for both add and edit forms
        document.getElementById('photo').addEventListener('change', function(e) {
            previewImage(this, 'photoPreview');
        });

        document.getElementById('editPhoto').addEventListener('change', function(e) {
            previewImage(this, 'currentPhotoPreview');
        });

        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.marginTop = '10px';
                    
                    const previewDiv = document.getElementById(previewId);
                    previewDiv.innerHTML = '';
                    previewDiv.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    </script> 
    
</body>
</html>