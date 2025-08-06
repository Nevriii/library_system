<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

session_start();

if (isset($_SESSION['ID']) && isset($_SESSION['email'])) {
    $id = $_SESSION['ID'];

    // Fetch the current values from the database
    $sql = "SELECT * FROM test WHERE ID = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Check if the row exists
    if (!$row) {
        header("location: Login.php");
        exit;
    }

    // Assign the values to variables for use in the form
    $firstn = $row['firstname'];
    $lastn = $row['lastname'];
    $gender = $row['gender'];
    $email = $row['email'];
    $pass = $row['password'];
    $number = $row['number'];
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>EDIT</title>
        <link rel="stylesheet" type="text/css" href="bootstrap.css">
    </head>

    <body>
        <div class="container">
            <div class="row col-md-6 col-md-offset-3">
                <div class="panel panel-primary">
                    <div class="panel-heading text-center">

                    </div>
                    <div class="panel-body">
                        <form method="post" action="update.php"> <!-- Assuming you have a separate file for handling the update -->
                            <!-- Add ID as a hidden input field to send it along with the form -->
                            <input type="hidden" name="ID" value="<?php echo $id; ?>">
                            <div class="form-group">
                                <label for="firstn">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $firstn; ?>">
                            </div>
                            <div class="form-group">
                                <label for="lastn">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastn; ?>">
                            </div>
                            <div class="form-group">
                                <label for="gender">Sex</label>
                                <div>
                                    <label for="male" class="radio-inline"><input type="radio" name="gender" value="m" id="male" <?php echo ($gender == 'm') ? 'checked' : ''; ?>>Male</label>
                                    <label for="female" class="radio-inline"><input type="radio" name="gender" value="f" id="female" <?php echo ($gender == 'f') ? 'checked' : ''; ?>>Female</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>
                            <div class="form-group">
                                <label for="pass">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo $pass; ?>">
                            </div>
                            <div class="form-group">
                                <label for="number">Phone Number</label>
                                <input type="number" class="form-control" id="number" name="number" value="<?php echo $number; ?>">
                            </div>
                            <input type="submit" class="btn btn-primary" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>

<?php
} else {
    header("Location: index.php");
    exit();
}
?>
