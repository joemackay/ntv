Ext.define('Notes.view.GlobalActionMenu', {
    extend: 'Ext.Panel',
    xtype: 'globalactionmenu',
    requires: ['Ext.dataview.List','Ext.Ajax'],
            
    config: {
        modal: true,
        hidden: true,
        hideOnMaskTap: true,
        right: 0,
        top: 0,
        width: 220,
        height: 105,
        layout: 'fit',
        items: [
            {
                xtype: 'list',
                store: 'GlobalMenu',
                itemTpl: Ext.create('Ext.XTemplate',
                    '<tpl if="this.signoutlink == true">',  //This is where I finally need the variable to complete my logic
                        '{name}',
                    '<tpl else>',
                        '<tpl if="authreq != \'true\'">',
                            '{name}',
                        '</tpl>',
                    '</tpl>'
                )
            }
        ]
    }
});