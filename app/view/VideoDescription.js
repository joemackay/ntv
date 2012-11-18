Ext.define("NTV.view.VideoDescription", {
    extend: 'Ext.Panel',
    xtype:'videodesc',
	
	config: {
	    title: 'Description',
        iconCls: 'home',
        scrollable: true,
        styleHtmlContent: true,	
        html:[
            this.html,
            //this.record('')             
        ].join("")
    },
    /**initialize: function () {

        this.callParent(arguments);
        var backButton = {
            xtype: 'button',
            text: 'Home',
            ui: 'back',
            handler: this.onBackButtonTap,
            scope: this,    
        };

        var playButton = {
            xtype: 'button',
            text: 'Play',
            ui: 'action',
            handler: this.onPlayButtonTap,
            scope: this,    
        };

        var detailsButton = {
            xtype: 'button',
            text: 'Description',
            ui: 'action',
            handler: this.onDetailsButtonTap,
            scope: this,    
        };

        var reviewsButton = {
            xtype: 'button',
            text: 'Reviews',
            ui: 'action',
            handler: this.onReviewsButtonTap,
            scope: this,    
        };

        var saveButton  = {
            xtype: 'button',
            text: 'Save',
            ui: 'action',
            handler: this.onSaveButtonTap,
            scope: this,    
        };
        var theDescription = {
            xtype: 'panel',
            html:[
                //'<h1>This is a description of the video/h1>'
                this.record('')             
            ].join("")
        };
        var topToolbar = {
            xtype: 'toolbar',
            docked: "top",
            items : [
                backButton, playButton, detailsButton, reviewsButton, saveButton
            ]   
        };

        this.add([topToolbar, theDescription]);
   },*/
   onSaveButtonTap: function() {
        this.fireEvent("saveNoteCommand", this);
   },
   onBackButtonTap: function() {
        console.log("backToHomeCommand");
        this.fireEvent("backToHomeCommand");
   },
   onPlayButtonTap: function() {
        console.log("videoPlayCommand");
        this.fireEvent("videoPlayCommand");
   },
   onDetailsButtonTap: function () {
        console.log("videoDescCommand");
        this.fireEvent("videoDescCommand");
   },
   onReviewsButtonTap: function () {
        console.log("videoReviewsCommand");
        this.fireEvent("videoReviewsCommand");
   }
});