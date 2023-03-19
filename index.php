<?php
  ini_set('display_errors', 1);
error_reporting(~0);
session_start();

if (!isset($_SESSION['cart'])){
$_SESSION['cart']=array(); 
}



 include("connect.php");
?>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/customStyle.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>


                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 mr-5 px-5 my-sm-0" type="submit">Search</button>

                        <button class="btn btn-outline-success my-2 mx-2 my-sm-0" type="submit"><a
                                href="checkout.php">Checkout</a> <span class="badge"
                                id="totalCartItemCount"><?php if(isset($_SESSION['totalCartItemCount'])){echo $_SESSION['totalCartItemCount'];} ?></span></button>
                        <?php if(isset($_SESSION['ISLOGIN'])) { ?>
                        <button class="btn btn-outline-success my-2 mx-2 my-sm-0" id="logout" onclick="logoutUser()"
                            type="submit"><a href="#">Logout</a></button>

                        <?php } else {?>
                        <button class="btn btn-outline-success my-2 mx-2 my-sm-0" id="login" type="submit"><a
                                href="login.php">Login</a></button>
                        <?php }?>
                        <a href="register.php"><input type="button" value="Register"
                                class="btn btn-outline-success my-2 mx-2 my-sm-0"></a>
                    </form>
                </div>
            </nav>
        </div>
        <div class="main-container">
            <?php  if(isset($_GET['msg'])&& $_GET['msg']=="userRegisterSuccess")
      {
      ?><p style="color:green;">Thank you ...You have successfully registered.</p>
            <?php } 
             if(isset($_GET['msg'])&& $_GET['msg']=="loginsuccess")
            {
            ?>
            <font color="#009900">Login Successful.</font>
            <?php } ?>

            <img src="assets/images/banner.jpg" width="100%">
            <div class="products py-4">
                <div class="row">

                    <?php
include("connect.php");
 $sql = "SELECT * FROM product_tbl where p_status='1' order by id desc ";
     
         $result = $dbConnect->query($sql);
           
         if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){  
                  ?>
                    <div class="col-md-3">
                        <div class="card">
                            <img class="card-img-top" src="assets/images/<?php echo $row['p_image']; ?>"
                                alt="Card image" style="width:100%;height: 200px;">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $row['product_name'];  ?> </h4>
                                <h6><b>RS <?php echo $row['p_price'];  ?></b></h6>
                                <p class="card-text"><?php echo $row['p_disc'];  ?></p>


                                <!--   <button onclick="clear2()">test</button> -->

                                <button class="btn btn-primary"
                                    onClick="saveProductToCart('<?php echo $row['id']; ?>','<?php echo $row['product_name']; ?>','<?php echo $row['p_disc']; ?>','<?php echo $row['p_image']; ?>','<?php echo $row['p_price']; ?>','<?php echo $row['p_price']; ?>','<?php echo $row['id'];?>');">Add
                                    to cart</button>




                            </div>




                        </div>

                    </div>



                    <?php         
            }
         }

?>






                    <!-- <div class="col-md-3">
                        <div class="card">
                            <img class="card-img-top" src="assets/images/laptop1.jpg" alt="Card image"
                                style="width:100%">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text some example text. John Doe is an architect and
                                    engineer</p>
                                <a href="#" class="btn btn-primary stretched-link">Add to cart</a>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="col-md-3">
                        <div class="card">
                            <img class="card-img-top" src="assets/images/laptop1.jpg" alt="Card image"
                                style="width:100%">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text some example text. John Doe is an architect and
                                    engineer</p>
                                <a href="#" class="btn btn-primary stretched-link">Add to cart</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <img class="card-img-top" src="assets/images/laptop1.jpg" alt="Card image"
                                style="width:100%">
                            <div class="card-body">
                                <h4 class="card-title">John Doe</h4>
                                <p class="card-text">Some example text some example text. John Doe is an architect and
                                    engineer</p>
                                <a href="#" class="btn btn-primary stretched-link">Add to cart</a>
                            </div>
                        </div>
                    </div> -->



                </div>
            </div>
        </div>
        <div class="footer"></div>
    </div>
</body>

</html>



<script src="assets/js/jquery-3.1.1.min.js">
</script>
<script src="assets/js/ajax.tether.min.js">
</script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>


<script type="text/javascript">
function logoutUser() {



    if (confirm("Are you sure you want to logout?") == true) {

        $.ajax({
            type: "POST",
            url: "add_to_cart.php",
            url: "logout.php",
            success: function(data) {
                window.location.reload();

            },
            error: function(response) {

            }


        });
    }

}


//add product to cart

function saveProductToCart(id, name, description, location, price, inventoryId) {

    var selProductQty = 1;



    if (confirm("Are you sure you want to add " + name + " to cart") == true) {


        $.ajax({ //create an ajax request to add product to cart
            type: "POST",
            // url: "add_to_cart.php",             
            url: "add_to_cart.php",
            dataType: "json", //expect json to be returned 
            data: {
                productId: id,
                productName: name,
                productDescription: description,
                productPrice: price,
                productQty: selProductQty,
                imgLoc: location,
                selInventoryId: id
            },
            success: function(data) {
                if (data.status == "success") {
                    $("#totalCartItemCount").empty();
                    $("#totalCartItemCount").append(data.totalCartItemCount);
                    console.log("count" + data.totalCartItemCount);
                    $('#selProductQty').val("");
                }

            },
            error: function(response) {
                $('#selProductQty').val("");
                console.log('ERROR BLOCK');
                console.log(response);
            }


        });

    } else {
        text = "No item added to cart";
    }



}
</script>