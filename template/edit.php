<?php

defined('ABSPATH') or die("No script kiddies please!");

/*if(isset( $_REQUEST['question-category'] ) && absint($_REQUEST['question-category']) > 0){

	$product_id = dwqa_siteinfo_get_product_by_question_category(absint($_REQUEST['question-category']));

	if($product_id > 0 && dwqa_siteinfo_has_user_purchased(get_current_user_id(), $product_id)){

		$disabled = false;

		?>

		<input type="hidden" name="question-category" value="<?php echo absint($_POST['question-category']); ?>" />

		<?php

	} else {

		$disabled = true;		

		//echo "invalid license";

	}

} else {

	$disabled = true;	

}

if(empty($domains)){

	$disabled = true;

}*/

if(dwqa_siteinfo_has_user_purchased(get_current_user_id(), NULL, NULL, 1)){

	$disabled = false;

} else {

	

	$disabled = true;

}

?>

<label for="select-userexpertise"><?php echo _e('Please select your general expertise in the area of this request','dwqa_site_info'); ?> &lowast;</label>

<select <?php echo $disabled === true ? "disabled" : ""; ?> id="select-userexpertise" name="userexpertise" class="inputbox required" style="width:auto;" aria-required="true" required>

	<option value="" selected="selected"><?php echo _e('Please select','dwqa_site_info'); ?></option>

	<option value="0"><?php echo _e('Please explain everything to me very carefully','dwqa_site_info'); ?></option>

	<option value="1"><?php echo _e('I do know some stuff, but please don\'t assume too much','dwqa_site_info'); ?></option>

	<option value="2"><?php echo _e('Overall I know my stuff, but I\'m a little shaky in this area','dwqa_site_info'); ?></option>

	<option value="3"><?php echo _e('I have a good understanding of this stuff','dwqa_site_info'); ?></option>

	<option value="4"><?php echo _e('Not to be rude, but I probably know more about this than you!','dwqa_site_info'); ?></option>

</select>

<fieldset class="sinfo-fieldset">

    <h1><legend><?php echo _e('Your Site information:','dwqa_site_info'); ?></legend></h1>

   <h3> <div class="alert alert-error">

        <?php echo _e('Please enter your site information below. Your information is visible only to our support team.','dwqa_site_info'); ?>

    </div></h3>

</fieldset>

