
<?php 
    session_start();

    // variable declaration
    $first_name ="";
    $last_name = "";
    $username = "";
    $email    = "";
    $errors = array(); 
    $_SESSION['success'] = "";

    // connect to database
    $db = mysqli_connect('localhost', 'abir1048', 'abir1048', 'ledp');

    // REGISTER USER
    if (isset($_POST['reg_user'])) {
        // receive all input values from the form
        $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

        // form validation: ensure that the form is correctly filled
        if (empty($first_name)) { array_push($errors, "First name is required"); }
        if (empty($last_name)) { array_push($errors, "Last name is required"); }
        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($email)) { array_push($errors, "Email is required"); }
        if (empty($password_1)) { array_push($errors, "Password is required"); }

        if (empty($_FILES["file"]["name"])) {
            array_push($errors, "Please select a file to upload");
        }

        $duplicate_username = mysqli_query($db,"select * from users where username='$username'");
        $duplicate_email = mysqli_query($db,"select * from users where email='$email'");

        if (mysqli_num_rows($duplicate_email)>0)
        {
            array_push($errors, "This Email is already taken");
        }

         if (mysqli_num_rows($duplicate_username)>0)
        {
            array_push($errors, "This username is already taken");
        }

        if ($password_1 != $password_2) {
            array_push($errors, "The two passwords do not match");
        }

        $targetDir = "images/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);


        // register user if there are no errors in the form
        if (count($errors) == 0) {
            $password = md5($password_1);//encrypt the password before saving in the database

            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                $query = "INSERT INTO users (username, email, password, first_name, last_name, file_name, uploaded_on) 
                      VALUES('$username', '$email', '$password', '$first_name', '$last_name', 
                      '".$fileName."', NOW())";

            mysqli_query($db, $query);
            }
            

            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }

    }

    // ... 

    // LOGIN USER
    if (isset($_POST['login_user'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }

        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $results = mysqli_query($db, $query);

            if (mysqli_num_rows($results) == 1) {
                $_SESSION['username'] = $username;
                
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php');
            }else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    }

?>