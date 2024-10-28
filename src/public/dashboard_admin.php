<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
$billController = new BillController($pdo);
$errorMessage = "";

$billsToVote = $billController->findByStatus('A');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["bill"])) {
        $errorMessage .= "<p>Please select bill!</p>";
    } else {
        $billId = $_POST['bill'];

        try {
            $billController->startVoteBill($billId);
            header("location: dashboard_admin.php");
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
            <h1>Approved Bills</h1>
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
                <?php foreach ($billsToVote as $bill): ?>
                    <tr>
                        <td><?= $bill['title']; ?></td>
                        <td><?= $bill['description']; ?></td>
                        <td><?= $bill['status']; ?></td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='bill' value='<?= $bill['id']; ?>'>
                                <input type='submit' name='submit' value='Vote!'>
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