jQuery(document).ready(function() {
	
	jQuery('#cancal_upload').live('click',function(){
		var cancel_id = jQuery(this).attr('rel');
		jQuery('.uploadifyQueueItem').remove();
		jQuery('#uploadfile_docid').val('');
		jQuery('#uploadfile_docid').next("label.error").show();
		jQuery(".awp_hrjobsform_submit_jaform").removeAttr("disabled");
	});
	    jQuery("#file_upload").uploadify({			
			'uploader'		: awp_upload.swfUrl, 
			'script'        : awp_upload.uploadUrl,
			'buttonText'	: 'Browse',
			'cancelImg'		: awp_upload.cancelImg,					
			'auto'			: true,
			'multi': false,
			'scriptAccess'  : 'always',
			'removeCompleted' : false,
			'fileDataName': 'File',
			'onSelect'    : function(event,data,fileObj ) {
	    	jQuery('.uploadifyQueueItem').remove();
	    	updateUploadSettings(event,"file_upload",fileObj.name);
	    	},
			'onProgress'   : function(file, e){ },
			 'onCancel'    : function(event,ID,fileObj,data) {
			    	uploadCancelhandler(ID);
			        },		    
			'onError' 		: function(errorObj, q, f, err) { },
			 'onComplete'	: function(event, ID, file, response, data) { 	
			  uploadComplete (event, ID, file, response, data);				
				
				}
		});
});


function updateUploadSettings(event,uploadDOMId,Filename)
{
jQuery("#"+uploadDOMId).uploadifySettings('scriptData', {
  		        'key':awp_upload.documentKey,
  		        'Content-Disposition': "attachment" + "; filename=\"" + Filename + "\"",
  		        'success_action_status': '201',
  		        'acl': 'private',
  		        'AWSAccessKeyId': awp_upload.accessKey,
  		        'policy': awp_upload.policy,
  		        'signature': awp_upload.signature
  		      });
}
function uploadComplete (event, ID, fileObj, response, data)
{   
	var isFound = response.search(awp_upload.documentKey);
     if(isFound < 0 )
	{
		uploadErrorhandler(ID);
		jQuery('#uploadfile_docid').val('');
	}else {
		var type = fileObj.type ? fileObj.type : '.' + fileObj.name.split('.').pop().toLowerCase();
	    var docData = {};
        docData.name = fileObj.name;
        docData.size = fileObj.size;
        docData.type = type;
        docData.key = awp_upload.documentKey;
        var doc_str = 'docname='+docData.name+'&docsize='+docData.size+'&doctype='+docData.type+'&dockey='+docData.key ;
        jQuery.ajax({  
            type: "POST",  
            url: "/wp-admin/admin-ajax.php?action=apptivo_business_jobs_docid",  
            data :doc_str, 
            success: function(msg){
        	if( msg > 0 )
        	{
             jQuery('#uploadfile_docid').val(msg);
             jQuery('#uploadfile_docid').next("label.error").hide();
        	}else{
        		jQuery('#uploadfile_docid').val('');
        		jQuery('#uploadfile_docid').next("label.error").show();
        		uploadErrorhandler(ID);
        	}
           },  
           error: function(){ 
            }  
       });
	}
}
function uploadErrorhandler(ID)
{
	jQuery("#file_upload" +  ID).find('.percentage').text(" Upload Failed");
    jQuery("#file_upload" +  ID).find('.uploadifyProgress').hide();
    jQuery("#file_upload" +  ID).addClass('uploadifyError');
}