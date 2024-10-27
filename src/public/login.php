<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$errorMessage = "";
$rememberUser = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $errorMessage .= "<p>Please fill all fields!</p>";
    } else {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $rememberUser = isset($_POST["remember"]);

            $userController = new UserController($pdo);
            $user = $userController->login($username, $password);

            if ($user) {
                
                if ($rememberUser) {
                    $lifetime = 60 * 60 * 24 * 7;
                    setcookie("username", $user['username'], time() + $lifetime, "/");
                    setcookie("user", $user['id'], time() + $lifetime, "/");
                    setcookie("role", $user['role'], time() + $lifetime, "/");
                }

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
    <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" required />
    <label>Password</label>
    <input type="password" name="password" required />
    <label>Remember Me</label>
    <input type="checkbox" name="remember" />
    <button type="submit">Login</button>
    <a href="register.php">
        <button type="button">Register</button>
    </a>
    <?php echo $errorMessage ?>
</form>