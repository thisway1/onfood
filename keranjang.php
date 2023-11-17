<?php
    include('includes/connect.php');
    include('functions/function_.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial scale=1.0">
        <title>Ken-Ken Food</title>

        <!--CSS File-->
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <style>
            .img_keranjang{
                width: 100px;
                height: 100px;
            }
        </style>
        <!--Link Bootstrap 5.1 CSS-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
        crossorigin="anonymous">
        <!--Font Awesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    </head>
    <body>
    <!--NAVBAR-->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-light bgr-ijoabu"> <!--background navbar-->
            <div class="container-fluid">
                <!--Logo-->
                <img src="../images/logo.png" alt="logo" class="logo">
                <!--End Logo-->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span> <!--navbar icon toggle-->
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="menu.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="display-makanan.php">Makanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <!--Cart-->
                        <li class="nav-item">
                            <a class="nav-link" href="keranjang.php">
                                <i class="fa-solid fa-cart-shopping" style="color: #ecd43c;"></i><sup><?php getAngka_keranjang(); ?></sup>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Total Harga : Rp. <?php getTotalharga();?></a>
                        </li>
                        
                    </ul>
                    <!--Placeholder Search | d-flex = Display Flex-->
                    
                </div>
            </div>
        </nav>
        <!--memanggil fungsi keranjang-->
        <?php
            getKeranjang();
        ?>
        <!--Section 2-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Selamat Datang, Guest</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Login</a>
                </li>
                
            </ul>
        </nav>

        <!--Section 3-->
        <div class="bg-light">
            <h3 class="text-center"><b>MENU MAKANAN</b></h3>
            <p class="text-center">Frozen Food</p>
        </div>

        <div class="container">
            <div class="row">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Food Images</th>
                            <th>Food Title</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Check</th>
                            <th colspan="2">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--PHP Line Code Dynamic-->
                        <?php
                            $getIp = getIPAddress();
                            $total_harga=0;
                            $getKeranjang_q = "SELECT * FROM `tbl_keranjang_detail` WHERE ip_address='$getIp'";
                            $res_q = mysqli_query($conn,$getKeranjang_q);
                                
                            while($row=mysqli_fetch_array($res_q)){
                                $food_id=$row['food_id'];
                                $select_food = "SELECT * FROM `tbl_food` WHERE food_id='$food_id'";
                                $resFood_q = mysqli_query($conn,$select_food);
                        
                                while($rowFoodprice=mysqli_fetch_array($resFood_q)){
                                    $price=array($rowFoodprice['price']);
                                    $tabel_price=$rowFoodprice['price'];
                                    $food_title=$rowFoodprice['food_title'];
                                    $food_img=$rowFoodprice['food_img'];
                                    $price_value=array_sum($price);
                                    $total_harga += $price_value;
                        ?>
                        <tr>
                            <td><img class="img_keranjang" src="../images/<?php echo $food_img?>" alt=""></td>
                            <!--Tabel nama makanan-->
                            <td>
                                <?php
                                    echo $food_title;
                                ?>
                            </td>
                            <!--Form kuantitas-->
<td>
    <input type="text" name="qty" class="form-input w-50">
</td>
<?php
    $getIp = getIPAddress();
    
    if(isset($_POST['update_item'])){
        // Validate Quantity
        if(isset($_POST['qty']) && is_numeric($_POST['qty']) && $_POST['qty'] > 0) {
            $quantities = $_POST['qty'];
            
            // Update Quantity in Database
            $update_item_ = "UPDATE `tbl_keranjang_detail` SET quantity=$quantities WHERE ip_address='$getIp'";
            $res_updateQty = mysqli_query($conn, $update_item_);
            
            if(!$res_updateQty) {
                echo "Error updating quantity: " . mysqli_error($conn);
            } else {
                $total_harga = $total_harga * $quantities;
            }
        } else {
            echo "Error: Invalid quantity input. Please enter a valid number greater than 0.";
        }
    }
?>

                            <td>
                                <?php
                                    echo $tabel_price;
                                ?>
                            </td>
                            <td><input type="checkbox"></td>
                            <td>
                                <input type="submit" value="Update Item" class="btn btn-yellow mx-3" name="update_item">
                                <!--input type="submit" name="remove_keranjang" class="btn btn-yellow" value="Remove Item"-->
                            </td>
                        </tr>
                        <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <div class="d-flex mb-5">
                    <h4 class="px-2">
                        <strong class="text-black">Total : <?php echo $total_harga?></strong>
                    </h4>
                    <a href="menu.php">
                        <button class="btn btn-yellow mx-2">Lanjut Belanja</button>
                    </a>
                    <a href="#">
                        <button class="btn btn-yellow mx-2">Check Out</button>
                    </a>
                </div>
            </div>
        </div>
        <!--Footer-->
        <?php include("includes/footer.php")?>
    </div>
    <!--Bootstrap js link-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
    crossorigin="anonymous"></script>
    </body>
</html>