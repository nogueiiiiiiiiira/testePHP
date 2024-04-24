<?php
include('conexao.php');

$errorMessage = "";

if(isset($_POST['email']) || isset($_POST['senha'])) {

        $email = $connection->real_escape_string($_POST['email']);
        $senha = $connection->real_escape_string($_POST['senha']);
    

        $sql_code = "SELECT * FROM librarians WHERE email = '$email' AND password = '$senha'";
        $sql_query = $connection->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: painel.php");

        } else {
            $errorMessage = "Librarian wasn't found in the database";
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>
    <title>Login</title>
</head>
<body class="mybody">
    <div class = "mycontainer">
          <h2 class="myh2">Sign In</h2>

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

          <form class="myinputs" method="POST">
            <input class="inputtext" name='email' id='email' type="text" placeholder="type your-email@gmail.com: " required />
            <input class="inputpassword" name='senha' id='senha' type="password" placeholder="type your password: " required />
            <br>
            <div class ="check">
              <input type="checkbox" id="remember-me"/>
              <label htmlFor="rememberMe">Remember me</label>
              <a href="#" class="forgotPassword">Forgot Password?</a>
            </div>
            <br />
            <br />
            <input class="mybutton" type="submit" value="Sig In" />
          </form>
          <br />
          <p><a class="firstAcess" href="/testephp/Register/register.php">Don't have an account? Sign up!</a></p>
        </div>

</body>
</html>