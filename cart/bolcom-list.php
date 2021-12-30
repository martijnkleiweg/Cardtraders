
<div class="gcheader">Bol.com Giftcards</div>
<div id="product-grid">

    <div class="rowhead">
      <div class = "thumb"></div>
      <div class = "value"><div class = "divheader">Value</div></div>
      <div class = "value"><div class = "divheader">Price</div></div>
      <div class = "value"><div class = "divheader">Discount</div></div>
      <div class = "value"><div class = "divheader">Expires</div></div>
      <div class = "addbutton"></div>
    </div>

    <?php
    $query = "SELECT giftcard.code, giftcard.id, giftcard.brand, brand.image, giftcard.value, sell_order.price, giftcard.expiration_date
FROM ((brand
INNER JOIN giftcard ON brand.brand = giftcard.brand)
INNER JOIN sell_order ON giftcard.id = sell_order.giftcard_id) WHERE giftcard.brand = 'Bol.com' AND giftcard.verified = 1 AND giftcard.sold = 0";

    $product_array = $shoppingCart->getAllProduct($query);
    if (! empty($product_array)) {
        foreach ($product_array as $key => $value) {
            ?>
        <div class="rowgc">
        <form method="post"
            action="cart.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">

            <div class="rowgc">
              <div class="thumb">
                <img src="<?php echo $product_array[$key]["image"]; ?>">
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
                      $expires = $product_array[$key]["expiration_date"];
                      $year = substr($expires, 0, -6);
                      $month = substr($expires, -5, -3);
                      $hyphen = "-";
                      echo $month;
                      echo $hyphen;
                      echo $year;
                      ?>


                    </div>
                    <div class="addbutton">
                      <input type="hidden" name="quantity" value="1"
                          size="2" class="input-cart-quantity" /><input type="image"
                      src="image/add-to-cart.png" class="btnAddAction" />
                    </div>
              </div>



            </form>
        </div>
        <?php
            }
        }
        else {
          ?>
          <div class="rowgc">No giftcards available.</div>
          <?php
        }
        ?>
    </div>

<div class = "description_gc">
<div class = "logo_gc"><div class = "logo_gc2"><img src="images/bol_com.jpg"></div></div>
<div class = "desc"><div class = "desc2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div></div>
</div>
