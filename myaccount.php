
<main>
  <div class="list">

<div class="gcheader">My account</div>

<div class="formtext3">Welcome <?php echo $username ?>. See below the details of your past orders..</div>

<div class="formtext3">Please contact us at info@cardtraders.io if you have any issues with an order.</div>

<div class="accountheader">Buy orders <?php echo $username ?></div>

<div id="product-grid">

    <div class="rowhead">
      <div class = "value"><div class = "divheader">Order ID</div></div>
      <div class = "value"><div class = "divheader">Brand</div></div>
      <div class = "value"><div class = "divheader">Value</div></div>
      <div class = "value"><div class = "divheader">Price</div></div>
      <div class = "value"><div class = "divheader">Discount</div></div>
      <div class = "value"><div class = "divheader">Date</div></div>
    </div>

    <?php
    $query = "SELECT tbl_order.id, giftcard.brand, giftcard.value, sell_order.price, tbl_order.order_status, tbl_order.order_at FROM ((((registered_users INNER JOIN sell_order ON registered_users.id = sell_order.member_id)
    INNER JOIN tbl_order_item ON sell_order.giftcard_id = tbl_order_item.product_id)
    INNER JOIN tbl_order ON tbl_order.id = tbl_order_item.order_id) INNER JOIN giftcard on giftcard.id = tbl_order_item.product_id) where tbl_order.customer_id = $member_id AND tbl_order.order_status = 'PAID'";

    $product_array = $shoppingCart->getAllProduct($query);
    if (! empty($product_array)) {
        foreach ($product_array as $key => $value) {
            ?>

            <div class="rowgc">
              <div class="value">
                <?php echo $product_array[$key]["id"]; ?>
                </div>
                <div class="value">
                  <?php echo $product_array[$key]["brand"]; ?>
                  </div>
                  <div class="value">
                    <?php
                    $euro = "€";
                    echo $euro;
                    echo $product_array[$key]["value"]; ?>
                  </div>
                  <div class="value">
                    <?php
                    echo $euro;
                    echo $product_array[$key]["price"]; ?>
                  </div>
                  <div class ="value">
                    <?php
                    $value = $product_array[$key]["value"];
                    $price = $product_array[$key]["price"];
                    $discount = (($value-$price)/$value)*100;
                    $percentage = "%";
                    echo $discount;
                    echo $percentage;
                    ?>
                    </div>
                    <div class="value">
                      <?php
                        $fulldate = $product_array[$key]["order_at"];
                        $date = substr($fulldate, 0, -8);
                        echo $date;

                      //$expires = $product_array[$key]["order_at"];
                      // $year = substr($expires, 0, -6);
                      // $month = substr($expires, -5, -3);
                      // $hyphen = "-";
                      // echo $month;
                      // echo $hyphen;
                      // echo $year;
                      ?>


                    </div>
              </div>

    <?php
        }
    }
    else {
      ?>
      <div class = "rowgc">No confirmed orders.</div>
      <?php
    }
    ?>
</div>

<div class="accountheader">Sell orders <?php echo $username ?></div>

<div id="product-grid">

    <div class="rowhead">
      <div class = "value"><div class = "divheader">Order ID</div></div>
      <div class = "value"><div class = "divheader">Brand</div></div>
      <div class = "value"><div class = "divheader">Value</div></div>
      <div class = "value"><div class = "divheader">Payout</div></div>
      <div class = "value"><div class = "divheader">Sold</div></div>
      <div class = "value"><div class = "divheader">Date</div></div>
    </div>

    <?php
    $query = "SELECT sell_order.order_sell_id, giftcard.brand, giftcard.value, sell_order.price, giftcard.sold, sell_order.datetime FROM ((((registered_users INNER JOIN sell_order ON registered_users.id = sell_order.member_id)
    INNER JOIN tbl_order_item ON sell_order.giftcard_id = tbl_order_item.product_id)
    INNER JOIN tbl_order ON tbl_order.id = tbl_order_item.order_id) INNER JOIN giftcard on giftcard.id = tbl_order_item.product_id) where sell_order.member_id = $member_id";

    $product_array = $shoppingCart->getAllProduct($query);
    if (! empty($product_array)) {
        foreach ($product_array as $key => $value) {
            ?>

            <div class="rowgc">
              <div class="value">
                <?php echo $product_array[$key]["order_sell_id"]; ?>
                </div>
                <div class="value">
                  <?php echo $product_array[$key]["brand"]; ?>
                  </div>
                  <div class="value">
                    <?php
                    $euro = "€";
                    echo $euro;
                    echo $product_array[$key]["value"]; ?>
                  </div>
                  <div class="value">
                    <?php
                    echo $euro;
                    $price = $product_array[$key]["price"];
                    $payout = 0.95*$price;
                    echo $payout;
                     ?>
                  </div>
                  <div class ="value">
                    <?php
                    $no = "No";
                    $yes = "Yes";
                    $sold = $product_array[$key]["sold"];
                    if($sold == 1){
                      echo $yes;
                    }else {
                      echo $no;
                    }
                    ?>
                    </div>
                    <div class="value">
                      <?php
                        $fulldate = $product_array[$key]["datetime"];
                        $date = substr($fulldate, 0, -8);
                        echo $date;

                      //$expires = $product_array[$key]["order_at"];
                      // $year = substr($expires, 0, -6);
                      // $month = substr($expires, -5, -3);
                      // $hyphen = "-";
                      // echo $month;
                      // echo $hyphen;
                      // echo $year;
                      ?>


                    </div>
              </div>

    <?php
        }
    }
    else {
      ?>
      <div class = "rowgc">No confirmed orders.</div>
      <?php
    }
    ?>
</div>


</div>

<!-- <div class = "description_gc">
<div class = "logo_gc"><div class = "logo_gc2"><img src="images/amazon-nl.jpg"></div></div>
<div class = "desc"><div class = "desc2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div></div>
</div> -->


</main>
