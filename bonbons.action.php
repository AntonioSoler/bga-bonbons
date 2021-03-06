<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * bonbons implementation : © Antonio Soler morgalad.es@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * 
 * bonbons.action.php
 *
 * bonbons main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/bonbons/bonbons/myAction.html", ...)
 *
 */
  
  
  class action_bonbons extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "bonbons_bonbons";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there


    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */
     
	 public function selectSquare()
    {
		self::setAjaxMode();
		$pos = self::getArg( "pos", AT_posint, true );
		$this->game->selectSquare($pos);
		self::ajaxResponse();    
	}
	
	 public function selectRound()
    {
		self::setAjaxMode();
		$pos = self::getArg( "pos", AT_posint, true );
		$field_id = self::getArg( "field_id", AT_posint, true );
		$this->game->selectRound($pos,$field_id);
		self::ajaxResponse();    
	}	
	
	 public function selectPass()
    {
		self::setAjaxMode();
		$this->game->selectPass();
		self::ajaxResponse();    
	}
	
  }
  