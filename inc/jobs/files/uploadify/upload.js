jQuery(document).ready(function() {
	    jQuery("#file_upload").uploadify({			
			'uploader'		: awp_upload.swfUrl, //get swf file from jobs.php using param awp_upload
			'buttonText'	: 'Browse',
			'cancelImg'		: awp_upload.cancelImg,					
			'fileExt'		: '*.*',			
			'auto'			: true,		
			'scriptAccess' : 'always',
			'removeCompleted' : false,
			'onSelectOnce'    : function(event,data ) {
			updateUploadSettings("file_upload");	
			    },
			 'onCancel'    : function(event,ID,fileObj,data) {
			    	uploadCancelhandler(ID);
			        },		    
			'onError' 		: function(errorObj, q, f, err) { 
				//alert(err.type + ' Error: ' + err.info);
				//console.log(err); 
				},
			'onComplete'	: function(event, ID, file, response, data) { 
				uploadComplete (event, ID, file, response, data);				
				//console.log(file); 
				}
		});
});
function updateUploadSettings(uploadDOMId)
{
	var scripturl = jQuery('#upload_script').val();
	jQuery("#"+uploadDOMId).uploadifySettings('script',scripturl);
}

function uploadComplete (event, ID, file, response, data)
{   
	
	if(response < 0 )
	{
		uploadErrorhandler(ID);
		jQuery('#uploadfile_docid').val('');
	}else {
		jQuery('#uploadfile_docid').val(response);
	}
}
function uploadErrorhandler(ID)
{
	jQuery("#file_upload" +  ID).find('.percentage').text(" Upload Failed");
    jQuery("#file_upload" +  ID).find('.uploadifyProgress').hide();
    jQuery("#file_upload" +  ID).addClass('uploadifyError');
}
function uploadCancelhandler(ID)
{
	jQuery('#uploadfile_docid').val('');
}