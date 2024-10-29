<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$errorMessage = "";
$rememberUser = false;

function handleLogin($pdo, $username, $password) {
    $userController = new UserController($pdo);
    return $userController->login($username, $password);
}

function setLoginCookies($user) {
    if (isset($_POST["remember"])) {
        $lifetime = 60 * 60 * 24 * 7; // 1 week
        setcookie("username", $user['username'], time() + $lifetime, "/");
        setcookie("user", $user['id'], time() + $lifetime, "/");
        setcookie("role", $user['role'], time() + $lifetime, "/");
    }
}

function redirectToDashboard($role) {
    switch ($role) {
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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $errorMessage .= "<p>Please fill all fields!</p>";
    } else {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $rememberUser = isset($_POST["remember"]);

            $user = handleLogin($pdo, $username, $password);

            if ($user) {

                setLoginCookies($user);

                $_SESSION['user'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                redirectToDashboard($_SESSION['role']);

            } else {
                echo "Invalid credentials!";
            }
        } catch (Exception $e) {
            $errorMessage .= "<p>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<?php include '../views/header.php' ?>
<div class="form-container">
<form class="login-form" method="POST">
    <label>Username</label>
    <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" required />
    <label>Password</label>
    <input type="password" name="password" required />
    <div class="remember-container">
        <input type="checkbox" id="remember" name="remember" />
        <label for="remember">Remember Me</label>
    </div>
    <div>
        <button type="submit">Login</button>
        <a href="register.php">
            <button type="button">Register</button>
        </a>
    </div>

    <?php echo $errorMessage ?>
</form>
</div>
<?php include '../views/footer.php' ?>