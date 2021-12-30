<?php
require_once "DBController.php";

class ShoppingCart extends DBController
{

    function getAllProduct($query)
    {
//         $query = "SELECT giftcard.code, giftcard.id, giftcard.brand, brand.image, giftcard.value, sell_order.price, giftcard.expiration_date
// FROM ((brand
// INNER JOIN giftcard ON brand.brand = giftcard.brand)
// INNER JOIN sell_order ON giftcard.id = sell_order.giftcard_id)";

        $productResult = $this->getDBResult($query);
        return $productResult;
    }


    function getMemberCartItem($member_id)
    {
        $query = "SELECT giftcard.*, tbl_cart.id as cart_id, tbl_cart.quantity, brand.image, sell_order.price FROM giftcard, tbl_cart, brand, sell_order WHERE
            giftcard.id = tbl_cart.product_id AND brand.brand = giftcard.brand AND giftcard.id = sell_order.giftcard_id AND tbl_cart.member_id = ?";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $member_id
            )
        );

        $cartResult = $this->getDBResult($query, $params);
        return $cartResult;
    }

    function getProductByCode($product_code)
    {
        $query = "SELECT giftcard.code, giftcard.id, giftcard.brand, brand.image, giftcard.value, sell_order.price, giftcard.expiration_date
FROM ((brand
INNER JOIN giftcard ON brand.brand = giftcard.brand)
INNER JOIN sell_order ON giftcard.id = sell_order.giftcard_id) WHERE giftcard.code=?";

        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $product_code
            )
        );

        $productResult = $this->getDBResult($query, $params);
        return $productResult;
    }

    function getCartItemByProduct($product_id, $member_id)
    {
        $query = "SELECT * FROM tbl_cart WHERE product_id = ? AND member_id = ?";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $product_id
            ),
            array(
                "param_type" => "i",
                "param_value" => $member_id
            )
        );

        $cartResult = $this->getDBResult($query, $params);
        return $cartResult;
    }

    function addToCart($product_id, $quantity, $member_id)
    {
        $query = "INSERT INTO tbl_cart (product_id,quantity,member_id) VALUES (?, ?, ?)";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $product_id
            ),
            array(
                "param_type" => "i",
                "param_value" => $quantity
            ),
            array(
                "param_type" => "i",
                "param_value" => $member_id
            )
        );

        $this->insertDB($query, $params);
    }

    function updateCartQuantity($quantity, $cart_id)
    {
        $query = "UPDATE tbl_cart SET  quantity = ? WHERE id= ?";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $quantity
            ),
            array(
                "param_type" => "i",
                "param_value" => $cart_id
            )
        );

        $this->updateDB($query, $params);
    }

    function deleteCartItem($cart_id)
    {
        $query = "DELETE FROM tbl_cart WHERE id = ?";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $cart_id
            )
        );

        $this->updateDB($query, $params);
    }

    function emptyCart($member_id)
    {
        $query = "DELETE FROM tbl_cart WHERE member_id = ?";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $member_id
            )
        );

        $this->updateDB($query, $params);
    }

    function insertOrder($email, $member_id, $amount)
    {
        $query = "INSERT INTO tbl_order (customer_id, amount, email, payment_type, order_status, order_at) VALUES (?, ?, ?, ?, ?, ?)";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $member_id
            ),
            array(
                "param_type" => "i",
                "param_value" => $amount
            ),
            array(
                "param_type" => "s",
                "param_value" => $email;
            ),
            array(
                "param_type" => "s",
                "param_value" => "PAYPAL"
            ),
            array(
                "param_type" => "s",
                "param_value" => "PENDING"
            ),
            array(
                "param_type" => "s",
                "param_value" => date("Y-m-d H:i:s")
            )
        );

        $order_id = $this->insertDB($query, $params);
        return $order_id;
    }

    function insertOrderItem($order, $product_id, $price, $quantity)
    {
        $query = "INSERT INTO tbl_order_item (order_id, product_id, item_price, quantity) VALUES (?, ?, ?, ?)";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $order
            ),
            array(
                "param_type" => "i",
                "param_value" => $product_id
            ),
            array(
                "param_type" => "i",
                "param_value" => $price
            ),
            array(
                "param_type" => "i",
                "param_value" => $quantity
            )
            );

        $this->insertDB($query, $params);
    }

    function insertPayment($order, $payment_status, $payment_response)
    {
        $query = "INSERT INTO tbl_payment(order_id, payment_status, payment_response) VALUES (?, ?, ?)";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $order
            ),
            array(
                "param_type" => "s",
                "param_value" => $payment_status
            ),
            array(
                "param_type" => "s",
                "param_value" => $payment_response
            )
        );

        $this->insertDB($query, $params);
    }

    function paymentStatusChange($order, $status) {
        $query = "UPDATE tbl_order SET  order_status = ? WHERE id= ?";

        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $status
            ),
            array(
                "param_type" => "i",
                "param_value" => $order
            )
        );

        $this->updateDB($query, $params);
    }
}
