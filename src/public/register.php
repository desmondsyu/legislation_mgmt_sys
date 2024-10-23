<?php
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['role'])) {
        $errorMessage .= "<p>Please fill all fields!</p>";
    } else {
        try {
            if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $_POST["password"])) {
                throw new Exception("<p>The password is too weak! Please enter a stronger password.</p>");
            }

            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            $userController = new UserController($pdo);
            $userId = $userController->register($username, $password, $role);

            if ($userId) {
                echo "User registered successfully!";
                header('Location: login.php');
            } else {
                echo "Failed to register user.";
            }
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="username" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <label>Role</label>
    <select name="role">
        <option value="PARLIAMENT">Parliament</option>
        <option value="REVIEWER">Reviewer</option>
        <option value="ADMINISTRATOR">Adminstrator</option>
    </select>
    <button type="submit">Register</button>
    <a href="login.php">
        <button type="button">Login</button>
    </a>
    <?php echo $errorMessage ?>
</form>