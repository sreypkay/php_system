<?php
session_start();
require_once 'session_check.php';
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/head.php'; ?>
    <style>
        .form-container {
            padding: 20px;
            margin: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h2 {
            color: #2d3748;
            font-size: 24px;
            font-weight: 600;
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
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-cancel {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-cancel:hover {
            background-color: #cbd5e0;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #f0fff4;
            color: #2f855a;
            border: 1px solid #c6f6d5;
        }

        .alert-error {
            background-color: #fff5f5;
            color: #c53030;
            border: 1px solid #fed7d7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'components/sidebar.php'; ?>
    
    <div class="ml-64">
        <?php include 'components/top_bar.php'; ?>
        
        <div class="form-container">
            <div class="form-header">
                <h2>Add New Course</h2>
            </div>

            <?php
            if(isset($_SESSION['message'])) {
                $messageClass = $_SESSION['message_type'] == 'success' ? 'alert-success' : 'alert-error';
                echo "<div class='alert {$messageClass}'>{$_SESSION['message']}</div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>

            <form action="process_course.php" method="POST">
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
                    <button type="submit" name="add_course" class="btn-submit">
                        <i class="fas fa-save mr-2"></i>Save Course
                    </button>
                    <button type="button" class="btn-cancel" onclick="window.location.href='inventory.php'">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
