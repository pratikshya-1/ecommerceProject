<?php
session_start();
include("connect.php");
$totalCartAmount=0;

include "functions.php";
if (isset($_POST["login"])) {
    include "connect.php";
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM user_tbl where username='$username' and password='$password' LIMIT 1";


     $result = mysqli_query($dbConnect,$sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["USERLOGIN"] = true;

        while ($row = mysqli_fetch_array($result)) { 
         $_SESSION['ISLOGIN']="TRUE";        
         $_SESSION["USERNAME"]=$row['username'];
         $_SESSION['USERID']=$row['u_id'];
        } 

       
        redirect("checkout.php?msg=loginsuccess");
    } else {
        redirect("checkout.php?msg=invaliduser");
    }
}


if(isset($_POST['checkoutConnectIps'])){


  function generateHash($string,$certificate) {
        if (!$cert_store = file_get_contents("$certificate")) {
          echo "Error: Unable to read the cert file\n";
          exit;
        }
        
        if (openssl_pkcs12_read($cert_store, $cert_info, "123")) {
          if($private_key = openssl_pkey_get_private($cert_info['pkey'])){
            $array = openssl_pkey_get_details($private_key);
              
          }
        } else {
          echo "Error: Unable to read the cert store.\n";
          exit;
        }
        $hash = "";  
        if(openssl_sign($string, $signature , $private_key, "sha256WithRSAEncryption")){      //computes a signature for the specified data(string) by generating a cryptographic digital signature using the private key associated with private_key.
          $hash = base64_encode($signature);
        //  $hash = base32_encode($signature);
          openssl_free_key($private_key);   //frees the key associated with the specified key from memory.
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }    
        return $hash;
    }


  $totalPaymentAmount=0;

    $stmt = $dbConnect->prepare("INSERT INTO oder_tbl (user_id,payment_gateway_id,payment_status, status) VALUES (?, ?,?,?)");
      $stmt->bind_param("iiii", $userId, $paymentGatewayId,$paymentStatus,$status);

      // set parameters and execute
      $userId =$_SESSION['USERID'];
      $paymentGatewayId = 1;
      $status =1;
      $paymentStatus=0;
      $stmt->execute();
      $orderId=$stmt->insert_id;

      $stmt->close();


    foreach ($_SESSION['cart'] as $key => $value) {
        $stmt = $dbConnect->prepare("INSERT INTO order_item_tbl (order_id,product_id,inventory_id,qty,price,order_date,status) VALUES (?,?,?,?,?,?,?)");
      $stmt->bind_param("iiiddsi", $orderId,$productId,$inventoryId,$qty,$price,$orderDate,$status);

      // set parameters and execute

      $productId =$value['productId'];
      $inventoryId =$value['inventoryId'];
      $qty=$value['productQty'];
      $price=$value['productPrice'];
      $orderDate="2022-10-22";
      $status=1;

      $totalPaymentAmount=$totalPaymentAmount+$qty*$price;
      $stmt->execute();

      $stmt->close();


   }


  $merchantId="980";
  $appId="MER-980-APP-1";
  $appName="Ecommerce Site";
  $tranId=$orderId;
  $tranDate="30-09-2020";
  $tranCurrency="NPR";
  $tranAmt=$totalPaymentAmount*100;  //converting RS into Paisa
  $refId=$orderId;
  $remarks="Test payment";
  $particulars="Test Payment";
  $certificate="CREDITOR.pfx";

  $data="MERCHANTID=$merchantId,APPID=$appId,APPNAME=$appName,TXNID=$tranId,TXNDATE=$tranDate,TXNCRNCY=$tranCurrency,TXNAMT=$tranAmt,REFERENCEID=$refId,REMARKS=$remarks,PARTICULARS=$particulars,TOKEN=TOKEN";
  
  $token = generateHash($data,$certificate);


  ?>
  <form action="https://uat.connectips.com:7443/connectipswebgw/loginpage" method="post" id="cipsLoginForm">
  <input type="hidden" name="MERCHANTID" id="MERCHANTID" value="<?php echo $merchantId; ?>" readonly/>
   <input type="hidden" name="APPID" id="APPID" value="<?php echo $appId; ?>" readonly/> 
   <input type="hidden" name="APPNAME" id="APPNAME" value="<?php echo $appName; ?>" readonly/>
   <input type="hidden" name="TXNID" id="TXNID" value="<?php echo $tranId; ?>" readonly/> 
   <input type="hidden" name="TXNDATE" id="TXNDATE" value="<?php echo $tranDate;  ?>" readonly/>
   <input type="hidden" name="TXNCRNCY" id="TXNCRNCY" value="<?php echo $tranCurrency; ?>" readonly/>
   <input type="hidden" name="TXNAMT" id="TXNAMT" value="<?php echo $tranAmt;  ?>"readonly/>
  <input type="hidden" name="REFERENCEID" id="REFERENCEID" value="<?php echo $refId; ?>" readonly/>
   <input type="hidden" name="REMARKS" id="REMARKS" value="<?php echo $remarks;  ?>" readonly/> 
  <input type="hidden" name="PARTICULARS" id="PARTICULARS" value="<?php echo $particulars;  ?>" readonly/>
  <input type="hidden" name="TOKEN" id="TOKEN" rows="9" value="<?php echo $token;?>" readonly >
  
 </form>

 <script>
  document.getElementById("cipsLoginForm").submit();   //auto submit form

</script>







  <?php

} else if(isset($_POST['checkoutEsewa'])){



   $totalPaymentAmount=0;

    $stmt = $dbConnect->prepare("INSERT INTO oder_tbl (user_id, payment_gateway_id,payment_status,status) VALUES (?,?,?,?)");
      $stmt->bind_param("iiii", $userId, $paymentGatewayId,$paymentStatus,$status);

      // set parameters and execute
      $userId =$_SESSION['USERID'];
      $paymentGatewayId = 2;
      $status =1;
      $paymentStatus=0;
      $stmt->execute();
      $orderId=$stmt->insert_id;

      $stmt->close();


    foreach ($_SESSION['cart'] as $key => $value) {
        $stmt = $dbConnect->prepare("INSERT INTO order_item_tbl (order_id,product_id,inventory_id,qty,price,order_date,status) VALUES (?,?,?,?,?,?,?)");
      $stmt->bind_param("iiiddsi", $orderId, $productId, $inventoryId,$qty,$price,$orderDate,$status);

      // set parameters and execute

      $productId =$value['productId'];
      $inventoryId =$value['inventoryId'];
      $qty=$value['productQty'];
      $price=$value['productPrice'];
      $orderDate="2022-10-22";
      $status=1;

      $totalPaymentAmount=$totalPaymentAmount+$qty*$price;
      $stmt->execute();

      $stmt->close();




   }


   ?>


    <form action="https://uat.esewa.com.np/epay/main" id="esewaForm" method="POST">
    <input value="<?php echo $totalPaymentAmount;?>" name="tAmt" type="hidden">
    <input value="<?php echo $totalPaymentAmount;?>" name="amt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">
    <input value="EPAYTEST" name="scd" type="hidden">
    <input value="ee2c3ca1-696b-4cc5-a6be-2c40d929d453" name="pid" type="hidden">
    <input value="http://localhost/esewa_payment_success?q=su" type="hidden" name="su">
    <input value="http://localhost/esewa_payment_failed?q=fu" type="hidden" name="fu">
    </form>


   <script>
    document.getElementById("esewaForm").submit();   //auto submit form
  </script>




   <?php








}












