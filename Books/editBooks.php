<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "testephp";

$connection = new mysqli($servername, $username, $password, $database);


$id = "";
$title = "";
$author = "";
$category = "";

$errorMessage = "";
$successMessage = "";

if( $_SERVER['REQUEST_METHOD'] == 'GET') {

    if( !isset($_GET["id"])) {
        header("location: /testephp/Books/books.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM books WHERE id=$id";
    $result = $connection-> query($sql);
    $row = $result->fetch_assoc();

    $title = $row["title"];
    $author = $row["author"];
    $category = $row["category"];     
}

else{
    $id = $_POST["id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $category = $_POST["category"];

    do{
        if( empty($id) || empty($title) || empty($author) || empty($category) ) {
            $errorMessage = "All the fields are required";
            break;
        }

        $sql = "UPDATE books " .
                "SET title = '$title', author = '$author', category = '$category' " . 
                "WHERE id = $id ";

        $result = $connection->query($sql);

        if(!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "Book updated correctly";

        header("location: /testephp/Books/books.php");
        exit;

    } while(true);
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
        <h2>Edit Book</h2>

        <?php
            if( !empty($errorMessage) ) {
                echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                ";
            }
        ?>

            <form method="post" >
            <input type="hidden" name="id" value="<?php echo $id; ?>" >
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="author" value="<?php echo $author; ?>">
                </div>
            </div> 
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Category</label>
                <div class="col-sm-6">
                    <select class="form-select" name="category" id="category">
                        <option value="">Select the category:</option>
                        <option value="Ficção" <?php if ($category == "ficcao") echo "selected"; ?>>Ficção</option>
                        <option value="Não-Ficção" <?php if ($category == "nao-ficcao") echo "selected"; ?>>Não Ficção</option>
                        <option value="Aventura" <?php if ($category == "aventura") echo "selected"; ?>>Aventura</option>
                        <option value="Romance" <?php if ($category == "romance") echo "selected"; ?>>Romance</option>
                    </select>
                </div>
            </div>


            <?php
                if( !empty($successMessage) ) {
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
                    <a href="/testephp/Books/books.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>