/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * bonbons implementation : © Antonio Soler morgalad.es@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * bonbons.js
 *
 * bonbons user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
	"ebg/stock",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.bonbons", ebg.core.gamegui, {
        constructor: function(){
            console.log('bonbons constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;
			this.cardwidth = 100;
            this.cardheight = 100;

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
            }
            
            // TODO: Set up your game interface here, according to "gamedatas"
            
			/*this.squaretiles = new ebg.stock();
            this.squaretiles.create( this, $('tablearea'), this.cardwidth, this.cardheight );
            this.squaretiles.image_items_per_row = 4;
			this.squaretiles.setSelectionMode( 1 );
			this.squaretiles.item_margin=3;
			
			for(  i=1;i<=36;i++ )
            {
            
            this.squaretiles.addItemType( i, 1, g_gamethemeurl+'img/tiles.jpg', i-1 );			
            this.squaretiles.addItemType( 100+i, i, g_gamethemeurl+'img/tiles.jpg', 34 );
            this.squaretiles.addToStockWithId( 100+i , i , 'player_boards');  
            }*/
			
            // Setup game notifications to handle (see "setupNotifications" method below)
            
			if ( this.gamedatas.fifthtile != null )
			{
				//debugger;
				player_id=this.gamedatas.fifthtile.replace(/\D/g,'');
				dojo.place(
                this.format_block('jstpl_round', {
					field1 : player_id ,
					field2 : player_id ,
                    pos1:	5 ,
					pos2:   5  }), 'playerField_'+player_id , "last" );
			}
			
			
			if ( Object.keys(this.gamedatas.table).length >= 1)
			{
				for( var i in this.gamedatas.table )
				{
					var card = this.gamedatas.table[i];
					this.flipsquare(card['location_arg'],card['type'],true);
				}
			}
			if ( Object.keys(this.gamedatas.playerfields).length >= 1)
			{
				for( var i in this.gamedatas.playerfields )
				{
					var card = this.gamedatas.playerfields[i];
					this.flipround(card['location'],card['location_arg'],card['type'],true);
				}
			}
			/*dojo.query('.roundtile').onclick( function(evt) { 
			dojo.toggleClass(this, 'flipped');
			});
			
			dojo.query('.squaretile').onclick( function(evt) { 
			dojo.toggleClass(this, 'flipped');
			});*/
			
			dojo.query( '.squaretile' ).connect( 'onclick', this, 'selectSquare' );
			dojo.query( '.roundtile' ).connect( 'onclick', this, 'selectRound' );
			 
			this.addTooltipToClass( "roundtile", _( "Round tile" ), "" );
			this.addTooltipToClass( "squaretile", _( "Square tile" ), "" );

			this.setupNotifications();
            console.log( "Ending game setup" );
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
            case 'endOfTurn':
			    dojo.query( '.flipped' ).removeClass( 'flipped' )   ;
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
				case 'flipRound':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'pass_button', _(' Pass '), 'selectPass' ); 
                    
                    break;	
			
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */
		
		flipsquare: function ( location_arg, card_id, visible )
		{
			xpos= -100*((card_id - 1 )%4 );
			ypos= -100*(Math.floor( (card_id -1 ) / 4 ));
			position= xpos+"px "+ ypos+"px ";
			
			dojo.style('stile_back_'+location_arg , "background-position", position);

			if (visible) 
				{
				dojo.toggleClass('stile_'+location_arg , "visible", true);
				}		
			else
				{
				dojo.toggleClass('stile_'+location_arg , "flipped", true);
				}
		},
		
		flipround: function ( location, location_arg, card_id, visible )
		{
			
			player_id = location.replace(/\D/g,'');
			xpos= -100*((card_id - 1 )%4 );
			ypos= -100*(Math.floor( (card_id -1 ) / 4 ));
			position= xpos+"px "+ ypos+"px ";
			/*dojo.place(
                this.format_block('jstpl_roundback', {
                    position: location_arg ,
					player_id : player_id ,
					x : xpos,
					y : ypos  }), 'rtile_back_'+location_arg+'_'+player_id , "replace" );
			*/		
			//dojo.place('<div id="rtile_back_'+location_arg+'_'+player_id+'" class="roundtile--back" style="background-position: '+xpos+'px '+ypos+'px ;"></div>','rtile_back_'+location_arg+'_'+player_id , "replace" );
			
			dojo.style('rtile_back_'+location_arg+'_'+player_id , "background-position", position);
			
			if (visible) 
				{
				dojo.toggleClass('rtile_'+location_arg+'_'+player_id,"visible", true);	
				}	
			else{
				dojo.toggleClass('rtile_'+location_arg+'_'+player_id,"flipped", true);	
				}
		},


        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/bonbons/bonbons/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */
		
		selectRound: function( evt )
        {
            // Stop this event propagation
            dojo.stopEvent( evt );
			if( ! this.checkAction( 'selectRound' ) )
            {   return; }

            // Get the cliqued pos and Player field ID
            var coords = evt.currentTarget.id.split('_');
            var pos = coords[1];
            var field_id = coords[2];

            if( dojo.hasClass( 'rtile_'+pos+'_'+field_id ,'visible' ) || dojo.hasClass( 'rtile_'+pos+'_'+field_id ,'flipped' )  )
            {
                // This is not a possible move => the click does nothing
                return ;
            }
            
			if(( this.gamedatas.gamestate.name=="swapRound" || this.gamedatas.gamestate.name=="buyRound" )&& (field_id != this.gamedatas.gamestate.active_player))
            {
                this.showMessage  ( _("You have to select one of YOUR round tiles..."), "info") // This is not a possible move => the click does nothing
                return ;
            }
			
            if( this.checkAction( 'selectRound' ) )    // Check that this action is possible at this moment
            {            
                this.ajaxcall( "/bonbons/bonbons/selectRound.html", {
                    pos:pos,
                    field_id:field_id
                }, this, function( result ) {} );
            }            
        },
		
		selectSquare: function( evt )
        {
            // Stop this event propagation
            dojo.stopEvent( evt );
			if( ! this.checkAction( 'selectSquare' ) )
            {   return; }

            // Get the cliqued pos and Player field ID
            var coords = evt.currentTarget.id.split('_');
            var pos = coords[1];

            if( dojo.hasClass( 'stile_'+pos , 'visible' ) || dojo.hasClass( 'stile_'+pos ,'flipped' ))
            {
                // This is not a possible move => the click does nothing
                return ;
            }
            
            if( this.checkAction( 'selectSquare' ) )    // Check that this action is possible at this moment
            {            
                this.ajaxcall( "/bonbons/bonbons/selectSquare.html", {
                    pos:pos
                }, this, function( result ) {} );
            }            
        },
		
		selectPass: function( evt )
        {
            // Stop this event propagation
            dojo.stopEvent( evt );
			if( ! this.checkAction( 'selectPass' ) )
            {   return; }
       
            if( this.checkAction( 'selectPass' ) )    // Check that this action is possible at this moment
            {            
                this.ajaxcall( "/bonbons/bonbons/selectPass.html", {
                    pos:pos
                }, this, function( result ) {} );
            }            
        },

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your bonbons.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
			
			dojo.subscribe( 'roundVisible', this, "notif_roundVisible" );
			this.notifqueue.setSynchronous( 'roundVisible', 2000 );
			dojo.subscribe( 'squareFliped', this, "notif_squareFliped" );
            //this.notifqueue.setSynchronous( 'squareFliped', 2000 );
			dojo.subscribe( 'emptyPackage', this, "notif_emptyPackage");
            this.notifqueue.setSynchronous('emptyPackage', 2000);
			dojo.subscribe('roundFliped', this, "notif_roundFliped");
            this.notifqueue.setSynchronous('roundFliped', 2000);
			dojo.subscribe('match', this, "notif_match");
            this.notifqueue.setSynchronous('match', 2000);
			dojo.subscribe('theft', this, "notif_theft");
            this.notifqueue.setSynchronous('theft', 2000);
			dojo.subscribe('pass', this, "notif_pass");
            this.notifqueue.setSynchronous('pass', 2000);
			dojo.subscribe('swapround', this, "notif_swapround");
            this.notifqueue.setSynchronous('swapround', 2000);
			
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        */
		
		notif_roundVisible: function( notif )
        {
            console.log( 'notif_roundVisible' );
			console.log( notif );
			
			this.flipround ( notif.args.player_id , notif.args.roundselected , notif.args.card_type, true );			
        },
