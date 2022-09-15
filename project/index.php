<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'المنتج أضيف بالفعل إلى عربة التسوق!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'المنتج يضاف الى عربة التسوق!';
   }

};

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'تم تحديث كمية سلة التسوق بنجاح!';
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:index.php');
}
  
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>FASTMANAGER</title>


<style>
   .form-container form .box{
   width: 100%;
   border-radius: 5px;
   border:var(--border);
   padding:12px 14px;
   font-size: 15px;
   margin:10px 0;
}


.btn,
.delete-btn,
.option-btn{
   display: inline-block;
   padding:10px 4px;
   cursor: pointer;
   font-size: 15px;
   color:var(--white);
   border-radius: 5px;
   text-transform: capitalize;
}

.container .products .box-container .box .name{
   font-size: 15px;
   color:var(--black);
   padding:5px 0;
}












:root{
   --blue:#3498db;
   --red:#e74c3c;
   --orange:#f39c12;
   --black:#333;
   --white:#fff;
   --light-bg:#eee;
   --box-shadow:0 58px 10px rgba(0,0,0,.1);
   --border:2px solid var(--black);
}


*{
   font-family: 'Poppins', sans-serif;
   margin:0; padding:0;
   box-sizing: border-box;
   outline: none; border: none;
   text-decoration: none;
}

*::-webkit-scrollbar{
   width: 0px;
   height: 0px;
}

*::-webkit-scrollbar-track{
   background-color: transparent;
}

*::-webkit-scrollbar-thumb{
   background-color: var(--blue);
}




body{
   background-color: var(--light-bg);
}



.btn:hover,
.delete-btn:hover,
.option-btn:hover{
   background-color: var(--black);
}

.btn{
   background-color: var(--blue);
   margin-top: 10px;
}

.delete-btn{
   background-color: var(--red);
}

.option-btn{
   background-color: var(--orange);
}




.container{
   padding:0 20px;
   margin:0 auto;
   max-width: 744200px;
   padding-bottom: 70px;
}








.container .products .box-container{
   display: flex;
   flex-wrap: wrap;
   gap:45px;
   justify-content: center;
}

.container .products .box-container .box{
   text-align: center;
   border-radius: 1px;
   box-shadow: var(--box-shadow);
   border:var(--border);
   position: relative;
   padding:10px;
   background-color: var(--white);
   width: 110px;

}






.container .products .box-container .box img{
   height: 70px;
}

.container .products .box-container .box .name{
   font-size: 5px;
   color:var(--black);
   padding:5px 0;
}

.container .products .box-container .box .price{
   position: absolute;
   top:1px; left:0px;
   transform: rotateZ(-40deg);
   padding:1px 1px;
   border-radius: 5px;
   background-color: var(--orange);
   color:var(--white);
   font-size: 17px;
}


.container .products .box-container .box input[type="number"]{
   margin:1px 0;
   width: 100%;
   border:var(--border);
   border-radius: 5px;
   font-size: 15px;
   color:var(--black);
   padding:4px 4px
}

.container .shopping-cart{
   padding:20px 0;
}

.container .shopping-cart table{
   width: 70%;
   text-align: center;
   border:var(--border);
   border-radius: 5px;
   box-shadow: var(--box-shadow);
   background-color: var(--white);
}



</style>

</head>
<body>
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">

<div class="user-profile">

   <?php
      $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_user) > 0){
         $fetch_user = mysqli_fetch_assoc($select_user);
      };
   ?>

   <p><h4>User: <span><?php echo $fetch_user['name']; ?></span> </p>
   <div class="flex">
      <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('exit');" class="delete-btn">EXIT</a>
   </div>

</div>

<div class="products">
<center>
   <h1 class="heading">FAST MANAGER</h1><br>
</center>
   <div class="box-container">

   <?php
   include('config.php');
   $result = mysqli_query($conn, "SELECT * FROM products");      
   while($row = mysqli_fetch_array($result)){
   ?>
      <form method="post" class="box" action="">
         <img src="admin/<?php echo $row['image']; ?>"  width="50">
         <div class="name"><?php echo $row['name']; ?></div>
         <div class="price"><?php echo $row['price']; ?></div>
         <input type="number" min="1" name="product_quantity" value="1">
         <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
         <input type="submit" value="add" name="add_to_cart" class="btn">
      </form>
   <?php
      };
   ?>

   </div>

</div>

<div class="shopping-cart">
<center>
   <h1 class="heading">SHOP</h1>
</center>
<center>
   <table>
      <thead>
         <th>photo</th>
         <th>nome</th>
         <th>prix</th>
         <th>number</th>
         <th>prix total</th>
         <th>options</th>
      </thead>
      <tbody>
      <?php
         $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         $grand_total = 0;
         if(mysqli_num_rows($cart_query) > 0){
            while($fetch_cart = mysqli_fetch_assoc($cart_query)){
      ?>
         <tr>
            <td><img src="admin/<?php echo $fetch_cart['image']; ?>" height="75" alt=""></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td><?php echo $fetch_cart['price']; ?>DA </td>
            <td>
               <form action="" method="post">
                  <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                  <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                  <input type="submit" name="update_cart" value="EDIT" class="option-btn">
               </form>
            </td>
            <td><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>DA</td>
            <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm( 'dellet');">DELET</a></td>
         </tr>
      <?php
         $grand_total += $sub_total;
            }
         }else{
            echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">Vide</td></tr>';
         }
      ?>
      <tr class="table-bottom">
         <td colspan="4">TOTAL :</td>
         <td><?php echo $grand_total; ?>DA</td>
         <td><a href="index.php?delete_all" onclick="return confirm('DELLET ALL');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Delet All</a></td>
      </tr>
   </tbody>
   </table>



</div>

</div>

</body>
</html>