<div id="taxonomy-site-info" class="site-infodiv">

	<nav role="navigation" class="main-navigation clearfix" id="site-info-navigation">

        <ul id="site-info-tabs" class="site-info-menu">

            <li class="menu-item"><a href="#site-info-live" id="site-info-live-label"><span><?php echo _e('Live Site','dwqa_site_info'); ?></a></span></li>

            <li class="menu-item"><a href="#site-info-test" id="site-info-test-label"><span><?php echo _e('Test Site','dwqa_site_info'); ?></a></span></li>

        </ul>

	</nav>

    

    <div id="site-info-live" class="site-info-panel">

        <div class="sinfo_box_right"><img src="<?php echo plugins_url(); ?>/jomsocial_site_info/images/safe-secure.png" align="right" alt="" title="" /></div>

		<div class="input-title" id="site_info_user_domains">

            <label for="sinfo_domain"><?php echo _e('Domain','dwqa_site_info'); ?> &lowast;</label>

            <select <?php echo $disabled === true ? "disabled" : ""; ?> name="sinfo_domain" id="sinfo_domain" class="required" style="background-color:#FFF;" aria-required="true" required>

                <option value="" selected="selected"><?php echo _e('Select a domain','dwqa_site_info'); ?></option>

                <?php 

                if(isset($domains) && !empty($domains)){

                    foreach($domains as $domain) {

                        $selected = (isset($sinfo_decoded) && $domain == $sinfo_decoded->sinfo_domain) ? 'selected="selected"' : '';

                        echo '<option ' . $selected . ' value="' . esc_html($domain) . '">' . esc_html($domain) . '</option>';

                    }

                }

                ?>

            </select>

        </div>    

        <h2><?php echo _e('Site Information (Mandatory)','dwqa_site_info'); ?></h2>

        <div class="input-title">

            <label for="sinfo_site_url"><?php echo _e('Admin URL','dwqa_site_info'); ?> &lowast;</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_site_url" class="required" aria-required="true" required style="width: 350px;" type="text" name="sinfo_site_url" value="<?php echo (isset($sinfo_decoded->sinfo_site_url)) ? esc_html($sinfo_decoded->sinfo_site_url) : '' ; ?>" autocomplete="off" />

        </div>

        <div class="input-title">

            <label for="sinfo_site_adminu"><?php echo _e('Admin Username','dwqa_site_info'); ?> &lowast;</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_site_adminu" class="ks input required" aria-required="true" required type="text" name="sinfo_site_adminu" value="<?php echo (isset($sinfo_decoded->sinfo_site_adminu)) ? esc_html($sinfo_decoded->sinfo_site_adminu) : '' ; ?>" autocomplete="off" />

        </div>

        <div class="input-title">

            <label for="sinfo_site_adminp"><?php echo _e('Admin Password','dwqa_site_info'); ?> &lowast;</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_site_adminp" class="ks input required" aria-required="true" required type="text" name="sinfo_site_adminp" value="<?php echo (isset($sinfo_decoded->sinfo_site_adminp)) ? esc_html($sinfo_decoded->sinfo_site_adminp) : '' ; ?>" autocomplete="off" />

        </div>

        <h2><?php echo _e('Site FTP Information (Recommended)','dwqa_site_info'); ?></h2>

        <p>

            <label for="sinfo_ftp_host"><?php echo _e('FTP Host','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_ftp_host" class="ks input" type="text" name="sinfo_ftp_host" value="<?php echo (isset($sinfo_decoded->sinfo_ftp_host)) ? esc_html($sinfo_decoded->sinfo_ftp_host) : ''; ?>" autocomplete="off" />

        </p>

        <p>

            <label for="sinfo_ftp_u"><?php echo _e('FTP Username','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_ftp_u" class="ks input" type="text" name="sinfo_ftp_u" value="<?php echo (isset($sinfo_decoded->sinfo_ftp_u)) ? esc_html($sinfo_decoded->sinfo_ftp_u) : ''; ?>" autocomplete="off" />

        </p>

        <p>

            <label for="sinfo_ftp_p"><?php echo _e('FTP Password','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_ftp_p" class="ks input" type="text" name="sinfo_ftp_p" value="<?php echo (isset($sinfo_decoded->sinfo_ftp_p)) ? esc_html($sinfo_decoded->sinfo_ftp_p) : ''; ?>" autocomplete="off" />

        </p>

    

        <h2><?php echo _e('Misc Information','dwqa_site_info'); ?></h2>

        <h5><?php echo _e('Enter any other information we may need in order to speed up our support (ie: phpMyAdmin, cPanel, etc.)','dwqa_site_info'); ?>:</h5>

        <textarea <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_misc" class="ks input" type="text" name="sinfo_misc" autocomplete="off"><?php echo (isset($sinfo_decoded->sinfo_misc)) ? esc_html($sinfo_decoded->sinfo_misc) : ''; ?></textarea>

        <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_os" type="hidden" name="sinfo_os" value="<?php echo getOsInfo(); ?>" autocomplete="off" />

        <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_browser" type="hidden" name="sinfo_browser" value="<?php echo getBrowser(); ?>" autocomplete="off" />

    </div>

    <div id="site-info-test" class="site-info-panel" style="display:none;">

        <div class="sinfo_box_right"><img src="<?php echo plugins_url(); ?>/jomsocial_site_info/images/safe-secure.png" align="right" alt="" title="" /></div>

        <h2><?php echo _e('Test/Development Site Information (Optional)','dwqa_site_info'); ?></h2>

        <p>

            <label for="sinfo_dev_url"><?php echo _e('Admin URL','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_dev_url" class="ks input" style="width: 350px;" type="text" name="sinfo_dev_url" value="<?php echo (isset($sinfo_decoded->sinfo_dev_url)) ? esc_html($sinfo_decoded->sinfo_dev_url) : '' ; ?>" autocomplete="off" />

        </p>

        <p>

            <label for="sinfo_dev_adminu"><?php echo _e('Admin Username','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_dev_adminu" class="ks input" type="text" name="sinfo_dev_adminu" value="<?php echo (isset($sinfo_decoded->sinfo_dev_adminu)) ? esc_html($sinfo_decoded->sinfo_dev_adminu) : '' ; ?>" autocomplete="off" />

        </p>

        <p>

            <label for="sinfo_dev_adminp"><?php echo _e('Admin Password','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_dev_adminp" class="ks input" type="text" name="sinfo_dev_adminp" value="<?php echo (isset($sinfo_decoded->sinfo_dev_adminp)) ? esc_html($sinfo_decoded->sinfo_dev_adminp) : '' ; ?>" autocomplete="off" />

        </p>

    

    

        <h2><?php echo _e('htaccess Login Information (if any)','dwqa_site_info'); ?></h2>

        <p>

            <label for="sinfo_htaccess_u"><?php echo _e('Username','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_htaccess_u" class="ks input" type="text" name="sinfo_htaccess_u" value="<?php echo (isset($sinfo_decoded->sinfo_htaccess_u)) ? esc_html($sinfo_decoded->sinfo_htaccess_u) : ''; ?>" autocomplete="off" />

        </p>

        <p>

            <label for="sinfo_htaccess_p"><?php echo _e('Password','dwqa_site_info'); ?>:</label>

            <input <?php echo $disabled === true ? "disabled" : ""; ?> id="sinfo_htaccess_p" class="ks input" type="text" name="sinfo_htaccess_p" value="<?php echo (isset($sinfo_decoded->sinfo_htaccess_p)) ? esc_html($sinfo_decoded->sinfo_htaccess_p) : ''; ?>" autocomplete="off" />

        </p>

    </div>

</div>