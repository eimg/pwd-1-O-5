<?php

$name = $_POST['name'];
$value = $_POST['value'];

$db = new PDO('mysql:host=localhost;dbname=project', 'root', '');

$sql = "INSERT INTO roles (name, value) VALUES (:name, :value)";

$statement = $db->prepare($sql);
$statement->execute(["name" => $name, "value" => $value]);

header("location: index.php");
