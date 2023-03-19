<?php
  ini_set('display_errors', 1);
error_reporting(~0);
	include("functions.php");



if (isset($_POST['registerUserBtn'])){
	include("connect.php");


	 $firstName = $_POST['firstName'];
	 $middleName = $_POST['middlName'];
	 $lastName = $_POST['lastName'];
	 $email = $_POST['email'];
	 $mobileNo = $_POST['mobileNo'];
	 $username = $_POST['username'];
	 $password = $_POST['password'];
	 $confirmPassword = $_POST['confirmPassword'];
	 $country = $_POST['country'];
	 $city = $_POST['city'];
	 $street = $_POST['street'];
	 $status=0;

   $verifcationCode = random_int(100000, 999999);  //generate random verification code


	 function checkUserAlreadyRegistered($username,$password,$dbConnect){
	 	 $sql = "SELECT * FROM user_tbl where username='$username' and password='$password'";
     
         $result = $dbConnect->query($sql);
           
         if ($result->num_rows > 0) {
         return	true;
         }

         return false;


	 }


//check if the required field is null or empty
	 if(isNullOrEmptyString($firstName) || isNullOrEmptyString($lastName)|| isNullOrEmptyString($email) || isNullOrEmptyString($mobileNo) || isNullOrEmptyString($username) || isNullOrEmptyString($password) || isNullOrEmptyString($confirmPassword) || isNullOrEmptyString($country) || isNullOrEmptyString($city) || isNullOrEmptyString($street) || ($password!=$confirmPassword)){

	 	redirect("register.php?msg=invalidInput");

	 }else if(checkUserAlreadyRegistered($username,$password,$dbConnect)){
	 	//check if user already register
	  // $sql = "SELECT * FROM user_tbl where username='$username' and password='$password'";
     
   //       $result = $dbConnect->query($sql);


           
   //       if ($result->num_rows > 0) {
   //       	redirect("register.php?msg=userAlreadyRegister");
   //       }

	 	redirect("register.php?msg=userAlreadyRegister");

	 }else{



//insert into table

	 date_default_timezone_set('Asia/Kathmandu');
	 $registerDate = date('d-m-Y h:i:s');  


$password=md5($password);
	  $stmt = $dbConnect->prepare("INSERT INTO user_tbl (username,password,email,mobile_no,first_name,middle_name,last_name,country,city,street,verification_code,register_date,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssssssssi',$username,$password,$email,$mobileNo,$firstName,$middleName,$lastName,$country,$city,$street,$verifcationCode,$registerDate,$status); 


    
    $stmt->execute();
    $stmt->close();




    //send verification mail




    // require 'phpmailer/PHPMailerAutoload.php';
    // $mail = new PHPMailer;



    // //send mail

    //       $mail->SMTPDebug = 4;  //for debugging
    

    //      $mail->isSMTP(); // Set mailer to use SMTP
    //       $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    //       $mail->SMTPAuth = true;                               // Enable SMTP authentication
    //       $mail->Username = 'test@gmail.com';                 // SMTP username
    //       $mail->Password = '1234';                           // SMTP password
    //       $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    //       $mail->Port = 587;                                    // TCP port to connect to

    //       $mail->setFrom('test@gmail.com', 'Ecommerce Site');

    //       $mail->addAddress($email, 'ecommerce site');

    //       $mail->isHTML(true);  

    //       $mail->Subject = 'Email Verification';
    //       $mail->Body    = 'Your email verification code is $verifcationCode';


    //        if ($mail->send()){
    //        	//echo "mail send";
    //        }






    //

    redirect("index.php?msg=userRegisterSuccess");

}


}

?>



<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/customStyle.css">
<div class="container mt-2 mb-3">
    <form name="userRegistrationForm" action="register.php" method="POST" onsubmit="return validateForm()">
        <div class="row">
            <div class="col-md-12">User Registration Form

                <?php if(isset($_GET['msg'])&& $_GET['msg']=="invalidInput")
			{?>
                <p style="color:red;">Invalid details provided.</p>
                <?php }
			 if(isset($_GET['msg'])&& $_GET['msg']=="userAlreadyRegister")
			{
						?>
                <p style="color:red;"> User already registered.</p>
                <?php }?>



            </div>
        </div>
        <hr class="mt-2 mb-3" />
        <div class="card mt-3">
            <div class="card-header">User Detail</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName"
                                aria-describedby="firstName" placeholder="Enter first name">
                            <div id="firstNameError" class="form-error-message"></div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middleName">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" name="middlName"
                                aria-describedby="middleName" placeholder="Enter middle name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName"
                                aria-describedby="lastName" placeholder="Enter last name">
                            <div id="lastNameError" class="form-error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="email"
                                placeholder="Enter email">
                            <div id="emailError" class="form-error-message"></div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mobileNo">Mobile Number</label>
                            <input type="number" class="form-control" id="mobileNo" name="mobileNo"
                                aria-describedby="mobileNo" placeholder="Enter mobile number">
                            <div id="mobileNoError" class="form-error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-4"> </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">Login Detail</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                aria-describedby="username" placeholder="Enter usernmae">
                            <div id="usernameError" class="form-error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                aria-describedby="password" placeholder="Enter password">
                            <div id="passwordError" class="form-error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                aria-describedby="confirmPassword" placeholder="Enter confirm password">
                            <div id="confirmPasswordError" class="form-error-message"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">Address Detail</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                aria-describedby="country" placeholder="Enter contry">
                            <div id="countryError" class="form-error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" aria-describedby="city"
                                placeholder="Enter city">
                            <div id="cityError" class="form-error-message"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">Street</label>
                            <input type="text" class="form-control" id="street" name="street" aria-describedby="street"
                                placeholder="Enter street">
                            <div id="streetError" class="form-error-message"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <button class="btn btn-secondary ">Cancel</button>
                <button class="btn btn-info " type="submit" name="registerUserBtn">Submit</button>
            </div>
        </div>
    </form>

</div>

<script src="assets/js/jquery.min.js"></script>

<script type="text/javascript">
function checkEmpty(str) {
    return $.trim(str).length > 0;
}



function validateForm() {

    var formValidationStatus = true;

    var firstName = $('#firstName').val();





    if (!checkEmpty(firstName)) {
        $('#firstNameError').empty();
        $("#firstNameError").append("First name should not be empty.");
        formValidationStatus = false;
    } else {
        $('#firstNameError').empty();
    }

    var lastName = $('#lastName').val();

    if (!checkEmpty(lastName)) {
        formValidationStatus = false;
        $("#lastNameError").append("Last name should not be empty.");
    } else {
        $('#lastNameError').empty();
    }


    var email = $('#email').val();

    if (!checkEmpty(email)) {
        formValidationStatus = false;
        $("#emailError").append("Email should not be empty.");
    } else {
        $('#emailError').empty();
    }


    var mobileNo = $('#mobileNo').val();

    if (!checkEmpty(mobileNo)) {
        formValidationStatus = false;
        $("#mobileNoError").append("Mobile number should not be empty.");
    } else {
        $('#mobileNoError').empty();
    }


    var username = $('#username').val();

    if (!checkEmpty(username)) {
        formValidationStatus = false;
        $("#usernameError").append("Username should not be empty.");
    } else {
        $('#usernameError').empty();
    }

    var password = $('#password').val();

    if (!checkEmpty(password)) {
        formValidationStatus = false;
        $("#passwordError").append("Password should not be empty.");
    } else {
        $('#passwordError').empty();
    }


    var confirmPassword = $('#confirmPassword').val();

    if (!checkEmpty(confirmPassword)) {
        formValidationStatus = false;
        $("#confirmPasswordError").append("Confirm Password should not be empty.");
    } else {
        $('#confirmPasswordError').empty();
    }


    var country = $('#country').val();

    if (!checkEmpty(country)) {
        formValidationStatus = false;
        $("#countryError").append("Country name should not be empty.");
    } else {
        $('#countryError').empty();
    }


    var city = $('#city').val();

    if (!checkEmpty(city)) {
        formValidationStatus = false;
        $("#cityError").append("City name should not be empty.");
    } else {
        $('#cityError').empty();
    }

    var street = $('#street').val();

    if (!checkEmpty(street)) {
        formValidationStatus = false;
        $("#streetError").append("Street name should not be empty.");
    } else {
        $('#streetError').empty();
    }


    return formValidationStatus;


    /*

       $.ajax({    //create an ajax request 
            type: "POST",
            url: "../data/check_user.php",             
            dataType: "json",  
             data : {username :username,mobileNo:mobileNo,email:email},            
            success: function(data){ 
            console.log('success');

            console.log(data);
            console.log(data.username);
            if(!data.username){
            	formValidationStatus=false;
              $("#usernameError").append("Username already used.");
            }else{
            	$('#usernameError').empty();
            }
            if(!data.email){
            	formValidationStatus=false;
            	$("#emailError").append("Email already used.");
            }else{
            	$('#emailError').empty();
            }

            if(!data.mobileNo){
            	formValidationStatus=false;
            	$("#mobileNoError").append("Mobile number already used.");
            }else{
            	$('#mobileNoError').empty();
            }

                           
              
            },
            error: function(response) {
            	formValidationStatus=false;
            console.log('ERROR BLOCK');
            console.log(response);
        }


        });

      */


}
</script>