<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once './session_check.php';
    ?>
    <title>Borrow Books - Library Management System</title>
    <?php include './components/head.php'; ?>
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
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-success {
            background-color: #22c55e;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
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
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
            margin: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .status-available {
            color: #22c55e;
            font-weight: 500;
        }

        .status-borrowed {
            color: #ef4444;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <?php include './components/sidebar.php'; ?>
    
    <div class="main-container">
  

        <div class="page-header">
            <h1>Return Books</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="searchBooks()">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Return
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>StudentID</th>
                        <!-- <th>BookID</th> -->
                        <th>Student Name</th>
                        <th>Title</th>
                        <!-- <th>Course</th> -->
                        <!-- <th>Borrow Date</th> -->
                        <th>Return Date</th>
                        <!-- <th>Status</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTableBody">
                    <?php
                    require("./db.php");
                    
                    $sql = "select * from vreturn";
                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo "<tr><td colspan='7' class='text-center'>Error executing query: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows === 0) {
                        echo "<tr><td colspan='7' class='text-center'>No books found in the database</td></tr>";
                    } else {
                        $counter = 1;
                        while($row = $result->fetch_assoc()) {
                            $statusClass = $row['status'] === 'Available' ? 'status-available' : 'status-borrowed';
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['first_name'] + ' ' + $row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            // echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                            // echo "<td>" . htmlspecialchars($row['borrow_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['return_date']) . "</td>";
                            echo "</tr>";
                            $counter++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Borrow Book Modal -->
    <div id="borrowBookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Borrow Book</h2>
                <button class="close-btn" onclick="closeBorrowModal()">&times;</button>
            </div>
            <form id="borrowBookForm" method="POST" action="process_borrow.php">
                <input type="hidden" id="borrow_book_id" name="book_id">
                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="borrow_date">Borrow Date</label>
                    <input type="date" id="borrow_date" name="borrow_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="return_date">Return Date</label>
                    <input type="date" id="return_date" name="return_date" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeBorrowModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <!-- New Borrow Modal -->
    <div id="newBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Borrow</h2>
                <button class="close-btn" onclick="closeNewBorrowModal()">&times;</button>
            </div>
            <form id="newBorrowForm" method="POST" action="process_borrow.php">
                <div class="form-group">
                    <label for="new_student_id">Student</label>
                    <select id="new_student_id" name="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        <?php
                        $student_sql = "SELECT * FROM tblstudents ORDER BY first_name, last_name";
                        $student_result = $conn->query($student_sql);
                        while($student = $student_result->fetch_assoc()) {
                            echo "<option value='" . $student['student_id'] . "'>" . 
                                 htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_book_id">Book</label>
                    <select id="new_book_id" name="book_id" class="form-control" required>
                        <option value="">Select Book</option>
                        <?php
                        $book_sql = "SELECT b.* FROM tblbooks b 
                                   LEFT JOIN tblborrower br ON b.book_id = br.book_id AND br.return_date IS NULL 
                                   WHERE br.borrower_id IS NULL 
                                   ORDER BY b.title";
                        $book_result = $conn->query($book_sql);
                        while($book = $book_result->fetch_assoc()) {
                            echo "<option value='" . $book['book_id'] . "'>" . 
                                 htmlspecialchars($book['title']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_borrow_date">Borrow Date</label>
                    <input type="date" id="new_borrow_date" name="borrow_date" class="form-control" required 
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="new_return_date">Return Date</label>
                    <input type="date" id="new_return_date" name="return_date" class="form-control" required 
                           value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeNewBorrowModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Search function
        function searchBooks() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();
            var table = document.getElementById("booksTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var found = false;
                var td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length - 1; j++) {
                    if (td[j]) {
                        var txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }

        
    const modal = document.getElementById("formModal");
    const btn = document.getElementById("openModal");
    const span = document.querySelector(".close");

    btn.onclick = () => modal.style.display = "block";
    span.onclick = () => modal.style.display = "none";
    window.onclick = (event) => {
      if (event.target == modal) modal.style.display = "none";
    }

    document.getElementById("borrowerForm").onsubmit = function(e) {    
      e.preventDefault();
      alert("Form submitted!");
      modal.style.display = "none";
      this.reset();
    }
    
  </script>
        <?php include './components/top_bar.php'; ?>
</body>
</html>
    