<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['ID'];
    $firstn = $_POST['firstname'];
    $lastn = $_POST['lastname'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $number = $_POST['number'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("UPDATE test SET firstname=?, lastname=?, gender=?, email=?, password=?, number=? WHERE ID=?");
    $stmt->bind_param("ssssssi", $firstn, $lastn, $gender, $email, $pass, $number, $id);

    if ($stmt->execute()) {
        header("Location: Profile.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect or handle the case when the form is not submitted via POST
    header("Location: index.php");
    exit();
}

$conn->close();
?>
