<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    

    <?php
// Include session check
require_once 'session_check.php';
include 'components/head.php';
?>
</head>
<body>
    
<?php include 'components/sidebar.php'; ?>
    
<div class="ml-64">
    <?php 
    include 'components/dashboard_stats.php';
    
    ?>
</div>


<?php include 'components/top_bar.php'; ?>
<script src="js/charts.js"></script>
</body>
</html>