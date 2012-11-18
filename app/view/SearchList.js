Ext.define("NTV.view.SearchList", {
    extend  : 'Ext.dataview.List',
    requires: ['Ext.Panel'],
    alias   : "widget.searchlist",
    xtype   : 'searchlist',
    config  : {
        playListId : null,
        itemId     : "searchResults",
        loadingText: 'Loading Videos...',
        itemCls    : 'search-list',
        emptyText  : '</pre><div class="videos-list-empty-text">No Videos found, please check your connection and try again.</div><pre>',
        itemTpl    : '<img src="{thumbnail}" width="80" /> <h5>{title}</h5> <span>{views} views</span> &nbsp; &nbsp; <span class="len">{duration}</span> ', //resources/images/
        listeners  : {
            itemtap: { fn: this.onDisplayVideo, scope: this }
        },        
    },
    initialize: function () {
        this.callParent(arguments);
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