Ext.define("NTV.view.Overlay", {
    extend  : "Ext.Panel",
    alias   : "widget.overlaypanel",
    xtype   :'overlaypanel',
    config:{
        centered        : true,
		width           : Ext.os.deviceType=='phone'? 250 : 250,// 400,
	    height          : Ext.os.deviceType=='phone'? 150 : 150, //400,
		modal           : true,
	    hideMode        : 'close',
	    hideOnMaskTap   : true,
		styleHtmlContent: true,
		layout          : 'hbox',
		cls             : 'opaqPopup',
		
		/**new vars**/
		videoId          : null,
        videoTitle       : null,
        introText        : null,
        videoDescription : null,
        videoReviews     : null,
        videoThumbnail   : null,
        /**new vars**/
       
        showAnimation   : {
	        type    : 'popIn',
	        duration: 100,
	        easing  : 'ease-in'
	    },
	    hideAnimation   :{
	    	type    : 'popOut',
	        duration: 100,
	        easing  : 'ease-out'
	    },	    
    },
    initialize : function(){
        var me = this;
        me.callParent();
        me.add([
            {
                xtype   : 'button',
                text    : 'Home',
                ui      : 'normal',
                html    : '<img src="resources/images/playBtn.png" width="60" height="60" />',
                flex    : 3,
                cls     : 'white pop-left',
                handler: this.onOverlayPlayBtnTap,
                scope: this,
            },
            {
                html : this.config.introText,
                cls     : 'white pop-right',
                flex    : 7,
            }   
        ])       
    },
    onOverlayPlayBtnTap: function(list, record, target, index, evt, options) {
        var me = this;
        me.hide();
   		//this.fireEvent("playVideoCommand", this, record);
   		var video = Ext.create('NTV.view.ViewVideo', {
            videoId          : me.config.videoId,
            videoDescription : me.config.videoDescription,  
            videoTitle       : me.config.videoTitle,
            introText        : me.config.introText,
            videoReviews     : me.config.videoReviews,
            videoThumbnail   : me.config.videoThumbnail,
        });
        Ext.Viewport.animateActiveItem(video, me.slideLeftTransition);
   		me.destroy(); 
    },
    slideLeftTransition: { 
        type: 'slide', direction: 'left' 
    },
    getIntroText: function(){
        return this.getIntroText();
    },
});