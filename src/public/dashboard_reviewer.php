<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
$billController = new BillController($pdo);
$errorMessage = "";

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
<div>
    <div>
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
            <?php
                $billsToReview = $billController->findByStatus('R');
                foreach ($billsToReview as $bill) {
                    echo "<tr>
                                        <td>{$bill['title']}</td>
                                        <td>{$bill['description']}</td>
                                        <td>{$bill['status']}</td>
                                        <td>
                                            <form method='post'>
                                                <input type='hidden' name='bill' value='{$bill['id']}'>
                                                <input type='hidden' name='action' value='reject'>
                                                <input type='submit' name='submit' value='Reject'>
                                            </form> 
                                            <form method='post'>
                                                <input type='hidden' name='bill' value='{$bill['id']}'>
                                                <input type='hidden' name='action' value='approve'>
                                                <input type='submit' name='submit' value='Approve'>
                                            </form>     
                                        </td>
                                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../views/footer.php' ?>