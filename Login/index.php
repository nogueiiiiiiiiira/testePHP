<?php
include('conexao.php');

if(isset($_POST['email']) || isset($_POST['senha'])) {

    if(strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

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
            echo "Falha ao logar! E-mail ou senha incorretos";
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
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class = "container">
          <h2>Login to Continue</h2>
          <br />
          <form class="inputs" method="POST">
            <input name='email' id='email' type="text" placeholder="type your-email@gmail.com: " required />
            <input name='senha' id='senha' type="password" placeholder="type your password: " required />
            <div class ="check">
              <input type="checkbox" id="remember-me"/>
              <label htmlFor="rememberMe">Remember me</label>
              <a href="#" class="forgotPassword">Forgot Password?</a>
            </div>
            <br />
            <br />
            <input class="button" type="submit" value="Sig In" />
          </form>
          <br />
          <p><a class="firstAcess" href="/PrimeiroAcesso">Don't have an account? Sign up!</a></p>
        </div>

</body>
</html>