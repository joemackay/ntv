Ext.define('NTV.view.SearchBar', {
    extend: 'Ext.Container',
    xtype : 'searchbar',
    alias   : "widget.searchbar",
    requires: ['Ext.SegmentedButton', 'Ext.Img', 'Ext.plugin.PullRefresh', 'Ext.field.Search'],
    config: {
        layout: {
            type: 'card'
        },
    },
    initialize: function() {
        this.callParent(arguments);
        var searchField = {
            xtype: 'fieldset',
            items: [
                {
                    xtype: 'searchfield',
                    name: 'query'
                }
            ]
        };
        var topToolBar = {
            xtype: 'toolbar',
            docked: 'top',
            title: 'Search Videos',
            cls : 'toolbar-font',
            items: [ 
                {
                    xtype   : 'button',
                    iconMask: true,
                    iconCls : 'pictos_back',
                    ui      : 'plain',
                    handler : this.onBackButtonTap,
                    scope   : this,
                },
            ]        
        };
        var sToolBar = {  
            xtype:'toolbar',                                       //  bottom toolbar  
            docked:'top',  
            items:[
                {  
                    xtype   : 'textfield',                          //  here is the searchfield  
                    itemId  :'video_search',  
                    id      :'video_search_field',                         //   we will be using this id in the controller  
                    placeHolder: 'Search NTV Videos',
                    name    : 'query'  
                },
                {
                    xtype   : 'button',
                    iconMask: true,
                    ui      : 'normal',
                    cls     : 'searchBtn',
                    iconCls : 'pictos_search_btn',
                    handler : this.onGoButtonTap,
                    scope   : this,
                }
            ],
        };
        this.add([topToolBar, sToolBar]);
    },
    onBackButtonTap:  function() {
        this.fireEvent("backToHomeCommand");       
    },
    onGoButtonTap : function() {
        console.log('goCommand');
        this.fireEvent("goCommand");   
        
    },
});
//http://www.youtube.com/embed?listType=search&list=QUERY