<?php
session_start();
date_default_timezone_set('Africa/Cairo');

if (!isset($_SESSION['CustomerID']) || isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location: login.php");
}

$conn = mysqli_connect("localhost", "root", "", "bank");
$selectStatement = "SELECT * FROM banktransaction where BTFromAccount = {$_SESSION['BankAccountID']}";
$selectResult = mysqli_query($conn, $selectStatement);

// ------------------------------------------------------ make transaction from one account to another.

if (isset($_POST['confirm'])) {
    $toAccount = htmlspecialchars($_POST['ToAccount']);
    $amount = htmlspecialchars($_POST['BTAmount']);
    $currentTime = time();

    if ($amount > 10 && $amount <= $_SESSION['BACurrentBalance']) {
        $insertStatement = "INSERT INTO banktransaction VALUES (
        null,
        $currentTime,
        $amount,
        {$_SESSION['BankAccountID']},
        $toAccount)
        ";

        $insertResult = mysqli_query($conn, $insertStatement);

        if ($insertResult) {
            $updateStatement = "UPDATE bankaccount SET BACurrentBalance = BACurrentBalance - $amount WHERE 
            BankAccountID = {$_SESSION['BankAccountID']}";

            $updateResult = mysqli_query($conn, $updateStatement);

            $updateStatement = "UPDATE bankaccount SET BACurrentBalance = BACurrentBalance + $amount WHERE 
            BankAccountID = $toAccount";

            $updateResult = mysqli_query($conn, $updateStatement);

            echo "<script> alert ('Transaction created successfully.'); 
                window.location.replace('index.php');
            </script>";
        } else {
            echo "<script>  
            window.onload = function(){ 
                alert ('Please, Enter valid Account ID.'); 
                window.location.replace('transactions.php');
            };
            </script>";
        }
    } else {
        echo "<script>  
            window.onload = function(){ 
                alert ('Please, Enter valid Amount.'); 
                window.location.replace('transactions.php');
            };
        </script>";
    }
}

// -------------------------------------------------------- delete specific transaction.

if (isset($_GET['delete'])) {

    $transactionID = htmlspecialchars($_GET['delete']);

    $selectStatement = "SELECT * FROM banktransaction WHERE 
    BankTransactionID = $transactionID AND BTFromAccount = {$_SESSION['BankAccountID']}";

    $selectResult = mysqli_query($conn, $selectStatement);
    $selectResultRow = mysqli_fetch_assoc($selectResult);

    if (mysqli_num_rows($selectResult) > 0) {
        $toAccount = $selectResultRow['BTToAccount'];
        $amount = $selectResultRow['BTAmount'];

        $currentTime = time();
        $deleteStatement = "DELETE FROM banktransaction WHERE BankTransactionID = $transactionID 
                        AND ($currentTime - BTCreationDate < 86400)";

        if ($conn->query($deleteStatement) && $conn->affected_rows > 0) {
            $updateStatement = "UPDATE bankaccount SET BACurrentBalance = BACurrentBalance + $amount WHERE 
            BankAccountID = {$_SESSION['BankAccountID']}";

            $updateResult = mysqli_query($conn, $updateStatement);

            $updateStatement = "UPDATE bankaccount SET BACurrentBalance = BACurrentBalance - $amount WHERE 
            BankAccountID = $toAccount";

            $updateResult = mysqli_query($conn, $updateStatement);

            echo "<script>  
            window.onload = function(){ 
                alert ('Transaction deleted successfully.'); 
                window.location.replace('index.php');
            };
            </script>";
        } else {
            echo "<script>  
            window.onload = function(){ 
                alert ('Transaction can\'t be deleted as it exceeded one day.'); 
                window.location.replace('transactions.php');
            };
            </script>";
        }
    } else {
        header("location: transactions.php");
    }
}

?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <title>Transactions</title>
</head>

<body>

    <a href="#" class="go-up">Go Up</a>

    <h1>Bank Transactions</h1>
    <a href="?logout=l" class="logout">Logout</a>

    <h2>Page Content</h2>
    <ul>
        <li><a href="#M">
                <h3>Make a transaction</h3>
            </a></li>
        <li><a href="#L">
                <h3>List of your transactions</h3>
            </a></li>
    </ul>

    <h2 id="M">Please, fill the form to make a transaction with minimum amount 10$.</h2>
    <h2 style="color: green; font-weight: bold;"><span style="color: black;">=></span> Your current balance is <?php echo $_SESSION['BACurrentBalance'] ?>$ .</h2>

    <form method="POST">
        <table class="transaction-insert-table">
            <tr>
                <td>To: </td>
                <td><input type="number" name="ToAccount" placeholder="Enter Bank Account ID" required></td>
            </tr>
            <tr>
                <td>Amount: </td>
                <td><input type="number" min="10" name="BTAmount" placeholder="Enter Amount" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="confirm" value="Confirm"></td>
            </tr>
        </table>
    </form>

    <a href="index.php" class="back">
        Back to your account details</a>

    <h2 id="L">List of your transactions.</h2>
    <div>
        <?php foreach ($selectResult as $row) { ?>
            <table class="transaction-show-table">
                <tr>
                    <td>Bank Transaction ID</td>
                    <td><?php echo $row["BankTransactionID"] ?></td>
                </tr>
                <tr>
                    <td>From Account</td>
                    <td><?php echo $row["BTFromAccount"] ?></td>
                </tr>
                <tr>
                    <td>To Account</td>
                    <td><?php echo $row["BTToAccount"] ?></td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td><?php echo $row["BTAmount"] ?>$</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td><?php echo date('d/m/Y H:i:s', $row["BTCreationDate"]) ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="?delete=<?php echo $row["BankTransactionID"] ?>" class="submit">Delete</a>
                    </td>
                </tr>
            </table>
            <br><br>
        <?php } ?>

        <?php if (mysqli_num_rows($selectResult) == 0) { ?>
            <h3 class="no-transaction">There is no any transaction.</h3><br>
        <?php } ?>
    </div>

</body>