Ext.define('NTV.controller.ViewVideo', {
    extend: 'Ext.app.Controller',
    
    config: {
        refs: {
        	overlayPanel        : "overlaypanel",
            viewVideo           : "viewvideo",
            viewVideoPlayer     : "viewvideo #playerCont",
            viewVideoDesc       : "viewvideo #descvideoId",
            viewVideoRevs       : "viewvideo",
            reviewEditor        : "overlaycommentpanel",
            searchBar           : "searchbar",
        },
        control: {
            reviewEditor: {
                saveReviewCommand   : "saveReview",
            },
            viewVideo :{
                show :'handleOrientation',
            },
            viewVideo : {
                backToHomeCommand   : "onBackToHomeCommand",
                newReviewCommand    : "onNewReviewCommand",
                shareSocialCommand  : "onShareSocialCommand",
                selectPrevious      : "onSelectPrevious",
                selectNext          : "onSelectNext",
            },
            searchbar   : {
                backToHomeCommand   : "onBackToVideoCommand",                 
            },
        },
        currentStore : null,
        currentId    : null,
    },
    
    launch: function () {
        this.callParent();
    },
    init: function () {
        this.callParent();
    },
    getRandomInt: function (min, max) {
	    return Math.floor(Math.random() * (max - min + 1)) + min;
	},
	
	/**slide transitions**/
	slideLeftTransition: { 
		type: 'slide', direction: 'left' 
	},
	slideRightTransition: { 
		type: 'slide', direction: 'right' 
	},
	
    /**saving an item - especially videos I like**/
    saveReview : function() {
        var reviewEditor  = this.getReviewEditor();
        var newValues     = reviewEditor.getValues();
        var currentId     = reviewEditor.getVideoId();
        var currentReview = reviewEditor.getRecord();        
        var me   = this, 
            user = me.getApplication().getController('User').getCurrentUser();
            //alert(user.id);
            
        if(user.id !=-1) {
            if(currentId && newValues.comments) {
                Ext.Viewport.setMasked({
                    xtype: 'loadmask',
                    message: 'Saving comments...',
                    indicator: true,
                });
                Ext.data.JsonP.request({
                    //url: 'http://briteskills.com/gdata/saveReviews.php',
                    url: 'http://localhost/Notes/php/saveReviews.php',
                    method: 'POST',
                    params: {
                        video_id: currentId,
                        user_id: user.id,
                        comments: newValues.comments,
                    },
                    success: function(result) {
                        //console.log(result.responseText);
                        //Ext.Msg.alert(result.responseText);
                        Ext.Viewport.unmask();
                        if(result.responseText !='-1') {
                            Ext.Msg.alert('Success', 'Saved Successfully');
                            //set user id from result and call loggedin class
                            reviewEditor.setValues('');
                        } else {
                            Ext.Msg.alert('Failure', "Sorry your comment was not saved");
                        }
                        reviewEditor.destroy();
                    },
                    failure: function(result) {
                        Ext.Msg.alert('Error ' + result);
                    }
                });
                
            } else {
                Ext.Msg.alert('Error', 'Please enter comments.');
            } 
        } else {
            Ext.Msg.alert('Please login', 'You must be logged in to post comments.');
        }   
    
    },
    
    /**saving an item - especially videos I like**/
    onSaveVideoCommand : function() {
         var noteEditor = this.getNoteEditor();

        var currentNote = noteEditor.getRecord();
        var newValues = noteEditor.getValues();
    
        // Update the current note's fields with form values.
        currentNote.set("title", newValues.title);
        currentNote.set("narrative", newValues.narrative);
    
        var errors = currentNote.validate();
    
        if (!errors.isValid()) {
            Ext.Msg.alert('Wait!', errors.getByField("title")[0].getMessage(), Ext.emptyFn);
            currentNote.reject();
            return;
        }
    
        var notesStore = Ext.getStore("NTV");
    
        if (null == notesStore.findRecord('id', currentNote.data.id)) {
            notesStore.add(currentNote);
        }
    
        notesStore.sync();
    
        notesStore.sort([{ property: 'dateCreated', direction: 'DESC'}]);
    
        this.activateNotesList();
    },
    /**Go back home**/
    onBackToHomeCommand: function() {
    	var me = this,
    	    app = me.getApplication(),
    	    homeController = app.getController('VideoList'),
    	    pl = Ext.getCmp('playerCont');
    	    pl.setHtml('');
    	homeController.activateNotesList();	
    },
    onSelectPrevious: function() {
        var storeName = this.getCurrentStore();
        var myStore = Ext.getStore(storeName);
        var cId = this.getCurrentId();
        var NewCId = (cId >0) ? cId - 1: cId;
        
        var prevRecord = myStore.getAt(NewCId);
        var pl = Ext.getCmp('playerCont');
        var ds = Ext.getCmp('descvideoId');
        this.setCurrentId(NewCId);
        //this.getViewVideo.setVideoId(prevRecord.get('videoid'));
        pl.setHtml('');
        ds.setHtml('');
        pl.setHtml('<div id="video1"><iframe width="480" height="320" src="http://www.youtube.com/embed/'+ prevRecord.get('videoid') +'?modestbranding=1&amp;fs=1&amp;hl=en_US&amp;rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe></div>');//.videoDescription = prevRecord.get('description');
        ds.setHtml(prevRecord.get('description'));//.videoDescription = prevRecord.get('description');
    },
    onSelectNext: function() {
        var storeName = this.getCurrentStore(),
            myStore = Ext.getStore(storeName),
            myStoreSize = myStore.getCount();
            cId = this.getCurrentId(),
            NewCId = (cId < myStoreSize) ? cId + 1: cId;
        console.log('store size: ' + myStoreSize);
        console.log('cur index: ' + cId);
        console.log('new index: ' + NewCId);
        
        //var id = myStore.getData().getAt(cId).id;
        //var selModel = grid.getSelectionModel();
        /*var selectedRecord = selModel.getLastSelected();
        var recordIndex = myStore.indexOf(selectedRecord);
        var nextRecord = myStore.getAt(recordIndex + 1);
        selModel.select(nextRecord);*/
        
       
       var prevRecord = myStore.getAt(NewCId);
       var pl = Ext.getCmp('playerCont');
       var ds = Ext.getCmp('descvideoId');
       this.setCurrentId(NewCId);
       pl.setHtml('');
       ds.setHtml('');
       pl.setHtml('<div id="video1"><iframe width="480" height="320" src="http://www.youtube.com/embed/'+ prevRecord.get('videoid') +'?modestbranding=1&amp;fs=1&amp;hl=en_US&amp;rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe></div>');//.videoDescription = prevRecord.get('description');
       ds.setHtml(prevRecord.get('description'));//.videoDescription = prevRecord.get('description');
    },
    /*
     * RSVP for the meetup.
     */
    rsvp: function(event_id, event_name, rsvp) {
        var me      = this,
            rsvpURL = serverUrl + 'php/submit_rsvp.php';

        Ext.data.JsonP.request({
            url: rsvpURL,
            params: {
                event_id: event_id,
                rsvp: rsvp
            },
            success: function(result, request) {
                
            },
            failure: function(result, request) {
                console.log('commfail', result);
                Ext.Msg.alert('Failed', 'Communication Failure', Ext.emptyFn);
            }
        });
    }    
});
//http://stackoverflow.com/questions/10840139/sencha-touch-2-accessing-config-values-while-creating-views?answertab=votes#tab-top
//login example: http://www.sencha.com/forum/showthread.php?205536-Pass-variable-from-one-panel-to-another-in-the-same-view