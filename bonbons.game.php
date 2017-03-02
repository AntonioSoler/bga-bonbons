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
  * bonbons.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class bonbons extends Table
{
	function bonbons( )
	{
        	
 
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();self::initGameStateLabels( array( 
               "moneytiles" => 10,
			   "squareselected" => 11,
			   "roundselected" => 12,
			   "fieldselected" => 13
			   
            //    "my_second_global_variable" => 11,
            //      ...
            //    "my_first_game_variant" => 100,
            //    "my_second_game_variant" => 101,
            //      ...	
        ) );
        $this->squares = self::getNew( "module.common.deck" );
		$this->squares->init( "squares" );
		$this->rounds = self::getNew( "module.common.deck" );
		$this->rounds->init( "rounds" );
	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "bonbons";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $default_colors = array( "ff0000", "008000", "0000ff", "ffa500", "773300" );
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, array(  "ff0000", "008000", "0000ff", "ffa500", "773300" ) );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        //self::setGameStateInitialValue( 'my_first_global_variable', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // TODO: setup the initial game situation here
       
	   self::setGameStateInitialValue( 'moneytiles', 0 );
	   self::setGameStateInitialValue( 'squareselected', 0 );
	   self::setGameStateInitialValue( 'roundselected', 44 );
	   self::setGameStateInitialValue( 'fieldselected', 11111 ); 
	   
	   $rounds = array();
       $squares = array();
	   
	   
	   for ($i = 1; $i <= 34; $i++)
        {
			if ($i  <= 32)   // candies
            {
                
				$card = array( 'type' => $i , 'type_arg' => 0 , 'nbr' => 1);
				array_push($squares, $card);
				array_push($rounds, $card);
            }
            else if ($i == 33)  //money tiles only on squares
            {	
                
				$card = array( 'type' => $i, 'type_arg' => 1 , 'nbr' => 3);
				array_push($squares, $card);	
            }
			else if ($i == 34)  // empty package only on squares
            {	
                
				$card = array( 'type' => $i, 'type_arg' => 1 , 'nbr' => 1);
				array_push($squares, $card);	
            }
			
	    }
		$this->squares->createCards( $squares, 'hidden' );
		$this->squares->shuffle( 'hidden' );
		
		$this->rounds->createCards( $rounds, 'deck' );
		$this->rounds->shuffle( 'deck' );
	   
													//pick round tiles for players	   
		foreach( $players as $player_id => $player )
		{
			for ($i = 1; $i <= 4; $i++)
			{
				$this->rounds->pickCardForLocation( 'deck', "hidden".$player_id , $i ); //  Draw a card
			}
		}
	    // Activate first player (which is in general a good idea :) )
		
		$this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array( 'players' => array() );
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
									
		$playerfields= array ();
		$result['table'] = $this->squares->getCardsInLocation( 'visible' );
		
		$sql = "SELECT  card_type type ,card_location location, card_location_arg  location_arg FROM rounds where card_location like 'visible%' ";
        $result['playerfields'] = self::getCollectionFromDb( $sql );
		
		$sql = "SELECT card_location location FROM rounds where card_location_arg=5 and card_location != 'deck' ";
        $result['fifthtile'] = self::getUniqueValueFromDB ( $sql );
		
		
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        
		$players = self::loadPlayersBasicInfos();
		$sql = "SELECT  card_type type ,card_location location, card_location_arg  location_arg FROM rounds where card_location like 'visible%' ";
        $visiblecards = self::getCollectionFromDb( $sql );
        $progression = 100 * sizeof($visiblecards) / sizeof($players)*4 ;
		
        return $progression;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */



//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in bonbons.action.php)
    */

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} played ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */
	function selectPass( )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'selectPass' ); 
		$player_id = self::getActivePlayerId();
		$player_name = self::getActivePlayerName();
        self::notifyAllPlayers( "pass", clienttranslate( '${player_name} passes and does not want to reveal a round tile this turn.' ), array(
				'player_id' => $player_id,
				'player_name' => $player_name
				) );
		$this->gamestate->nextState( 'endOfTurn' );
          
    }
	
	function selectSquare( $pos )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'selectSquare' ); 
        self::setGameStateValue( 'squareselected', $pos );
		$this->gamestate->nextState( 'processSquare' );
          
    }
	
	//////////////////////////////////////////////////////////////////////////////
	
	function selectRound( $pos , $field_id)
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'selectRound' ); 
        $player_id = self::getActivePlayerId();
		$sql = "SELECT card_type from rounds where card_location_arg=".$pos." and card_location like 'hidden".$field_id."'" ;
        $card_type = self::getUniqueValueFromDb( $sql );
		
		$state=$this->gamestate->state(); 
		
		if( $state['name'] == 'flipRound' ) 
			{
			self::setGameStateValue( 'roundselected', $pos );
			self::setGameStateValue( 'fieldselected', $field_id );
			
			$this->gamestate->nextState( 'processRound' );
			}
			
		if( $state['name'] == 'buyRound' ) 
			{
			$sql = "UPDATE rounds SET card_location='visible$player_id' WHERE card_location_arg=$pos and card_location like 'hidden$field_id'";
	        self::DbQuery( $sql ); 
			self::DbQuery( "UPDATE player SET player_score=player_score+1 WHERE player_id='$player_id'" );
			self::notifyAllPlayers( "roundVisible", clienttranslate( '${player_name} has revealed a round token for free after finding three money tiles.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'roundselected' => $pos,
				'fieldselected' => $player_id,
				'card_type' => $card_type
				) );
			
			$this->gamestate->nextState( 'endOfTurn' );
			}
			
		if( $state['name'] == 'swapRound' ) 
			{
			$roundselected=self::getGameStateValue( 'roundselected' );
		    $fieldselected=self::getGameStateValue('fieldselected');	
			
		    $sql = "UPDATE rounds SET card_location='visible$field_id' , card_location_arg=$pos WHERE card_location_arg=$roundselected and card_location like 'hidden$fieldselected'";
	        self::DbQuery( $sql );   //make visible the round tile
			$sql = "UPDATE rounds SET card_location='hidden$fieldselected' , card_location_arg=$roundselected WHERE card_location_arg=$pos and card_location like 'hidden$field_id'";
	        self::DbQuery( $sql );   //give the selected tile to other player
			
			self::notifyAllPlayers( "swapround", clienttranslate( '${player_name} has exchanged a round tile after finding a match from other player tiles.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'pos' => $pos,
				'field_id' => $field_id,
				'roundselected' => $roundselected,
				'fieldselected' => $fieldselected
				) );
	         			
			$this->gamestate->nextState( 'endOfTurn' );
			}
             
    }

    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    /*
    
    Example for game state "MyGameState":
*/    
		function stflipSquare()
    {
		//wait for the player to flip a square tile
	}
	
		function stendofturn()
    {   
		self::setGameStateValue( 'moneytiles', 0 );
        $player_id = self::getActivePlayerId();
		$sql = "SELECT card_type type ,card_location location, card_location_arg  location_arg FROM rounds where card_location like 'hidden$player_id' ";
        $remaining = self::getCollectionFromDb( $sql );
        if ( sizeof($remaining) == 0 )
		{
			$this->gamestate->nextState( 'gameEnd' );
		}
		else 
		{   
			self::activeNextPlayer();
			$this->gamestate->nextState( 'flipSquare' );
		}
    }    
    
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function stprocessSquare()
    {
        // Notify all players about the tile fliped
		$player_id = self::getActivePlayerId();
		$squareselected=self::getGameStateValue( 'squareselected');
		$sql = "SELECT card_type from squares where card_location_arg=".$squareselected ;
        $card_type = self::getUniqueValueFromDb( $sql );
		
		if ( $card_type <= 32 )
		{
			$moneytiles=self::getGameStateValue( 'moneytiles');
			self::notifyAllPlayers( "squareFliped", clienttranslate( '${player_name} fliped a square tile.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'pos' => $squareselected,
				'card_type' => $card_type,
				'moneytiles' => $moneytiles,
				'ismoney'  => false
			) );
			$this->gamestate->nextState( 'flipRound' );
		}
		else if ( $card_type == 33 )
		{
			self::incGameStateValue( 'moneytiles', 1 );
		    
			$moneytiles=self::getGameStateValue( 'moneytiles');
			
			if ( $moneytiles >= 3 ) 
				{ 
				self::notifyAllPlayers( "squareFliped", clienttranslate( '${player_name} found 3 money tiles !!! and can flip a round tile for free' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'pos' => $squareselected,
				'card_type' => $card_type,
				'moneytiles' => $moneytiles,
				'ismoney'  => true
				) );
				
				
				$sql = "UPDATE squares SET card_location='visible' WHERE card_type=33";
	            self::DbQuery( $sql ); // 
				$this->gamestate->nextState( 'buyRound' );
				}
			
			
			else 
				{ 
				self::notifyAllPlayers( "squareFliped", clienttranslate( '${player_name} found a money tile and can flip another square tile. ${moneytiles}'), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'pos' => $squareselected,
				'card_type' => $card_type,
				'moneytiles' => $moneytiles,
				'ismoney'  => true
				) );
				$this->gamestate->nextState( 'flipSquare' );
				}
		} 
		else if ( $card_type == 34 )
		{
			$sql = "UPDATE squares SET card_location='visible' WHERE card_location_arg=$squareselected";
	        self::DbQuery( $sql ); 
			$this->rounds->pickCardForLocation( 'deck', "hidden".$player_id , 5 );
			self::notifyAllPlayers( "emptyPackage", clienttranslate( '${player_name} found an black empty package... BAD LUCK! Now this player takes an extra round tile.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'pos' => $squareselected,
				'card_type' => $card_type
			) );
			$this->gamestate->nextState( 'endOfTurn' );
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////
       function stflipRound()
    {
        
        
    }    
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
       function stprocessRound()
    {
        $player_id = self::getActivePlayerId();
		$squareselected=self::getGameStateValue( 'squareselected' );
		$roundselected=self::getGameStateValue( 'roundselected' );
		$fieldselected=self::getGameStateValue('fieldselected');
		
		$sql = "SELECT card_type from squares where card_location_arg=$squareselected" ;
        $square_type = self::getUniqueValueFromDb( $sql );
		$sql = "SELECT card_type from rounds where card_location_arg=$roundselected and card_location like 'hidden$fieldselected'";
        $round_type = self::getUniqueValueFromDb( $sql );
		
        if ( $square_type != $round_type  )
		{
			self::notifyAllPlayers( "roundFliped", clienttranslate( '${player_name} fliped a round tile.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'roundselected' => $roundselected,
				'fieldselected' => $fieldselected,
				'card_type' => $round_type
			) );
			$this->gamestate->nextState( 'endOfTurn' );
		}
		
		else if ( $player_id == $fieldselected )
		{
			$sql = "UPDATE rounds SET card_location='visible$player_id' WHERE card_location_arg=$roundselected and card_location like 'hidden$fieldselected'";
	        self::DbQuery( $sql );
			$sql = "UPDATE squares SET card_location='visible' WHERE card_location_arg=$squareselected";
	        self::DbQuery( $sql ); 
			self::DbQuery( "UPDATE player SET player_score=player_score+1 WHERE player_id='$player_id'" );
			
			self::notifyAllPlayers( "match", clienttranslate( '${player_name} found a match.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'roundselected' => $roundselected,
				'squareselected' => $squareselected,
				'fieldselected' => $fieldselected,
				'card_type' => $round_type
			) );
			
			$this->gamestate->nextState( 'endOfTurn' );
		}
		
		else if ( $player_id != $fieldselected )
		{
			
			$sql = "UPDATE squares SET card_location='visible' WHERE card_location_arg=$squareselected";
	        self::DbQuery( $sql ); 
			self::DbQuery( "UPDATE player SET player_score=player_score+1 WHERE player_id='$player_id'" );
			
			self::notifyAllPlayers( "theft", clienttranslate( '${player_name} found a match of other player. Now this player can give one round tile to this player in exchange.' ), array(
				'player_id' => $player_id,
				'player_name' => self::getActivePlayerName(),
				'roundselected' => $roundselected,
				'squareselected' => $squareselected,
				'fieldselected' => $fieldselected,
				'card_type' => $round_type
			) );
			
			$this->gamestate->nextState( 'swapRound' );
		}
		
    }  
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
     function stbuyround()
    {
        
    }    
   
   //////////////////////////////////////////////////////////////////////////////////////////////////
       function stswapRound()
    {
        
    }    
   
   
//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] == "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] == "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $sql = "
                UPDATE  player
                SET     player_is_multiactive = 0
                WHERE   player_id = $active_player
            ";
            self::DbQuery( $sql );

            $this->gamestate->updateMultiactiveOrNextState( '' );
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            $sql = "ALTER TABLE xxxxxxx ....";
//            self::DbQuery( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            $sql = "CREATE TABLE xxxxxxx ....";
//            self::DbQuery( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
