<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';
require_once '../controllers/BillController.php';
require_once '../controllers/AmendmentController.php';
$errorMessage = "";
$billController = new BillController($pdo);
$amendmentController = new AmendmentController($pdo);
$readonly = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['bill']) && $_GET['bill'] == 'new') {
        $title = "";
        $description = "";
        $content = "";
    } elseif (isset($_GET['bill'])) {

        $billId = intval($_GET['bill']);
        $bill = $billController->findById($billId);

        if ($bill) {
            $title = $bill['title'];
            $description = $bill['description'];
            $content = $bill['content'];
        } else {
            $errorMessage .= "<p>Bill not found!</p>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["title"]) || empty($_POST["description"]) || empty($_POST["content"])) {
        $errorMessage .= "<p>Please fill all fields!</p>";
    } else {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $content = $_POST['content'];
        $author = $_SESSION['user'];

        if (isset($_POST['bill']))
            $billId = intval($_POST['bill']);

        try {
            if (isset($billId)) {
                $billController->updateBill($billId, $title, $description, $content);
                header('Location: dashboard_mp.php');
            } else {
                $billController->createNewBill($title, $description, $author, $content);
                header('Location: dashboard_mp.php');
            }
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<?php include '../views/header.php' ?>
<div class="bill-area">
    <div>
        <h1>Bill</h1>
    </div>
    <?php echo $errorMessage; ?>
    <form method="post">
        <?php include '../views/bill_form.php' ?>
        <?php if (isset($_GET['bill']) && $_GET['bill'] != 'new'): ?>
            <input type="hidden" name="bill" value="<?php echo $_GET['bill']; ?>">
        <?php endif; ?>
        <button type="submit">Save</button>
    </form>
    <div class="table-area">
        <table>
            <thead>
                <tr>
                    <th>Amendment</th>
                    <th>Time posted</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($billId))
                    return;
                $amendmentsForBill = $amendmentController->findByBill($billId) ?: [];
                foreach ($amendmentsForBill as $amendment) {
                    echo "<tr>
                        <td>{$amendment['amendments']}</td>
                        <td>{$amendment['create_time']}</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../views/footer.php' ?>