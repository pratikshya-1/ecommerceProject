<?php 
session_start();
// $productId=1;
// $productName="dell";
// $productDescription="description";
// $productPrice=100;
// $productQty=1;
// $imgLoc="img.png";
// $selInventoryId=11;


$productId=$_POST['productId'];
$productName=$_POST['productName'];
$productDescription=$_POST['productDescription'];
$productPrice=$_POST['productPrice'];
$productQty=$_POST['productQty'];
$imgLoc=$_POST['imgLoc'];
$selInventoryId=$_POST['selInventoryId'];




$addNewItem=true;
$qty=0;
$updateProduct=false;


//   foreach ($_SESSION['cart'] as $key => $value) {
//     echo "key".$key;

//     echo "value".$value;

//     echo "pid=".$value['productId']."<br>";

//   if($value['productId']==$productId){
//     echo "equeal";

//   	$qty=$value['productQty']+$productQty;

//     echo "qty".$qty;

  	 

//   	 $productToRemove=$key;

//       // unset($_SESSION['cart'][$key]);



//     $updateProduct=true;

//   	$addNewItem=false;
//   }
// }

//if($updateProduct){



for ($i = 0; $i < count($_SESSION['cart']); $i++)
{
   if($_SESSION['cart'][$i]['productId'] ==$productId);
   {
      //unset($_SESSION['cart'][$i]);

    echo "update qty";
    var_dump($_SESSION['cart'][$i]);
       $_SESSION['cart'][$i]['productQty'] =$_SESSION['cart'][$i]['productQty']+$productQty;


echo "update after";
        var_dump($_SESSION['cart'][$i]);

        $addNewItem=false;
   }
}



 // $product=array("productId"=>"$productId","productName"=>"$productName","productDescription"=>"$productDescription","productPrice"=>"$productPrice","productQty"=>"$qty","imgLoc"=>"$imgLoc","inventoryId"=>"$selInventoryId");


     // $result=array_diff($_SESSION['cart'],$product);

    //  $_SESSION['cart']=array_diff($_SESSION['cart'],$product);

 //$removedArray=array_diff($_SESSION['cart'],$product);

 //unset($_SESSION['cart']);
 //session_destroy();

 //$_SESSION['cart']=$removedArray;


    //  $product["productQty"]=$qty;

 echo "remove key=".$productToRemove;

       //unset($_SESSION['cart'][$productToRemove]);

    //   array_push($_SESSION['cart'],$product);

       echo "old item added"; 


//}

if($addNewItem){

  echo "new item place";
	  $product=array("productId"=>$productId,"productName"=>"$productName","productDescription"=>"$productDescription","productPrice"=>"$productPrice","productQty"=>$productQty,"imgLoc"=>"$imgLoc","inventoryId"=>"$selInventoryId");
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
