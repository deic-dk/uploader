function submit_uploader_form(){
	var folder = stripLeadingSlash($('[name=HTML5FileUpload_destdir]').val());
	var group = $('#uploader_group_folder').val();
	$.ajax({
		type:'POST',
		url:OC.linkTo('uploader','ajax/updatePersonalSettings.php'),
				dataType:'json',
				data: {upload_folder: folder, upload_group: group},
				async:false,
				success:function(s){
					if(s.length!=0){
						$("#uploader_msg").html(s);
					}
					else{
						OC.msg.finishedSaving('#uploader_msg', {status: 'success', data: {message:  	t('uploader', 'Settings saved')}});

					}
				},
				error:function(s){
					$("#uploader_msg").html("Unexpected error!");
				}
	});
}

$(document).ready(function(){
	$("fieldset#uploaderPersonalSettings #uploader_settings_submit").bind('click', function(){
		submit_uploader_form();
	});
	
});

