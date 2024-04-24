<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);

$name = "";
$email = "";
$cpf = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    if (empty($name) || empty($email) || empty($cpf) || empty($phone) || empty($address)) {
        $errorMessage = "All the fields are required";
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
            while ($row = $checkResult->fetch_assoc()) {
                if ($row) {
                    $errorMessage = "The CPF: $cpf already exists in the database";
                    break;
                }
            }
            while ($row = $checkResult2->fetch_assoc()) {
                if ($row) {
                    $errorMessage = "The phone number: $phone already exists in the database";
                    break;
                }
            }
            if (empty($errorMessage)) {
                $errorMessage = "The email: $email already exists in the database";
            }
        } else {
            $sql = "INSERT INTO readers(name, email, cpf, phone, address) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $cpf, $phone, $address);
            $result = $stmt->execute();

            if (!$result) {
                $errorMessage = "Invalid query: " . $connection->error;
            } else {
                $successMessage = "Reader added correctly";
            }
        }
    }
    // Redirecionamento após o processamento do formulário
    if (!empty($successMessage)) {
        header("location: /testephp/Readers/readers.php");
        exit;
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
        <h2>+ New Reader</h2>

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
                    <input type="text" class="form-control" name="cpf" value="<?php echo $cpf; ?>" oninput="maskCPF(this); limitCharacters(this, 14)" maxlength="14">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>" oninput="maskPhone(this); limitCharacters(this, 15)" maxlength="15">
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/testephp/Readers/readers.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <script>
  // Mask for CPF
  function maskCPF(element) {
    element.value = element.value.replace(/\D/g, '').replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
  }

  // Mask for phone number
  function maskPhone(element) {
    element.value = element.value.replace(/\D/g, '').replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
  }

  // Limit character input
  function limitCharacters(element, maxLength) {
    if (element.value.length > maxLength) {
      element.value = element.value.slice(0, maxLength);
    }
  }
</script>
</body>
</html>
