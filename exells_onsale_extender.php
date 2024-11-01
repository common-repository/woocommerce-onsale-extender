<?php
/*
Plugin Name: WooCommerce OnSale Extender by exells.com
Plugin URI: http://exells.com
Description: This WooCommerce Extension replaces the "Sale" text for OnSale products with saved amout or % discount. 
Version: 1.0.0
Author: Daniel Bakovic
Author URI: http://myarcadeplugin.com
Requires at least: 3.1
Tested up to: 3.3
*/

add_filter('woocommerce_sale_flash', 'exells_onsale_filter', 10, 3);
add_filter('woocommerce_catalog_settings', 'exells_extend_catalog_settings');
//add_filter('woocommerce_sale_price_html', 'exells_remove_sale_price', 10, 2);
load_plugin_textdomain( 'onsale_extender', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

function exells_onsale_filter($text, $post, $_product) {

  if ( get_option('onsale_extender_format') == 'amount' ) {
    $sale = $_product->regular_price - $_product->sale_price;
  }
  else {
    $sale = number_format( (100 - ($_product->sale_price * 100 / $_product->regular_price)), 2);
    $check = explode('.', $sale);    
    
    if ( isset($check[1]) && ($check[1] == '00' || empty($check[1])) ) {
      $sale = $check[0];
    }  
  }
  
  $template = get_option('onsale_extender_template');    
  $output = str_replace('[value]' , $sale, $template);
 
  return '<span class="onsale">'.$output.'</span>';  
}

function exells_extend_catalog_settings($catalog_settings_array) {

  $catalog_settings_array[] = array(	'name' => __( 'OnSale Extender Options', 'onsale_extender' ), 'type' => 'title', 'desc' => '', 'id' => 'onsale_extender_options' );
  
  $catalog_settings_array[] = array(  
      'name' => __( 'OnSale Extender Template', 'onsale_extender' ),
      'desc' 		=> __( 'This controls the onsale output. Plugin will replace [value] with the amount or percentage.', 'onsale_extender' ),
      'tip' 		=> '',
      'id' 		=> 'onsale_extender_template',
      'css' 		=> 'width:150px;',
      'std' 		=> 'Save [value] %!',
      'type' 		=> 'text', 
    );
  
  	$catalog_settings_array[] = array(  
		'name'    => __( 'OnSale Extender Format', 'onsale_extender' ),
		'desc' 		=> __( 'This controls if OnSale Extender will show the an amount or % discount.', 'onsale_extender' ),
		'id' 		  => 'onsale_extender_format',
		'css' 		=> 'min-width:150px;',
		'std' 		=> 'percentage',
		'type' 		=> 'select',
		'options' => array( 
      'percentage'  => __( 'Percentage', 'onsale_extender' ),
      'amount'       => __( 'Amount', 'onsale_extender' )  
      )
    );
    
    $catalog_settings_array[] = array( 'type' => 'sectionend', 'id' => 'onsale_extender_options' );

  return $catalog_settings_array;
}

function exells_remove_sale_price($price, $_product) { 

  if ($_product->is_on_sale() && isset($_product->regular_price)) {  
    return woocommerce_price($_product->get_price());
  }
  else {
    return $price;
  }
}
?>