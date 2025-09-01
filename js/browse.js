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
	 name = name.replace(/\/$/, "");
		$.post(
			OC.webroot+'/themes/deic_theme_oc7/apps/files/ajax/newfolder.php',	
			{
				dir: parentDir,
				foldername: name,
				group: $('#uploader_group_folder').val()
			},
			function(result) {
				if (result.status === 'success') {
					// Insert folder icon in tree listing
					//$('ul.jqueryFileTree li.directory').last().clone().appendTo($('ul.jqueryFileTree li.directory a[rel="'+parentDir+'"').parent()).find('a').attr('rel', name).text(name);
					// Nope - there will be no bindings attached. Instead we just click to refresh.
					$('a.chosen').click();
					$('a.chosen').click();
				}
				else {
					OC.dialogs.alert(result.data.message, t('uploader', 'Could not create folder'));
				}
			}
		);
 }
 
 function chooseUploadFolder(folder, group){
	 $('#download_folder').attr('group', group);
	 $('[name=HTML5FileUpload_destdir]').val(folder);
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
		var folder = stripTrailingSlash($('#download_folder').text());
		var group = $('#uploader_group_folder').val();
		chooseUploadFolder(folder, group);
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
	title: t("uploader", "Choose  folder"),
	dialogClass: "no-close",
	autoOpen: false,
	height: 440,
	width: 620,
	modal: true,
	buttons: buttons
	})).then(function(){
	$('.ui-dialog-buttonset button:contains("+")').last().css({left: 'calc(100% - 590px)', position: 'relative'}).attr('Title', t("uploader", "New folder"));
	$('.ui-dialog-titlebar-close').on('click', function(ev){
		$('#new_folder_name').remove();
		$(document).off('keyup', keyPressAction);
		});
	});
 }

 function expirationCheck(show) {
	if(show) {
		OC.Share.showExpirationDate('');
		$('.app-uploader #expirationDate').css('display', 'inline-block');
		}
	else {
		$('.app-uploader #expirationDate').hide('slow');
	}
}

 function isDirty() {
		var dirty = $('.HTML5FileUploadShare_Button[disabled]').length!=$('.HTML5FileUploadShare_Button').length;
		return dirty;
	}

// From share.js
function linkShare(itemSource, itemSourceName, callback){
	var itemType = 'file';
	var token = '';
	var expireDateString = '';
	if ($('.app-uploader #expirationDate').val() && $('.app-uploader #expirationDate').val()!='') {
		expireDateString = $('.app-uploader #expirationDate').val() + ' 00:00:00';
	}
	var requireLogin = $('.app-uploader input#loginCheckbox').is(':checked');
	var notifyOnDownload = $('.app-uploader input#notifyCheckbox').is(':checked');

	// Create a link
	OC.Share.share(itemType, itemSource, OC.Share.SHARE_TYPE_LINK, $('#linkPassText').val(), OC.PERMISSION_READ, itemSourceName, expireDateString, callback, token, requireLogin, notifyOnDownload);
}

function finishSharing() {
	var isLastElement = $('#HTML5FileUpload_Tbl tr.HTML5FileUpload_Complete').length==$('#HTML5FileUpload_Tbl tr.HTML5FileUpload_Complete[token!=""]').length && $('#HTML5FileUpload_Tbl tr.HTML5FileUpload_Complete').length==oc_uploader_links.length;
if(!isLastElement){
		return false;
	};
	var expireDateString = '';
	if ($('.app-uploader #expirationDate').val() && $('.app-uploader #expirationDate').val()!='') {
		expireDateString = $('.app-uploader #expirationDate').val() + ' 00:00:00';
	}
	var myData = {
			recipient: $('#uploader_recipients').val(),
			expiration: expireDateString,
			links: oc_uploader_links,
			filenames: oc_uploader_filenames,
			fileids: oc_uploader_fileids,
			require_login: $('.app-uploader input#loginCheckbox').is(":checked")?'yes':'no',
			notify_on_download: $('.app-uploader input#notifyCheckbox').is(":checked")?'yes':'no',
	};
	var jsonData = JSON.stringify(myData);
	console.log('Emailing links: '+jsonData);
	$.ajax({
		type:'POST',
		url:OC.linkTo('uploader','ajax/shareFiles.php'),
				dataType:'json',
				data: jsonData,
				async:false,
				success:function(s){
					OC.dialogs.alert(t('core', 'Your file'+(myData.filenames.length>1?'s have ':' has ')+'been shared'+(myData.recipient?(' and a link sent to'+myData.recipient):'')+'!'), s.message);
				},
				error:function(s){
					OC.dialogs.alert(s.message, t('core', 'Error while sharing'));
				}
	});
}

function doShareUploaded(ok, input /*used for password input - null here*/, args /*set to true when called by clicking confirm box*/){	
	if(ok){
		var tr;
		var masterurl = $('#uploaderapp').attr('masterurl');
		var trs = $('#HTML5FileUpload_Tbl tr.HTML5FileUpload_Complete');
		oc_uploader_links = [];
		oc_uploader_filenames = [];
		oc_uploader_fileids = [];
		var shareUrl = '';
		var fileName = '';		
		var fileID = '';		
		console.log('Sharing...'+trs.length);
		trs.each(function(index, value){
			if($(this).attr('fileid') && $(this).attr('filename') && !$(this).attr('token')){
				fileName = $(this).attr('filename');
				fileID = $(this).attr('fileid');
				oc_uploader_filenames.push(fileName);
				oc_uploader_fileids.push(fileID);
				console.log('Sharing '+$(this).attr('fileid')+', '+fileName);
				linkShare(parseInt($(this).attr('fileid')), fileName, function(data) {
					console.log('Got token '+data.data.file_source+'-->'+data.data.token);
					tr = $('tr[fileid="'+data.data.item_source+'"]');
					shareUrl = masterurl+'shared/'+data.data.token;
					oc_uploader_links.push(shareUrl);
					tr.find('td.sharing_link').last().append('<a class="openLink" href="'+shareUrl+'" target="_blank" title="'+t("uploader", "Open URL")+'"><img class="linkIcon" src="'+OC.webroot+'/core/img/actions/public.svg"></a>').append('<a class="copyLink" href="'+shareUrl+'" target="_blank" title="'+t("uploader", "Copy URL")+'"><img  class="linkIcon" src="'+OC.webroot+'/themes/deic_theme_oc7/core/img/actions/copy.svg"></a>');
					$.when(tr.attr('token', data.data.token)).then(function(){
					// After the last item, send email etc.
						finishSharing();
					});
				});
			}
		});
		$('.HTML5FileUploadShare_Button').prop("disabled", true);
	}
}

