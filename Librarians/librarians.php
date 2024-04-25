<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

if($connection->connect_error){
    die("Connection failed: ". $connection->connect_error);
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM librarians WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR cpf LIKE '%$search%'";
    $result = $connection->query($sql);
    if (!$result) {
        die("Invalid query: ". $connection->error);
    }
} else {
    $sql = "SELECT * FROM librarians";
    $result = $connection->query($sql);
    if (!$result) {
        die("Invalid query: ". $connection->error);
    }
}
?>

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
    <div id="confirm" class="confirm-modal" style="display: none;">
        <label>Confirm Action</label>
        <br>
        <br>
        <p>Are you sure that you want to delete this librarian?</p>
        <button id="confirm-yes" class='btn btn-primary btn-sm'>Yes</button>
        <button id="confirm-no" class='btn btn-danger btn-sm' >No</button>
    </div>
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
    <div class="container my-5">
        <h2>List of Librarians</h2>
        <br>
        <a class="btn btn-primary" href="/testephp/Librarians/createLibrarians.php" role="button">+ New Librarian</a>
        <form class="search" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
            <input type="text" name="search" placeholder="Search for a librarian: ">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
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
        <?php while($row = $result->fetch_assoc()) {?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['email'];?></td>
                <td><?php echo $row['cpf'];?></td>
                <td><?php echo $row['phone'];?></td>
                <td><?php echo $row['address'];?></td>
                <td><?php echo $row['created_at'];?></td>
                <td>
                    <a href='/testephp/Librarians/editLibrarians.php?id=<?php echo $row['id'];?>' class='btn btn-primary btn-sm'>Edit</a>
                    <button class='btn btn-danger btn-sm delete-btn' data-url='/testephp/Librarians/deleteLibrarians.php?id=<?php echo $row['id'];?>'>Delete</button>
                </td>
            </tr>
        <?php }?>
    </tbody>
</table>
    </div>
    <script>
        // Função para exibir o modal de confirmação
        function showConfirmationModal(url) {
            document.getElementById('confirm').style.display = 'block';
            
            // Se o botão "Yes" for clicado, redirecionar para a URL de exclusão
            document.getElementById('confirm-yes').onclick = function() {
                window.location.href = url;
            };

            // Se o botão "No" for clicado, fechar o modal de confirmação
            document.getElementById('confirm-no').onclick = function() {
                document.getElementById('confirm').style.display = 'none';
            };
        }

        // Adicionar evento de clique aos botões "Delete"
        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var deleteUrl = button.getAttribute('data-url');
                showConfirmationModal(deleteUrl);
            });
        });
    </script>
</body>
</html>