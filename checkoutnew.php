<?php
session_start();
include("connect.php");
$totalCartAmount=0;

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

    $stmt = $dbConnect->prepare("INSERT INTO order_tbl (user_id, payment_gateway_id, status) VALUES (?, ?, ?)");
      $stmt->bind_param("iii", $userId, $paymentGatewayId, $status);

      // set parameters and execute
      $userId =1;
      $paymentGatewayId = 1;
      $status =1;
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
   <hiddenarea type="hidden" name="TOKEN" id="TOKEN" rows="9" cols="30" readonly ><?php echo $token; ?></hiddenarea> 
  
 </form>

 <script>
  document.getElementById("cipsLoginForm").submit();   //auto submit form

</script>







  <?php

} else if(isset($_POST['checkoutEsewa'])){



   $totalPaymentAmount=0;

    $stmt = $dbConnect->prepare("INSERT INTO order_tbl (user_id, payment_gateway_id, status) VALUES (?, ?, ?)");
      $stmt->bind_param("iii", $userId, $paymentGatewayId, $status);

      // set parameters and execute
      $userId =1;
      $paymentGatewayId = 1;
      $status =1;
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
      echo "key checkout: $key";
     	$totalCartAmount=$totalCartAmount+($value['productPrice']*$value['productQty']); 
        ?>

         <tr>
        <td><?php echo $value['productName']; ?></td>
        <td><img src="assets/images/<?php echo $value['imgLoc']; ?>" height="100px" width="100px"></td>
        <td><?php echo $value['productQty']; ?></td>
        <td><?php echo $value['productPrice']; ?></td>
        <td><?php echo $value['productPrice']*$value['productQty']; ?></td>
        <td><button type="button" class="btn btn-danger" onClick="removeProductFromCart('<?php echo $key; ?>')">Remove</button>/Edit</td>
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
          <button type="submit" name="checkoutConnectIps" id="checkoutConnectIps" class="btn"><img src="assets/images/cips_logo.png" height="50px" width="50px"></button>


            <button type="submit" name="checkoutEsewa" id="checkoutEsewa" class="btn"><img src="assets/images/esewa_epay_logo.png" height="50px" width="50px"></button>

        </td>
      </tr>

     
   
    </tbody>
  </table>
  </form>
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




   $.ajax({    //create an ajax request to load_page.php
        type: "POST",
        url: "delete_product_from_cart.php",             
        dataType: "json",   //expect html to be returned 
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