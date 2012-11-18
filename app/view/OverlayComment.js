Ext.define("NTV.view.OverlayComment", {
    extend  : "Ext.form.Panel",
    requires: ['Ext.field.Hidden', 'Ext.form.FieldSet'],
    alias   : "widget.overlaycommentpanel",
    xtype   : 'overlaycommentpanel',
    config  :{
        centered        : true,
		width           : Ext.os.deviceType=='phone'? '60%' : '80%',// 400,
        height          : Ext.os.deviceType=='phone'? '60%' : '80%', //400,
        modal           : true,
        padding         : 10,
	    hideMode        : 'close',
	    hideOnMaskTap   : true,
		styleHtmlContent: true,
		layout          : 'vbox',
		cls: 'popup',
		videoId         : null,
		videoTitle      : null,
		videoThumbnail  : null,
		showAnimation   : {
	        type: 'popIn',
	        duration: 100,
	        easing: 'ease-in'
	    },
	    hideAnimation   :{
	    	type: 'popOut',
	        duration: 100,
	        easing: 'ease-out'
	    }
    },
    initialize : function(){
        this.callParent(arguments);
        this.add([
            {
                xtype: 'panel',
                html    : '<div style="float:left; width: 70px; padding: 5px 0 0 5px;"><img src="'+ this.config.videoThumbnail +'" height="40" /></div><div style="float:left; width: 60%;"> '+ this.config.videoTitle +'</div>',
            },
            {
                xtype: 'fieldset',
                title:  'Type your comments here',
                //instructions:   'Type your comments here',
                items : [
                    {
                        xtype   : 'hiddenfield',
                        name    : 'video_id',
                        value   : this.config.videoId
                    },
                    {
                        xtype   : 'textareafield',
                        name    : 'comments',
                    }                        
                ]
            },
            {
                xtype: 'toolbar',
                docked: "bottom",
                items : [
                    {
                        xtype   : 'button',
                        text    : 'Save',
                        ui      : 'confirm',
                        handler : this.onSaveButtonTap,
                        scope   : this, 
                    },
                    { 
                        xtype   : "spacer" 
                    },
                    {
                        xtype   : 'button',
                        text    : 'Cancel',
                        handler : this.onCancelButtonTap,
                        scope   : this, 
                    },
                ]
            } 
        ]);  
    },
    onSaveButtonTap: function() {
        //console.log("saveReviewCommand");
        this.fireEvent("saveReviewCommand");
    },
    onCancelButtonTap: function() {
        console.log("cancelOverLayCommand");
        //this.fireEvent("cancelOverLayCommand");
        this.destroy();
    }
});