<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../config/database.php';
require_once '../controllers/BillController.php';
$errorMessage = "";
$billController = new BillController($pdo);

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

        try {
            if (isset($billId)) {
                $billController->updateBill($billId, $title, $description, $content);
            } else {
                $billController->createNewBill($title, $description, $author, $content);
            }
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<?php include '../views/header.php' ?>
    <div>
        <div>
            <h1>Bill</h1>
        </div>
        <?php echo $errorMessage; ?>
            <form method="post">
                <?php include '../views/bill_form.php' ?>
                <button type="submit">Save</button>
            </form>
    </div>
<?php include '../views/footer.php' ?>