?>

<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">

<div class="container">
  <h2>Your Cart</h2>


             <?php if(isset($_GET['msg'])&& $_GET['msg']=="invaliduser")
            {?>
             <div style="font-size:18px; color:#FF0000;">Invalid Username or passowrd</div>
             <?php }


              if(isset($_GET['msg'])&& $_GET['msg']=="loginsuccess")
            {
            ?>
             <font color="#009900">Login Successful.</font>
       <?php } ?>

  <form action="#" method="POST">          
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Image</th>
        <th>Qty</th>
        <th>Price per unit</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

<?php 
   
     foreach ($_SESSION['cart'] as $key => $value) {
     	$totalCartAmount=$totalCartAmount+($value['productPrice']*$value['productQty']); 
        ?>

         <tr>
        <td><?php echo $value['productName']; ?></td>
        <td><img src="assets/images/<?php echo $value['imgLoc']; ?>" height="100px" width="100px"></td>
        <td><?php echo $value['productQty']; ?></td>
        <td><?php echo $value['productPrice']; ?></td>
        <td><?php echo $value['productPrice']*$value['productQty']; ?></td>
        <td><button type="button" class="btn btn-danger" onClick="removeProductFromCart('<?php echo $key; ?>')">Remove</button></td>
      </tr>




        <?php
    }
?>


  <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total = <?php echo $totalCartAmount;?> </td>
        <td>
          <!-- <button type="submit" name="checkout" id="checkout" class="btn btn-success">Checkout</button> -->
          Checkout
            <?php if(isset($_SESSION['ISLOGIN'])) { ?>
          <button type="submit" name="checkoutConnectIps" id="checkoutConnectIps" class="btn"><img src="assets/images/cips_logo.png" height="50px" width="50px"></button>


            <button type="submit" name="checkoutEsewa" id="checkoutEsewa" class="btn"><img src="assets/images/esewa_epay_logo.png" height="50px" width="50px"></button>
          <?php }else { ?>

            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#loginModal">
  Login to checkout
</button>

            <?php

          } ?>

        </td>
      </tr>

     
   
    </tbody>
  </table>
  </form>
</div>


<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
      <div class="modal-body">
          <div class="row">
    <div class="col-md-9 mx-auto">
      <form class="form-signin" action="#" method="POST">
        <h1 class="h3 mb-3 font-weight-normal text-center"> Sign in</h1>

         

        


        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <div class="mb-3"></div>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        <div class="mb-3">
          <a href="forgetPassword.php">Forget Password</a>
        </div>
        <div class="col-md-6 mx-auto"><button name="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button></div>
        <div class="mb-3"> Does not have an account? <a href="register.php"> New Register</a>
        </div>
      </form>
    </div>
  </div>
      </div>
    
    </div>
  </div>
</div>


<!-- JS code -->
<script src="assets/js/jquery-3.1.1.min.js">
</script>
<script src="assets/js/ajax.tether.min.js">
</script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<!--JS below-->

<script type="text/javascript">
	
	function removeProductFromCart(productKey){
  alert(productKey);




   $.ajax({    
        type: "POST",
        url: "delete_product_from_cart.php",             
        dataType: "json",   
         data : {productKey :productKey},            
        success: function(data){ 
        if(data.status=="success"){
        	window.location.reload();
          
           
        }
                       
        },
        error: function(response) {
        console.log('ERROR BLOCK');
        console.log(response);
    }


    });
}

</script>