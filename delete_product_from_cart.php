<?php 
session_start();

$key=$_POST['productKey'];  //key is the index of array
   
    //  foreach ($_SESSION['cart'] as $key => $value) {
    
    //   echo $key;
    //     if($value['productId']==1){
    //         unset($_SESSION['cart'][$key]);
    //     }
    // }

unset($_SESSION['cart'][$key]);

$totalProduct=0;

for ($i = 0; $i < count($_SESSION['cart']); $i++){
     $totalProduct=$totalProduct+$_SESSION['cart'][$i]['productQty'];
    }

$_SESSION['totalCartItemCount']=$totalProduct;

$data = array("status" => "success");

header("Content-Type: application/json");
echo json_encode($data);
?>