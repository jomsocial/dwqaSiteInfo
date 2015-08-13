function ThreadEdit(id){
	document.getElementById(id).disabled = false;
	document.getElementById(id).addClass('sinfo-notes-enabled').removeClass('sinfo-notes-disabled');
}

function ThreadSave(id,post_id){
	var $JS		= jQuery.noConflict();
	var val		= document.getElementById(id).value;
	//val			= val.replace(/\r\n/g, '<br />');
	//val			= val.replace(/\n/g, '<br />');
	val = encodeURIComponent(val);
	
	if(document.getElementById(id).disabled == false && val != ""){
		//update the database
		$JS.ajax({
			url: sinfoEdit.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: ({action : 'ThreadSave', post_id : post_id, value : val, field : 'notesthread'}),			
			success: function(response){
				if(response.error){
					//display error msg
					$JS('#notesThreadMsg').html(NotesNotEdited);
					$JS('#notesThreadMsg').delay(3000).fadeOut(3000, function() {
						$JS('#notesThreadMsg').html('').fadeIn(1);
					});					
				}else if(response.message){
					//proceed - no error message
					$JS('#notesThreadMsg').html(NotesEdited);
					$JS('#notesThreadMsg').delay(3000).fadeOut(3000, function() {
						$JS('#notesThreadMsg').html('').fadeIn(1);
					});
				}
			}
		});	
		document.getElementById(id).disabled = true;
		document.getElementById(id).addClass('sinfo-notes-disabled').removeClass('sinfo-notes-enabled');
	}
}

function UserEdit(id){
	document.getElementById(id).disabled = false;
	document.getElementById(id).addClass('sinfo-notes-enabled').removeClass('sinfo-notes-disabled');
}

function UserSave(id,user_id){

	var $JS		= jQuery.noConflict();
	var val		= document.getElementById(id).value;
	//val			= val.replace(/\r\n/g, '<br />');
	//val			= val.replace(/\n/g, '<br />');
	val = encodeURIComponent(val);
	
	if(document.getElementById(id).disabled == false && val != ""){
		//update the database
		$JS.ajax({
			url: sinfoEdit.ajaxurl,			
			type: 'post',
			dataType: 'json',
			data: ({action : 'UserSave', id : id, value : val, user_id : user_id}),			
			success: function(response){
				if(response.error){
					//display error msg
					$JS('#notesUserMsg').html(NotesNotEdited);
					$JS('#notesUserMsg').delay(3000).fadeOut(3000, function() {
						$JS('#notesUserMsg').html('').fadeIn(1);
					});					
				}else if(response.message){
					//proceed - no error message
					$JS('#notesUserMsg').html(NotesEdited);
					$JS('#notesUserMsg').delay(3000).fadeOut(3000, function() {
						$JS('#notesUserMsg').html('').fadeIn(1);
					});
				}
			}
		});	
		document.getElementById(id).disabled = true;
		document.getElementById(id).addClass('sinfo-notes-disabled').removeClass('sinfo-notes-enabled');
	}	
}

jQuery(document).ready(function($) {
	$('#site-info-live-label').click(function(e){
		e.preventDefault();
		$('#site-info-test').hide();		
		$('#site-info-live').show();
	});
	
	$('#site-info-test-label').click(function(e){
		e.preventDefault();
		$('#site-info-live').hide();		
		$('#site-info-test').show();
	});		
});