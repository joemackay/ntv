Ext.define('HelpDesk.controller.GlobalActionMenu', {
    extend: 'Ext.app.Controller',
    requires: ['Ext.Ajax'],
    config: {
        refs: {
            globalTitlebar: 'globaltitlebar',
            globalActionChooserButton: 'button[action=globalActionMenu]',
            GlobalActionBubble: 'globalactionmenu',
            GlobalActionList: 'globalactionmenu list'
        },
        control: {
            globalActionChooserButton: {
                tap: 'showGlobalActionMenu'
            },
            GlobalActionList: {
                select: 'followMenuURL'
            }
        }
    },
    
    showGlobalActionMenu: function(button) {
        this.getGlobalActionBubble().showBy(button)
    },
    
    followMenuURL: function(dataview, record) {
        if(record.data.name == 'Sign out') {
            Ext.Ajax.request({
                url: 'scripts/logout.php',
                success: function(response) {
                    if(response.responseText == "TRUE") {
                        //User has been logged out, return to sign in page
                        Ext.Viewport.setActiveItem({
                            xclass: 'HelpDesk.view.Auth'
                        });
                    } else {
                        //An error occured
                        Ext.Msg.alert('Error', response.responseText+'| An error occured while logging you out. Please try again.');
                    }
                },
                
                failure: function(reponse) {
                    Ext.Msg.alert('Error', "A critical error has occured. Please contact the Help Desk.");
                }
            });
        } else {
            window.open = record.data.href;
        }
    }
});