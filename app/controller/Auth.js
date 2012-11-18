Ext.define('Notes.controller.Auth', {
    extend: 'Ext.app.Controller',
    requires: ['Ext.Ajax'],
    config: {
        refs: {
            authPanel: 'authview',
            signinForm: '#signinform',
            loginPage   : 'loginpage',
            signinButton: 'button[action=SignIn]',
        },
        control: {
            signinButton: {
                tap: 'signInUser'
            }
        }
    },
    promptForLogin: function(){
        
    },
    signInUser: function() {
        var values = this.getAuthPanel().getValues();
        if(values.ulid && values.password) {
            this.getAuthPanel().submit({
                url: 'scripts/login.php',
                method: 'POST',
                params: {
                    rememberme: Ext.get("rememberme").dom.checked
                },
                success: function(form, result) {
                    Ext.Viewport.setActiveItem({
                        xclass: 'HelpDesk.view.Main'
                    });
                },
                failure: function(form, result) {
                    Ext.Msg.alert('Error', result);
                }
            });
        } else {
            Ext.Msg.alert('Error', 'Please provide your ULID and password.');
        }
    }
});