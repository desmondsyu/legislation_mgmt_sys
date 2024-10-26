<?php
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $errorMessage .= "<p>Please fill all fields!</p>";
    } else {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userController = new UserController($pdo);
            $user = $userController->login($username, $password);

            if ($user) {
                $lifetime = 60 * 60 * 24 * 7;
                session_set_cookie_params($lifetime, '/');
                session_start();
                setcookie("user", $user['id'], time() + $lifetime, "/");
                setcookie("role", $user['role'], time() + $lifetime, "/");
                $_SESSION['user'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                switch ($_SESSION['role']) {
                    case 'PARLIAMENT':
                        header('Location: dashboard_mp.php');
                        break;
                    case 'REVIEWER':
                        header('Location: dashboard_reviewer.php');
                        break;
                    case 'ADMINISTRATOR':
                        header('Location: dashboard_admin.php');
                        break;
                    default:
                        break;
                }
            } else {
                echo "Invalid credentials!";
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
    <input type="text" name="password" required>
    <button type="submit">Login</button>
    <a href="register.php">
        <button type="button">Register</button>
    </a>
    <?php echo $errorMessage ?>
</form>