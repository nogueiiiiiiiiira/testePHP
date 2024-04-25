<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$title = "";
$cpfReader = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["id"])) {
        header("location: /testephp/Loans/loans.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM loans WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    $title = $row["title"];
    $cpfReader = $row["cpfReader"];
} else {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $cpfReader = $_POST["cpfReader"];

    // Consulta SQL para verificar se o livro existe
    $bookSql = "SELECT * FROM books WHERE title='$title'";
    $bookResult = $connection->query($bookSql);

    // Consulta SQL para verificar se o leitor existe
    $readerSql = "SELECT * FROM readers WHERE cpf='$cpfReader'";
    $readerResult = $connection->query($readerSql);

    if ($bookResult->num_rows == 0 || $readerResult->num_rows == 0) {
        $errorMessage = "Book or reader does not exist. With this data, it is not possible to edit the loan";
    } else {
        // Se o livro e o leitor existirem, atualize o empréstimo
        $sql = "UPDATE loans " .
            "SET title = '$title', cpfReader = '$cpfReader' " .
            "WHERE id = $id ";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Loan updated correctly";
            header("location: /testephp/Loans/loans.php");
            exit;
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div id="confirm" class="confirm-modal" style="display: none;">
        <label>Confirm Action</label>
        <br>
        <br>
        <p>Are you sure that you want to edit this loan?</p>
        <button id="confirm-yes" class='btn btn-primary btn-sm'>Yes</button>
        <button id="confirm-no" class='btn btn-danger btn-sm'>No</button>
    </div>
    <div class="container my-5">
        <h2>Edit Loan</h2>

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

        <form id="editForm" method="post" >
            <input type="hidden" name="id" value="<?php echo $id; ?>" >
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
                    <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/testephp/Loans/loans.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('submitBtn').addEventListener('click', function() {
            document.getElementById('confirm').style.display = 'block';

            document.getElementById('confirm-yes').onclick = function() {
                document.getElementById('editForm').submit();
            };

            document.getElementById('confirm-no').onclick = function() {
                document.getElementById('confirm').style.display = 'none';
            };
        });
    </script>
</body>
</html>