<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    // Include session check
    require_once 'session_check.php';
    include 'components/head.php';
    ?>
</head>
<body class="bg-gray-100">
    <?php include 'components/sidebar.php'; ?>
    
    <div class="ml-64">
        <?php 
 
        include 'components/dashboard_stats.php';
        include 'components/dashboard_charts.php';
 //       include 'components/top_bar.php';
        ?>
    </div>

    <script src="js/charts.js"></script>
</body>
</html>