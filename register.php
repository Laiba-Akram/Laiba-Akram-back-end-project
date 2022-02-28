<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $username = $password = $confirm_password = "";
$name_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    }
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
        <li class="nav-item"><a href="#" class="nav-link active"  data-toggle="pill">Registration</a></li>
        <li class="nav-item"><a href="login.php" class="nav-link " >Login</a></li>
        </ul>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter Name" value="<?php echo $name; ?>">
        <span class="help-block"><?php echo $name_err; ?></span>
    </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="username" class="form-control" placeholder="Enter Username" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
            <button type="submit" class="btn btn-primary" value="Login">Submit</button> 
            <button type="reset" class="btn btn-default" value="Reset">Reset</button>
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>

            <!-- <div class="form">
      
      <ul class="tab-group">
        <li class="tab active"><a href="#signup">Sign Up</a></li>
        <li class="tab"><a href="#login">Log In</a></li>
      </ul>
      
      <div class="tab-content">
        <div id="signup">   
          <h1>Sign Up for Free</h1>
          
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          
          <div class="top-row">
            <div class="field-wrap  <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off"  name="name" class="form-control"  value="<?php echo $name; ?>">
               <span class="help-block"><?php echo $name_err; ?></span>
            </div>
        
            <div class="field-wrap<?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text"required autocomplete="off" name="name" class="form-control"  value="<?php echo $name; ?>">
               <span class="help-block"><?php echo $name_err; ?></span>
            </div>
          </div>

          <div class="field-wrap <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" required autocomplete="off" name="username" class="form-control" value="<?php echo $username; ?>">
             <span class="help-block"><?php echo $password_err; ?></span>
          </div>
          
          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
          </div>
          <div class="field-wrap <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
          <button type="submit" class="button button-block"/>Get Started</button>
           
           </form>

        </div> 
 

        </form>
    </div>     -->
</body>
</html>