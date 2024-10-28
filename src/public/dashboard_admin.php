<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/BillController.php';
require_once '../controllers/UserController.php';
require_once '../controllers/VoteController.php';
$billController = new BillController($pdo);
$userController = new UserController($pdo);
$voteController = new VoteController($pdo);
$errorMessage = "";

$billsToVote = $billController->findByStatus('A');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["bill"]) || empty($_POST["action"])) {
        $errorMessage .= "<p>Please select bill!</p>";
    } else {
        $billId = $_POST['bill'];
        $action = $_POST['action'];

        switch ($action) {
            case "vote":
                try {
                    $billController->startVoteBill($billId);
                    header("location: dashboard_admin.php");
                } catch (Exception $e) {
                    $errorMessage .= "<p>" . $e->getMessage() . "</p>";
                }
                break;
            case "results":
                $votesFor = count($voteController->findVotesFor($billId));
                $votesAgainst = count($voteController->findVotesAgainst($billId));
                $totalVotes = $userController->totalMP();

                if ($totalVotes != $votesFor + $votesAgainst) {
                    $errorMessage .= "<p>Not all MP has voted!</p>";
                    break;
                }

                if ($votesFor >= $totalVotes / 2) {
                    $billController->votePassed($billId);
                    $errorMessage .= "<p>Votes passed!</p>";
                } else {
                    $billController->voteDenied($billId);
                    $errorMessage .= "<p>Votes denied!</p>";
                }
                break;
        }
        header("location: dashboard_admin.php");
    }
}
?>

<?php include '../views/header.php' ?>
<div class="container">
    <div class="table-area">
        <div>
            <h1>Approved Bills</h1>
        </div>
        <?php echo $errorMessage; ?>
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
                                <button type='submit' name='action' value='vote'>Vote!</button>
                            </form>     
                        </td>
                      </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="table-area">
        <div>
            <h1>Active vote</h1>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Bill title</th>
                    <th>Votes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $activeBills = $billController->findByStatus('V');
                $totalVotes = $userController->totalMP();
                foreach ($activeBills as $bill) {
                    $votes = $voteController->numberOfVotes($bill['id']);
                    echo "<tr>
                            <td>{$bill['title']}</td>
                            <td>{$votes}/{$totalVotes}</td>
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='bill' value='{$bill['id']}'>
                                    <button type='submit' name='action' value='results'>Get results</button>
                                </form>     
                            </td>
                          </tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php include './report.php' ?>
    <?php include './notification.php' ?>
</div>
<?php include '../views/footer.php' ?>