<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
$billController = new BillController($pdo);
$errorMessage = "";

$myBills = $billController->findByAuthor((int)$_SESSION['user']);
$votingBills = $billController->findByStatus('V');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["bill"]) && isset($_POST["action"])) {
        $billId = $_POST['bill'];
        $action = $_POST['action'];
        try {
            switch ($action) {
                case "pass":
                    $billController->votePassed($billId);
                    break;
                case "deny":
                    $billController->voteDenied($billId);
                    break;
                default:
                    break;
            }
            header("location: dashboard_reviewer.php");
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    } elseif (isset($_POST["bill"])) {
        $billId = $_POST['bill'];
        try {
            $billController->submitBill($billId);
            header("location: dashboard_mp.php");
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    } else {
        $errorMessage .= "<p>Please select bill!</p>";
    }
}
?>

<?php include '../views/header.php' ?>
<div class="container">
    <div class="table-area">
        <div>
            <h1>My Bills</h1>
            <a href="bill_details.php?bill=new">
                <button type="button">Create</button>
            </a>
            <?php echo $errorMessage; ?>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myBills as $bill): ?>
                    <tr>
                        <td><?= $bill['title']; ?></td>
                        <td><?= $bill['description']; ?></td>
                        <td><?= $bill['status']; ?></td>
                        <td>
                            <a href='bill_details.php?bill=<?= $bill['id']; ?>'>
                                <button type='button'>Edit</button>
                            </a>
                            <form method='post'>
                                <input type='hidden' name='bill' value='<?= $bill['id']; ?>'>
                                <input type='submit' name='submit' value='Submit'>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-area">
        <div>
            <h1>Voting Bills</h1>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($votingBills as $bill): ?>
                    <tr>
                        <td><?= $bill['title']; ?></td>
                        <td><?= $bill['description']; ?></td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='bill' value='<?= $bill['id']; ?>'>
                                <input type='hidden' name='action' value='pass'>
                                <input type='submit' name='submit' value='Pass'>
                            </form>
                            <form method='post'>
                                <input type='hidden' name='bill' value='<?= $bill['id']; ?>'>
                                <input type='hidden' name='action' value='deny'>
                                <input type='submit' name='submit' value='Deny'>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include './report.php' ?>
    <?php include './notification.php' ?>
</div>
<?php include '../views/footer.php' ?>