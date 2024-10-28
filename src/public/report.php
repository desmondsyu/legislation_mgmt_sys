<?php
require_once '../controllers/ReportController.php';

$reportController = new ReportController($pdo);
$filter = [];
$filteredBills = $reportController->getReport($filter);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filter = [
        'title' => $_POST['title'] ?? null,
        'description' => $_POST['description'] ?? null,
        'status' => $_POST['status'] ?? null
    ];
    $filteredBills = $reportController->getReport($filter);
}
?>

<div class="report-area">
    <div>
        <h1>Report</h1>
    </div>
    <div class="form-area">
        <form action="" method="POST">
            <label>Title</label>
            <input type="text" name="title" id="title" />

            <label>Description</label>
            <input type="text" name="description" id="description" />

            <label>Status</label>
            <select name="status" id="status">
                <option value="">Select Status</option>
                <option value="D">Draft</option>
                <option value="R">Review</option>
                <option value="A">Approved</option>
                <option value="N">Rejected</option>
                <option value="V">Voting</option>
                <option value="P">Passed</option>
                <option value="E">Denied</option>
            </select>

            <button type="submit">Search</button>
        </form>
    </div>

    <div class="table-area">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Create Time</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredBills as $bill): ?>
                    <tr>
                        <td><?= htmlspecialchars($bill['title']); ?></td>
                        <td><?= htmlspecialchars($bill['description']); ?></td>
                        <td><?= htmlspecialchars($bill['author']); ?></td>
                        <td><?= htmlspecialchars($bill['status']); ?></td>
                        <td><?= htmlspecialchars($bill['create_time']); ?></td>
                        <td>
                            <form action="download.php" method="GET">
                                <input type="hidden" name="bill_id" value="<?= htmlspecialchars($bill['id']); ?>">
                                <button type="submit">Export</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>