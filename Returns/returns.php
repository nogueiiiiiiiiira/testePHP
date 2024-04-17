<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Nova</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>List of Returns</h2>
        <br>
        <a class="btn btn-primary" href="/testephp/Returns/createReturns.php" role="button">+ New Return</a>
        <table class="table">
            <br>
            <br>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book's Title</th>
                    <th>Reader's CPF</th>
                    <th>Loan's ID</th>
                    <th>Loan's Date</th>
                    <th>Returns At</th>
                    <th>Status</th>
                    <th>Fine</th>
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
                    

                    $sql = "SELECT * FROM returnbooks";
                    $result = $connection-> query($sql);

                    if(!$result){
                        die("Invalid query: " . $connection->error);
                    }

                    while($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[title]</td>
                            <td>$row[cpfReader]</td>
                            <td>$row[idLoan]</td>
                            <td>$row[dateLoan]</td>
                            <td>$row[created_at]</td>
                            <td>$row[status]</td>
                            <td>$row[fine]</td>
                            <td>
                                <a href='/testephp/Returns/editReturns.php?id=$row[id]' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/testephp/Returns/deleteReturns.php?id=$row[id]' class='btn btn-danger btn-sm' >Delete</a>
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