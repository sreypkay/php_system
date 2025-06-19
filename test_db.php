<?php
require("db.php");

// Get table structure
$sql = "DESCRIBE tbluser";
$result = $conn->query($sql);

if ($result) {
    echo "<h3>Table Structure:</h3>";
    while($row = $result->fetch_assoc()) {
        echo "Field: " . $row['Field'] . " | Type: " . $row['Type'] . "<br>";
    }
} else {
    echo "Error getting table structure: " . $conn->error;
}

// Get sample data (without showing passwords)
$sql = "SELECT userid, username, fullname FROM tbluser LIMIT 1";
$result = $conn->query($sql);

if ($result) {
    echo "<h3>Sample Data:</h3>";
    while($row = $result->fetch_assoc()) {
        echo "UserID: " . $row['userid'] . " | Username: " . $row['username'] . " | Fullname: " . $row['fullname'] . "<br>";
    }
} else {
    echo "Error getting sample data: " . $conn->error;
}
?> 