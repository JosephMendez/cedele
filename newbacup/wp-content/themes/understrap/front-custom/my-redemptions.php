<?php
// redemptions
add_action( 'init', function () {
  add_rewrite_endpoint( 'my-redemptions', EP_ROOT | EP_PAGES );
});
function template_redemption_endpoint_content()
{
  $redemptions_list = queryRedemptionEvent();
  ob_start();
  require_once get_template_directory() . '/front-custom/template/redemtions-list.php';
  $template = ob_get_contents();
  ob_end_clean();

  echo $template;
}
add_action( 'woocommerce_account_my-redemptions_endpoint', 'template_redemption_endpoint_content' );

