Ext.define("NTV.view.PlayList", {
    extend: 'Ext.dataview.List',
    alias   : "widget.playlist",
    xtype   : 'playlist',
    config  : {
        layout :{
            type: 'fit'
        },
        items: [
            {
	            xtype: "toolbar",
	            docked: "top",
	            items: [
	                {
    	                xtype: "spacer"
    	            }, 
    	            {
    	                xtype: "button",
    	                text: "Playlists",
    	                ui: "action",
    	                itemId:"playlistBtn"
    	            },
    	            {
                        xtype: "button",
                        text: "My Videos",
                        ui: "action",
                        itemId:"myVideosBtn"
                    },
                    {
                        xtype: "spacer"
                    },
                    {
                        xtype: "button",
                        iconCls: 'search',
                        iconMask: true,
                        ui: "action",
                        itemId:"mainSrchBtn"
                    },
                    {
                        xtype: "button",
                        iconCls: 'delete',
                        iconMask: true,
                        ui: "action",
                        itemId:"mainCloseBtn"
                    },
                     
                    
        	    ]
	        }, 
	        {
	            xtype      : "list",
	            store      : "ListVideosStore",
	            itemId     : "videosList",
	            loadingText: 'Loading Videos...',
                emptyText  : '</pre><div class="notes-list-empty-text">No Videos found.</div><pre>',
                cls        : 'NTVHome',
                itemTpl    : '<img src="{thumbnail}" width="80" />', //resources/images/
	        }
        ], //items
        listeners: [
            {
                delegate    : "#mainSrchBtn",
                event       : "tap",
                fn          : "onNewButtonTap",
            },
        ]
    },
    /**onVideoListDisclose: function (list, record, target, index, evt, options) {
        console.log("showOverlayCommand");
        this.fireEvent('showOverlayCommand', this, record);
    },
    onNewButtonTap: function () {
        console.log('newNoteCommand');
        this.fireEvent("newNoteCommand", this);
    },
    */
    
    
});