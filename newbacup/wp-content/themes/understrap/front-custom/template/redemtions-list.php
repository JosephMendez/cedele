<?php
defined( 'ABSPATH' ) || exit;
?>
<style>
  .myaccount-redemptions-list {
    width: 100%;
  }
  .my-redemtions-detail {
    margin-top: 10px;
    min-height: 50px;
  }
  table {
    border: 1px solid black;
  }
</style>
<?php
if (empty($redemptions_list)) {
  $redemptions_list = (object)[];
}
?>

<div class="my-redemtions">
  <?php if (isset($redemptions_list->redemption_events)): ?>
    <?php foreach ($redemptions_list->redemption_events as $key => $redemtion_event): ?>
    <div class="my-redemtions-item" data-redemption-code="<?php echo $redemtion_event->redemption_event_code; ?>">
      <h3>Event: <?php echo $redemtion_event->redemption_event_name; ?></h3>
      <div class="my-redemtions-detail"></div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>