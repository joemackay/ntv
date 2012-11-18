Ext.define("NTV.view.PlayListChildren", {
    extend  : 'Ext.Panel',
    requires: 'Ext.dataview.List',
    alias   : "widget.playlistchildren",
    xtype   : 'playlistchildren',
    config  : {
        playListId : null,
    },
    initialize: function () {
        this.callParent(arguments);
        //alert(this.config.playListId);
        var toolbar = {
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
        };
        var videos = {
            xtype      : "list",
            store      : Ext.getStore("PlaylistChildrenStore"),
            itemId     : "playListVideos",
            loadingText: 'Loading Videos...',
            emptyText  : '</pre><div class="notes-list-empty-text">No Videos found.</div><pre>',
            cls        : 'NTVHome',
            itemTpl    : '<img src="{thumbnail}" width="80" />', //resources/images/
        }
        this.add([ videos ])
    },
    onVideoListDisclose: function (list, record, target, index, evt, options) {
        console.log("showOverlayCommand");
        this.fireEvent('showOverlayCommand', this, record);
    },
    onNewButtonTap: function () {
        console.log('newNoteCommand');
        this.fireEvent("newNoteCommand", this);
    },
});