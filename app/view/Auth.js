Ext.define("Notes.view.Auth", {
   extend: 'Ext.form.Panel',
       xtype: 'authview',
       requires: ['HelpDesk.view.GlobalTitlebar', 'Ext.form.FieldSet', 'Ext.field.Password'],
       config: {
             scrollable: {
                   direction: 'vertical'
             },
             items: [
                   {
                         xtype: 'globaltitlebar',
                         signoutlink: true  //This is the variable I'm trying to pass
                   }
             ]
       }
});