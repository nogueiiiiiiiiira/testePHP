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
$password = "";
$passwordConfirm = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["passwordConfirm"];

    // Verifica se CPF, telefone ou e-mail já existem na tabela readers ou librarians
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
        $errorMessage = "CPF, phone number, or email already exists in the database";
    } else {
        $sql = "INSERT INTO librarians(name, email, cpf, phone, address, password, passwordConfirm) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssssss", $name, $email, $cpf, $phone, $address, $password, $passwordConfirm);
        $result = $stmt->execute();

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Librarian added correctly";
            header("location: /testephp/Register/register.php");
            exit;
        }
    }

    $stmt->close();
    $stmt2->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <script>
        window.onload = function() {
            var cpfInput = document.getElementById('cpf');
            var phoneInput = document.getElementById('phone');

            cpfInput.addEventListener('input', function() {
                var value = this.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                this.value = value;
            });

            phoneInput.addEventListener('input', function() {
                var value = this.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
                this.value = value;
            });
        };
    </script>
</head>
<body class="mybody">
    <div class="mycontainer">
        <h2 class="myh2" >Sign up</h2>

        <?php
            if( !empty($errorMessage) ) {
            echo "
            <div class='error-container'>
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>
                ";
            }

            else{
                echo "
                    <br>
                ";
            }
        ?>

        <form method="POST" class="myinputs">
            <input class="inputtext" placeholder="type your whole name: " type="text" name="name" value="<?php echo $name; ?>" required>
            <input class="inputtext" placeholder="type your email: " type="text" name="email" value="<?php echo $email; ?>" required>
            <input class="inputtext" placeholder="type your cpf: " type="text" id="cpf" name="cpf" value="<?php echo $cpf; ?>" required>
            <input class="inputtext" placeholder="type your phone: " type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
            <input class="inputtext" placeholder="type your address" type="text" name="address" value="<?php echo $address; ?>" required>
            <input class="inputpassword" placeholder="type your password: " type="password" name="password" value="<?php echo $password; ?>" required>
            <input class="inputpassword" placeholder="confirm your password: " type="password" name="passwordConfirm" value="<?php echo $passwordConfirm; ?>" required>
            <br>
            <input value="Sign Up" class="mybutton" type="submit"/>
            <br>
            <p><a class="login" href="/testephp/Login/index.php">Already have an account? Sign in!</a></p>
        </form>
    </div>
</body>
</html>