///////////////////////////////////////////////////				
		notif_pass: function( notif )
        {
            console.log( 'notif_pass' );
			console.log( notif );
			
	    },
///////////////////////////////////////////////////		
		notif_squareFliped: function( notif )
        {
            console.log( 'notif_squareFliped' );
			console.log( notif );
			
			this.flipsquare ( notif.args.pos , notif.args.card_type , false );
			if ( notif.args.ismoney )
			{
				dojo.addClass("stile_"+notif.args.pos ,"moneytile");
			}
			if ( notif.args.moneytiles==3 )
			{
				dojo.query(".moneytile").addClass("visible");
			}
        },
///////////////////////////////////////////////////		
		notif_roundFliped: function( notif )
        {
            console.log( 'notif_roundFliped' );
			console.log( notif );
			this.flipround ( notif.args.fieldselected , notif.args.roundselected , notif.args.card_type, false );			
	    },
///////////////////////////////////////////////////		
		notif_emptyPackage: function( notif )
        {
            console.log( 'notif_emptyPackage' );
			console.log( notif );
			
			this.flipsquare (  notif.args.pos, notif.args.card_type , true );
			
			player_id=notif.args.player_id;
			
			dojo.place(
                this.format_block('jstpl_round', {
					field1 : player_id ,
					field2 : player_id ,
                    pos1:	5 ,
					pos2:   5 
					}), 'playerField_'+player_id , "last" );
					
			dojo.query( '.roundtile' ).connect( 'onclick', this, 'selectRound' );
		},
