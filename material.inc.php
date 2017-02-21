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
 * material.inc.php
 *
 * bonbons game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */

 $this->resources = array(
    "bonbons"     => clienttranslate('Bonbons'),
    "money"   => clienttranslate('Money'),
    "empty"     => clienttranslate('Empty package'),
);

 //
 
 $this->card_types = array(
	1  => array( 'name' => $this->resources["bonbons" ], 'type_id' =>  1),
	2  => array( 'name' => $this->resources["money"   ], 'type_id' =>  2),
	3  => array( 'name' => $this->resources["empty"   ], 'type_id' =>  3)
	);

 
 
/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);

*/




