<?php
require_once '../controllers/ReportController.php';

$reportController = new ReportController($pdo);
$filterResults = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filter = [
        'title' => $_POST['title'] ?? null,
        'status' => $_POST['status'] ?? null,
        'start_date' => $_POST['start_date'] ?? null,
        'end_date' => $_POST['end_date'] ?? null
    ];
    $filteredBills = $reportController->getReport($filter);
}
?>

<div>
    <form action="" method="POST">
        <label>Title</label>
        <input type="text" name="title" id="title" />

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

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" />

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" />
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Author</th>
                <th>Status</th>
                <th>Create Time</th>
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
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>