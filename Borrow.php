<?php
require_once 'db.php';
require_once 'session_check.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Borrow Books - Library Management System</title>
    <?php include 'components/head.php'; ?>
    <?php include 'components/sidebar.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .main-container {
            margin-left: 16rem;
            padding: 2rem;
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
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            width: 300px;
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
        }

        .books-table th,
        .books-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .books-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3730a3;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #22c55e;
            color: white;
        }

        .btn-success:hover {
            background-color: #16a34a;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .close-btn:hover {
            color: #000;
            background-color: #f3f4f6;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .status-available {
            color: #22c55e;
            font-weight: 500;
            background-color: #f0fdf4;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .status-borrowed {
            color: #ef4444;
            font-weight: 500;
            background-color: #fef2f2;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .status-returned {
            color: #3b82f6;
            font-weight: 500;
            background-color: #eff6ff;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn.edit {
            background: none;
            color: #3b82f6;
            border: 1.5px solid #3b82f6;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .action-btn.edit:hover {
            background: #3b82f611;
            color: #2563eb;
        }

        .action-btn.delete {
            background: none;
            color: #ef4444;
            border: 1.5px solid #ef4444;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .action-btn.delete:hover {
            background: #ef444411;
            color: #b91c1c;
        }

        .action-btn i {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="page-header">
            <h1>Borrow Books</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="searchBooks()">
                <button type="button" class="btn btn-primary" onclick="openNewBorrowModal()">
                    <i class="fas fa-plus"></i> Add New Borrow
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>BorrowID</th>
                        <th>Student Name</th>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="borrowTableBody">
                    <?php
                    // Fetch borrowed books with student information
                    $sql = "SELECT 
                                b.borrow_id,
                                b.book_id,
                                b.student_id,
                                b.borrow_date,
                                b.return_date,
                                b.status,
                                bk.title,
                                bk.author,
                                s.firstname,
                                s.lastname,
                                c.course_name
                            FROM tblborrower b
                            INNER JOIN tblbooks bk ON b.book_id = bk.book_id
                            INNER JOIN tblstudent s ON b.student_id = s.student_id
                            LEFT JOIN tblcourse c ON s.course_id = c.course_id
                            WHERE b.status = 'borrowed'
                            ORDER BY b.borrow_date DESC";
                    
                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo "<tr><td colspan='8' class='text-center text-danger'>Error executing query: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows === 0) {
                        echo "<tr><td colspan='8' class='text-center'>No books are currently borrowed</td></tr>";
                    } else {
                        while($row = $result->fetch_assoc()) {
                            // Format dates
                            $borrow_date = date('Y-m-d', strtotime($row['borrow_date']));
                            $return_date = date('Y-m-d', strtotime($row['return_date']));
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['borrow_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['course_name'] ?? 'N/A') . "</td>";
                            echo "<td>" . $borrow_date . "</td>";
                            echo "<td>" . $return_date . "</td>";
                            echo "<td><span class='status-" . strtolower($row['status']) . "'>" . 
                                 ucfirst(htmlspecialchars($row['status'])) . "</span></td>";
                            echo "<td class='actions'>";
                            echo "<button class='action-btn edit' onclick='openEditModal(" . htmlspecialchars($row['borrow_id']) . ")'>";
                            echo "<span style='font-size:1.1em;'>✏️</span> Edit</button>";
                            echo "<button class='action-btn delete' onclick='deleteBorrow(" . htmlspecialchars($row['borrow_id']) . ")'>";
                            echo "<span style='font-size:1.1em;'>🗑️</span> Delete</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- New Borrow Modal -->
    <div id="newBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Borrow</h2>
                <button type="button" class="close-btn" onclick="closeNewBorrowModal()">&times;</button>
            </div>
            <form id="newBorrowForm" method="POST" action="process_borrow.php">
                <div class="form-group">
                    <label for="new_student_id">Student</label>
                    <select id="new_student_id" name="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        <?php
                        $student_sql = "SELECT student_id, CONCAT(firstname, ' ', lastname) as full_name 
                                      FROM tblstudent 
                                      ORDER BY firstname, lastname";
                        $student_result = $conn->query($student_sql);
                        
                        if (!$student_result) {
                            echo "<option value=''>Error loading students: " . $conn->error . "</option>";
                        } else if ($student_result->num_rows === 0) {
                            echo "<option value=''>No students found in database</option>";
                        } else {
                        while($student = $student_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($student['student_id']) . "'>" . 
                                     htmlspecialchars($student['full_name']) . 
                                 "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_book_id">Book</label>
                    <select id="new_book_id" name="book_id" class="form-control" required>
                        <option value="">Select Book</option>
                        <?php
                        $book_sql = "SELECT * FROM tblbooks b 
                                   WHERE b.book_id NOT IN (
                                       SELECT book_id FROM tblborrower 
                                       WHERE status = 'borrowed'
                                   )
                                   ORDER BY b.title";
                        $book_result = $conn->query($book_sql);
                        
                        if (!$book_result) {
                            echo "<option value=''>Error loading books: " . $conn->error . "</option>";
                        } else if ($book_result->num_rows === 0) {
                            echo "<option value=''>No available books found</option>";
                        } else {
                        while($book = $book_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($book['book_id']) . "'>" . 
                                     htmlspecialchars($book['title']) . " </option> " ; 
                                  
                            }
                        }
                        ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="new_course_id">Course</label>
                    <select id="new_course_id" name="course_id" class="form-control" required>
                        <option value="">Select Course</option>
                        <?php
                        $course_sql = "SELECT course_id, course_name FROM tblcourse ORDER BY course_name";
                        $course_result = $conn->query($course_sql);
                        if (!$course_result) {
                            echo "<option value=''>Error loading Course: " . $conn->error . "</option>";
                        } else if ($course_result->num_rows === 0) {
                            echo "<option value=''>No Course found</option>";
                        } else {
                            while($course = $course_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($course['course_id']) . "'>" . 
                                     htmlspecialchars($course['course_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="new_borrow_date">Borrow Date</label>
                    <input type="date" id="new_borrow_date" name="borrow_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_return_date">Return Date</label>
                    <input type="date" id="new_return_date" name="return_date" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeNewBorrowModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Borrow Modal -->
    <div id="editBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Borrow</h2>
                <button type="button" class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editBorrowForm">
                <input type="hidden" name="borrow_id" id="edit_borrow_id">
                
                <div class="mb-3">
                    <label for="edit_student_id" class="form-label">Student</label>
                    <select name="student_id" id="edit_student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        <?php
                        $students_sql = "SELECT * FROM tblstudent ORDER BY lastname, firstname";
                        $students_result = $conn->query($students_sql);
                        if ($students_result && $students_result->num_rows > 0) {
                            while ($student = $students_result->fetch_assoc()) {
                                echo "<option value='" . $student['student_id'] . "'>" . 
                                     htmlspecialchars($student['lastname'] . ', ' . $student['firstname']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="edit_book_id" class="form-label">Book</label>
                    <select name="book_id" id="edit_book_id" class="form-control" required>
                        <option value="">Select Book</option>
                        <?php
                        $books_sql = "SELECT * FROM tblbooks ORDER BY title";
                        $books_result = $conn->query($books_sql);
                        if ($books_result && $books_result->num_rows > 0) {
                            while ($book = $books_result->fetch_assoc()) {
                                echo "<option value='" . $book['book_id'] . "'>" . 
                                     htmlspecialchars($book['title']) . "</option>";
                             
                            }
                        }
                        ?>
                    </select>
                </div>

                 
 <div class="mb-3">
     <label for="edit_course_id" class="form-label">Course</label>
     <select name="course_id" id="edit_course_id" class="form-control" required>
         <option value="">Select Course</option>
         <?php
         $courses_sql = "SELECT * FROM tblcourse ORDER BY course_name";
         $courses_result = $conn->query($courses_sql);
         if ($courses_result && $courses_result->num_rows > 0) {
             while ($course = $courses_result->fetch_assoc()) {
                 echo "<option value='" . $course['course_id'] . "'>" . 
                      htmlspecialchars($course['course_name']) . "</option>";
             }
         }
         ?>
     </select>
 </div>
                
                <div class="mb-3">
                    <label for="edit_borrow_date" class="form-label">Borrow Date</label>
                    <input type="date" name="borrow_date" id="edit_borrow_date" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="edit_return_date" class="form-label">Return Date</label>
                    <input type="date" name="return_date" id="edit_return_date" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="edit_status" class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-control" required>
                        <option value="borrowed">Borrowed</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
               
               
               
                
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- JavaScript for functionality -->
    <script>
        // Function to open new borrow modal
        function openNewBorrowModal() {
            const modal = document.getElementById('newBorrowModal');
            if (modal) {
                modal.style.display = 'block';
                setDefaultDates();
            }
        }

        // Function to close new borrow modal
        function closeNewBorrowModal() {
            const modal = document.getElementById('newBorrowModal');
            if (modal) {
                modal.style.display = 'none';
                // Reset form
                const form = document.getElementById('newBorrowForm');
                if (form) {
                    form.reset();
                }
            }
        }

        // Function to open edit modal
        function openEditModal(borrowId) {
            fetch('get_borrow.php?id=' + borrowId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const borrow = data.borrow;
                        document.getElementById('edit_borrow_id').value = borrow.borrow_id;
                        document.getElementById('edit_student_id').value = borrow.student_id;
                        document.getElementById('edit_book_id').value = borrow.book_id;
                        document.getElementById('edit_borrow_date').value = borrow.borrow_date;
                        document.getElementById('edit_return_date').value = borrow.return_date;
                        document.getElementById('edit_status').value = borrow.status;
                        document.getElementById('edit_course_id').value = borrow.course_id;
                        document.getElementById('editBorrowModal').style.display = 'block';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error fetching borrow details');
                });
        }

        // Function to close edit modal
        function closeEditModal() {
            const modal = document.getElementById('editBorrowModal');
            if (modal) {
                modal.style.display = 'none';
                // Reset form
                const form = document.getElementById('editBorrowForm');
                if (form) {
                    form.reset();
                }
            }
        }

        // Function to delete borrow record
        function deleteBorrow(borrowId) {
            if (confirm('Are you sure you want to delete this borrow record?')) {
                const formData = new FormData();
                formData.append('borrow_id', borrowId);

                fetch('delete_borrow.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) // Always read as text first
                .then(text => {
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        alert('Server error: ' + text);
                        throw new Error('Server returned non-JSON: ' + text);
                    }
                    if (data.success) {
                        alert('Borrow record deleted successfully');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    alert('Error deleting borrow record: ' + error.message);
                });
            }
        }

        // Set default dates for new borrow
        function setDefaultDates() {
            const today = new Date();
            const returnDate = new Date();
            returnDate.setDate(today.getDate() + 7);
            
            document.getElementById('new_borrow_date').value = today.toISOString().split('T')[0];
            document.getElementById('new_return_date').value = returnDate.toISOString().split('T')[0];
        }

        // Add form submission handlers
        document.addEventListener('DOMContentLoaded', function() {
            // New borrow form
            const newForm = document.getElementById('newBorrowForm');
            if (newForm) {
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Validate dates
                    const borrowDate = new Date(formData.get('borrow_date'));
                    const returnDate = new Date(formData.get('return_date'));
                    
                    if (returnDate < borrowDate) {
                        alert('Return date cannot be earlier than borrow date');
                        return;
                    }
                    
                    // Show loading state
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = 'Processing...';
                    submitButton.disabled = true;
                    
                    // Send the request
                    fetch('Add_Borrow.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Get the table body
                            const tableBody = document.getElementById('borrowTableBody');
                            
                            // Create new row
                            const newRow = document.createElement('tr');
                            
                            // Set the row HTML
                            newRow.innerHTML = `
                                <td>${data.record.borrow_id}</td>
                                <td>${data.record.firstname} ${data.record.lastname}</td>
                                <td>${data.record.title}</td>
                                <td>${data.record.course_name || 'N/A'}</td>
                                <td>${data.record.borrow_date}</td>
                                <td>${data.record.return_date}</td>
                                <td><span class="status-${data.record.status.toLowerCase()}">${data.record.status}</span></td>
                                <td class="actions">
                                    <button class="action-btn edit" onclick="openEditModal(${data.record.borrow_id})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="action-btn delete" onclick="deleteBorrow(${data.record.borrow_id})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            `;
                            
                            // Remove "No books" message if it exists
                            const noBooksRow = tableBody.querySelector('tr td[colspan="8"]');
                            if (noBooksRow) {
                                noBooksRow.parentElement.remove();
                            }
                            
                            // Add new row at the top of the table
                            if (tableBody.firstChild) {
                                tableBody.insertBefore(newRow, tableBody.firstChild);
                            } else {
                                tableBody.appendChild(newRow);
                            }
                            
                            // Show success message
                            alert('Book borrowed successfully!');
                            
                            // Close modal and reset form
                            closeNewBorrowModal();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error borrowing book. Please try again.');
                    })
                    .finally(() => {
                        // Reset button state
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    });
                });
            }
            
            // Edit borrow form
            const editForm = document.getElementById('editBorrowForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch('edit_borrow.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Borrow record updated successfully');
                            closeEditModal();
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating borrow record');
                    });
                });
            }
        });

        // Add this function for testing
        function testDeleteEndpoint() {
            console.log('Testing delete endpoint...');
            
            const formData = new FormData();
            formData.append('borrow_id', '1'); // Test with ID 1
            
            fetch('delete_borrow.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Raw response:', data);
                try {
                    const jsonData = JSON.parse(data);
                    console.log('Parsed response:', jsonData);
                } catch (e) {
                    console.log('Response is not JSON:', data);
                }
            })
            .catch(error => {
                console.error('Test error:', error);
            });
        }

        // Call this in console to test: testDeleteEndpoint()

        function searchBooks() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.books-table');
            const trs = table.querySelectorAll('tbody tr');

            trs.forEach(tr => {
                const text = tr.textContent.toLowerCase();
                tr.style.display = text.includes(filter) ? '' : 'none';
            });
        }
    </script>
         <?php include 'components/top_bar.php'; ?>
</body>
</html>
    
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

    