///////////////////////////////////////////////////		
		notif_match: function( notif )
        {
            console.log( 'notif_match' );
			console.log( notif );
			
			this.flipsquare (  notif.args.squareselected , notif.args.card_type , true );			
			this.flipround ( notif.args.fieldselected , notif.args.roundselected , notif.args.card_type, true );			
        },
///////////////////////////////////////////////////		
		notif_theft: function( notif )
        {
            console.log( 'notif_theft' );
			console.log( notif );
		this.flipsquare (  notif.args.squareselected , notif.args.card_type , true );			
		this.flipround ( notif.args.fieldselected , notif.args.roundselected , notif.args.card_type, false );	
			
        },
///////////////////////////////////////////////////		
		notif_swapround: function( notif )
        {
            console.log( 'notif_theft' );
			console.log( notif );
		
		dojo.removeClass('rtile_'+notif.args.roundselected+'_'+notif.args.fieldselected,"flipped")
		
		this.slideTemporaryObject( '<div class="roundtile--front"></div>', 'page-content', 
		'rtile_'+notif.args.pos+'_'+notif.args.field_id , 
		'rtile_'+notif.args.roundselected+'_'+notif.args.fieldselected , 500 , 0 );
		
		this.slideTemporaryObject( '<div class="roundtile--front"></div>', 'page-content', 
		'rtile_'+notif.args.roundselected+'_'+notif.args.fieldselected,
		'rtile_'+notif.args.pos+'_'+notif.args.field_id , 500 , 0 );
		
		thisstyle=dojo.attr('rtile_back_'+notif.args.roundselected+'_'+notif.args.fieldselected, "style");
		dojo.attr('rtile_back_'+notif.args.pos+'_'+notif.args.field_id, "style" ,thisstyle);
		dojo.toggleClass('rtile_'+notif.args.pos+'_'+notif.args.field_id, "visible" );
			
        },
   });             
});
