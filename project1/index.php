<?php 

include 'server.php';

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: login.php");
    }

    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE username = '$username' ";

    $datas = mysqli_fetch_all(mysqli_query($db, $sql),MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Home Page</h2>
    </div>
    <div class="content" align="center">

        <!-- logged in user information -->
        <?php  if (isset($_SESSION['username'])) : 
             foreach ($datas as $data) {
                $imageURL = 'images/'.$data["file_name"]; 
            ?>
            <img src="<?php echo $imageURL;?>" width="200" height="200">
            <p>Name: <strong><?php echo $data['first_name'] ." " .$data['last_name']; ?></strong></p>
            <p>Username: <strong><?php echo $data['username']; ?></strong></p>
            <p>Email: <strong><?php echo $data['email']; ?></strong></p>
            <p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
        <?php } endif ?>
    </div>
        
</body>
</html>