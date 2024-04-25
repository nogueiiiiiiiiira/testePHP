<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

$title = "";
$cpfReader = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST["title"];
    $cpfReader = $_POST["cpfReader"];

    // Consulta SQL para verificar se o livro existe e se há estoque disponível
    $bookSql = "SELECT * FROM books WHERE title='$title' AND stock > 0";
    $bookResult = $connection->query($bookSql);

    // Consulta SQL para verificar se o leitor existe
    $readerSql = "SELECT * FROM readers WHERE cpf='$cpfReader'";
    $readerResult = $connection->query($readerSql);

    if ($bookResult->num_rows == 0 || $readerResult->num_rows == 0) {
        $errorMessage = "Book or reader does not exist, or there is no available stock for this book.";
    } else {
        // Se o livro e o leitor existirem, proceda com o empréstimo
        $returnForecast = date('Y-m-d', strtotime('+7 days'));

        $sql = "INSERT INTO loans(title, cpfReader, returnForecast) " .
            "VALUES ('$title', '$cpfReader', '$returnForecast')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            // Atualiza o estoque do livro
            $bookRow = $bookResult->fetch_assoc();
            $currentStock = $bookRow['stock'];
            $newStock = $currentStock - 1;
            $updateSql = "UPDATE books SET stock = $newStock WHERE title = '$title'";
            $updateResult = $connection->query($updateSql);

            if (!$updateResult) {
                // Se a atualização do estoque falhar, você pode querer lidar com isso de forma adequada
                $errorMessage = "Failed to update stock";
            } else {
                $successMessage = "Loan added correctly";
                header("location: /testephp/Loans/loans.php");
                exit;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>+ New Loan</h2>

        <?php
            if( !empty($errorMessage) ) {
                echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                ";
            }
        ?>

        <form method="post" >
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Book Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Reader CPF</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="cpfReader" value="<?php echo $cpfReader; ?>">
                </div>
            </div> 

            <?php
                if( !empty($successMessage) ) {
                    echo "
                        <div class='row mb-3'>
                            <div class='offset-sm-3 col-sm-6'>
                                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>$successMessage</strong>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            </div>
                        </div>
                    ";
                }
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/testephp/Loans/loans.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
