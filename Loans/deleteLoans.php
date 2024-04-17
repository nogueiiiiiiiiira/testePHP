<?php

if( isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "testephp";

    $connection = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM loans WHERE id=$id";
    $connection->query($sql);
}

header("location: /testephp/Loans/loans.php");
exit;

?>