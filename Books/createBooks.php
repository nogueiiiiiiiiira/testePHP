<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$title = "";
$author = "";
$category = "";
$stock = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $category = $_POST["category"];
    $stock = $_POST["stock"];

    if (empty($title) || empty($author) || empty($category) || empty($stock)) {
        $errorMessage = "All the fields are required";
    } else {
        $checkSql = "SELECT * FROM books WHERE title='$title' AND author='$author' AND category='$category'";
        $checkResult = $connection->query($checkSql);

        if ($checkResult->num_rows > 0) {
            // Se o livro já existir, adicione a quantidade ao estoque existente
            
            $row = $checkResult->fetch_assoc();
            $currentStock = $row["stock"];
            $newStock = $currentStock + $stock;

            $updateSql = "UPDATE books SET stock='$newStock' WHERE title='$title' AND author='$author' AND category='$category'";
            $updateResult = $connection->query($updateSql);

            if (!$updateResult) {
                $errorMessage = "Failed to update stock: " . $connection->error;
            } else {
                $successMessage = "Stock updated successfully";
                header("location: /testephp/Books/books.php");
                exit;
            }
        } else {
            // Se o livro não existir, insira-o no banco de dados
            $sql = "INSERT INTO books(title, author, category, stock) " .
                "VALUES ('$title', '$author', '$category', '$stock')";
            $result = $connection->query($sql);

            if (!$result) {
                $errorMessage = "Invalid query: " . $connection->error;
            } else {
                $successMessage = "Book added correctly";
                header("location: /testephp/Books/books.php");
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
        <h2>+ New Book</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                ";
        }
        ?>

        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="author" value="<?php echo $author; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Category</label>
                <div class="col-sm-6">
                    <select name="category" id="category" class="form-control">
                        <option value="">Select the category:</option>
                        <option value="Ficção">Ficção</option>
                        <option value="Não-Ficção">Não Ficção</option>
                        <option value="Aventura">Aventura</option>
                        <option value="Romance">Romance</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Stock</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="stock" value="<?php echo $stock; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/testephp/Books/books.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
