Ext.define("NTV.view.Register", {
    extend  : 'Ext.form.Panel',
    requires: ['Ext.field.Hidden', 'Ext.form.FieldSet', 'Ext.field.Password' ,'Ext.field.Email'],
    xtype   : 'registerpage',
    alias   : "widget.registerpage",
    config:{
        scrollable       :'vertical',        
        layout           : 'vbox',               
    },
    initialize : function(){
        var me = this;
        this.callParent(arguments);
        var topToolBar = {
            xtype: 'toolbar',
            docked: 'top',
            title: 'Register',
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
        var altLogin = {
            xtype   : 'panel',
            cls : 'Ntv-login-fb',
            html    : '<div><a class="btn_fb" href="">Connect with Facebook</a></div>' 
        };
        var fields = {
            xtype   : 'fieldset',
            title   : '',
            cls : 'Ntv-login-form',
            items: [
                {
                    xtype   : 'emailfield',
                    label   : 'Email',
                    name    : 'email',
                    required: true
                },
                {
                    xtype   : 'passwordfield',
                    label   : 'Password',
                    name    : 'password',
                    required: true
                },
                {
                    xtype   : 'passwordfield',
                    label   : 'Verify Password',
                    name    : 'password_conf',
                    required: true
                }
            ]
        };
        var submitBtn = {
            xtype   : 'button',
            text    : 'Submit',
            cls     : 'btn_default',
            width   : '87%',
            handler : this.onSubmitButtonTap,
            scope   : this,
        }
        this.add([topToolBar, altLogin, fields, submitBtn]);      
    },
    
    /**submit a user form**/
    onSubmitButtonTap: function() {
        this.fireEvent("saveUserCommand", this);        
    },
    /**Go back home**/
    onBackButtonTap: function() {
        this.fireEvent("backToHomeCommand");
    }
});