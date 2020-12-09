<?php
if (empty($redemptions_data)) {
  $redemptions_data = (object)[];
}
?>

<table cellpadding="8" class="myaccount-redemptions-list shop_table shop_table_responsive my_account_orders table-hover table-striped">
  <thead>
    <tr>
      <th width="30%">Coupon code</th>
      <th width="30%">Redemption points</th>
      <th width="30%">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($redemptions_data->coupon_rels)): ?>
    <?php foreach($redemptions_data->coupon_rels as $coupon_key => $coupon): ?>
      <tr data-redemption-type="coupon" data-coupon-code="<?php echo $coupon->coupon_code; ?>">
        <td><?php echo $coupon->coupon_code ?></td>
        <td><?php echo $coupon->redeem_point ?></td>
        <td>
          <button class="btn btn-primary myredemption-action">Redemptions</button>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
<br>
<table cellpadding="8" class="myaccount-redemptions-list shop_table shop_table_responsive my_account_orders table-hover table-striped">
  <thead>
    <tr>
      <th width="30%">Gift code</th>
      <th width="30%">Redemption points</th>
      <th width="30%">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($redemptions_data->gift_rels)): ?>
    <?php foreach($redemptions_data->gift_rels as $coupon_key => $gift): ?>
      <tr data-redemption-type="gift" data-coupon-code="<?php echo $gift->gift_code ?>">
        <td><?php echo $gift->gift_code ?></td>
        <td><?php echo $gift->redeem_point ?></td>
        <td>
          <button class="btn btn-primary myredemption-action">Redemptions</button>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>