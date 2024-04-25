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

    if (empty($title) || empty($cpfReader)) {
        $errorMessage = "All the fields are required";
    } else {
        // Verifica se existe um empréstimo associado ao título e ao CPF fornecidos
        $stmt = $connection->prepare("SELECT title, id, created_at, returnForecast FROM loans WHERE title = ? AND cpfReader = ?");
        $stmt->bind_param("ss", $title, $cpfReader);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($loanTitle, $idLoan, $dateLoan, $returnForecast);
            $stmt->fetch();

            $returnDate = date('Y-m-d');
            $daysOverdue = $returnDate - $dateLoan;

            if ($returnDate > $returnForecast) {
                $reason = "Return made correctly";
                $fine = "None";
            } elseif ($returnDate < $returnForecast) {
                $reason = "Return made late";
                $status = "Attributed";
                $fine = "Attributed";
                $price = 1 * $daysOverdue;

                $sql = "INSERT INTO fines(title, cpfReader, daysLate, reason, status, price) VALUES ('$title', '$cpfReader', '$daysOverdue', '$reason', '$status', '$price')";
                $connection->query($sql);

                $successMessage = "Fine attributed";
            }

            $stmt = $connection->prepare("INSERT INTO returns(title, cpfReader, idLoan, dateLoan, returnForecast, status, fine) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $loanTitle, $cpfReader, $idLoan, $dateLoan, $returnForecast, $reason, $fine);

            if ($stmt->execute()) {
                // Atualiza o estoque do livro
                $updateStockSql = "UPDATE books SET stock = stock + 1 WHERE title = ?";
                $updateStockStmt = $connection->prepare($updateStockSql);
                $updateStockStmt->bind_param("s", $title);
                $updateStockStmt->execute();

                $successMessage = "Return added correctly and stock updated";
                header("location: /testephp/Returns/returns.php");
                exit;
            } else {
                $errorMessage = "Error inserting return data: " . $connection->error;
            }
        } else {
            $errorMessage = "No loan found for the provided title and CPF reader";
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
        <h2>+ New Return</h2>

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
            if (!empty($successMessage)) {
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
                    <a href="/testephp/Returns/returns.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
