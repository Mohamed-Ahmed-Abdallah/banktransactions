<?php
session_start();

if (!isset($_SESSION['CustomerID']) || isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location: login.php");
}

$conn = mysqli_connect("localhost", "root", "", "bank");

$bankAccountID = false;

$selectStatement = "SELECT * FROM bankaccount WHERE CustomerID = {$_SESSION['CustomerID']}";
$selectResult = mysqli_query($conn, $selectStatement);

if (mysqli_num_rows($selectResult) > 0) {
    $selectResult = mysqli_fetch_assoc($selectResult);

    $_SESSION['BankAccountID'] = $selectResult['BankAccountID'];
    $_SESSION['BACurrentBalance'] = $selectResult['BACurrentBalance'];

    $bankAccountID = true;
}

if (isset($_GET['addaccount'])) {

    $randomBankID = rand(10000, 200000);
    $insertStatement =
        " INSERT INTO bankaccount VALUES (
        $randomBankID,
        DEFAULT,
        1000,
        {$_SESSION['CustomerID']})
        ";

    mysqli_query($conn, $insertStatement);

    echo "<script>alert('Account created successfully.');
            window.location.replace('index.php');
    </script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <title>Customer home</title>
</head>

<body>

    <h1>Bank Transactions</h1>
    <a href="?logout=l" class="logout">Logout</a>

    <?php if ($bankAccountID == false) { ?>

        <h2>You haven't an account, please add account.</h2>
        <form>
            <input type="submit" name="addaccount" value="Add Account">
        </form>

    <?php } else { ?>

        <h2>Bank Account ID : <?php echo $selectResult['BankAccountID'] ?></h2>
        <h2>Current Balance : <?php echo $selectResult['BACurrentBalance'] ?>$</h2>

        <form action="transactions.php">
            <input type="submit" value="View list of transactions">
        </form>

    <?php } ?>
</body>

</html>