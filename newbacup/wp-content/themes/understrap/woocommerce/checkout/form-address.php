<?php

defined( 'ABSPATH' ) || exit;

$customerAddress = json_decode(stripslashes($_COOKIE['customerAddress']));
if ($customerAddress && property_exists($customerAddress, 'deliveryAddress')){
  $deliveryAddress = json_decode(stripslashes($customerAddress->deliveryAddress));
}
?>

<div class="woo-shipping-info clearfix">
  <h3><?php echo $customerAddress->deliveryType == 'delivery' ? 'Shipping and Billing information' : 'Pickup Information' ?></h3>
  <div>
    <?php if ($customerAddress->deliveryType == 'delivery') { ?>
<!--       <div class="form-row d-block">
        <label>Postal Code</label>
        <div>
          <p><?php echo $deliveryAddress->zipcode; ?></p>
        </div>
      </div> -->
      <div class="form-row d-block">
        <label>Delivery Address</label>
        <div>
          <p><?php echo $deliveryAddress->formatted_address; ?></p>
        </div>
      </div>
      <div class="form-row d-block">
        <label>Building Name/ Floor Number</label>
        <div>
          <p><?php echo $customerAddress->deliveryAddress2; ?></p>
        </div>
      </div>
      <div class="form-row d-block">
        <label>Delivery Time</label>
        <div>
          <p><?php echo $customerAddress->date.', '.$customerAddress->time; ?></p>
        </div>
      </div>
      <div class="clearfix custom-control custom-checkbox">
        <input type="checkbox" id="same-address" class="custom-control-input" checked>
        <label class="custom-control-label" for="same-address">Billing Address is the same as Shipping Address</label>
      </div>
    <?php } else { ?>
      <div class="form-row d-block">
        <label>Store name</label>
        <div>
          <p><?php echo $customerAddress->pickupStoreName; ?></p>
        </div>
      </div>
      <div class="form-row d-block">
        <label>Address</label>
        <div>
          <p><?php echo $customerAddress->pickupStoreOnlyAddress; ?></p>
        </div>
      </div>
      <div class="form-row d-block">
        <label>Collect Time</label>
        <div>
          <p><?php echo $customerAddress->date.', '.$customerAddress->time; ?></p>
        </div>
      </div>
    <?php } ?>
    <div class="billing-address-fields" style="display: none"></div>
  </div>
</div>
