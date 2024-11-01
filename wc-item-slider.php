<?php
/*
Plugin Name: Woocomerce Multi Item Slider
Plugin URI: https://wordpress.org/plugins/woocommerce-item-slider
Version:0.1
Description:Woocommerce item slider from selected  Categories.
Author: srinivasan
Author URI: http://beeplugins.com/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html*/

if ( ! defined( 'ABSPATH' ) ) {	exit;}

add_action( 'wp_enqueue_scripts', 'wc_item_slider_files' );
// register jquery and style on initialization
// Register style sheet.
/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
function wc_item_slider_files() {
    // Respects SSL, Style.css is relative to the current file
 wp_register_style( 'wc_item_slider', plugins_url('/assets/css/style.css', __FILE__) );
 wp_enqueue_style( 'wc_item_slider' );
// wp_enqueue_script('the_js', plugins_url('wc-item-slider/assets/js/jquery.catslider.js',true,__FILE__) );
  } 
  
  add_action( 'wp_footer', 'wc_item_slider_jquery' );

function wc_item_slider_jquery() {
   wp_enqueue_script('the_js', plugins_url('/assets/js/jquery.catslider.js',__FILE__) );
   echo "<script>	
   jQuery(document).ready(function(){
jQuery('#mi-slider').catslider();})
					
</script>";
}
 
// Modernizr For all device support
function wc_item_slider_modernizr() {
		wp_deregister_script('modernizr'); // deregister
		wp_enqueue_script('wp_enqueue_scripts', plugins_url('/assets/js/modernizr.js', __FILE__), false, '2.8.3', false);
}
add_action('wp_enqueue_scripts', 'wc_item_slider_modernizr');
/*
 * Add the admin page
 */

add_action('admin_menu', 'wc_item_slider_admin_page');
function wc_item_slider_admin_page(){
add_menu_page('Wc Slider Settings', 'WC Slider Settings', 'administrator', 'wc_item_slider_settings', 'wc_item_slider_admin_page_callback');
	}
/*
 * Register the settings
 */
 ///Add jquery localy
add_action('admin_init', 'wc_item_slider_register_settings');
function wc_item_slider_register_settings(){
    //this will save the option in the wp_options table as 'wpse61431_settings'
    //the third parameter is a function that will validate your input values
    register_setting('wc_item_slider_settings', 'wc_item_slider_settings');
}
//The markup for your plugin settings page
function wc_item_slider_admin_page_callback(){?>
    <div class="wrap">
    <h2>Woocommerce Item Slider Settings</h2>
    <form action="options.php" method="post">
<?php
        settings_fields( 'wc_item_slider_settings' );
        do_settings_sections( __FILE__ );
        //get the older values, wont work the first time
        $options = get_option( 'wc_item_slider_settings' );?>
        <table class="form-table">
            <tr>
                              <td>
                    <fieldset>
                        <label>
<?php						
						$chosen_cat1 =(isset($options['cat1']) && $options['cat1'] != '') ? $options['cat1'] : '';
						$chosen_cat2 =(isset($options['cat2']) && $options['cat2'] != '') ? $options['cat2'] : '';
						$chosen_cat3 =(isset($options['cat3']) && $options['cat3'] != '') ? $options['cat3'] : '';
						$chosen_cat4 =(isset($options['cat4']) && $options['cat4'] != '') ? $options['cat4'] : '';					
						
						$args1 = array('taxonomy'  => 'product_cat','name' => 'wc_item_slider_settings[cat1]','selected' => $chosen_cat1);//Category 1
						$args2 = array('taxonomy'  => 'product_cat','name' => 'wc_item_slider_settings[cat2]','selected' => $chosen_cat2);//Category 2
						$args3 = array('taxonomy'  => 'product_cat','name' => 'wc_item_slider_settings[cat3]','selected' => $chosen_cat3);//Category 3
						$args4 = array('taxonomy'  => 'product_cat','name' => 'wc_item_slider_settings[cat4]','selected' => $chosen_cat4);//Category 4						
		///Display Drowpdown boxes/////
		echo     'Category 1' ; wp_dropdown_categories( $args1 );
		echo     'Category 2' ; wp_dropdown_categories( $args2 );
		echo     'Category 3' ; wp_dropdown_categories( $args3);
		echo     'Category 4' ; wp_dropdown_categories( $args4);?>
                          
                             </label>
                    </fieldset>
                </td>
            </tr>
        </table>
<?php 
		//Generating Category Drop Downs   
   echo submit_button();
?>
    </form>
</div>
<?php }?>
<?php
function wc_item_slider( )
{
////Slider Function Begins
echo '<div id="mi-slider" class="mi-slider">';
echo '<ul>';
//Getting Category options from DB
$wc_categories = get_option( 'wc_item_slider_settings' );
$cate1=$wc_categories['cat1'];
$cate2=$wc_categories['cat2'];
$cate3=$wc_categories['cat3'];
$cate4=$wc_categories['cat4'];

//Getting Category Slugs

$cat1_terms = get_term($cate1, 'product_cat'); 
$cat2_terms = get_term($cate2, 'product_cat'); 
$cat3_terms = get_term($cate3, 'product_cat'); 
$cat4_terms = get_term($cate4, 'product_cat'); 
$theslug1 = $cat1_terms->slug; 
$theslug2 = $cat2_terms->slug; 
$theslug3 = $cat3_terms->slug; 
$theslug4 = $cat4_terms->slug; 

//Now assign   category 1 scroll from array:
$args = array(
'post_type' => 'product',
'posts_per_page' => 4,
'product_cat' => $theslug1,
'orderby' => 'name',
'order' => 'ASC',
);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				woocommerce_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();

echo '</ul>	';
echo '<ul>';	
//Now assign   category 2 scroll from array:
$args = array(
'post_type' => 'product',
'posts_per_page' => 4,
'product_cat' => $theslug2,
'orderby' => 'name',
'order' => 'ASC',
);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				woocommerce_get_template_part( 'content', 'product' );			
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();

echo '</ul>	';
echo '<ul>';
//Now assign   category 3 scroll from array:
	$args = array(
'post_type' => 'product',
'posts_per_page' => 4,
'product_cat' => $theslug3,
'orderby' => 'name',
'order' => 'ASC',
);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				woocommerce_get_template_part( 'content', 'product' );
			
			endwhile;
		} else {
			echo __('No products found' );
		}
		wp_reset_postdata();

echo '</ul>	';
echo '<ul>';
//Now assign   category 4 scroll from array:	
		$args = array(
'post_type' => 'product',
'posts_per_page' => 4,
'product_cat' => $theslug4,
'orderby' => 'name',
'order' => 'ASC',
);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				woocommerce_get_template_part( 'content', 'product' );
				
			endwhile;
		} else {
			echo __('No products found' );
		}
		wp_reset_postdata();

echo '</ul>	';
echo '<nav>
<a href="#">' . $theslug1 . '</a>
<a href="#">' . $theslug2 . '</a>
<a href="#">' . $theslug3 . '</a>
<a href="#">' . $theslug4 . '</a>
</nav></div>';  
}
add_shortcode('wc_item_slider','wc_item_slider');