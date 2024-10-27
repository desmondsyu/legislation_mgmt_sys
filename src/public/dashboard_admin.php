<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
$billController = new BillController($pdo);
$errorMessage = "";

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
<div>
    <div>
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
            <?php
            $billsToVote = $billController->findByStatus('A');
            foreach ($billsToVote as $bill) {
                echo "<tr>
                        <td>{$bill['title']}</td>
                        <td>{$bill['description']}</td>
                        <td>{$bill['status']}</td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='bill' value='{$bill['id']}'>
                                <input type='submit' name='submit' value='Vote!'>
                            </form>     
                        </td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'notification.php' ?>
<?php include 'report.php' ?>
<?php include '../views/footer.php' ?>