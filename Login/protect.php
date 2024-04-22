<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['id'])) {
    die("You can't acess this page.<p><a href=\"index.php\">Enter</a></p>");
}


?>