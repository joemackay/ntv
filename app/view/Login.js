Ext.define("NTV.view.Login", {
    extend: 'Ext.form.Panel',
    requires: ['Ext.field.Hidden', 'Ext.form.FieldSet', 'Ext.field.Password' ,'Ext.field.Email'],
    xtype : 'loginpage',
    alias : "widget.loginpage",
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
            title: 'Login',
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
        var txtEmail = {
            xtype: 'emailfield',
            name: 'email',
            label: 'Email',
            required: true
        };
        var txtPassword = {
            xtype: 'passwordfield',
            name: 'password',
            label: 'Password',
            required: true
        };
        var fields = {
            xtype   : 'fieldset',
            title   : '',
            cls : 'Ntv-login-form',
            items: [
                {
                    xtype: 'emailfield',
                    label: 'Email',
                    name: 'email',
                    required: true
                },
                {
                    xtype: 'passwordfield',
                    label: 'Password',
                    name: 'password',
                    required: true
                }
            ]
        };
        var submitBtn = {
            xtype   : 'button',
            text    : 'Login',
            cls     : 'btn_default',
            width   : '87%',
            handler : this.onLoginButtonTap,
            scope   : this,
        }
        this.add([topToolBar, altLogin, fields, submitBtn]);      
    },
    
    /**submit a user form**/
    onLoginButtonTap: function() {
        this.fireEvent("LoginUserCommand", this);        
    },
    /**Go back home**/
    onBackButtonTap: function() {
        this.fireEvent("backToHomeCommand");
    }
});