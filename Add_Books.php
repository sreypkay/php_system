<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once 'session_check.php';
    ?>
    <title>Books Management - Library Management System</title>
    <?php include 'components/head.php'; ?>
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

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background-color: #4f46e5;
            
            color: white;
            border: none;
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem;
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

        .book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
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
            pointer-events: auto; /* Ensure modal content can be clicked */
        }

        /* Add overlay to prevent clicks outside modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
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

        .close-btn:hover {
            color: #333;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .modal-buttons button {
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .modal-buttons .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .modal-buttons .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .modal-buttons button:hover {
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.375rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        #coverPreview {
            max-width: 100px;
            margin-top: 0.5rem;
            display: none;
        }

        .btn-submit {
            width: 100%;
            margin-top: 1rem;
        }

        /* Action buttons styles */
        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
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
            background-color: #4f46e5 !important;
            color: #fff;
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

        .btn-add {
            background-color:rgb(31, 5, 177);
            color: #fff;
            border: none;
            border-radius: 0.375rem;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }
        .btn-add:hover {
          
            background-color: #3730a3;
        }
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    
    <div class="main-container">
     

        <div class="page-header">
            <h1> Books Management</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="searchBooks()">
                <button class="btn btn-primary" style="  background-color:rgb(25, 80, 231); color: #fff; border: none;" onclick="openAddBookModal()">
                    <i class="fas fa-plus mr-2"></i> Add New Book
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <!-- <th>ISBN</th> -->
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTableBody">
                    <?php
                    require("db.php");
                    
                    // Check table structure
                    $check_table = "DESCRIBE tblbooks";
                    $table_result = $conn->query($check_table);
                    
                    if (!$table_result) {
                        echo "<tr><td colspan='7' class='text-center'>Error checking table structure: " . $conn->error . "</td></tr>";
                    } else {
                        echo "<!-- Table structure: ";
                        while($row = $table_result->fetch_assoc()) {
                            echo $row['Field'] . " (" . $row['Type'] . "), ";
                        }
                        echo " -->";
                    }
                    
                    // Add error handling for the query
                    $sql = "SELECT * FROM tblbooks ORDER BY book_id ASC";
                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo "<tr><td colspan='7' class='text-center'>Error executing query: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows === 0) {
                        echo "<tr><td colspan='7' class='text-center'>No books found in the database</td></tr>";
                    } else {
                        $counter = 1; // Add counter for sequential display
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>"; // Display sequential number
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['publisher']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td class='actions'>
                                    <button onclick='openViewModal(" . htmlspecialchars($row['book_id']) . ")' class='btn btn-info btn-sm'>
                                        <span style='font-size:1.1em;'>👁️</span> View
                                    </button>
                                    <button onclick='openEditModal(" . htmlspecialchars($row['book_id']) . ")' class='btn btn-primary btn-sm'>
                                        <span style='font-size:1.1em;'>✏️</span> Edit
                                    </button>
                                    <button onclick='openDeleteModal(" . htmlspecialchars($row['book_id']) . ")' class='btn btn-danger btn-sm'>
                                        <span style='font-size:1.1em;'>🗑️</span> Delete
                                    </button>
                                  </td>";
                            echo "</tr>";
                            $counter++; // Increment counter
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Book Modal -->
    <div id="viewBookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Book Details</h2>
                <button class="close-btn" onclick="closeViewModal()">&times;</button>
            </div>
            <div id="viewBookContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div id="editBookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Book</h2>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editBookForm" method="POST" action="Book/editbook.php">
                <input type="hidden" id="edit_book_id" name="book_id">
                <div class="form-group">
                    <label for="edit_title">Title</label>
                    <input type="text" id="edit_title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_author">Author</label>
                    <input type="text" id="edit_author" name="author" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_publisher">Publisher</label>
                    <input type="text" id="edit_publisher" name="publisher" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_category">Category</label>
                    <input type="text" id="edit_category" name="category" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Book Modal -->
    <div id="deleteBookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Book</h2>
                <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
            </div>
            <p>Are you sure you want to delete this book?</p>
            <div class="modal-buttons">
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
                <button onclick="window.location.href='Book/deletebook.php?book_id=' + bookToDelete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>

    <!-- Add New Book Modal -->
    <div id="addBookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Book</h2>
                <button class="close-btn" onclick="closeAddModal()">&times;</button>
            </div>
            <form id="addBookForm" method="POST" action="Book/addnewbook.php">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Publisher</label>
                    <input type="text" id="publisher" name="publisher" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Book</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // View Book Modal Functions
        function openViewModal(bookId) {
            // Fetch book details using AJAX
            fetch('Book/getbook.php?book_id=' + bookId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('viewBookContent').innerHTML = `
                        <p><strong>Book ID:</strong> ${data.book_id}</p>
                        <p><strong>Title:</strong> ${data.title}</p>
                        <p><strong>Author:</strong> ${data.author}</p>
                        <p><strong>Publisher:</strong> ${data.publisher}</p>
                        <p><strong>Category:</strong> ${data.category}</p>
                    `;
                    document.getElementById('viewBookModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewBookModal').style.display = 'none';
        }

        // Edit Book Modal Functions
        function openEditModal(bookId) {
            // Fetch book details using AJAX
            fetch('Book/getbook.php?book_id=' + bookId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_book_id').value = data.book_id;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_author').value = data.author;
                    document.getElementById('edit_publisher').value = data.publisher;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('editBookModal').style.display = 'flex';
                })
                .catch(error => {
                    alert('Error loading book details: ' + error);
                });
        }

        function closeEditModal() {
            document.getElementById('editBookModal').style.display = 'none';
        }

        // Delete Book Modal Functions
        let bookToDelete = null;

        function openDeleteModal(bookId) {
            bookToDelete = bookId;
            document.getElementById('deleteBookModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteBookModal').style.display = 'none';
            bookToDelete = null;
        }

        // Add New Book Modal Functions
        function openAddBookModal() {
            document.getElementById('addBookModal').style.display = 'flex';
            // Clear form fields
            document.getElementById('addBookForm').reset();
        }

        function closeAddModal() {
            document.getElementById('addBookModal').style.display = 'none';
        }

        // Search Function
        function searchBooks() {
            const searchInput = document.getElementById('searchInput');
            const filter = searchInput.value.toLowerCase();
            const tbody = document.getElementById('booksTableBody');
            const rows = tbody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                // Search in all cells including the ID
                for (let j = 0; j < cells.length - 1; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const text = cell.textContent || cell.innerText;
                        if (text.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }

                // Show/hide row based on search
                row.style.display = found ? '' : 'none';
            }
        }

        // Add event listener for search input
        document.getElementById('searchInput').addEventListener('keyup', searchBooks);
    </script>
       <?php include 'components/top_bar.php'; ?>
</body>
</html>

