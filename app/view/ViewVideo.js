Ext.define("NTV.view.ViewVideo", {
    extend  : 'Ext.Panel',
    alias   : "widget.viewvideo",
    xtype   :'viewvideo',
    requires: ['Ext.SegmentedButton', 'Ext.Img', 'Ext.plugin.PullRefresh', 'Ext.TitleBar', 'Ext.dataview.NestedList'],
    config:{
        scrollable       :'vertical',        
        videoId          : null,
        videoTitle       : null,
        introText        : null,
        videoDescription : null,
        videoReviews     : null,
        videoThumbnail   : null, 
        videoUrl         : null,       
        layout           : 'card',  
        baseCls          : 'NtvBg',
    },
    initialize : function(){
        var me = this;
        
        // Add a Listener. Listen for [Viewport ~ Orientation] Change.
        this.config.videoUrl = (Ext.Viewport.getOrientation()== 'landscape') ? '<div id="video1"><iframe width="480" height="320" src="http://www.youtube.com/embed/'+ this.config.videoId +'?modestbranding=1&amp;fs=1&amp;hl=en_US&amp;rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe></div>' : '<div id="wrong-orientation">Please rotate your phone to view this video</div>';
        
        Ext.Viewport.on('orientationchange', 'handleOrientationChange', this, {buffer: 50 });
        this.callParent(arguments);
        var logoButton = Ext.create('Ext.Img', {
            src     : 'resources/images/logo.png',
            height  : 32,
            width   : 32,
            margin  : 10,
        });
        var backBtn = {
            iconMask: true,
            iconCls : 'pictos_back',
            cls     : 'btnx greyed',
            itemId  : "homeBtn",
            ui      : 'plain',
            handler: this.onBackButtonTap,
            scope: this,
        };
        var prevBtn = {
            itemId  : "previousBtn",
            iconMask: true,
            iconCls : 'pictos_previous',
            cls     : 'btnx greyed',
            ui      : 'plain',
            handler: this.onPreviousVideoButtonTap,
            scope: this,
        };
        var segmentedBtns = {
            xtype:  'segmentedbutton',
            allowDepress:   false,
            pressedCls : 'btn-pressed',
            items: [
               {
                   text: 'Play',
                   pressed: true,
                   handler : this.onplayButtonTap,
                   scope   : this,
               },
               {
                   text: 'Description',
                   handler : this.ondescButtonTap,
                   scope   : this,
               },
               {
                   text: 'Reviews',
                   handler : this.onRevsButtonTap,
                   scope   : this,
               },
            ],                       
        };
        var nextBtn = {
            iconMask: true,
            iconCls : 'pictos_next',
            cls     : 'btnx greyed',
            itemId  : "nextBtn",
            ui      : 'plain',
            handler: this.onNextVideoButtonTap,
            scope: this,
        };
        var searchBtn = {
            iconMask: true,
            iconCls : 'pictos_search',
            itemId  : "searchBtn",
            ui      : 'plain',
            handler: this.onSearchButtonTap,
            scope: this,
        };
        var space = { xtype: 'spacer' };
        var topToolbar = {
            xtype: 'toolbar',
            docked: 'top',
            defaults: {
                xtype: 'button'
            },
            items: [logoButton, space, prevBtn, space, segmentedBtns, space, nextBtn, space, backBtn,],            
        };
        var bottomToolBarDesc = {
            xtype: 'toolbar',
            docked: "bottom",
            items : [
                { 
                    xtype: 'spacer' 
                },{
                    xtype   : 'button',
                    iconMask: true,
                    text    : 'Share',
                    cls     : 'btn_default',
                    handler : this.onShareButtonTap,
                    scope: this
                },
                { 
                    xtype: 'spacer' 
                },
            ]                
        }
        var bottomToolBarRev = {
            xtype: 'toolbar',
            docked: "bottom",
            items : [
                { 
                    xtype: 'spacer' 
                },
                {
                    xtype   : 'button',
                    iconMask: true,
                    text    : 'Add Comment',
                    cls     : 'btn_default',
                    handler : this.onNewReviewsButtonTap,
                    scope: this
                },
                { 
                    xtype: 'spacer' 
                },
            ]                
        }
        var player = {
            xtype       : 'panel',
            id          : 'playerCont',
            title       : 'Play',
            cls         : 'player-cont',
            fullscreen  : true,
            itemId      : "playvideoId",
            html        :[this.config.videoUrl].join("")
        };
        var desc = {
            xtype       : 'panel',
            title       : 'Description',
            id      : "descvideoId",
            scrollable  : true,
            styleHtmlContent: true, 
            html:[
                this.config.videoDescription,
            ].join(""),
            items: [bottomToolBarDesc],
        };
        var reviews = {
            xtype: 'nestedlist',
            title: this.config.videoTitle,
            cls : 'reviews-font',
            displayField: 'intro',
            
            store: {
                type: 'tree',
                fields: [
                    'video_id', 'username', 'intro', 'comments', 'date_made',
                    {name: 'leaf', defaultValue: true}
                ],
            
                root: {
                    leaf: false
                },
                
                proxy: {
                    type: 'jsonp',
                    url: 'http://localhost/Notes/php/readReviews.php?videoid=' + this.config.videoId,
                    reader: {
                        type: 'json',
                        rootProperty: 'reviews'
                        }
                    }
            },
             
            /*store: {
                type: 'tree',

                fields: [
                    'title', 'link', 'author', 'contentSnippet', 'content',
                    {name: 'leaf', defaultValue: true}
                ],

                root: {
                    leaf: false
                },

                proxy: {
                    type: 'jsonp',
                    url: 'https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=http://feeds.feedburner.com/SenchaBlog',
                    reader: {
                        type: 'json',
                        rootProperty: 'responseData.feed.entries'
                    }
                }
            },*/

            detailCard: {
                xtype: 'panel',
                scrollable: true,
                styleHtmlContent: true,
                itemCls    : 'playlist-list',
                loadingText: 'Loading Comments...',
                emptyText  : '<div class="videos-list-empty-text">No Comments found, please check your connection and try again.</div>',
                itemTpl    : '{comments}<div class="meta_date">{username} - {date_made}</div>', //resources/images/
            },
            listeners: {
                itemtap: function(nestedList, list, index, element, post) {
                    this.getDetailCard().setHtml(post.get('comments'));
                }
            },
            items: [bottomToolBarRev],
        }
        this.add([topToolbar, player, desc, reviews]);      
    },
    
    handleOrientationChange : function() {
        var qr = Ext.getCmp('playerCont'), video;
        var me = this,
            orientation = Ext.Viewport.getOrientation();
        if(orientation == "landscape") {
            video = '<div id="video1"><iframe width="480" height="320" src="http://www.youtube.com/embed/'+ this.config.videoId +'?modestbranding=1&amp;fs=1&amp;hl=en_US&amp;rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe></div>';
        } else if(orientation == "portrait"){
            video = '<div id="wrong-orientation">Please rotate your phone to view this video</div>';  
        }
        //return video; 
        qr.setHtml(video);        
    },
    onplayButtonTap: function() {
        this.setActiveItem(0,'fade'); 
    },
    /**show description panel tab**/
    ondescButtonTap: function() {
        //var myPlayer = document.getElementById('playerid'); 
        //myPlayer.pauseVideo();
        this.setActiveItem(1,'fade');
    },
    /**show reviews panel tab**/
    onRevsButtonTap: function() {
        this.setActiveItem(2,'fade');
    },
    /**submit a review**/
    onSaveButtonTap: function() {
        this.fireEvent("saveNoteCommand", this);
    },
    /**Go back home**/
    onBackButtonTap: function() {
        this.fireEvent("backToHomeCommand");
    },
    /**Go to previous Video **/
    onPreviousVideoButtonTap: function() {
        this.fireEvent("selectPrevious", this);
    },
    /**Go to Next Video **/
    onNextVideoButtonTap: function() {
        this.fireEvent("selectNext");
    },
    /**Go to search page**/
    onSearchButtonTap: function () {
        console.log('searchCommand');
        this.fireEvent('searchCommand');
    },
    
    /****load comment box***/
    onNewReviewsButtonTap: function(me, index, target, record, e, eOpts) {
        var me = this;
        var now       = new Date();
        var ids       = (now.getTime()).toString() + (this.getRandomInt(0,100)).toString();
        var videoId   = me.config.videoId;
        var newReview = Ext.create("NTV.model.Review", {
            id          : ids,
            videoid     : videoId,
            comments    : "",
        });
        //console.log(videoId);
        var popup = Ext.create('NTV.view.OverlayComment', {
            videoThumbnail   : me.config.videoThumbnail,
            videoTitle       : me.config.videoTitle,
            videoId          : me.config.videoId,
        });
        Ext.Viewport.add([popup]);
        popup.show(); 
    },
    onShareButtonTap: function (Me, index, target, record, e, eOpts) {
        var me = this;
        var popup = Ext.create('NTV.view.OverlaySocial', {
            videoId          : me.config.videoId,
            videoTitle       : me.config.videoTitle,
        });
        Ext.Viewport.add([popup]);
        popup.show(); 
    },
    getRandomInt: function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },
      
});