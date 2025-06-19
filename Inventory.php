<!-- <?php
session_start();
require_once 'session_check.php';
require_once 'db.php'; // Include database connection
?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/head.php'; ?>
    <style>
        /* Custom Table Styles */
        .inventory-container {
            padding: 20px;
            margin: 0px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            width: 300px;
            font-size: 14px;
        }

        .btn-add {
            background-color: #4f46e5;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-add:hover {
            background-color: #3730a3;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }

        .inventory-table th {
            background-color: #f8fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
        }

        .inventory-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }

        .inventory-table tr:hover {
            background-color: #f8fafc;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            background: none;
            color: #2563eb;
            border: 1.5px solid #2563eb;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .btn-edit:hover {
            background: #2563eb11;
            color: #174ea6;
        }

        .btn-delete {
            background: none;
            color: #e74c3c;
            border: 1.5px solid #e74c3c;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .btn-delete:hover {
            background: #e74c3c11;
            color: #b91c1c;
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-inactive {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination button {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            background-color: white;
            color: #4a5568;
            border-radius: 4px;
            cursor: pointer;
        }

        .pagination button.active {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .pagination button:hover:not(.active) {
            background-color: #f8fafc;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            margin: 50px auto;
            padding: 20px;
            width: 50%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            animation: modalAppear 0.3s ease-out;
        }

        /* Add animation for modal appearance */
        @keyframes modalAppear {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            color: #666;
            cursor: pointer;
            transition: color 0.2s;
            padding: 0 8px;
            border-radius: 4px;                 
        }

        .close:hover {
            color: #000;
            background-color: #f3f4f6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            color: #4a5568;
        }

        .form-control:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Add overlay styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'components/sidebar.php'; ?>
    
    <div class="ml-64">
   
        
        <!-- Add this modal HTML before the inventory-container div -->
        <div id="addCourseModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Add New Course</h2>
                
                <form id="addCourseForm">
                    <div class="form-group">
                        <label for="course_name">Course Title</label>
                        <input type="text" id="course_name" name="course_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="course_code">Course Code</label>
                        <input type="text" id="course_code" name="course_code" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn-add">
                            <i class="fas fa-save mr-2"></i>Save Course
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Course Modal -->
        <div id="editCourseModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Course</h2>
                
                <form id="editCourseForm">
                    <input type="hidden" id="edit_course_id" name="course_id">
                    
                    <div class="form-group">
                        <label for="edit_course_name">Course Title</label>
                        <input type="text" id="edit_course_name" name="course_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_course_code">Course Code</label>
                        <input type="text" id="edit_course_code" name="course_code" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" class="form-control" required></textarea>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save mr-2"></i>Update Course
                        </button>
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="inventory-container">
            <div class="table-header">
                <h2 class="text-2xl font-semibold text-gray-800">Course Inventory</h2>
                <div class="search-box">
                    <input type="text" placeholder="Search courses..." class="search-input" id="searchInput">
                    <button class="btn-add" onclick="openModal()">
                        
                        <i class="fas fa-plus mr-2"></i>Add New Course
                    </button>
                    
                </div>
            </div>

            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Title</th>
                        <th>Code</th>
                        <th>Description</th>
                        <!-- <th>Status</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch courses from database
                    $sql = "SELECT * FROM tblcourse ORDER BY course_id ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // $status_class = $row['status'] == 'Active' ? 'status-active' : 'status-inactive';
                            echo "<tr>
                                    <td>".$row['course_id']."</td>
                                    <td>".$row['course_name']."</td>
                                    <td>".$row['course_code']."</td>
                                    <td>".$row['description']."</td>
                                    
                                    <td>
                                        <div class='action-buttons'>
                                            <button class='btn-edit' onclick='editCourse(".$row['course_id'].")'><span style='font-size:1.1em;'>✏️</span> Edit</button>
                                            <button class='btn-delete' onclick='deleteCourse(".$row['course_id'].")'><span style='font-size:1.1em;'>🗑️</span> Delete</button>
                                        </div>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- JavaScript for functionality -->
    <script>
        // Get the modals
        var addModal = document.getElementById("addCourseModal");
        var editModal = document.getElementById("editCourseModal");
        var addSpan = document.querySelector("#addCourseModal .close");
        var editSpan = document.querySelector("#editCourseModal .close");

        // Open add modal function
        function openModal() {
            addModal.style.display = "block";
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        // Open edit modal function
        function editCourse(courseId) {
            // Fetch course data
            fetch('get_course.php?id=' + courseId)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Fill the form with course data
                        document.getElementById('edit_course_id').value = data.course.course_id;
                        document.getElementById('edit_course_name').value = data.course.course_name;
                        document.getElementById('edit_course_code').value = data.course.course_code;
                        document.getElementById('edit_description').value = data.course.description;
                        
                        // Show the modal
                        editModal.style.display = "block";
                        document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    } else {
                        alert('Error fetching course data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error fetching course data. Please try again.');
                });
        }

        // Close modals functions
        function closeAddModal() {
            addModal.style.display = "none";
            document.body.style.overflow = ''; // Restore scrolling
        }

        function closeEditModal() {
            editModal.style.display = "none";
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close modals when clicking (x)
        addSpan.onclick = closeAddModal;
        editSpan.onclick = closeEditModal;

        // Handle add form submission
        document.getElementById('addCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('process_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Course added successfully!');
                    closeAddModal();
                    location.reload();
                } else {
                    alert('Error adding course: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding course. Please try again.');
            });
        });

        // Handle edit form submission
        document.getElementById('editCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'update');
            
            fetch('process_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Course updated successfully!');
                    closeEditModal();
                    location.reload();
                } else {
                    alert('Error updating course: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating course. Please try again.');
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('.inventory-table tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });

        // Delete course function
        function deleteCourse(courseId) {
            if(confirm('Are you sure you want to delete this course?')) {
                const formData = new FormData();
                formData.append('course_id', courseId);
                
                fetch('delete_course.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Course deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting course: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting course. Please try again.');
                });
            }
        }
    </script>
         <?php include 'components/top_bar.php'; ?>
</body>
</html>
    