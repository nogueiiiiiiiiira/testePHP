<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

$title = "";
$cpfReader = "";
$idLoan = "";
$dateLoan = "";
$status = "";
$fine = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST["title"];
    $cpfReader = $_POST["cpfReader"];
    $idLoan = $_POST["idLoan"];
    $dateLoan = $_POST["dateLoan"];
    $status = $_POST["status"];
    $fine = $_POST["fine"];

    if (empty($title) || (empty($cpfReader) && empty($idLoan))) {
        $errorMessage = "Preencha o título do livro e o CPF do leitor ou preencha o identificador do empréstimo.";
    } else {
        $returnForecastQuery = "SELECT returnForecast FROM loans WHERE cpfReader = '$cpfReader' AND title = '$title'";
        $returnForecastResult = $connection->query($returnForecastQuery);

        if (!$returnForecastResult) {
            $errorMessage = "Erro ao executar a consulta: " . $connection->error;
        } else {
            $row = $returnForecastResult->fetch_assoc();
            $returnForecast = $row['returnForecast'];

            if ($returnForecast < date('Y-m-d')) {
                $status = "Return made late";
                $fine = "Attributed";
            } elseif ($returnForecast >= date('Y-m-d')) {
                $status = "Return made successfully";
                $fine = "None";
            }

            $dateLoan = "SELECT created_at FROM loans WHERE cpfReader = '$cpfReader' AND title = '$title' ";
            $sql = "INSERT INTO returnbooks(title, cpfReader, idLoan, dateLoan, status, fine) VALUES ('$title', '$cpfReader', '$idLoan', '$dateLoan', '$status', '$fine')";
            $result = $connection->query($sql);

            if (!$result) {
                $errorMessage = "Consulta inválida: " . $connection->error;
            } else {
                $successMessage = "Empréstimo adicionado corretamente";
                header("Location: /testephp/Returns/returns.php");
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
            <br>
            <h2>OR</h2>
            <br>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Loan ID</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="idLoan" value="<?php echo $idLoan; ?>">
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
