<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
if (isset($_POST['addProduct'])){

	include("../connect.php");


	   $productName = $_POST['productName'];
	 $productDesc = $_POST['productDesc'];
	 $price = $_POST['price'];
	  $imageName ="imagename.png";
	 // $date = 
	  $status=1;


	 	 date_default_timezone_set('Asia/Kathmandu');
	 $addedDate = date('d-m-Y h:i:s'); 





 $productImage=$_FILES['productImage']['name']; 
            $temp = explode(".", $_FILES["productImage"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
             move_uploaded_file($_FILES["productImage"]["tmp_name"], "../assets/images/" . $newfilename);

             $imageName=$newfilename;









	 $sql = "INSERT INTO product_tbl (name,description,image_name,price,added_date,status) VALUES ('$productName','$productDesc','$imageName',$price,'$addedDate',$status)";
if(mysqli_query($dbConnect, $sql)){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbConnect);
}
 
mysqli_close($dbConnect); 




	
}


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="../assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/customStyle.css"> </head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 mx-auto">
				<form action="add_product.php" method="POST" enctype="multipart/form-data" >
					<div class="form-group">
						<label for="productName">Product Name</label>
						<input type="text" class="form-control" id="productName" name="productName" aria-describedby="productName" placeholder="Enter product name">
					</div>
					<div class="form-group">
						<label for="exampleFormControlTextarea1">Product Description</label>
						<textarea class="form-control" id="exampleFormControlTextarea1" name="productDesc" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="price">Price</label>
						<input type="number" class="form-control" id="price" name="price" aria-describedby="priceHelp" placeholder="Enter product price">
					</div>

					<div class="form-group">
						<label for="price">Product Image</label>
						<input type="file"  name="productImage"  id="addImg" required> 
					</div>
					<button type="submit" name="addProduct" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</body>

</html>