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
$$qty=0;

  foreach ($_SESSION['cart'] as $key => $value) {
  if($value['productId']==$productId){
  	$qty=$value['productQty']+$productQty;

  	  $product=array("productId"=>"$productId","productName"=>"$productName","productDescription"=>"$productDescription","productPrice"=>"$productPrice","productQty"=>"$productQty","imgLoc"=>"$imgLoc","inventoryId"=>"$selInventoryId");

       array_push($_SESSION['cart'],$product); 

  	 

     //  unset($_SESSION['cart'][$key]);



  	$addNewItem=false;
  }
}

if($addNewItem){
	  $product=array("productId"=>"$productId","productName"=>"$productName","productDescription"=>"$productDescription","productPrice"=>"$productPrice","productQty"=>"$productQty","imgLoc"=>"$imgLoc","inventoryId"=>"$selInventoryId");
    array_push($_SESSION['cart'],$product);  
}
// else{
//    $product=array("productId"=>"$productId","productName"=>"$productName","productDescription"=>"$productDescription","productPrice"=>"$productPrice","productQty"=>"$qty","imgLoc"=>"$imgLoc");
//     array_push($_SESSION['cart'],$product);  

// }





$totalCartItemCount=0;

$max=sizeof($_SESSION['cart']);
for($i=0; $i<$max; $i++) { 

while (list ($key, $val) = each ($_SESSION['cart'][$i])) { 
  if($key=="productQty"){
      $totalCartItemCount=$totalCartItemCount+$val;
  }

} 

}

$_SESSION['totalCartItemCount']=$totalCartItemCount;


$data = array("status" => "success","totalCartItemCount"=>$totalCartItemCount);

header("Content-Type: application/json");
echo json_encode($data);

?>
