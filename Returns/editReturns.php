<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);


$title = "";
$cpfReader = "";
$id = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["id"])) {
        header("location: /testephp/Returns/returns.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM returns WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if ($result->num_rows === 0) {
        $errorMessage = "No return found for the provided ID";
    } else {
        $title = $row["title"];
        $cpfReader = $row["cpfReader"];
    }

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $cpfReader = $_POST["cpfReader"];

    // Verifica se existe um empréstimo associado ao título e ao CPF fornecidos
    $stmt = $connection->prepare("SELECT * FROM loans WHERE title = ? AND cpfReader = ?");
    $stmt->bind_param("ss", $title, $cpfReader);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $errorMessage = "No loan found for the provided title and CPF reader";
    } else {
        if (empty($title) || empty($cpfReader)) {
            $errorMessage = "All the fields are required";
        } else {
            $sql = "UPDATE returns " .
                    "SET title = '$title', cpfReader = '$cpfReader' " . 
                    "WHERE id = $id ";

            $result = $connection->query($sql);

            if (!$result) {
                $errorMessage = "Invalid query: " . $connection->error;
            } else {
                $successMessage = "Return updated correctly";
                header("location: /testephp/Returns/returns.php");
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div id="confirm" class="confirm-modal" style="display: none;">
        <label>Confirm Action</label>
        <br>
        <br>
        <p>Are you sure that you want to edit this return?</p>
        <button id="confirm-yes" class='btn btn-primary btn-sm'>Yes</button>
        <button id="confirm-no" class='btn btn-danger btn-sm'>No</button>
    </div>
    <div class="container my-5">
    <h2>Edit Return</h2>

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

        <form id="editForm" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">CPF</label>
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
