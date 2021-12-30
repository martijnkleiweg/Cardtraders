<div class="rowgc">
  <div class="thumb">
    <img src="<?php echo $product_array[$key]["image"]; ?>">
    </div>
    <div class="value">
      <?php echo $product_array[$key]["value"]; ?>
      </div>
      <div class="price">
        <?php echo $product_array[$key]["price"]; ?>
      </div>
      <div class ="discount">
        <?php
        $value = $product_array[$key]["value"];
        $price = $product_array[$key]["price"];
        $discount = (($value-$price)/$value)*100;
        echo $discount + " %";
        ?>
        </div>
        <div class="expires">
          <?php echo $product_array[$key]["expiration_date"]; ?>
        </div>
        <div class="addbutton">
          <input type="image"
          src="image/add-to-cart.png" class="btnAddAction" />
        </div>
  </div>
