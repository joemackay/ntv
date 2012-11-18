Ext.define("NTV.view.VideoList", {
    extend: 'Ext.dataview.List',
    requires: ['Ext.Panel'],
    alias   : "widget.videolist",
    xtype   : 'pvideolist',
    config  : {
        title      : 'Playlist',
        itemId     : "pVideosList",
        myStore    : null,
        pTitle     : null,
        itemCls    : 'list-grid',
        loadingText: 'Loading Videos...',
        emptyText  : '<div class="videos-list-empty-text">No Videos found.</div>',
        cls        : 'video-list',
        plugins: [
            {
                type: 'pullrefresh',
                pullRefreshText: 'Pull down to refresh Videos!'
            }, 
            {
                type: 'listpaging',
                autoPaging: true,
                loadMoreText: ''
            }
        ], 
        itemTpl    : '<img src="{thumbnail}" width="90" />',
        listeners  : {
            itemtap: { fn: this.onDisplayVideo, scope: this }
        },
    },
    initialize: function () {
        this.callParent(arguments);
        var topToolBar = {
            xtype: 'titlebar',
            docked: 'top',
            cls : 'toolbar-font',
            title: this.config.pTitle,
            items: [{ xtype: 'spacer' }]        
        };  
        this.add([topToolBar]);
    },
    onDisplayVideo: function(){
        this.fireEvent('ShowOverlayCommand');
    },       
});