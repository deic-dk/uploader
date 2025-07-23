 function keyPressAction(e){
	 var name = $('#new_folder_name').val();
	if(name &&(e.key==='Enter' || e.keyCode===13)) {
		var parentDir = $('#download_folder').text();
		doCreateFolder(parentDir, name);
		$('#new_folder_name').remove();
	}
	if (e.key==='Escape' || e.keyCode===27) {
		$('#new_folder_name').remove();
		$(document).off('keyup', keyPressAction);
	}
}
 
 function createFolder(){
	 // Listen for enter or <esc> in the input field
	 $('.ui-dialog-buttonpane').append('<input id="new_folder_name" title="Name of new folder" />');//.on('keyup', keyPressAction);
	 // Listen for click outside the input field
	 $(document).on('keyup', keyPressAction);
 }
 
 function doCreateFolder(parentDir, name){
		$.post(
			OC.webroot+'/themes/deic_theme_oc7/apps/files/ajax/newfolder.php',	
			{
				dir: parentDir,
				foldername: name,
				group: $('#group_folder').val()
			},
			function(result) {
				if (result.status === 'success') {
					// Insert folder icon in tree listing
					//$('ul.jqueryFileTree li.directory').last().clone().appendTo($('ul.jqueryFileTree li.directory a[rel="'+parentDir+'"').parent()).find('a').attr('rel', name).text(name);
					$('a.chosen').click();
					$('a.chosen').click();
				}
				else {
					OC.dialogs.alert(result.data.message, t('core', 'Could not create folder'));
				}
			}
		);
 }
 
 function chooseDownloadFolder(folder){
	 $('#download_folder').attr('group', group);
	 $('[name=HTML5FileUpload_destdir]').val(folder)
 }
 
 function stripTrailingSlash(str) {
	 if(str.substr(-1)=='/') {
		 str = str.substr(0, str.length - 1);
	 }
	 if(str.substr(1)!='/') {
		 str = '/'+str;
	 }
	 return str;
 }

 function stripLeadingSlash(str) {
	 if(str.substr(0,1)=='/') {
		 str = str.substr(1, str.length-1);
	 }
	 return str;
 }

 var choose_download_folder_dialog = null;

 function createBrowser(){
	if(choose_download_folder_dialog != null){
		return false;
	}
	var buttons = {};
	buttons[ "+"] = function() {
		createFolder();
 	};
 	buttons[t("uploader", "Choose")] = function() {
		folder = stripTrailingSlash($('#download_folder').text());
		chooseDownloadFolder(folder);
		$('#new_folder_name').remove();
		$(document).off('keyup', keyPressAction);
		choose_download_folder_dialog.dialog("close");
 	};
 	buttons[t("uploader", "Cancel")] = function() {
 		$('#new_folder_name').remove();
 		$(document).off('keyup', keyPressAction);
		choose_download_folder_dialog.dialog("close");
	};
	$.when(
	choose_download_folder_dialog = $("div.uploader_folder_dialog").dialog({//create dialog, but keep it closed
	title: t("uploader", "Choose destination folder"),
	dialogClass: "no-close",
	autoOpen: false,
	height: 440,
	width: 620,
	modal: true,
	buttons: buttons
	})).then(function(){
		$('.ui-dialog-buttonset button').first().css({left: 'calc(100% - 590px)', position: 'relative'}).attr('Title', t("uploader", "New folder"))
	$('.ui-dialog-titlebar-close').on('click', function(ev){
		$('#new_folder_name').remove();
		$(document).off('keyup', keyPressAction);
		});
	});
 }

 $(document).ready(function(){
	 $('.uploader_choose_download_folder').live('click', function(){
		 createBrowser();
		 choose_download_folder_dialog.dialog('open');
		 choose_download_folder_dialog.show();
		 folder = stripLeadingSlash($('[name=HTML5FileUpload_destdir]').val());
		 group = $('#group_folder').val();
		 $('.uploader_folder_dialog div.loadFolderTree').fileTree({
			 //root: '/',
			 script: '../../apps/chooser/jqueryFileTree.php',
			 multiFolder: false,
			 selectFile: false,
			 selectFolder: true,
			 folder: folder,
			 file: '',
			group: group
		 },
		 // single-click
		 function(file) {
			 $('#download_folder').text(file);
		 },
		 // double-click
		 function(file) {
			 if(file.indexOf("/", file.length-1)!=-1){// folder double-clicked
				chooseDownloadFolder(file);
				$('#new_folder_name').remove();
				$(document).off('keyup', keyPressAction);
				choose_download_folder_dialog.dialog("close");
		 }
	 });
 });

 });
