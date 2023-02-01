<?php
session_start();

if (isset($_SESSION['CustomerID'])) {
    header("location: index.php");
}

$conn = mysqli_connect("localhost", "root", "", "bank");

if (isset($_POST['login'])) {
    $id = htmlspecialchars($_POST['ID']);
    $password = htmlspecialchars($_POST['password']);
    $selectStatement = "SELECT * FROM customer WHERE CustomerID = $id AND CustomerPassword = '$password'";
    $selectResult = mysqli_query($conn, $selectStatement);
    if (mysqli_num_rows($selectResult) > 0) {
        $_SESSION['CustomerID'] = $id;
        header("location: index.php");
    } else {
        echo "<script>  
            window.onload = function(){ 
                alert ('Please, Enter valid client ID and password.'); 
                window.location.replace('login.php');
            };
            </script>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <title>Login</title>
</head>

<body>
    <h1>Bank Transactions</h1>
    <form method="POST" class="login-form">
        <table>
            <tr>
                <td colspan="2">
                    <h2>Login</h2>
                </td>
            </tr>
            <tr>
                <td>Client_ID: </td>
                <td><input type="number" name="ID" required></td>
            </tr>
            <tr>
                <td>Password: </td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><input type="submit" name="login" value="Login"></p>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>