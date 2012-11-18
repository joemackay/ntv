Ext.define("NTV.view.OverlayMenu", {
    extend: "Ext.Panel",
    alias: "widget.overlaymenupanel",
    xtype:'overlaymenupanel',
    config:{
        xtype           : 'panel',
        padding         : 20,
        centered        : true,
        width           : Ext.os.deviceType=='phone'? 200 : 200,// 400,
        height          : Ext.os.deviceType=='phone'? 250 : 250, //400,
        modal           : true,
        hideMode        : 'close',
        hideOnMaskTap   : true,
        styleHtmlContent: true,
        layout          : 'vbox',
        showAnimation   : {
            type: 'popIn',
            duration: 100,
            easing: 'ease-out'
        },
        hideAnimation   :{
            type: 'popOut',
            duration: 100,
            easing: 'ease-out'
        },
        cls: 'menuSettingsPopup',
        defaults         : {
            xtype   : 'button', 
            iconMask: true,
        },            	    
    },
    initialize : function(){
        this.callParent(arguments);      
        var about = { 
            text    : 'About',
            cls     : 'btnSettingsMenu',
            iconCls : 'info2',
            action  : 'about',
            handler : this.onAboutButton,
            scope   : this,
        }
        var register = {
            text    : 'Register',
            cls     : 'btnSettingsMenu',
            iconCls : 'user_add',
            action  : 'register',
            handler : this.onRegisterButton,
            scope   : this,
        }
        var login = {
            text    : 'Login',
            cls     : 'btnSettingsMenu',
            iconCls : 'user',
            action  : 'login',
            handler : this.onLoginButton,
            scope   : this,
        }
        var exit = {
            text    : 'Exit',
            cls     : 'btnSettingsMenu',
            iconCls : 'pictos_close',
            action  : 'exit',
            handler : this.onExitButton,
            scope   : this,
        }
        var back = {
            text    : 'Back',
            cls     : 'btnSettingsMenu',
            iconCls : 'pictos_back',
            action  : 'back',
            handler : this.onBackButton,
            scope   : this,
        }
        this.add([about, register, login, exit, back]);  
    },
    onAboutButton : function () {
        console.log('showAboutCommand');
        this.hide();
        this.fireEvent('showAboutCommand');
        this.destroy();
    },
    onRegisterButton : function () {
        this.hide();
        this.fireEvent('registerButtonCommand');
        this.destroy();
    },
    onLoginButton : function () {
        this.hide();
        this.fireEvent('loginButtonCommand');
        this.destroy();
    },
    onExitButton : function () {
        this.hide();
        this.fireEvent('ExitButtonCommand');
        this.destroy();
    },
    onBackButton : function () {
        this.hide();
        this.destroy();
    },
    
});