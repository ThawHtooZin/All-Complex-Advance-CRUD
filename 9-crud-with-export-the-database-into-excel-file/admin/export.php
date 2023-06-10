<?php
// Load the database configuration file
include_once 'config/connect2.php';

// Filter the excel data
function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// Excel file name for download
$fileName = "users-data_" . date('Y-m-d') . ".xls";

// Column names
$fields = array('id', 'username', 'password', 'email', 'role', 'date');

// Display column names as first row
$excelData = implode("\t", array_values($fields)) . "\n";

// Fetch records from database
$query = $db->query("SELECT * FROM advance2_crud ORDER BY id ASC");
if($query->num_rows > 0){
    // Output each row of the data
    while($row = $query->fetch_assoc()){
        $lineData = array($row['id'], $row['username'], $row['password'], $row['email'], $row['role'], $row['date']);
        array_walk($lineData, 'filterData');
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
}else{
    $excelData .= 'No records found...'. "\n";
}

// Headers for download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data
echo $excelData;

echo "<script>alert('exported successfully!') window.location.href='index.php'</script>";
