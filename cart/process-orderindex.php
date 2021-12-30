<main>
  <div class = "list">
    <div class = "cartdiv">

<?php


if(!empty($_POST["proceed_payment"])) {
    $email = $_POST ['email'];
}
$order = 0;
if (! empty ($email)) {
    // able to insert into database

    $order = $shoppingCart->insertOrder ( $_POST, $member_id, $item_price);

    if(!empty($order)) {
        if (! empty($cartItem)) {
            if (! empty($cartItem)) {
                foreach ($cartItem as $item) {
                    $shoppingCart->insertOrderItem ( $order, $item["id"], $item["price"], $item["quantity"]);
                }
            }
        }
    }
}
?>

<?php $errormessage=""; ?>

<div id="shopping-cart">
        <div class="txt-heading">
            <div class="txt-heading-label">Shopping Cart</div>

            <a id="btnEmpty" href="index.php?action=empty"><img
                src="image/empty-cart.png" alt="empty-cart"
                title="Empty Cart" class="float-right" /></a>
            <div class="cart-status">
                <div>Total Quantity: <?php echo $item_quantity; ?></div>
                <div>Total Price: € <?php echo $item_price; ?></div>
            </div>
        </div>
        <?php
        if (! empty($cartItem)) {
            ?>
<?php
            require_once ("cart-list.php");
            ?>

<div class="formtext3">Giftcard(s) will be sent to <?php echo $email; ?>.</div>

<?php
        } // End if !empty $cartItem
        ?>

</div>


<?php


function checkAvailable($order)
{

        $servername = "localhost";
        $username = "gcadmin";
        $password = "GCpass@86";
        $dbname = "giftcard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT giftcard.id, giftcard.sold FROM ((tbl_order INNER JOIN tbl_order_item ON tbl_order.id = tbl_order_item.order_id)
        INNER JOIN giftcard ON tbl_order_item.product_id = giftcard.id) WHERE tbl_order.id = $order";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            //echo "id: " . $row["id"]. " - Customer ID: " . $row["customer_id"]. " " . $row["email"]. "<br>";
            $error = "process-order.php";
            $succes = "http://86.93.145.183/cardtrader/user/paypalapi/request.php";

            if ($row["sold"] == 1){
              return $error;
            }
          }
        } else {
          echo "0 results";
        }
        $conn->close();

        return $succes;
}



?>



<?php if(!empty($order)) { ?>





    <form name="frm_customer_detail" action="<?php echo checkAvailable($order);?>" method="POST">
    <input type='hidden'
							name='business' value='sb-dbco07547256@business.example.com'> <input
							type='hidden' name='item_name' value='Cart Item'> <input
							type='hidden' name='item_number' value="<?php echo $order;?>"> <input
							type='hidden' name='amount' value='<?php echo $item_price; ?>'> <input type='hidden'
							name='currency_code' value='EUR'> <input
							type="hidden" name="order" value="<?php echo $order;?>">
    <div>
        <input type="submit" class="btn-action"
                name="continue_payment" value="Continue Payment">
    </div>
    </form>
<?php } else { ?>
<div class="error">Error: one of the giftcards is sold out. Please try again!</div>
<div>
        <button class="btn-action">Back</button>
    </div>
<?php } ?>

</div>
</div>
</main>
