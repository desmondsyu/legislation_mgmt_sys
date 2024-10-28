<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
$billController = new BillController($pdo);
$errorMessage = "";

$billsToReview = $billController->findByStatus('R');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["bill"]) || empty($_POST["action"])) {
        $errorMessage .= "<p>Please select bill!</p>";
    } else {
        $billId = $_POST['bill'];
        $action = $_POST['action'];
        try {
            switch ($action) {
                case "reject":
                    $billController->rejectBill($billId);
                    break;
                case "approve":
                    $billController->approveBill($billId);
                    break;
                default:
                    break;
            }
            header("location: dashboard_reviewer.php");
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<?php include '../views/header.php' ?>
<div class="container">
    <div class="table-area">
        <div>
            <h1>Review Bills</h1>
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
                <?php foreach ($billsToReview as $bill): ?>
                    <tr>
                        <td><?= $bill['title']; ?></td>
                        <td><?= $bill['description']; ?></td>
                        <td><?= $bill['status']; ?></td>
                        <td>
                            <form action='bill_review.php'>
                                <input type='hidden' name='bill' value='<?= $bill['id']; ?>'>
                                <input type='submit' value='Review'>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include './report.php' ?>
</div>
<?php include './notification.php'; ?>

<?php include '../views/footer.php' ?>