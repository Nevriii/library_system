<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if (isset($_POST['email'])  &&  isset($_POST['password'])){

    function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;

    }



    $uname = validate($_POST['email']);
    $pass = validate($_POST['password']);


    if (empty($uname)){
        header("Location: test.php?error=No Username");
    exit();

    }else if (empty($pass)){     
        header("Location: test.php?error=No Password");  
    
    }else{
        $sql= "SELECT *FROM test WHERE email='$uname' AND password='$pass'";
        $result = mysqli_query($conn,$sql);

        if (mysqli_num_rows($result)){
                echo "Hello";
                $row= mysqli_fetch_assoc($result);

                if($row['email'] === $uname && $row['password'] ===$pass){

                    $_SESSION['email'] = $row['email'];
                    $_SESSION['firstn'] = $row['firstn'];
                    $_SESSION['ID'] = $row['ID'];

                    header("Location: Home.html");
                    exit();
    
    }}else{
        header("Location: test.php?error=Incorect username or password");
    }
    
    
} 
}else{
    header("Location: index.php?error=No entries");
    exit();
}





?>