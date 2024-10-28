<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
require_once '../controllers/AmendmentController.php';
$billController = new BillController($pdo);
$amendmentController = new AmendmentController($pdo);
$errorMessage = "";
$billId = -1;
$readonly = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["action"])) {
        $errorMessage .= "<p>Please select bill!</p>";
    } else {
        $billId = $_POST["billId"];
        if ($billId == -1) {
            $errorMessage .= "<p>Bill is not selected!</p>";
        } else {
            $action = $_POST['action'];
            $amendment = $_POST['amendment'];
            try {
                switch ($action) {
                    case "reject":
                        $amendmentController->createNewAmendment($billId, $amendment);
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
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['bill'])) {

        $billId = intval($_GET['bill']);
        $bill = $billController->findById($billId);

        if ($bill) {
            $title = $bill['title'];
            $description = $bill['description'];
            $content = $bill['content'];
        } else {
            $errorMessage .= "<p>Bill not found!</p>";
        }
    } else {
        $title = "";
        $description = "";
        $content = "";
        $errorMessage .= "<p>Bill has to be specified!</p>";
    }
}
?>

<?php include '../views/header.php' ?>
<div class="bill-area">
    <div>
        <h1>Bill review</h1>
    </div>
    <?php echo $errorMessage; ?>
    <form method="post" action="bill_review.php">
        <?php include '../views/bill_form.php' ?>
        <label for="amendment">Amendment: </label>
        <input type="text" name="amendment" id="amendment">
        <br>
        <?php
           echo "
            <input type='hidden' name='billId' value='{$billId}'>
            <button type='submit' name='action' value='reject'>Reject</button>
            <button type='submit' name='action' value='approve''>Approve</button>
            ";
        ?>
    </form>
</div>
<?php include '../views/footer.php' ?>
