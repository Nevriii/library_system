<!DOCTYPE html>
<html>
    <head>
        <title>LOGIN</title>
        <link rel="stylesheet" type ="text/css" href="style.css">
    </head>
    <body>
        <form action="main.php" method="post">
            <h2>LOGIN</h2>
            <?php if (isset($_GET['error'])){ ?>
                <p class="error"><?php echo $_GET['error']; ?> </p>
            <?php } ?>
            <label>User Name</label>
            <input type="text" name="email" placeholder="Username"><br>
            <label>Password</label>
            <input type="Password" name="password" placeholder="Password"><br>

            <button type="submit">Enter</button>
            
            
        </form>
    </body> 
</html> 