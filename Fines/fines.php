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
    <div>
        
    </div>
    <div class="container my-5">
        <h2>List of Fines</h2>
        <br>
        <a class="btn btn-primary" href="/testephp/Readers/createReaders.php" role="button">+ New Reader</a>
        <table class="table">
            <br>
            <br>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book's Title</th>
                    <th>Reader's CPF</th>
                    <th>Days Overdue</th>
                    <th>Status</th>
                    <th>Price</th>
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
                    

                    $sql = "SELECT * FROM fines";
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
                            <td>$row[daysLate]</td>
                            <td>$row[status]</td>
                            <td>$row[price]</td>
                            <td>$row[created_at]</td>
                            <td>
                                <a href='/testephp/Fines/editFines.php?id=$row[id]' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/testephp/Fines/deleteFines.php?id=$row[id]' class='btn btn-danger btn-sm' >Delete</a>
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