<?php 

 require_once("GVPinfeed.php");
 $pinfeed = new GVPinfeed;
 if(isset($instance) && function_exists("is_page")){ // check if it's Wordpress or not
	 $pinAccount = $instance['pin_user'];
	 $pinBoard = ($instance['pin_board'] != "")?$instance['pin_board']:false;
	 $wp = true;
 }else{
	 $pinAccount = "pinterest"; //set your own account name here if implementing this widget outside wordpress
	 $pinBoard = "";
	 $wp = false;
 }
 
 $pins = $pinfeed->get_pins_for_user($pinAccount, $pinBoard, 3);
 $pinuser = $pinfeed->get_user_info($pinAccount);

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
					?>
						<li>
							<a href="<?php echo $pin['link']; ?>" >
								<img class="pin-img" src="<?php echo $pin['image_236']; ?>" />
							</a>
							<p class="pin-desc" >
								<?php echo $pin['text']; ?>
								<span class="repin-icon" >
									<a data-pin-do="buttonPin" data-pin-config="beside" href="//www.pinterest.com/pin/create/button/?url=<?php echo urlencode($pin['link']); ?>&media=<?php echo  urlencode($pin['image_236']); ?>&description=<?php echo urlencode(strip_tags($pin['description'])); ?>" target="_blank" >
									<img class="repin-icon" src="/wp-content/plugins/GVPinfeed/images/pin-icon-28.png" />
									</a>
								</span>
							</p>
							
							
							<a class="pin-user-link" target="_blank"  href="http://www.pinterest.com/<?php print $pinusername; ?>/">
								<div class="pin-user-info">
										<img class="pin-icon" src="/wp-content/plugins/GVPinfeed/images/pinterest_icon_28.png" />
										<div class="pin-date">pinned <?php print $pin['time_ago']; if($pin['board'] != $pinuser['name']) echo " to '".$pin['board'] . "'"; ?></div>
										<div class="pin-user-name">by <?php print $pinuser['name']; ?></div>
										
										
								</div>
							</a>
						</li>
				<?php } ?>
	
			</ul>
		</div>
		
		<!-- <i class="ss-icon ss-social">&#xF650;</i> -->
	</div>
	
	<?php if(!$wp){ ?>}
		<script src="//code.jquery.com/jquery-latest.min.js"></script>
		<script src="//unslider.com/unslider.min.js"></script>
		<!-- <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script> -->
		<script src="/wp-content/plugins/pinfeed/pin.js"></script>
	<?php } ?>