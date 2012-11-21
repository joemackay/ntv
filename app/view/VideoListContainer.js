Ext.define("NTV.view.VideoListContainer", {
    extend  : 'Ext.Container',
    alias   : "widget.videolistcontainer",
    xtype   : 'videolistcontainer',
    requires: ['Ext.SegmentedButton', 'Ext.Img', 'Ext.plugin.PullRefresh', 'Ext.plugin.ListPaging', 'Ext.TitleBar'],
    config: {
        layout  : {
            type: 'card',
            
        },
        baseCls : 'NtvBg',
        cls     : 'main-cont',
    },
    initialize: function () {

        this.callParent(arguments);
        Ext.Viewport.on('orientationchange', 'handleOrientationChange', this, {buffer: 50 });
        var logoButton = Ext.create('Ext.Img', {
            src     : 'resources/images/logo.png',
            height  : 32,
            width   : 32,
            margin  : 10,
        });
        var searchButton = {
            xtype   : "button",
            iconCls : 'pictos_search',
            iconMask: true,
            ui      : 'plain',
            itemId  :"mainSrchBtn",
            //cls     : 'btnx',
            handler : this.onSearchButtonTap,
            scope   : this,
        };
        var closeBtn = {
            xtype   : "button",
            iconCls : 'pictos_close',
            iconMask: true,
            ui      : "plain",
            itemId  :"mainCloseBtn",
            //cls     : 'btnx',
        };
            
        var topToolBar = {
        	xtype  : 'toolbar',
        	docked : 'top',
        	items  : [
            	   {
                    xtype  : 'toolbar',
                    docked : 'top',
                    //items  : [logoButton, { xtype: 'spacer' }, latestVideosButton, playlistButton, { xtype: 'spacer' }, searchButton, closeBtn],
                    items  : [
                       Ext.create('Ext.Img', {
                            src     : 'resources/images/logo.png',
                            height  : 32,
                            width   : 32,
                            margin  : 10,
                        }),
                       { 
                           xtype: 'spacer' 
                       }, 
                       {
                           xtype:  'segmentedbutton',
                           allowDepress:   false,
                           pressedCls : 'btn-pressed',
                           items: [
                               {
                                   text: 'Latest',
                                   pressed: true,
                                   handler : this.onLatestButtonTap,
                                   scope   : this,
                               },
                               {
                                   text: 'Playlists',
                                   handler : this.onPlaylistButtonTap,
                                   scope   : this,
                               },
                           ],                       
                       },
                       { 
                           xtype: 'spacer' 
                       }, 
                       {
                            xtype   : 'button',
                            iconMask: true,
                            iconCls : 'search2',
                            cls     : 'btnx',
                            ui      : 'plain',
                            itemId  : "mainSrchBtn",
                            handler : this.onSearchButtonTap,
                            scope   : this,
                        }, 
                        {
                            xtype   : 'button',
                            iconMask: true,
                            cls     : 'btnx',
                            xtype   : "button",
                            iconCls : 'pictos_close',
                            ui      : 'plain',
                            itemId  : "mainCloseBtn",
                        },
                    ],
                }
        	]
        };
        var bottomToolBar = {
            xtype: 'toolbar',
            docked: "bottom",
            //layout: { pack: 'right' },
            items : [
                { 
                    xtype: 'spacer' 
                },
                {
                    xtype   : 'button',
                    iconMask: true,
                    //id      : refreshList,
                    cls     : 'btnx greyed',
                    iconCls : 'refresh2',
                    handler : this.onRefreshButtonTap,
                    scope: this
                },
                { 
                    xtype: 'spacer' 
                },
                {
                    xtype   : 'button',
                    iconMask: true,
                    cls     : 'btnx greyed',
                    iconCls : 'list',
                    handler : this.onMenuButtonTap,
                    scope: this
                },                
            ]                
        };
        var playList = {
            xtype      : "list",
            store      : Ext.getStore("PlaylistStore"),
            itemId     : "playList",
            itemCls    : 'playlist-list',
            loadingText: 'Loading Playlists...',
            emptyText  : '<div class="videos-list-empty-text">No Playlists found, please check your connection and try again.</div>',
            itemTpl    : '<img src="{thumbnail}" width="80" /> <h4>{title}</h4><span>{size} videos </span>', //resources/images/
            
        };        
        var latestList = {
            xtype      : "list",
            store      : Ext.getStore("ListVideosStore"),
            itemId     : "videosList",
            itemCls    : 'list-grid',
            loadingText: 'Loading Videos...',
            emptyText  : '<div class="videos-list-empty-text">No Videos found, please check your connection and try again.</div>',
            itemTpl    : '<img src="{thumbnail}" width="90" />', //resources/images/
            listeners  : {
                //itemtap: { fn: this.onVideoListDisclose, scope: this }
            },
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
        };        
        this.add([topToolBar, latestList, playList, bottomToolBar]);
    },
    onLatestButtonTap: function () {
        this.setActiveItem(0,'fade');
    },
    onPlaylistButtonTap: function () {
        this.setActiveItem(1,'fade');
    },
    onSearchButtonTap: function () {
        this.fireEvent('searchCommand');
    },
    onRefreshButtonTap: function () {
        this.fireEvent('onRefreshCommand');
    },
    onMenuButtonTap: function () {
        this.fireEvent('onMenuCommand');
    },
    
});