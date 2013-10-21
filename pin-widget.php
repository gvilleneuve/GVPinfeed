<?php 

require_once("GVPinfeed.php");
 $pinfeed = new GVPinfeed;
 if(isset($instance) && function_exists("is_page")){ // check if it's Wordpress or not
	 $pinAccount = $instance['pin_user'];
	 $pinBoard = ($instance['pin_board'] != "")?$instance['pin_board']:false;
	 $wp = true;
 }else{
	 $pinAccount = "pinterest";
	 $pinBoard = "";
	 $wp = false;
 }
 
 $pins = $pinfeed->get_pins_for_user($pinAccount, $pinBoard, 1);
 $pinuser = $pinfeed->get_user_info($pinAccount);
 
 
 $lastPin = $pins[0];
 //print_r($pins);
 //print_r($pinuser);
if(!$wp){
?>
	
	
	<link rel="stylesheet" type="text/css" href="/wp-content/plugins/GVPinfeed/pin-default.css" />
	<link rel="stylesheet" type="text/css" href="/wp-content/plugins/GVPinfeed/pin.css" />
<?php } ?>
	<div id="pinterest-widget" class="widget-box">
		
		<div id="pin-cycler">
			<ul>
				<?php 
					
					foreach($pins as $pin){
						echo '<li>';
							echo '<a href="'.$pin['link'].'" ><img class="pin-img" src="'.$pin['image_236'].'" /></a>';
							echo '<p class="pin-desc" >';
								echo $pin['text'];
								echo '<span class="repin-icon" ><a data-pin-do="buttonPin" data-pin-config="beside" href="//www.pinterest.com/pin/create/button/?url='. urlencode($pin['link']) .'&media='. urlencode($pin['image_236']) .'&description='. urlencode(strip_tags($pin['description'])) .'" target="_blank" ><img class="repin-icon" src="/wp-content/plugins/GVPinfeed/images/pin-icon-28.png" /></a></span>';

							echo '</p>';
									
																
							echo '</li>';
					}
				?>
	
			</ul>
		</div>
		
		<a class="pin-user-link" target="_blank"  href="http://www.pinterest.com/<?php print $pinusername; ?>/">
			<div class="pin-user-info">
					<img class="pin-icon" src="/wp-content/plugins/GVPinfeed/images/pinterest_icon_28.png" />
					<div class="pin-date">pinned <?php print $lastPin['time_ago']; if($lastPin['board'] != $pinuser['name']) echo " to '".$lastPin['board'] . "'"; ?></div>
					<div class="pin-user-name">by <?php print $pinuser['name']; ?></div>
					
					
			</div>
		</a>
		<!-- <i class="ss-icon ss-social">&#xF650;</i> -->
	</div>
	<!-- <script src="//code.jquery.com/jquery-latest.min.js"></script> -->
	<!-- <script src="//unslider.com/unslider.min.js"></script> -->
	<!-- <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script> -->
	<!-- <script src="/wp-content/plugins/pinfeed/pin.js"></script> -->
	