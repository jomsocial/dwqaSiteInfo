<?php
defined('ABSPATH') or die("No script kiddies please!");
?>

<div class="alert alert-error dwqa-content">
    <div class="sinfo-box">
        <ul class="sinfo-preview">

            <li>
                <strong><?php echo _e( 'Domain','dwqa_site_info' ); ?></strong><br />
                <a href="<?php echo 'http://' . $decoded_sinfo->domain; ?>" target="_blank"><?php echo $decoded_sinfo->domain; ?></a><br /><br />
                <strong><?php echo _e( 'Administration','dwqa_site_info' ); ?></strong><br />
                <?php echo _e( 'URL','dwqa_site_info' ); ?>: <a href="<?php echo isset($decoded_sinfo->site_url) ? $decoded_sinfo->site_url : "#"; ?>" target="_blank"><?php echo isset($decoded_sinfo->site_url) ? $decoded_sinfo->site_url : ""; ?></a><br />
                <?php echo _e( 'User','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->site_adminu) ? $decoded_sinfo->site_adminu : ""; ?><br />
                <?php echo _e( 'Password','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->site_adminp) ? $decoded_sinfo->site_adminp : ""; ?><br /><br />
                <strong><?php echo _e( 'htaccess','dwqa_site_info' ); ?></strong><br />
                <?php echo _e( 'Username','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->htaccess_u) ? $decoded_sinfo->htaccess_u : ""; ?><br />
                <?php echo _e( 'Password','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->htaccess_p) ? $decoded_sinfo->htaccess_p : ""; ?><br /><br />
            </li>
            <li>
                <strong><?php echo _e( 'Test/Development Site','dwqa_site_info' ); ?></strong><br />
                <?php echo _e( 'URL','dwqa_site_info' ); ?>: <a href="<?php echo isset($decoded_sinfo->dev_url) ? $decoded_sinfo->dev_url : "#"; ?>" target="_blank"><?php echo isset($decoded_sinfo->dev_url) ? $decoded_sinfo->dev_url : ""; ?></a><br />
                <?php echo _e( 'User','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->dev_adminu) ? $decoded_sinfo->dev_adminu : ""; ?><br />
                <?php echo _e( 'Password','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->dev_adminp) ? $decoded_sinfo->dev_adminp : ""; ?><br /><br />
            </li>
            <li>
                <strong><?php echo _e( 'FTP','dwqa_site_info' ); ?></strong><br />
                <?php echo _e( 'Host','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->ftp_host) ? $decoded_sinfo->ftp_host : ""; ?><br />
                <?php echo _e( 'User','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->ftp_u) ? $decoded_sinfo->ftp_u : ""; ?><br />
                <?php echo _e( 'Password','dwqa_site_info' ); ?>: <?php echo isset($decoded_sinfo->ftp_p) ? $decoded_sinfo->ftp_p : ""; ?><br /><br />
                <strong><?php echo _e( 'Miscellaneous','dwqa_site_info' ); ?></strong><br />
                <?php echo isset($decoded_sinfo->misc) ? $decoded_sinfo->misc : ""; ?>
            </li>
            <li>                                
                <strong><?php echo _e( 'Knowledge Level','dwqa_site_info' ); ?></strong><br />
                <?php 
                    if(isset($decoded_sinfo->userexpertise)){
                        $userexpertise = $decoded_sinfo->userexpertise >= 0 ? $decoded_sinfo->userexpertise : 0 ;
						switch($decoded_sinfo->userexpertise){
							case 0:
		                        echo _e( 'Please explain everything to me very carefully','dwqa_site_info' );
							break;
							case 1:
		                        echo _e( 'I do know some stuff, but please don\'t assume too much','dwqa_site_info' );
							break;
							case 2:
		                        echo _e( 'Overall I know my stuff, but I\'m a little shaky in this area','dwqa_site_info' );
							break;
							case 3:
		                        echo _e( 'I have a good understanding of this stuff','dwqa_site_info' );
							break;
							case 4:
		                        echo _e( 'Not to be rude, but I probably know more about this than you!','dwqa_site_info' );
							break;																												
						}
                    }
                ?><br /><br />
            </li> 
            <li>                                
                <strong><?php echo _e( 'OS','dwqa_site_info' ); ?></strong><br />
                <?php echo isset($decoded_sinfo->os) ? $decoded_sinfo->os : ""; ?><br />
            </li>
            <li>                                
                <strong><?php echo _e( 'Browser','dwqa_site_info' ); ?></strong><br />
                <?php echo isset($decoded_sinfo->browser) ? $decoded_sinfo->browser : "";	?><br />
            </li>                                                        
        </ul>                        
    </div>
    <div class="sinfo-box">
        <ul class="sinfo-preview">
            <li>
                <span id="notesThreadMsg" class="clearfix"></span>
                <strong><?php echo _e( 'Notes about the thread','dwqa_site_info' ); ?></strong><br />
                    <textarea class="sinfo-notes-disabled" disabled name="notesThread" id="notesThread" rows="10" cols="30"><?php echo isset($decoded_sinfo->notesthread) ? html_entity_decode(urldecode($decoded_sinfo->notesthread)) : ""; ?></textarea><br/>
	                <input type="button" name="edit-notes" onClick="javascript:ThreadEdit('notesThread');" id="edit-notes" value="<?php echo _e('Edit','dwqa_site_info'); ?>" class="dwqa-btn dwqa-btn-primary" />
					<input type="button" name="save-notes" onClick="javascript:ThreadSave('notesThread', <?php echo $post_id; ?> )" id="save-notes" value="<?php echo _e('Save','dwqa_site_info'); ?>" class="dwqa-btn dwqa-btn-default" />
                    </br>
                <span id="notesUserMsg" class="clearfix"></span>                               
                <strong><?php echo _e( 'Notes about the user','dwqa_site_info' ); ?></strong><br />
                    <textarea class="sinfo-notes-disabled" disabled name="notesUser" id="notesUser" rows="10" cols="30"><?php echo isset($user_notes_final) ? html_entity_decode(urldecode($user_notes_final)) : ""; ?></textarea><br/>
	                <input type="button" name="edit-user-notes" onClick="javascript:UserEdit('notesUser')" id="edit-user-notes" value="<?php echo _e('Edit','dwqa_site_info'); ?>" class="dwqa-btn dwqa-btn-primary" />
					<input type="button" name="save-user-notes" onClick="javascript:UserSave('notesUser', <?php echo $post->post_author; ?> )"id="save-user-notes" value="<?php echo _e('Save','dwqa_site_info'); ?>" class="dwqa-btn dwqa-btn-default" />                    
                    </br>
            </li> 
        </ul>
    </div>                        
</div>	