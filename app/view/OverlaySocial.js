Ext.define("NTV.view.OverlaySocial", {
    extend: "Ext.Panel",
    alias: "widget.overlaysocialpanel",
    xtype:'overlaysocialpanel',
    config:{
        xtype           : 'panel',
        padding         : 40,
        centered        : true,
        //bottom          :0,
        width           : Ext.os.deviceType=='phone'? '60%' : '80%',// 400,
        height          : Ext.os.deviceType=='phone'? 200 : 200, //400,
        modal           : true,
        hideMode        : 'close',
        hideOnMaskTap   : true,
        styleHtmlContent: true,
        layout          : 'vbox',
        showAnimation   : {
            type: 'popIn',
            duration: 100,
            easing: 'ease-out'
        },
        hideAnimation   :{
            type: 'popOut',
            duration: 100,
            easing: 'ease-out'
        },
        videoId          : null,
        videoTitle       : null,
        introText        : null,
        cls: 'popup',
            	    
    },
    initialize : function(){
        this.callParent(arguments);
        console.log(this.config.videoTitle);
        this.add([
            {
                flex    : 1,
                html    :'<div><a class="btn_tw" href="http://twitter.com/home?status=NTV Videos: '+ this.config.videoTitle +': http://www.youtube.com/embed/'+ this.config.videoId +'?fs=1&amp;hl=en_US&amp;rel=0&autoplay=0" class="twitter-share-button" data-url="" data-lang="en">Share on Twitter</a></div>'
            },
            {
                flex    : 1,
                //html:'<div class="fb-like" data-href="http://test.com" data-send="false" data-layout="button_count" data-width="4" data-show-faces="false">Facebook</div>'
                html    : '<div><a class="btn_fb" href="http://www.facebook.com/sharer.php?u=http://www.youtube.com/embed/'+ this.config.videoId +'?fs=1">Share on Facebook</a></div>',
            }    
        ]);
    },
    onOverlayPlayBtnTap: function() {
   		console.log("playVideoCommand");
   		this.fireEvent("playVideoCommand");
    }
});