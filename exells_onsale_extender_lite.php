<?php
/*
Plugin Name: WooCommerce OnSale Extender Lite by exells.com
Plugin URI: http://exells.com
Description: This WooCommerce Extension replaces the "Sale" text for OnSale products with the % discount. If you need more flexibility and if you want to make the output configurable check out the Pro version on <a href="http://exells.com/" title="Marketplace for digital goods">exells.com</a>
Version: 1.0.0
Author: Daniel Bakovic
Author URI: http://myarcadeplugin.com
Requires at least: 3.1
Tested up to: 3.3
*/

add_filter('woocommerce_sale_flash', 'exells_onsale_filter_lite', 10, 3);

function exells_onsale_filter_lite($text, $post, $_product) {

  $sale = number_format( (100 - ($_product->sale_price * 100 / $_product->regular_price)), 2);
  $check = explode('.', $sale);    
  
  if ( isset($check[1]) && ($check[1] == '00' || empty($check[1])) ) {
    $sale = $check[0];
  }
  
  $output = "Save ".$sale."%";
 
  return '<span class="onsale">'.$output.'</span>';
}
?>