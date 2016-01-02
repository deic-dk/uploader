 function chooseDownloadFolder(folder){
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
	choose_download_folder_dialog = $("div.uploader_folder_dialog").dialog({//create dialog, but keep it closed
	title: "Choose destination folder",
	dialogClass: "no-close",
	autoOpen: false,
	height: 440,
	width: 620,
	modal: true,
	buttons: {
		"Choose": function() {
			folder = stripTrailingSlash($('#download_folder').text());
			chooseDownloadFolder(folder);
			choose_download_folder_dialog.dialog("close");
		},
		"Cancel": function() {
			choose_download_folder_dialog.dialog("close");
		}
	}
	});
 }

 $(document).ready(function(){
	 $('.uploader_choose_download_folder').live('click', function(){
		 createBrowser();
		 choose_download_folder_dialog.dialog('open');
		 choose_download_folder_dialog.show();
		 folder = stripLeadingSlash($('[name=HTML5FileUpload_destdir]').val());
		 $('.uploader_folder_dialog div.loadFolderTree').fileTree({
			 //root: '/',
			 script: '../../apps/chooser/jqueryFileTree.php',
			 multiFolder: false,
			 selectFile: false,
			 selectFolder: true,
			 folder: folder,
			 file: ''
		 },
		 // single-click
		 function(file) {
			 $('#download_folder').text(file);
		 },
		 // double-click
		 function(file) {
			 if(file.indexOf("/", file.length-1)!=-1){// folder double-clicked
				chooseDownloadFolder(file);
				choose_download_folder_dialog.dialog("close");
		 }
	 });
 });

 });
