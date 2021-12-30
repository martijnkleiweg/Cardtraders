<?php
require_once "ShoppingCart.php";

// session_start();
// if (isset($_SESSION["username"])) {
//     $username = $_SESSION["username"];
//
//     $member_id = $_SESSION["id"];
//
//
//     session_write_close();
// }

$shoppingCart = new ShoppingCart();
if (! empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (! empty($_POST["quantity"])) {

                $productResult = $shoppingCart->getProductByCode($_GET["code"]);
                //echo $productResult[0]["image"];

                $cartResult = $shoppingCart->getCartItemByProduct($productResult[0]["id"], $member_id);
                //echo $cartResult[0]["id"];

                if (! empty($cartResult)) {
                    // Update cart item quantity in database
                    $error = "This giftcard is already in your cart.";
                    //echo $error;

                    //$newQuantity = $cartResult[0]["quantity"] + $_POST["quantity"];
                    //$shoppingCart->updateCartQuantity($newQuantity, $cartResult[0]["id"]);
                } else {
                    // Add to cart table
                    $shoppingCart->addToCart($productResult[0]["id"], $_POST["quantity"], $member_id);
                }
            }
            break;
        case "remove":
            // Delete single entry from the cart
            $shoppingCart->deleteCartItem($_GET["id"]);
            break;
        case "empty":
            // Empty cart
            $shoppingCart->emptyCart($member_id);
            break;
    }
}
?>

<?php
$cartItem = $shoppingCart->getMemberCartItem($member_id);
$item_quantity = 0;
$item_price = 0;
if (! empty($cartItem)) {
    if (! empty($cartItem)) {
        foreach ($cartItem as $item) {
            $item_quantity = $item_quantity + $item["quantity"];
            $item_price = $item_price + ($item["price"] * $item["quantity"]);
        }
    }
}
?>
