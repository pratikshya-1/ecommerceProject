<?php 
session_start();

$productId=$_POST['productId'];
$productName=$_POST['productName'];
$productDescription=$_POST['productDescription'];
$productPrice=$_POST['productPrice'];
$productQty=$_POST['productQty'];
$imgLoc=$_POST['imgLoc'];
$selInventoryId=$_POST['selInventoryId'];




$addNewItem=true;
$totalProduct=0;



//update to cart if product already exist
for ($i = 0; $i < count($_SESSION['cart']); $i++){
   $id=$_SESSION['cart'][$i]['productId'];
   if($id==$productId){
       $_SESSION['cart'][$i]['productQty'] =$_SESSION['cart'][$i]['productQty']+$productQty;

        $addNewItem=false;
   }

}



//add to cart if product does not exist
if($addNewItem){

	  $product=array("productId"=>"$productId","productName"=>"$productName","productDescription"=>"$productDescription","productPrice"=>"$productPrice","productQty"=>"$productQty","imgLoc"=>"$imgLoc","inventoryId"=>"$selInventoryId");
    array_push($_SESSION['cart'],$product);  
}





$totalCartItemCount=0;

$max=sizeof($_SESSION['cart']);  //get the size of unique product in cart
for($i=0; $i<$max; $i++) { 

while (list ($key, $val) = each ($_SESSION['cart'][$i])) { 
  if($key=="productQty"){
      $totalCartItemCount=$totalCartItemCount+$val;
  }

} 

}



for ($i = 0; $i < count($_SESSION['cart']); $i++){
     $totalProduct=$totalProduct+$_SESSION['cart'][$i]['productQty'];
    }

$_SESSION['totalCartItemCount']=$totalProduct;


$data = array("status" => "success","totalCartItemCount"=>$totalProduct,"productId"=>$productId);

header("Content-Type: application/json");
echo json_encode($data);

?>
