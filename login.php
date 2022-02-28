<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
 <!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  

  </head>
  <style>
       body
    {background-image:url("com.jpg");
    position: relative;
	min-height: 100vh;
	background-size: cover;
	background-position: right;
	display: flex;
	justify-content: space-between;
	align-items: center;
    }
             .hero::after { background-color:#17202A  ;
  content: ""; 
             
             display: block; position: absolute;background-size: cover; top: 0px; left: 0px; width: 100%; height: 100%; z-index: -1; opacity: 0.75; }
             .dfg{
    
     background: rgba(255, 255, 255, 0.05);
     box-shadow: 0 15px 35px rgba(0,0,0,0.2);
     border-radius: 15px;
     backdrop-filter: blur(10px);
                 color: white;
                 width: 40%;
             }
             .form-control {
              
              
    border: none;
    background: transparent;
    border-bottom: 1px solid black;
              
             
             }
             ::placeholder{
               color: white;
               opacity: 0.4;
             }
  </style>
  <body>
 <div class="hero"> 
    </div>
    <div class="container dfg">
      <ul class="nav nav-pills nav-justified" role="tablist">
        <li class="nav-item "><a href="register.php" class="nav-link" >Registration</a></li>
        <li class="nav-item"><a href="#" class="nav-link active"  data-toggle="pill">Login</a></li>
        </ul>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="username" class="form-control" placeholder="Enter Email" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
       <div class="form-group">
            <button type="submit" class="btn btn-primary" value="Login">Submit</button>
          </div>
     
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form> 
    
</body>
</html>




