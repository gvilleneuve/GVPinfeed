<?php

/**
 * Pinfeed
 *
 * @category   Pinterest
 * @package    GVPinfeed
 * @author     Geoff Villeneuve
 * @copyright  2013 Resolution Interactive Media
 * @version    1.0
 * @link       http://gffvllnv.com/wp-content/plugins/pinfeed/pinfeed.zip
 * @since      File available since Release 1.0
 */
 
add_action('widgets_init', 'gv_pinfeed_register_widget');
    
function gv_pinfeed_register_widget() {
    
    register_widget( 'GV_Pinfeed_Widget' );
    
}
 
class GV_Pinfeed_Widget extends WP_Widget{

	
	function GV_Pinfeed_Widget() {

        $widget_ops = array(
            'classname' => 'gv_pinfeed_widget',
            'description' => __('Displays your Pinterest pins on your website.', 'gv_pinfeed')
        );

        $this->WP_Widget( false, __('GV Pinfeed', 'gv_pinfeed'), $widget_ops );
        
    }
/*
    public function __construct(){

    }
*/
    
    
    function widget($args, $instance) {
        
        extract($args, EXTR_SKIP);
        
        // Enqueue Style Sheet
        wp_enqueue_style( 'gv-pinfeed-plugin' );
        if($instance['pin_style'] == "yes"){
	        wp_enqueue_style( 'gv-pinfeed-plugin-default-style' );
        }
        
        if($instance['pin_slider'] == "yes"){
	        wp_enqueue_script('jquery-latest');
	        wp_enqueue_script('unslider');
	        wp_enqueue_script('pin-widget-js');
        }
        


        
        // Ensure not undefined for updates
        if ( ! isset( $instance['pinterest_user'] ) )
            $instance['pinterest_user'] = '';
        
        // Output opening Widget HTML
        if($instance['pin_style'] != "yes") echo $before_widget;
        
        // If Title is set, output it with Widget title opening and closing HTML
        if($instance['pin_style'] != "yes"){
	        if ( isset($instance['title'] ) && ! empty( $instance['title'] ) ) {
	
	            echo $before_title;
	            echo $instance['title'];
	            echo $after_title;
	            
	        }
        }
        
        /*
         * Check which Style (Slider/List) has been chosen and use correct view file, default List.
         */
		include("pin-widget.php");
        
        // Output closing Widget HTML
       if($instance['pin_style'] != "yes") echo $after_widget;
        
    }
    
    
    /*
     * Outputs Options Form
     */
    function form( $instance ) {
        ?>

        <?php

        // Add defaults.
        if( !isset( $instance['pin_user'] ) )
            $instance['pin_user'] = "pinterest";
        if( !isset( $instance['pin_board'] ) )
            $instance['pin_board'] = '';
        if( !isset( $instance['pin_style'] ) )
            $instance['pin_style'] = 'no';
        if( !isset( $instance['pin_slider'] ) )
            $instance['pin_slider'] = 'no';
        if( !isset( $instance['pin_count'] ) )
            $instance['pin_count'] = '1';
           

        ?>
        
        <label for="<?php echo $this->get_field_id('pin_user'); ?>">
            <p><?php _e('Pinterest account', 'gv_pinfeed'); ?>: <input style="width: 100%;" type="text" value="<?php echo $instance['pin_user']; ?>" name="<?php echo $this->get_field_name('pin_user'); ?>" id="<?php echo $this->get_field_id('pin_user'); ?>"></p>
        </label>
        
         <label for="<?php echo $this->get_field_id('pin_board'); ?>">
            <p><?php _e('Board', 'gv_pinfeed'); ?>: <input style="width: 100%;" type="text" value="<?php echo $instance['pin_board']; ?>" name="<?php echo $this->get_field_name('pin_board'); ?>" id="<?php echo $this->get_field_id('pin_board'); ?>"></p>
        </label>
        
        <label for="<?php echo $this->get_field_id('pin_count'); ?>">
            <p><?php _e('# of pins', 'gv_pinfeed'); ?>: <input style="width: 40px" type="text" value="<?php echo $instance['pin_count']; ?>" name="<?php echo $this->get_field_name('pin_count'); ?>" id="<?php echo $this->get_field_id('pin_count'); ?>"></p>
        </label>
        
        <label for="<?php echo $this->get_field_id('pin_style'); ?>">
            <p><?php _e('Pinterest style', 'gv_pinfeed'); ?>: <input style="width: 100%;" type="checkbox" value="yes" <?php if($instance['pin_style'] == "yes"){ echo "checked='checked'"; } ?> name="<?php echo $this->get_field_name('pin_style'); ?>" id="<?php echo $this->get_field_id('pin_style'); ?>"></p>
        </label>
        
        <label for="<?php echo $this->get_field_id('pin_slider'); ?>">
            <p><?php _e('Slider', 'gv_pinfeed'); ?>: <input style="width: 100%;" type="checkbox" value="yes" <?php if($instance['pin_slider'] == "yes"){ echo "checked='checked'"; } ?> name="<?php echo $this->get_field_name('pin_slider'); ?>" id="<?php echo $this->get_field_id('pin_slider'); ?>"></p>
        </label>

 

        <?php
    }
    
    
    /*
     * Validates and Updates Options
     */
    function update($new_instance, $old_instance) {
        
        $instance = array();
        
        // Use old figures in case they are not updated.
        $instance = $old_instance;
        
        // Update text inputs and remove HTML.
        $instance['pin_board'] = wp_filter_nohtml_kses( $new_instance['pin_board'] );
        $instance['pin_user'] = wp_filter_nohtml_kses( $new_instance['pin_user'] );
        $instance['pin_style'] = wp_filter_nohtml_kses( $new_instance['pin_style'] );
        $instance['pin_slider'] = wp_filter_nohtml_kses( $new_instance['pin_slider'] );
        $instance['pin_count'] = wp_filter_nohtml_kses( $new_instance['pin_count'] );

        
        // Check 'count' is numeric.
        if ( is_numeric( $new_instance['count'] ) ) {
            
            // If 'count' is above 50 reset to 50.
            if ( 50 <= intval( $new_instance['count'] ) ) {
                $new_instance['count'] = 50;
            }
            
            // If 'count' is below 1 reset to 1.
            if ( 1 >= intval( $new_instance['count'] ) ) {
                $new_instance['count'] = 1;
            }
            
            // Update 'count' using intval to remove decimals.
            $instance['count'] = intval( $new_instance['count'] );
            
        }
        
        return $instance;
    }

}