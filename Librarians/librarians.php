<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Nova</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
            </ul>
        </nav>
    </div>
    <div class="container my-5">
        <h2>List of Librarians</h2>
        <br>
        <a class="btn btn-primary" href="/testephp/Librarians/createLibrarians.php" role="button">+ New Librarian</a>
        <table class="table">
            <br>
            <br>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "testephp";

                    $connection = new mysqli($servername, $username, $password, $database);

                    if($connection->connect_error){
                        die("Connection failed: " . $connection->connect_error);
                    }
                    

                    $sql = "SELECT * FROM librarians";
                    $result = $connection-> query($sql);

                    if(!$result){
                        die("Invalid query: " . $connection->error);
                    }

                    while($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[name]</td>
                            <td>$row[email]</td>
                            <td>$row[cpf]</td>
                            <td>$row[phone]</td>
                            <td>$row[address]</td>
                            <td>$row[created_at]</td>
                            <td>
                                <a href='/testephp/Librarians/editLibrarians.php?id=$row[id]' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/testephp/Librarians/deleteLibrarians.php?id=$row[id]' class='btn btn-danger btn-sm' >Delete</a>
                            </td>
                        </tr>
                        ";
                    }
                    ?>
            </tbody>
        </table>
    </div>
</body>
</html>