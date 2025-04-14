<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'ciclo_suerte');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                header("location: home.php");
                exit();
            } else {
                $_SESSION['error_message'] = "invalid input";
                header("location: Login.php");
                exit();
            }
        } else {
            echo "No account found with that username.";
        }

        $stmt->close();
    } else {
        echo "Please enter both username and password.";
    }
}

$conn->close();
?>