function shareUploaded(ok, input /*used for password input - null here*/, args /*set to true when called by clicking confirm box*/){
	if(ok){
		OC.dialogs.notify(
				t("uploader", "Sharing data via public links to un-authenticated users is generally not appropriate for confidential and/or personal sensitive data.\n" +
				"For such, please consider sharing with authenticated users - and, if necessary, invite collaborators to become users of this service.\n" +
				"Any obligations concerning data shared via public links rest solely with you.\n\n" +
				"Do you wish to proceed?"), t("core", "Confirm"), doShareUploaded, true, null, null, OCdialogs.YES_NO_BUTTONS, true);
	}
}

$(document).ready(function(){
	$('.uploader_choose_download_folder').live('click', function(){
		createBrowser();
		choose_download_folder_dialog.dialog('open');
		choose_download_folder_dialog.show();
		var folder = stripLeadingSlash($('[name=HTML5FileUpload_destdir]').val());
		var group = $('#uploader_group_folder').val();
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
				var group = $('#uploader_group_folder').val();
				chooseUploadFolder(file, group);
				$('#new_folder_name').remove();
				$(document).off('keyup', keyPressAction);
				choose_download_folder_dialog.dialog("close");
			}
		});
	});
	
	 $('#shareCheckbox').change(function(ev){
		$('.uploader_share_meta').toggle('slow');
		$('#uploader_recipients.first').val("").removeClass('first');
	 });
	 
	 $('#passwordCheckbox').change(function(ev){
			$('#linkPassText').toggle('slow');
		 });
	 
	 $("#expirationCheckbox").change(function() {
		expirationCheck(this.checked);
	});
	 
	 window.addEventListener("beforeunload", function (e) {
		if(!isDirty()) {
			return undefined;
		}
		// Custom messages are no longer allowed:
		// https://stackoverflow.com/questions/66230698/how-to-set-my-own-message-on-beforeunload-event
		var confirmationMessage = "It looks like you haven't shared your uploaded files. If you leave this page, you'll have to share them manually/individually.";
		(e || window.event).returnValue = confirmationMessage; //Gecko + IE
		e.preventDefault();
		e.stopPropagation();
		e.returnValue = confirmationMessage;
		return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
	});

	$('#uploader_recipients').on("focusout", function() {
		var emailInput = $('#uploader_recipients')[0];
		if(emailInput.validity.typeMismatch || $('#uploader_recipients').val()=="" && $('#shareCheckbox').is(":checked")) {
			OC.dialogs.alert(t('uploader', 'Please enter one or several valid email addresses.'), t('uploader', 'Invalid email'));
			emailInput.setCustomValidity("Please enter one or several valid email addresses.");
		}
		else{
			emailInput.setCustomValidity("");
		}
	});
	
	$('.HTML5FileUploadShare_Button').on('click', function(ev){
		var len = $('#HTML5FileUpload_Tbl tr.HTML5FileUpload_Complete').length;
		if(len==0){
			OC.dialogs.alert(t('uploader', 'No files uploaded.'), t('uploader', 'No uploads'));
			return false;
		}
		var searchParams = new URLSearchParams(window.location.search);
		if($('#uploader_recipients').val()=='' && searchParams.has('filetransfer') && searchParams.get('filetransfer')!=='false'){
			OC.dialogs.notify(
					t("uploader", "Notice: You haven\'t filled in any recipients. The file"+(len>1?"s":"")+" will be shared, but you'll have to send the link"+(len>1?"s":"")+" manually. With many files this can be tedious, so consider adding one or several recipients before sharing.\n\n" +
					"Do you wish to proceed?"), t("core", "No recpient"), shareUploaded, true, null, null, OCdialogs.YES_NO_BUTTONS, true);
		}
		else{
			shareUploaded(true, null, true);
		}
	});
	
	//$('#choose_upload_folder').addClass('hidden');
	//$('.uploader_share').addClass('hidden');
	
	$(document).on('click','a.copyLink', function(event){
		event.preventDefault();
		event.stopPropagation();
		var link = $(this).attr('href');
		navigator.clipboard.writeText(link);
		$(this).css('opacity','0.3');
		$(this).delay(1600).queue(function (next) { 
			$(this).fadeTo(1600, 1.0);
			next(); 
			});
	});
	
	// Hide places, show apps when filetransfer is set
	var searchParams = new URLSearchParams(window.location.search);
	if(searchParams.has('filetransfer') && searchParams.get('filetransfer')!=='false'){
		if($('ul.nav-sidebar li#places span i.icon-angle-down').is(':visible')){
			$('#places .icon-angle-down:visible').first().click();
		}
		// Show info
		$('#content.app-uploader').append($('#HTML5FileUpload_information'));
		$('#HTML5FileUpload_information').show();
	}
	
 });
