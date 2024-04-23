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

    $checkSql = "SELECT * FROM readers WHERE cpf='$cpf' OR phone='$phone' OR email='$email'
             UNION
             SELECT * FROM librarians WHERE cpf='$cpf' OR phone='$phone' OR email='$email'";
    $checkResult = $connection->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $errorMessage = "CPF, phone number, or email already exists in the database";
    } else {
        $sql = "INSERT INTO librarians(name, email, cpf, phone, address, password, passwordConfirm) " .
            "VALUES ('$name', '$email', '$cpf', '$phone', '$address', '$password', '$passwordConfirm')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Librarian added correctly";
            header("location: /testephp/Register/register.php");
            exit;

        }
    }

    $name = "";
    $email = "";
    $cpf = "";
    $phone = "";    
    $address = "";
    $password = "";
    $passwordConfirm = "";

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
        ?>

        <form method="POST" class="myinputs">
            <input class="inputtext" placeholder="type your whole name: " type="text" name="name" value="<?php echo $name; ?>" required>
            <input class="inputtext" placeholder="type your email: " type="text" name="email" value="<?php echo $email; ?>" required>
            <input class="inputtext" placeholder="type your cpf: " type="text" name="cpf" value="<?php echo $cpf; ?>" required>
            <input class="inputtext" placeholder="type your phone: " type="text" name="phone" value="<?php echo $phone; ?>" required>
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
