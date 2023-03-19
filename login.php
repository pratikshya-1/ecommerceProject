<?php
@session_start();
include "functions.php";
if (isset($_POST["login"])) {
    include "connect.php";
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password=md5($password);
    $sql = "SELECT * FROM user_tbl where username='$username' and password='$password' LIMIT 1";


     $result = mysqli_query($dbConnect,$sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["USERLOGIN"] = true;

        while ($row = mysqli_fetch_array($result)) { 
         $_SESSION['ISLOGIN']="TRUE";        
         $_SESSION["USERNAME"]=$row['username'];
         $_SESSION['USERID']=$row['u_id'];
        } 

       
        redirect("index.php?msg=loginsuccess");
    } else {
        redirect("login.php?msg=invaliduser");
    }
}
?>

 <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">
<div class="container">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <form class="form-signin" action="#" method="POST">
        <h1 class="h3 mb-3 font-weight-normal text-center"> Sign in</h1>

          
             <?php if(isset($_GET['msg'])&& $_GET['msg']=="invaliduser")
            {?>
             <div style="font-size:18px; color:#FF0000;">Invalid Username or passowrd</div>
             <?php }?>

        


        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <div class="mb-3"></div>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        <div class="mb-3">
          <a href="forgetPassword.php">Forget Password</a>
        </div>
        <button name="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <div class="mb-3"> Does not have an account? <a href="register.php"> New Register</a>
        </div>
      </form>
    </div>
  </div>
</div>