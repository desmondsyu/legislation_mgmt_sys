<?php
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['role'])) {
        $errorMessage .= "<p>Please fill all fields!</p>";
        return;
    }

    try {
        $password = $_POST['password'];
        $passwordErrors = validatePassword($password);
    
        if (!empty($passwordErrors)) {
            throw new Exception(implode("<br>", $passwordErrors));
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $userController = new UserController($pdo);
        $userId = $userController->register($username, $password, $role);

        if ($userId) {
            echo "User registered successfully!";
        } else {
            echo "Failed to register user.";
        }
    } catch (Exception $e) {
        $errorMessage .= "<p>" . $e->getMessage() . "</p>";
    }

}

function validatePassword($password) {
    $errors = [];

    // Check for at least one uppercase letter
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Your password must contain at least one uppercase letter.";
    }

    // Check for at least one lowercase letter
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Your password must contain at least one lowercase letter.";
    }

    // Check for at least one digit
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Your password must contain at least one number.";
    }

    // Check for at least one special character
    if (!preg_match("/[#?!@$%^&*-]/", $password)) {
        $errors[] = "Your password must contain at least one special character.";
    }

    // Check for minimum length of 8 characters
    if (strlen($password) < 8) {
        $errors[] = "Your password must be at least 8 characters long.";
    }

    return $errors;
}
?>

<?php include '../views/header.php' ?>
<form class="register-form" method="POST">
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
    <div>
        <button type="submit">Register</button>
        <a href="login.php">
            <button type="button">Login</button>
        </a>
    </div>

    <?php echo $errorMessage ?>
</form>
<?php include '../views/footer.php' ?>