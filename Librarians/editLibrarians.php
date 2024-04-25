<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$name = "";
$email = "";
$cpf = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["id"])) {
        header("location: /testephp/Librarians/librarians.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM librarians WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    $name = $row["name"];
    $email = $row["email"];
    $cpf = $row["cpf"];
    $phone = $row["phone"];
    $address = $row["address"];

} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];


    if (empty($name) || empty($email) || empty($cpf) || empty($phone) || empty($address)) {
        $errorMessage = "All fields are required";
    } else {
        $checkSql = "SELECT * FROM readers WHERE cpf=? OR phone=? OR email=?";
        $stmt = $connection->prepare($checkSql);
        $stmt->bind_param("sss", $cpf, $phone, $email);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        $checkSql2 = "SELECT * FROM librarians WHERE cpf=? OR phone=? OR email=?";
        $stmt2 = $connection->prepare($checkSql2);
        $stmt2->bind_param("sss", $cpf, $phone, $email);
        $stmt2->execute();
        $checkResult2 = $stmt2->get_result();

        if ($checkResult->num_rows > 0 || $checkResult2->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            if ($row) {
                $errorMessage = "The CPF: $cpf already exists in the database";
            } else {
                $row = $checkResult->fetch_assoc();
                if ($row) {
                    $errorMessage = "The phone number: $phone already exists in the database";
                } else {
                    $errorMessage = "The email: $email already exists in the database";
                }
            }
        } else {
            $sql = "UPDATE librarians " .
                "SET name = '$name', email = '$email', cpf = '$cpf', phone = '$phone', address = '$address' " .
                "WHERE id = $id ";

            $result = $connection->query($sql);

            if (!$result) {
                $errorMessage = "Invalid query: " . $connection->error;
            } else {
                $successMessage = "Librarian updated correctly";

                header("location: /testephp/Librarians/librarians.php");
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
        <p>Are you sure that you want to edit this librarian?</p>
        <button id="confirm-yes" class='btn btn-primary btn-sm'>Yes</button>
        <button id="confirm-no" class='btn btn-danger btn-sm'>No</button>
    </div>
    <div class="container my-5">
        <h2>Edit Librarian</h2>

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
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">CPF</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="cpf" value="<?php echo $cpf; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
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
                    <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/testephp/Librarians/librarians.php" class="btn btn-outline-primary" role="button">Cancel</a>
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
