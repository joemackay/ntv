Ext.define('NTV.view.About', {
    extend  : 'Ext.Container',
    xtype   : 'aboutpage',
    alias   : "widget.aboutpage",
    requires: ['Ext.SegmentedButton'],
    config  : {
        layout  : {
            type: 'vbox'
        },
        baseCls : 'NtvBg',
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
            title: 'About',
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
        var about = {
            xtype: 'panel',
            cls : 'aboutPage',
            styleHtmlContent : true,
            html : "Official NTV Videos App:<br><ul>" + "<li>Version: 1.0</li>" + "<li>NTV Videos Official App. Catch the latest stories from our videos Channel</li>" + '<li> <img src="resources/images/logo.png" height="40" /> Briteskills Tech.</li></ul>',
            flex: 1,
        };        
        
        this.add([topToolBar, about]);
    },
    onBackButtonTap:  function() {
        this.fireEvent("backToHomeCommand");       
    },
    onFeedbackButtonTap : function() {
        console.log('feedCommand');
        this.fireEvent("feedCommand");        
    },
});
//http://www.youtube.com/embed?listType=search&list=QUERY