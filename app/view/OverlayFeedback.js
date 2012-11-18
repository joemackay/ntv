Ext.define("NTV.view.OverlayFeedback", {
    extend  : "Ext.form.Panel",
    requires: ['Ext.field.Hidden', 'Ext.form.FieldSet'],
    alias   : "widget.overlayfeedbackpanel",
    xtype   : 'overlayfeedbackpanel',
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
                html    : '<div style="float:left; width: 70px; padding: 5px 0 0 5px;"><img src="" height="40" /></div><div style="float:left; width: 60%;"> We highly appreciate your feedback towards improving the experience of our Videos App</div>',
            },
            {
                xtype: 'fieldset',
                title:  'Type your Feedback here',
                items : [
                    {
                        xtype   : 'textareafield',
                        name    : 'feedback',
                    }                        
                ]
            },
            {
                xtype: 'toolbar',
                docked: "bottom",
                items : [
                    {
                        xtype   : 'button',
                        text    : 'Submit',
                        ui      : 'confirm',
                        handler : this.onSubmitButtonTap,
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
    onSubmitButtonTap: function() {
        console.log("submitFeedbackCommand");
        this.fireEvent("submitFeedbackCommand");
    },
    onCancelButtonTap: function() {
        console.log("cancelOverLayCommand");
        this.destroy();
    }
});