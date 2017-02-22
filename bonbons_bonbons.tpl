{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- bonbons implementation : © Antonio Soler morgalad.es@gmail.com
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    bonbons_bonbons.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->
<div id="playarea">

<div id="tablearea" class="boardtable">
		<!-- BEGIN tablearea -->
		<div id="stile_{POSITION}" class="squaretile">
			<div class="squaretile--front"></div><div id="stile_back_{POSITION}" class="squaretile--back"></div>
		</div>
		<!-- END tablearea -->
</div>

	<div id="fieldswrapper">
				<!-- BEGIN playerfield -->
				<div id="playerField_{PLAYER_ID}" class="playerfield">
						<p style="color:#{PLAYER_COLOR}; text-align: center ;"  >{PLAYER_NAME}</p>
						<div id="tile_1_{PLAYER_ID}" class="roundtile">
							<div class="roundtile--front"></div><div class="roundtile--back"></div>
						</div>
						<div id="tile_2_{PLAYER_ID}" class="roundtile">
							<div class="roundtile--front"></div><div class="roundtile--back"></div>
						</div>
						<div id="tile_3_{PLAYER_ID}" class="roundtile">
							<div class="roundtile--front"></div><div class="roundtile--back"></div>
						</div>
						<div id="tile_4_{PLAYER_ID}" class="roundtile">
							<div class="roundtile--front"></div><div class="roundtile--back"></div>
						</div>
						
				</div>
			<!-- END playerfield -->
	</div>
</div>
<script type="text/javascript">

// Javascript HTML templates

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${id}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
