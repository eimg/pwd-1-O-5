<?php

$name = $_POST['name'];
$value = $_POST['value'];

$sql = "INSERT INTO roles (name, value) VALUES ('$name', $value)";
echo $sql;

$db = new PDO('mysql:host=localhost;dbname=project', 'root', '');
$db->query($sql);

header("location: index.php");
