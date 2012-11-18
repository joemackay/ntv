Ext.define('Notes.view.NtvTitleBar', {
    extend: 'Ext.TitleBar',
    xtype: 'ntvtitlebar',
    config: {
        docked: 'top',
        cls: 'topToolbar',
        id: 'ntvtitlebar',
        defaults: {
            xtype: 'button'
        },
        items: [
            {
                itemId: 'homeButton',
                hidden: true,
                iconMask: true,
                iconCls: 'home',
                action: 'home',
                align: 'left',
                text: 'home',
            },
            {
                itemId: 'latest',
                hidden: true,
                iconMask: true,
                iconCls: 'latest',
                action: 'latest',
                align: 'left',
                text: 'latest',
            },
            {
                itemId: 'playlist',
                hidden: true,
                iconMask: true,
                iconCls: 'playlist',
                action: 'playlist',
                align: 'left',
                text: 'playlist',
            },
            {
                itemId: 'previous',
                hidden: true,
                iconMask: true,
                iconCls: 'previous',
                action: 'previous',
                align: 'left',
                text: 'prev',
            },
            {
                itemId: 'playvideo',
                hidden: true,
                iconMask: true,
                iconCls: 'playvideo',
                action: 'playvideo',
                align: 'left',
                text: 'play',
            },
            {
                itemId: 'description',
                hidden: true,
                iconMask: true,
                iconCls: 'description',
                action: 'description',
                align: 'left',
                text: 'desc',
            },
            {
                itemId: 'reviews',
                hidden: true,
                iconMask: true,
                iconCls: 'reviews',
                action: 'reviews',
                align: 'left',
                text: 'reviews',
            },
            {
                itemId: 'next',
                hidden: true,
                iconMask: true,
                iconCls: 'next',
                action: 'next',
                align: 'left',
                text: 'next',
            },
            {
                itemId: 'back',
                hidden: true,
                iconMask: true,
                iconCls: 'back',
                action: 'back',
                align: 'right',
                text: 'back',
            },
            {
                itemId: 'search',
                hidden: true,
                iconMask: true,
                iconCls: 'search',
                action: 'search',
                align: 'right',
                text: 'search',
            },
            {
                itemId: 'exit',
                hidden: true,
                iconMask: true,
                iconCls: 'exit',
                action: 'exit',
                align: 'right',
                text: 'exit',
            }
        ]
    }
});