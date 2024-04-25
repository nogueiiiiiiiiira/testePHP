<?php

include('protect.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Painel</title>
</head>
<body>
<div class="gap">
        <nav>
            <ul class="gap" >
                <li class="dropDown">
                    <a>LIBRARIANS</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Librarians/librarians.php">Librarians</a>
                        <br />
                    </div>
                </li>
                <li class="dropDown">
                    <a>BOOKS</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Books/books.php">Books</a>
                        <br />
                    </div>
                </li>
                <li class="dropDown">
                    <a>READERS</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Readers/readers.php">Readers</a>
                        <br />
                    </div>
                </li>
                <li class="dropDown">
                    <a>LOANS</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Loans/loans.php">Loans</a>
                        <br />
                    </div>
                </li>
                <li class="dropDown">
                    <a>RETURNS</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Returns/returns.php">Returns</a>
                        <br />
                    </div>
                </li>
                <li class="dropDown">
                    <a>FINES</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Fines/fines.php">Fines</a>
                        <br />
                    </div>
                </li>
                <li class="dropDown">
                    <a>ACTIONS</a>
                    <div class="dropdownMenu" >
                        <a href="/testephp/Login/logout.php">Log Out</a>
                        <br />
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>