<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
require_once '../controllers/VoteController.php';
$billController = new BillController($pdo);
$voteController = new VoteController($pdo);
$errorMessage = "";
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["bill"]) && isset($_POST["action"])) {
        $billId = $_POST['bill'];
        $action = $_POST['action'];
        try {
            switch ($action) {
                case "pass":
                    $voteController->voteFor($billId, $user);
                    break;
                case "deny":
                    $voteController->voteAgainst($billId, $user);
                    break;
                default:
                    break;
            }
            header("location: dashboard_mp.php");
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
<div>
    <div>
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
                <?php
                $myBills = $billController->findByAuthor((int)$_SESSION['user']);
                foreach ($myBills as $bill) {
                    echo "<tr>
                            <td>{$bill['title']}</td>
                            <td>{$bill['description']}</td>
                            <td>{$bill['status']}</td>
                            <td>
                                <a href='bill_details.php?bill={$bill['id']}'>
                                    <button type='button'>Edit</button>
                                </a>
                                <form method='post'>
                                    <input type='hidden' name='bill' value='{$bill['id']}'>
                                    <input type='submit' name='submit' value='Submit'>
                                </form>     
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div>
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
                <?php
                $votingBills = $billController->findByStatus('V');
                foreach ($votingBills as $bill) {
                    echo "<tr>
                                <td>{$bill['title']}</td>
                                <td>{$bill['description']}</td>
                                <td>";
                    if (!$voteController->userHasVoted($bill['id'], $user)) {
                    echo "          <form method='post'>
                                        <input type='hidden' name='bill' value='{$bill['id']}'>
                                        <input type='hidden' name='action' value='pass'>
                                        <input type='submit' name='submit' value='Pass'>
                                    </form>
                                    <form method='post'>
                                        <input type='hidden' name='bill' value='{$bill['id']}'>
                                        <input type='hidden' name='action' value='deny'>
                                        <input type='submit' name='submit' value='Deny'>
                                    </form>";
                    }
                    echo "    </td>
                              </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../views/footer.php' ?>