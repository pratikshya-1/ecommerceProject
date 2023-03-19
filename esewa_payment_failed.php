<?php
session_start();
if(isset($_SESSION['PAYMENTINIT'])){
include("connect.php");
$totalCartAmount=0;
$orderId=$_SESSION['ORDERID'];

//payment_status=1 failed
$sql = "UPDATE order_tbl  SET `payment_status`='1' WHERE id='$orderId'";

if(mysqli_query($dbConnect, $sql)){
    echo "Records updated successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbConnect);
}




?>

<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">


<div class="row">
    <div class="col-md-3 mx-auto">
        <h3 style="color:red;">Sorry your payment is failed</h3>
    </div>
</div>

<div class="col-md-5  mx-auto">
    <div class="row" style="border:1px solid #dee2e6;padding: 5px;">
        <?php 
   
     foreach ($_SESSION['cart'] as $key => $value) {
      $totalCartAmount=$totalCartAmount+($value['productPrice']*$value['productQty']); 
        ?>
        <div class="card mb-3 mx-auto">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div>
                            <img src="assets/images/<?php echo $value['imgLoc']; ?>" class="img-fluid rounded-3"
                                alt="Shopping item" style="width: 65px;">
                        </div>
                        <div class="mx-4">
                            <h5><?php echo $value['productName']; ?></h5>
                            <p class="small mb-0">NRS.
                                <?php echo $value['productPrice']; ?>(Qty:<?php echo $value['productQty']; ?>)</p>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center">
                        <div style="width: 80px;">
                            <h5 class="fw-normal mb-0"></h5>
                        </div>
                        <div style="width: 140px;">
                            <h5 class="mb-0">NRS.<?php echo $value['productPrice']*$value['productQty']; ?></h5>
                        </div>
                        <a href="#!" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
?>

    </div>
    <div class="col-md-6 card-body mx-auto font-weight-bold">
        Total Payment Amount=<?php echo $totalCartAmount;?>
    </div>
    <a href="http://localhost/ecommerce-project/"><button type="button" class="btn btn-info">Return</button></a>
</div>
</div>


<?php
}?>