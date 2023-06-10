<?php
include 'connect.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM basic_crud WHERE id=$id");
$stmt->execute();
echo "<script>alert('Deleted Successfully!'); window.location.href='index.php';</script>";

 ?>
