Ext.define('Notes.view.GlobalTitlebar', {
       extend: 'Ext.TitleBar',
       xtype: 'globaltitlebar',
    
       config: {
             docked: 'top',
             title: '<div id="hdlogo'+(!Ext.os.is.Phone ? '-tablet' : '')+'"></div>',
             items: [
                   {
                         html: 'Test '+this.signoutlink  //Just trying to get the value to show up here
                   },
                   {
                         iconCls: 'settings',
                         iconMask: true,
                         align: 'right',
                         action: 'globalActionMenu'  //Will need to pass the variable again to the action
                   }
             ]
       }
});