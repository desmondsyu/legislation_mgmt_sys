<html>

<head>
    <title>Legislation Process Management System</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>

<body>
    <header>
        <h1>Legislation System</h1>
        <?php if (isset($_SESSION['user'])): ?>
        <form action="../middleware/logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    <?php endif; ?>
    </header>