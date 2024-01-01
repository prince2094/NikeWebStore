<?php
session_start();
require_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = $_POST["login_id"];
    $login_pswd = $_POST["login_pswd"];

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE (username = '$login_id' OR email = '$login_id')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($login_pswd, $row["password"])) {
            // Set the username in the session variable
            $_SESSION["username"] = $row["username"];
            // Redirect to the welcome page or wherever you want
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }
}

$conn->close();
?>
