<?php

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm"]) && $_POST["confirm"] == "yes") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "testephp";

        $connection = new mysqli($servername, $username, $password, $database);

        $sql = "DELETE FROM librarians WHERE id=$id";
        $connection->query($sql);

        header("location: /testephp/Librarians/librarians.php");
        exit;
    }

    // Exibir confirmação
    echo "Tem certeza que deseja deletar este registro?";
    echo "<form method='post'>";
    echo "<input type='hidden' name='confirm' value='yes'>";
    echo "<button type='submit'>Sim</button>";
    echo "<a href='/testephp/Librarians/librarians.php'>Não</a>";
    echo "</form>";
} else {
    echo "ID não fornecido.";
}

?>
