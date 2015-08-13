<?php
defined('ABSPATH') or die("No script kiddies please!");
if(empty($domains)){
	
	$Path 		= $_SERVER['REQUEST_URI'];	
	
	_e('This form is disabled. Please select the order and enter your support domain<br/> ','dwqa_site_info');
	if(!empty($purchases)){
		foreach($purchases as $payment){
			$licenses = edd_software_licensing()->get_licenses_of_purchase( $payment->ID );
			
			if(!empty($licenses)){
				
				foreach($licenses as $license){
					
					if ($license->post_status == 'expired') {
						continue;
					}
					
					$download_id 	= edd_software_licensing()->get_download_id($license->ID);
					$post			= get_post($download_id);
					
					$download_files = get_post_meta( $post->ID, 'edd_download_files', true );
					
					
                    foreach ( $download_files as $filekey => $file ) {
						$post_meta = get_post_meta($licenses[$cart_row]->ID, '_edd_sl_download_id', true);
						$product   = get_post($file['attachment_id']);
						
						if(!empty($product)){
							?>
							<div style="float:left;">
								<span class="edd_downloads_license_number">
									<?php echo $post->post_title ?><br/>
									<?php _e( 'License No: '.edd_software_licensing()->get_license_key( $license->ID ), 'dwqa_site_info' ); ?>
								</span>
							</div>
							<div style="float:left;">
		
								<form action="<?php echo site_url($Path); ?>" method="post" class="edd_downloads_domains" id="edd_downloads_domains_domain_form_<?php echo absint( $license->ID ) ?>">
									<input id="edd_downloads_domains_domain_<?php echo absint($license->ID); ?>" class="ks input" <?php echo $disabled; ?> style="width: 150px;" type="text" name="edd_downloads_domains_domain" value="" autocomplete="off" />
									<button style="submit" onClick="return check_domain(<?php echo absint($license->ID); ?>);" class="button blue edd-submit"><?php echo _e('Save','edd_downloads'); ?></button><br/>
		
									<input type="hidden" name="domains_action" value="save_domains_sites" />
									<input type="hidden" name="product_id" value="<?php echo $product->ID; ?>" />
									<input type="hidden" name="license_id" value="<?php echo absint( $license->ID ); ?>" />
									<input type="hidden" name="domain_changes" value="0" />
		
								</form>
                            	<span class="edd-loading" id="edd_downloads_domains_domain_<?php echo absint($license->ID); ?>_loading"  style="margin-left: 0px; margin-top: 10px; display:none;"><i class="edd-icon-spinner edd-icon-spin"></i></span>		
							</div>                    
							<div style="clear:both;">&nbsp;</div>
							<?php
						}
					}
				}
			}
		}
	}
}