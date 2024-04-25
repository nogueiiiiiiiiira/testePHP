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
        <p>Are you sure that you want to delete this reader?</p>
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
        <h2>List of Readers</h2>
        <br>
        <a class="btn btn-primary" href="/testephp/Readers/createReaders.php" role="button">+ New Reader</a>
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
                    

                    $sql = "SELECT * FROM readers";
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
                                <a href='/testephp/Readers/editReaders.php?id=$row[id]' class='btn btn-primary btn-sm'>Edit</a>
                                <button class='btn btn-danger btn-sm delete-btn' data-url='/testephp/Readers/deleteReaders.php?id=$row[id]'>Delete</button>
                            </td>
                        </tr>
                        ";
                    }
                    ?>
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