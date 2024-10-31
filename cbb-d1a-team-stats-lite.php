<?php
/*
Plugin Name: NCAA Basketball Team Stats Lite
Description: Provides the latest NCAAB stats of your NCAAB Basketball Team, updated regularly throughout the NCAAB regular season.
Author: A93D
Version: 0.8.2
Author URI: http://www.thoseamazingparks.com/getstats.php
*/

require_once(dirname(__FILE__) . '/rss_fetch.inc'); 
define('MAGPIE_FETCH_TIME_OUT', 60);
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
define('MAGPIE_CACHE_ON', 0);

// Get Current Page URL
function CBB_D1ALPageURL() {
 $CBB_D1ALpageURL = 'http';
 $CBB_D1ALpageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $CBB_D1ALpageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $CBB_D1ALpageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $CBB_D1ALpageURL;
}
/* This Registers a Sidebar Widget.*/
function widget_cbb_d1alstats() 
{
?>
<h2>NCAAB Team Stats Lite</h2>
<?php cbb_d1al_stats(); ?>
<?php
}

function cbb_d1alstats_install()
{
register_sidebar_widget(__('NCAAB-D1A Team Stats Lite'), 'widget_cbb_d1alstats'); 
}
add_action("plugins_loaded", "cbb_d1alstats_install");

/* When plugin is activated */
register_activation_hook(__FILE__,'cbb_d1al_stats_install');

/* When plugin is deactivation*/
register_deactivation_hook( __FILE__, 'cbb_d1al_stats_remove' );

function cbb_d1al_stats_install() 
{
// Initial Team
$initialcbb_d1alteam = 'air_force_falcons_team_stats';
add_option("cbb_d1al_stats_color", "#000000", "This is my default stats color", "yes");

// Add the Options
add_option("cbb_d1al_stats_team", "$initialcbb_d1alteam", "This is my cbb-d1a team", "yes");

if ( ($ads_id_1 == 1) || ($ads_id_1 == 0) )
	{
	mail("links@a93d.com", "LITE CBB_D1AL Stats-News Installation", "Hi\n\nLITE CBB_D1AL Stats Activated at \n\n".CBB_D1ALPageURL()."\n\nCBB_D1AL Stats Service Support\n","From: links@a93d.com\r\n");
	}
}
function cbb_d1al_stats_remove() 
{
/* Deletes the database field */
delete_option('cbb_d1al_stats_team');
delete_option('cbb_d1al_stats_color');
}

if ( is_admin() ){

/* Call the html code */
add_action('admin_menu', 'cbb_d1al_stats_admin_menu');

function cbb_d1al_stats_admin_menu() {
add_options_page('NCAAB D1A Stats Lite', 'NCAAB D1A Stats Lite Settings', 'administrator', 'cbb-d1a-team-stats-lite.php', 'cbb_d1al_stats_plugin_page');
}
}

function cbb_d1al_stats_plugin_page() {
?>
<script language=JavaScript>

var TCP = new TColorPicker();

function TCPopup(field, palette) {
	this.field = field;
	this.initPalette = !palette || palette > 3 ? 0 : palette;
	var w = 194, h = 240,
	move = screen ? 
		',left=' + ((screen.width - w) >> 1) + ',top=' + ((screen.height - h) >> 1) : '', 
	o_colWindow = window.open('<?php echo '../wp-content/plugins/ncaab-team-stats-lite/picker.html'; ?>', null, "help=no,status=no,scrollbars=no,resizable=no" + move + ",width=" + w + ",height=" + h + ",dependent=yes", true);
	o_colWindow.opener = window;
	o_colWindow.focus();
}

function TCBuildCell (R, G, B, w, h) {
	return '<td bgcolor="#' + this.dec2hex((R << 16) + (G << 8) + B) + '"><a href="javascript:P.S(\'' + this.dec2hex((R << 16) + (G << 8) + B) + '\')" onmouseover="P.P(\'' + this.dec2hex((R << 16) + (G << 8) + B) + '\')"><img src="pixel.gif" width="' + w + '" height="' + h + '" border="0"></a></td>';
}

function TCSelect(c) {
	this.field.value = '#' + c.toUpperCase();
	this.win.close();
}

function TCPaint(c, b_noPref) {
	c = (b_noPref ? '' : '#') + c.toUpperCase();
	if (this.o_samp) 
		this.o_samp.innerHTML = '<font face=Tahoma size=2>' + c +' <font color=white>' + c + '</font></font>'
	if(this.doc.layers)
		this.sample.bgColor = c;
	else { 
		if (this.sample.backgroundColor != null) this.sample.backgroundColor = c;
		else if (this.sample.background != null) this.sample.background = c;
	}
}

function TCGenerateSafe() {
	var s = '';
	for (j = 0; j < 12; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i ++)
				s += this.bldCell(k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, i * 51, 8, 10);
		s += "</tr>";
	}
	return s;
}

function TCGenerateWind() {
	var s = '';
	for (j = 0; j < 12; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i++)
				s += this.bldCell(i * 51, k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, 8, 10);
		s += "</tr>";
	}
	return s	
}
function TCGenerateMac() {
	var s = '';
	var c = 0,n = 1;
	var r,g,b;
	for (j = 0; j < 15; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i++){
				if(j<12){
				s += this.bldCell( 255-(Math.floor(j / 2) * 51), 255-(k * 51 + (j % 2) * 51 * 3),255-(i * 51), 8, 10);
				}else{
					if(n<=14){
						r = 255-(n * 17);
						g=b=0;
					}else if(n>14 && n<=28){
						g = 255-((n-14) * 17);
						r=b=0;
					}else if(n>28 && n<=42){
						b = 255-((n-28) * 17);
						r=g=0;
					}else{
						r=g=b=255-((n-42) * 17);
					}
					s += this.bldCell( r, g,b, 8, 10);
					n++;
				}
			}
		s += "</tr>";
	}
	return s;
}

function TCGenerateGray() {
	var s = '';
	for (j = 0; j <= 15; j ++) {
		s += "<tr>";
		for (k = 0; k <= 15; k ++) {
			g = Math.floor((k + j * 16) % 256);
			s += this.bldCell(g, g, g, 9, 7);
		}
		s += '</tr>';
	}
	return s
}

function TCDec2Hex(v) {
	v = v.toString(16);
	for(; v.length < 6; v = '0' + v);
	return v;
}

function TCChgMode(v) {
	for (var k in this.divs) this.hide(k);
	this.show(v);
}

function TColorPicker(field) {
	this.build0 = TCGenerateSafe;
	this.build1 = TCGenerateWind;
	this.build2 = TCGenerateGray;
	this.build3 = TCGenerateMac;
	this.show = document.layers ? 
		function (div) { this.divs[div].visibility = 'show' } :
		function (div) { this.divs[div].visibility = 'visible' };
	this.hide = document.layers ? 
		function (div) { this.divs[div].visibility = 'hide' } :
		function (div) { this.divs[div].visibility = 'hidden' };
	// event handlers
	this.C       = TCChgMode;
	this.S       = TCSelect;
	this.P       = TCPaint;
	this.popup   = TCPopup;
	this.draw    = TCDraw;
	this.dec2hex = TCDec2Hex;
	this.bldCell = TCBuildCell;
	this.divs = [];
}

function TCDraw(o_win, o_doc) {
	this.win = o_win;
	this.doc = o_doc;
	var 
	s_tag_openT  = o_doc.layers ? 
		'layer visibility=hidden top=54 left=5 width=182' : 
		'div style=visibility:hidden;position:absolute;left:6px;top:54px;width:182px;height:0',
	s_tag_openS  = o_doc.layers ? 'layer top=32 left=6' : 'div',
	s_tag_close  = o_doc.layers ? 'layer' : 'div'
		
	this.doc.write('<' + s_tag_openS + ' id=sam name=sam><table cellpadding=0 cellspacing=0 border=1 width=181 align=center class=bd><tr><td align=center height=18><div id="samp"><font face=Tahoma size=2>sample <font color=white>sample</font></font></div></td></tr></table></' + s_tag_close + '>');
	this.sample = o_doc.layers ? o_doc.layers['sam'] : 
		o_doc.getElementById ? o_doc.getElementById('sam').style : o_doc.all['sam'].style

	for (var k = 0; k < 4; k ++) {
		this.doc.write('<' + s_tag_openT + ' id="p' + k + '" name="p' + k + '"><table cellpadding=0 cellspacing=0 border=1 align=center>' + this['build' + k]() + '</table></' + s_tag_close + '>');
		this.divs[k] = o_doc.layers 
			? o_doc.layers['p' + k] : o_doc.all 
				? o_doc.all['p' + k].style : o_doc.getElementById('p' + k).style
	}
	if (!o_doc.layers && o_doc.body.innerHTML) 
		this.o_samp = o_doc.all 
			? o_doc.all.samp : o_doc.getElementById('samp');
	this.C(this.initPalette);
	if (this.field.value) this.P(this.field.value, true)
}
</script>

   <div>
   <h2>NCAAB-D1A Team Stats Options Page</h2>
  
   <form method="post" action="options.php">
   <?php wp_nonce_field('update-options'); ?>
  
   
   <h2>My Current Team: 
   <?php $theteam = get_option('cbb_d1al_stats_team'); 
  	$currentteam = preg_replace('/_|stats/', ' ', $theteam);
	$finalteam = ucwords($currentteam);
	echo $finalteam;
   	?></h2><br /><br />
     <small>My New Team:</small><br />
     <p>
<select name="cbb_d1al_stats_team" id="cbb_d1al_stats_team">
<option value="air_force_falcons_team_stats">Air Force Falcons</option>
<option value="akron_zips_team_stats">Akron Zips</option>
<option value="alabama_am_bulldogs_team_stats">Alabama A&M Bulldogs</option>
<option value="alabama_crimson_tide_team_stats">Alabama Crimson Tide</option>
<option value="alabama_state_hornets_team_stats">Alabama State Hornets</option>
<option value="albany_great_danes_team_stats">Albany Great Danes</option>
<option value="alcorn_state_braves_team_stats">Alcorn State Braves</option>
<option value="american_university_eagles_team_stats">American University Eagles</option>
<option value="appalachian_state_mountaineers_team_stats">Appalachian State Mountaineers</option>
<option value="arizona_state_sun_devils_team_stats">Arizona State Sun Devils</option>
<option value="arizona_wildcats_team_stats">Arizona Wildcats</option>
<option value="arkansas_little_rock_trojans_team_stats">Arkansas-Little Rock Trojans</option>
<option value="arkansas_pine_bluff_golden_lions_team_stats">Arkansas-Pine Bluff Golden Lions</option>
<option value="arkansas_razorbacks_team_stats">Arkansas Razorbacks</option>
<option value="arkansas_state_red_wolves_team_stats">Arkansas State Red Wolves</option>
<option value="army_black_knights_team_stats">Army Black Knights</option>
<option value="auburn_tigers_team_stats">Auburn Tigers</option>
<option value="austin_peay_governors_team_stats">Austin Peay Governors</option>
<option value="ball_state_cardinals_team_stats">Ball State Cardinals</option>
<option value="baylor_bears_team_stats">Baylor Bears</option>
<option value="belmont_bruins_team_stats">Belmont Bruins</option>
<option value="bethune_cookman_wildcats_team_stats">Bethune-Cookman Wildcats</option>
<option value="binghamton_bearcats_team_stats">Binghamton Bearcats</option>
<option value="boise_state_broncos_team_stats">Boise State Broncos</option>
<option value="boston_college_eagles_team_stats">Boston College Eagles</option>
<option value="boston_university_terriers_team_stats">Boston University Terriers</option>
<option value="bowling_green_falcons_team_stats">Bowling Green Falcons</option>
<option value="bradley_braves_team_stats">Bradley Braves</option>
<option value="brigham_young_cougars_team_stats">Brigham Young Cougars</option>
<option value="brown_bears_team_stats">Brown Bears</option>
<option value="bucknell_bison_team_stats">Bucknell Bison</option>
<option value="buffalo_bulls_team_stats">Buffalo Bulls</option>
<option value="butler_bulldogs_team_stats">Butler Bulldogs</option>
<option value="cal_poly_mustangs_team_stats">Cal Poly Mustangs</option>
<option value="cal_state_bakersfield_roadrunners_team_stats">Cal State Bakersfield Roadrunners</option>
<option value="cal_state_fullerton_titans_team_stats">Cal State Fullerton Titans</option>
<option value="cal_state_northridge_matadors_team_stats">Cal State Northridge Matadors</option>
<option value="california_golden_bears_team_stats">California Golden Bears</option>
<option value="california_riverside_highlanders_team_stats">California Riverside Highlanders</option>
<option value="campbell_fighting_camels_team_stats">Campbell Fighting Camels</option>
<option value="canisius_golden_griffins_team_stats">Canisius Golden Griffins</option>
<option value="centenary_gentlemen_team_stats">Centenary Gentlemen</option>
<option value="central_arkansas_bears_team_stats">Central Arkansas Bears</option>
<option value="central_michigan_chippewas_team_stats">Central Michigan Chippewas</option>
<option value="charleston_cougars_team_stats">Charleston Cougars</option>
<option value="charleston_southern_buccaneers_team_stats">Charleston Southern Buccaneers</option>
<option value="charlotte_49ers_team_stats">Charlotte 49ers</option>
<option value="chattanooga_mocs_team_stats">Chattanooga Mocs</option>
<option value="chicago_state_cougars_team_stats">Chicago State Cougars</option>
<option value="cincinnati_bearcats_team_stats">Cincinnati Bearcats</option>
<option value="citadel_bulldogs_team_stats">Citadel Bulldogs</option>
<option value="clemson_tigers_team_stats">Clemson Tigers</option>
<option value="cleveland_state_vikings_team_stats">Cleveland State Vikings</option>
<option value="coastal_carolina_chanticleers_team_stats">Coastal Carolina Chanticleers</option>
<option value="colgate_raiders_team_stats">Colgate Raiders</option>
<option value="colorado_buffaloes_team_stats">Colorado Buffaloes</option>
<option value="colorado_state_rams_team_stats">Colorado State Rams</option>
<option value="columbia_lions_team_stats">Columbia Lions</option>
<option value="connecticut_huskies_team_stats">Connecticut Huskies</option>
<option value="coppin_state_eagles_team_stats">Coppin State Eagles</option>
<option value="cornell_big_red_team_stats">Cornell Big Red</option>
<option value="creighton_bluejays_team_stats">Creighton Bluejays</option>
<option value="dartmouth_big_green_team_stats">Dartmouth Big Green</option>
<option value="davidson_wildcats_team_stats">Davidson Wildcats</option>
<option value="dayton_flyers_team_stats">Dayton Flyers</option>
<option value="delaware_fightin_blue_hens_team_stats">Delaware Fightin Blue Hens</option>
<option value="delaware_state_hornets_team_stats">Delaware State Hornets</option>
<option value="denver_pioneers_team_stats">Denver Pioneers</option>
<option value="depaul_blue_demons_team_stats">DePaul Blue Demons</option>
<option value="detroit_titans_team_stats">Detroit Titans</option>
<option value="drake_bulldogs_team_stats">Drake Bulldogs</option>
<option value="drexel_dragons_team_stats">Drexel Dragons</option>
<option value="duke_blue_devils_team_stats">Duke Blue Devils</option>
<option value="duquesne_dukes_team_stats">Duquesne Dukes</option>
<option value="east_carolina_pirates_team_stats">East Carolina Pirates</option>
<option value="east_tennessee_state_buccaneers_team_stats">East Tennessee State Buccaneers</option>
<option value="eastern_illinois_panthers_team_stats">Eastern Illinois Panthers</option>
<option value="eastern_kentucky_colonels_team_stats">Eastern Kentucky Colonels</option>
<option value="eastern_michigan_eagles_team_stats">Eastern Michigan Eagles</option>
<option value="eastern_washington_eagles_team_stats">Eastern Washington Eagles</option>
<option value="elon_phoenix_team_stats">Elon Phoenix</option>
<option value="evansville_aces_team_stats">Evansville Aces</option>
<option value="fairfield_stags_team_stats">Fairfield Stags</option>
<option value="fiu_golden_panthers_team_stats">FIU Golden Panthers</option>
<option value="florida_am_rattlers_team_stats">Florida A&M Rattlers</option>
<option value="florida_atlantic_owls_team_stats">Florida Atlantic Owls</option>
<option value="florida_gators_team_stats">Florida Gators</option>
<option value="florida_state_seminoles_team_stats">Florida State Seminoles</option>
<option value="fordham_rams_team_stats">Fordham Rams</option>
<option value="fresno_state_bulldogs_team_stats">Fresno State Bulldogs</option>
<option value="furman_paladins_team_stats">Furman Paladins</option>
<option value="gardner_webb_runnin_bulldogs_team_stats">Gardner-Webb Runnin' Bulldogs</option>
<option value="george_mason_patriots_team_stats">George Mason Patriots</option>
<option value="george_washington_colonials_team_stats">George Washington Colonials</option>
<option value="georgetown_hoyas_team_stats">Georgetown Hoyas</option>
<option value="georgia_bulldogs_team_stats">Georgia Bulldogs</option>
<option value="georgia_southern_eagles_team_stats">Georgia Southern Eagles</option>
<option value="georgia_state_panthers_team_stats">Georgia State Panthers</option>
<option value="georgia_tech_yellow_jackets_team_stats">Georgia Tech Yellow Jackets</option>
<option value="gonzaga_bulldogs_team_stats">Gonzaga Bulldogs</option>
<option value="grambling_state_tigers_team_stats">Grambling State Tigers</option>
<option value="green_bay_phoenix_team_stats">Green Bay Phoenix</option>
<option value="hampton_pirates_team_stats">Hampton Pirates</option>
<option value="hartford_hawks_team_stats">Hartford Hawks</option>
<option value="harvard_crimson_team_stats">Harvard Crimson</option>
<option value="hawaii_warriors_team_stats">Hawaii Warriors</option>
<option value="high_point_panthers_team_stats">High Point Panthers</option>
<option value="hofstra_pride_team_stats">Hofstra Pride</option>
<option value="holy_cross_crusaders_team_stats">Holy Cross Crusaders</option>
<option value="houston_baptist_huskies_team_stats">Houston Baptist Huskies</option>
<option value="houston_cougars_team_stats">Houston Cougars</option>
<option value="howard_bison_team_stats">Howard Bison</option>
<option value="idaho_state_bengals_team_stats">Idaho State Bengals</option>
<option value="idaho_vandals_team_stats">Idaho Vandals</option>
<option value="illinois_fighting_illini_team_stats">Illinois Fighting Illini</option>
<option value="illinois_state_redbirds_team_stats">Illinois State Redbirds</option>
<option value="indiana_hoosiers_team_stats">Indiana Hoosiers</option>
<option value="indiana_state_sycamores_team_stats">Indiana State Sycamores</option>
<option value="iona_gaels_team_stats">Iona Gaels</option>
<option value="iowa_hawkeyes_team_stats">Iowa Hawkeyes</option>
<option value="iowa_state_cyclones_team_stats">Iowa State Cyclones</option>
<option value="ipfw_mastodons_team_stats">IPFW Mastodons</option>
<option value="iupui_jaguars_team_stats">IUPUI Jaguars</option>
<option value="jackson_state_tigers_team_stats">Jackson State Tigers</option>
<option value="jacksonville_dolphins_team_stats">Jacksonville Dolphins</option>
<option value="jacksonville_state_gamecocks_team_stats">Jacksonville State Gamecocks</option>
<option value="james_madison_dukes_team_stats">James Madison Dukes</option>
<option value="kansas_jayhawks_team_stats">Kansas Jayhawks</option>
<option value="kansas_state_wildcats_team_stats">Kansas State Wildcats</option>
<option value="kennesaw_state_owls_team_stats">Kennesaw State Owls</option>
<option value="kent_state_golden_flashes_team_stats">Kent State Golden Flashes</option>
<option value="kentucky_wildcats_team_stats">Kentucky Wildcats</option>
<option value="la_salle_explorers_team_stats">La Salle Explorers</option>
<option value="lafayette_leopards_team_stats">Lafayette Leopards</option>
<option value="lamar_cardinals_team_stats">Lamar Cardinals</option>
<option value="lehigh_mountain_hawks_team_stats">Lehigh Mountain Hawks</option>
<option value="liberty_flames_team_stats">Liberty Flames</option>
<option value="lipscomb_bisons_team_stats">Lipscomb Bisons</option>
<option value="long_beach_state_49ers_team_stats">Long Beach State 49ers</option>
<option value="longwood_lancers_team_stats">Longwood Lancers</option>
<option value="louisiana_lafayette_ragin_cajuns_team_stats">Louisiana-Lafayette Ragin' Cajuns</option>
<option value="louisiana_monroe_warhawks_team_stats">Louisiana-Monroe Warhawks</option>
<option value="louisiana_tech_bulldogs_team_stats">Louisiana Tech Bulldogs</option>
<option value="louisville_cardinals_team_stats">Louisville Cardinals</option>
<option value="loyola_il_ramblers_team_stats">Loyola (IL) Ramblers</option>
<option value="loyola_marymount_lions_team_stats">Loyola Marymount Lions</option>
<option value="loyola_md_greyhounds_team_stats">Loyola (MD) Greyhounds</option>
<option value="lsu_tigers_team_stats">LSU Tigers</option>
<option value="maine_black_bears_team_stats">Maine Black Bears</option>
<option value="manhattan_jaspers_team_stats">Manhattan Jaspers</option>
<option value="marist_red_foxes_team_stats">Marist Red Foxes</option>
<option value="marquette_golden_eagles_team_stats">Marquette Golden Eagles</option>
<option value="marshall_thundering_herd_team_stats">Marshall Thundering Herd</option>
<option value="maryland_baltimore_county_retrievers_team_stats">Maryland-Baltimore County Retrievers</option>
<option value="maryland_eastern_shore_hawks_team_stats">Maryland-Eastern Shore Hawks</option>
<option value="maryland_terrapins_team_stats">Maryland Terrapins</option>
<option value="massachusetts_minutemen_team_stats">Massachusetts Minutemen</option>
<option value="mcneese_state_cowboys_team_stats">McNeese State Cowboys</option>
<option value="memphis_tigers_team_stats">Memphis Tigers</option>
<option value="mercer_bears_team_stats">Mercer Bears</option>
<option value="miami_fl_hurricanes_team_stats">Miami (FL) Hurricanes</option>
<option value="miami_oh_redhawks_team_stats">Miami (OH) RedHawks</option>
<option value="michigan_state_spartans_team_stats">Michigan State Spartans</option>
<option value="michigan_wolverines_team_stats">Michigan Wolverines</option>
<option value="middle_tennessee_blue_raiders_team_stats">Middle Tennessee Blue Raiders</option>
<option value="milwaukee_panthers_team_stats">Milwaukee Panthers</option>
<option value="minnesota_golden_gophers_team_stats">Minnesota Golden Gophers</option>
<option value="mississippi_rebels_team_stats">Mississippi Rebels</option>
<option value="mississippi_state_bulldogs_team_stats">Mississippi State Bulldogs</option>
<option value="mississippi_valley_state_delta_devils_team_stats">Mississippi Valley State Delta Devils</option>
<option value="missouri_state_bears_team_stats">Missouri State Bears</option>
<option value="missouri_tigers_team_stats">Missouri Tigers</option>
<option value="montana_grizzlies_team_stats">Montana Grizzlies</option>
<option value="montana_state_bobcats_team_stats">Montana State Bobcats</option>
<option value="morehead_state_eagles_team_stats">Morehead State Eagles</option>
<option value="morgan_state_golden_bears_team_stats">Morgan State Golden Bears</option>
<option value="murray_state_racers_team_stats">Murray State Racers</option>
<option value="navy_midshipmen_team_stats">Navy Midshipmen</option>
<option value="nebraska_cornhuskers_team_stats">Nebraska Cornhuskers</option>
<option value="nevada_wolf_pack_team_stats">Nevada Wolf Pack</option>
<option value="new_hampshire_wildcats_team_stats">New Hampshire Wildcats</option>
<option value="new_mexico_lobos_team_stats">New Mexico Lobos</option>
<option value="new_mexico_state_aggies_team_stats">New Mexico State Aggies</option>
<option value="new_orleans_privateers_team_stats">New Orleans Privateers</option>
<option value="niagara_purple_eagles_team_stats">Niagara Purple Eagles</option>
<option value="nicholls_state_colonels_team_stats">Nicholls State Colonels</option>
<option value="njit_highlanders_team_stats">NJIT Highlanders</option>
<option value="norfolk_state_spartans_team_stats">Norfolk State Spartans</option>
<option value="north_carolina_asheville_bulldogs_team_stats">North Carolina Asheville Bulldogs</option>
<option value="north_carolina_at_aggies_team_stats">North Carolina A&T Aggies</option>
<option value="north_carolina_greensboro_spartans_team_stats">North Carolina Greensboro Spartans</option>
<option value="north_carolina_state_wolfpack_team_stats">North Carolina State Wolfpack</option>
<option value="north_carolina_tar_heels_team_stats">North Carolina Tar Heels</option>
<option value="north_carolina_wilmington_seahawks_team_stats">North Carolina Wilmington Seahawks</option>
<option value="north_dakota_state_bison_team_stats">North Dakota State Bison</option>
<option value="north_florida_ospreys_team_stats">North Florida Ospreys</option>
<option value="north_texas_mean_green_team_stats">North Texas Mean Green</option>
<option value="northeastern_huskies_team_stats">Northeastern Huskies</option>
<option value="northern_arizona_lumberjacks_team_stats">Northern Arizona Lumberjacks</option>
<option value="northern_colorado_bears_team_stats">Northern Colorado Bears</option>
<option value="northern_illinois_huskies_team_stats">Northern Illinois Huskies</option>
<option value="northern_iowa_panthers_team_stats">Northern Iowa Panthers</option>
<option value="northwestern_state_demons_team_stats">Northwestern State Demons</option>
<option value="northwestern_wildcats_team_stats">Northwestern Wildcats</option>
<option value="notre_dame_fighting_irish_team_stats">Notre Dame Fighting Irish</option>
<option value="oakland_golden_grizzlies_team_stats">Oakland Golden Grizzlies</option>
<option value="ohio_bobcats_team_stats">Ohio Bobcats</option>
<option value="ohio_state_buckeyes_team_stats">Ohio State Buckeyes</option>
<option value="oklahoma_sooners_team_stats">Oklahoma Sooners</option>
<option value="oklahoma_state_cowboys_team_stats">Oklahoma State Cowboys</option>
<option value="old_dominion_monarchs_team_stats">Old Dominion Monarchs</option>
<option value="oral_roberts_golden_eagles_team_stats">Oral Roberts Golden Eagles</option>
<option value="oregon_ducks_team_stats">Oregon Ducks</option>
<option value="oregon_state_beavers_team_stats">Oregon State Beavers</option>
<option value="pacific_tigers_team_stats">Pacific Tigers</option>
<option value="penn_state_nittany_lions_team_stats">Penn State Nittany Lions</option>
<option value="pennsylvania_quakers_team_stats">Pennsylvania Quakers</option>
<option value="pepperdine_waves_team_stats">Pepperdine Waves</option>
<option value="pittsburgh_panthers_team_stats">Pittsburgh Panthers</option>
<option value="portland_pilots_team_stats">Portland Pilots</option>
<option value="portland_state_vikings_team_stats">Portland State Vikings</option>
<option value="prairie_view_am_panthers_team_stats">Prairie View A&M Panthers</option>
<option value="princeton_tigers_team_stats">Princeton Tigers</option>
<option value="providence_friars_team_stats">Providence Friars</option>
<option value="purdue_boilermakers_team_stats">Purdue Boilermakers</option>
<option value="radford_highlanders_team_stats">Radford Highlanders</option>
<option value="rhode_island_rams_team_stats">Rhode Island Rams</option>
<option value="rice_owls_team_stats">Rice Owls</option>
<option value="richmond_spiders_team_stats">Richmond Spiders</option>
<option value="rider_broncs_team_stats">Rider Broncs</option>
<option value="rutgers_scarlet_knights_team_stats">Rutgers Scarlet Knights</option>
<option value="sacramento_state_hornets_team_stats">Sacramento State Hornets</option>
<option value="saint_louis_billikens_team_stats">Saint Louis Billikens</option>
<option value="saint_marys_gaels_team_stats">Saint Mary's Gaels</option>
<option value="sam_houston_state_bearkats_team_stats">Sam Houston State Bearkats</option>
<option value="samford_bulldogs_team_stats">Samford Bulldogs</option>
<option value="san_diego_state_aztecs_team_stats">San Diego State Aztecs</option>
<option value="san_diego_toreros_team_stats">San Diego Toreros</option>
<option value="san_francisco_dons_team_stats">San Francisco Dons</option>
<option value="san_jose_state_spartans_team_stats">San Jose State Spartans</option>
<option value="santa_clara_broncos_team_stats">Santa Clara Broncos</option>
<option value="savannah_state_tigers_team_stats">Savannah State Tigers</option>
<option value="seton_hall_pirates_team_stats">Seton Hall Pirates</option>
<option value="siena_saints_team_stats">Siena Saints</option>
<option value="south_alabama_jaguars_team_stats">South Alabama Jaguars</option>
<option value="south_carolina_gamecocks_team_stats">South Carolina Gamecocks</option>
<option value="south_carolina_state_bulldogs_team_stats">South Carolina State Bulldogs</option>
<option value="south_carolina_upstate_spartans_team_stats">South Carolina Upstate Spartans</option>
<option value="south_dakota_state_jackrabbits_team_stats">South Dakota State Jackrabbits</option>
<option value="south_florida_bulls_team_stats">South Florida Bulls</option>
<option value="southeast_missouri_state_redhawks_team_stats">Southeast Missouri State Redhawks</option>
<option value="southeastern_louisiana_lions_team_stats">Southeastern Louisiana Lions</option>
<option value="southern_illinois_salukis_team_stats">Southern Illinois Salukis</option>
<option value="southern_methodist_mustangs_team_stats">Southern Methodist Mustangs</option>
<option value="southern_miss_golden_eagles_team_stats">Southern Miss Golden Eagles</option>
<option value="southern_university_jaguars_team_stats">Southern University Jaguars</option>
<option value="southern_utah_thunderbirds_team_stats">Southern Utah Thunderbirds</option>
<option value="st_bonaventure_bonnies_team_stats">St. Bonaventure Bonnies</option>
<option value="st_johns_red_storm_team_stats">St. John's Red Storm</option>
<option value="st_josephs_pa_hawks_team_stats">St. Joseph's (PA) Hawks</option>
<option value="st_peters_peacocks_team_stats">St. Peter's Peacocks</option>
<option value="stanford_cardinal_team_stats">Stanford Cardinal</option>
<option value="stephen_f_austin_lumberjacks_team_stats">Stephen F. Austin Lumberjacks</option>
<option value="stetson_hatters_team_stats">Stetson Hatters</option>
<option value="stony_brook_seawolves_team_stats">Stony Brook Seawolves</option>
<option value="syracuse_orange_team_stats">Syracuse Orange</option>
<option value="tcu_horned_frogs_team_stats">TCU Horned Frogs</option>
<option value="temple_owls_team_stats">Temple Owls</option>
<option value="tennessee_martin_skyhawks_team_stats">Tennessee-Martin Skyhawks</option>
<option value="tennessee_state_tigers_team_stats">Tennessee State Tigers</option>
<option value="tennessee_tech_golden_eagles_team_stats">Tennessee Tech Golden Eagles</option>
<option value="tennessee_volunteers_team_stats">Tennessee Volunteers</option>
<option value="texas_am_aggies_team_stats">Texas A&M Aggies</option>
<option value="texas_am_corpus_christi_islanders_team_stats">Texas A&M-Corpus Christi Islanders</option>
<option value="texas_arlington_mavericks_team_stats">Texas-Arlington Mavericks</option>
<option value="texas_longhorns_team_stats">Texas Longhorns</option>
<option value="texas_pan_american_broncs_team_stats">Texas-Pan American Broncs</option>
<option value="texas_southern_tigers_team_stats">Texas Southern Tigers</option>
<option value="texas_state_bobcats_team_stats">Texas State Bobcats</option>
<option value="texas_tech_red_raiders_team_stats">Texas Tech Red Raiders</option>
<option value="toledo_rockets_team_stats">Toledo Rockets</option>
<option value="towson_tigers_team_stats">Towson Tigers</option>
<option value="troy_trojans_team_stats">Troy Trojans</option>
<option value="tulane_green_wave_team_stats">Tulane Green Wave</option>
<option value="tulsa_golden_hurricane_team_stats">Tulsa Golden Hurricane</option>
<option value="uab_blazers_team_stats">UAB Blazers</option>
<option value="uc_davis_aggies_team_stats">UC Davis Aggies</option>
<option value="uc_irvine_anteaters_team_stats">UC-Irvine Anteaters</option>
<option value="uc_santa_barbara_gauchos_team_stats">UC-Santa Barbara Gauchos</option>
<option value="ucf_knights_team_stats">UCF Knights</option>
<option value="ucla_bruins_team_stats">UCLA Bruins</option>
<option value="uic_flames_team_stats">UIC Flames</option>
<option value="umkc_kangaroos_team_stats">UMKC Kangaroos</option>
<option value="unlv_runnin_rebels_team_stats">UNLV Runnin' Rebels</option>
<option value="usc_trojans_team_stats">USC Trojans</option>
<option value="utah_runnin_utes_team_stats">Utah Runnin' Utes</option>
<option value="utah_state_aggies_team_stats">Utah State Aggies</option>
<option value="utah_valley_university_wolverines_team_stats">Utah Valley University Wolverines</option>
<option value="utep_miners_team_stats">UTEP Miners</option>
<option value="utsa_roadrunners_team_stats">UTSA Roadrunners</option>
<option value="valparaiso_crusaders_team_stats">Valparaiso Crusaders</option>
<option value="vanderbilt_commodores_team_stats">Vanderbilt Commodores</option>
<option value="vermont_catamounts_team_stats">Vermont Catamounts</option>
<option value="villanova_wildcats_team_stats">Villanova Wildcats</option>
<option value="virginia_cavaliers_team_stats">Virginia Cavaliers</option>
<option value="virginia_commonwealth_rams_team_stats">Virginia Commonwealth Rams</option>
<option value="virginia_military_keydets_team_stats">Virginia Military Keydets</option>
<option value="virginia_tech_hokies_team_stats">Virginia Tech Hokies</option>
<option value="wake_forest_demon_deacons_team_stats">Wake Forest Demon Deacons</option>
<option value="washington_huskies_team_stats">Washington Huskies</option>
<option value="washington_state_cougars_team_stats">Washington State Cougars</option>
<option value="weber_state_wildcats_team_stats">Weber State Wildcats</option>
<option value="west_virginia_mountaineers_team_stats">West Virginia Mountaineers</option>
<option value="western_carolina_catamounts_team_stats">Western Carolina Catamounts</option>
<option value="western_illinois_leathernecks_team_stats">Western Illinois Leathernecks</option>
<option value="western_kentucky_hilltoppers_team_stats">Western Kentucky Hilltoppers</option>
<option value="western_michigan_broncos_team_stats">Western Michigan Broncos</option>
<option value="wichita_state_shockers_team_stats">Wichita State Shockers</option>
<option value="william__mary_tribe_team_stats">William & Mary Tribe</option>
<option value="winston_salem_state_rams_team_stats">Winston-Salem State Rams</option>
<option value="winthrop_eagles_team_stats">Winthrop Eagles</option>
<option value="wisconsin_badgers_team_stats">Wisconsin Badgers</option>
<option value="wofford_terriers_team_stats">Wofford Terriers</option>
<option value="wright_state_raiders_team_stats">Wright State Raiders</option>
<option value="wyoming_cowboys_team_stats">Wyoming Cowboys</option>
<option value="xavier_musketeers_team_stats">Xavier Musketeers</option>
<option value="yale_bulldogs_team_stats">Yale Bulldogs</option>
<option value="youngstown_state_penguins_team_stats">Youngstown State Penguins</option>
</select>
 
     <br />
     <small>Select Your Team from the Drop-Down Menu Above, then Click "Update"</small>
   <input type="hidden" name="action" value="update" />
   <input type="hidden" name="page_options" value="cbb_d1al_stats_team" />
  
   <p>
   <input type="submit" value="<?php _e('Save Changes') ?>" />
   </p>
  
   </form>
<!-- End Team Select -->
<!-- Start Color Select -->
Manage Your Scroller's Colors Below
Select Scrolling Text Color from Web Safe Palette (Default color is Black: #000000): 
            <br />
            <strong>Color Sample:</strong>
            <br />
            <input type="text" class="textbox" style="background:<?php echo get_option('cbb_d1al_stats_color'); ?>;" />
            <br />
            <small>*If White (#FFFFFF) is chosen, it will not appear on this page, since the page is already white</small>

<form name="tcp_test" method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
	<input type="Text" name="cbb_d1al_stats_color" id="cbb_d1al_stats_color" value="<?php echo get_option('cbb_d1al_stats_color'); ?>" />

			<a href="javascript:TCP.popup(document.forms['tcp_test'].elements['cbb_d1al_stats_color'])"><img width="15" height="13" border="0" alt="Click Here Pick A Color" src="<?php echo '../wp-content/plugins/ncaab-team-stats-lite/cpiksel.gif'; ?>" /></a>
      <br />
      <input type="hidden" name="action" value="update" />
   <input type="hidden" name="page_options" value="cbb_d1al_stats_color" />
  
   <p>
   <input type="submit" value="<?php _e('Save Changes') ?>" />
      <input name="defaultfontcolor" type="hidden" value="#000000" />
<input type="button" value="Default Color" onClick="document.tcp_test.cbb_d1al_stats_color.value=document.tcp_test.defaultfontcolor.value">
   </p>
  
   </form>
<!-- end color select -->  
<!-- Start Advanced Plugins List -->
  <h2>If You Want MORE Stats and Information:</h2>
  <p>A93D Offers FREE upgrades for this stats package, that allow you to display advanced and more complete NCAAB team stats.
  <h5>Step 1. <?php _e('Use the link below to upgrade to our FREE advanced NCAAB stats package') ?></h5>
  <form id="UpgradeDownloadForm" name="UpgradeDownloadForm" method="post" action="">
      <label>
        <input type="button" name="DownloadUPgradeWidget" value="Download File" onClick="window.open('http://www.ibet.ws/download/cbbd1a-team-stats.zip', 'Download'); return false;">
      </label>
    <br />
    <a href="http://www.ibet.ws/download/cbbd1a-team-stats.zip" title="Click Here to Download or use the Button" target="_blank"><strong>Click Here</strong> to Download if Button Does Not Function</a>
  </form>
  	<h5>Step 2. <?php _e('Now Locate The File You Just Downloaded and Upload Here. It will install automatically.') ?></h5>
	<p class="install-help"><?php _e('Find the .zip file from the step above on your computer, then click the "Install Now" button.') ?></p>
	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('update.php?action=upload-plugin') ?>">
		<?php wp_nonce_field( 'plugin-upload') ?>
		<label class="screen-reader-text" for="pluginzip"><?php _e('Plugin zip file'); ?></label>
		<input type="file" id="pluginzip" name="pluginzip" />
		<input type="submit" class="button" value="<?php esc_attr_e('Install Now') ?>" />
	</form>

  
  <h2>Other FREE Sports Stats and Information Plugins:</h2>
  <p>Download and install in seconds using the Wordpress 3.0 Plugin Installer. You Can also auto-install by downloading any of the plugins below, and then uploading using our form above. Just make sure to select the correct downloaded .zip file on your computer!</p>
  <p><strong>Football</strong><br />
    <strong>NFL Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NFL Team, plus optional news scroller<br />
    <strong>NFL News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NFL Headlines<br />
  <strong>NFL Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 32 NFL Teams</p>
  <p><strong>NCAAF D1A Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1a-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1A Football Team<br />
    <strong>NCAAF D1AA Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1aa-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1AA Football Team <br />
    <strong>NCAAF News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/ncaaf-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NCAAF Headlines<br />
    <strong>NCAAF D1 Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - 
  Top 25 College Football Teams Updated Weekly</p>
  <p><strong>Basketball</strong><br />
    <strong>NBA Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NBA Team, plus optional news scroller<br />
    <strong>NBA News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NBA Headlines<br />
    <strong>NBA Power rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 30 NBA Teams</p>
  <p><strong>NCAAB D1 Team Stats </strong><a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cbbd1a-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1A Basketball Team<br />
    <strong>NCAAB D1 News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/ncaab-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NCAAB Headlines <br />
    <strong>NCAAB D1
  Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cbb-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 25 College Basketball Teams Updated Weekly</p>
  <p><strong>NASCAR</strong><br />
  <strong>NASCAR Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nascar-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NASCAR Drivers Updated Weekly</p>
<p><strong>Hockey</strong><br />
  <strong>NHL Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NHL Team, plus optional news scroller<br />
    <strong>NHL News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NHL Headlines<br />
    <strong>NHL Power Rankings</strong> 
    <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 30 Teams</p>
<p><small><strong>WordPress Versions 2.9+ Directions</strong> - Click the link of the stats package you would like to install. The link will open a download window that will save the plugin's .zip file to your computer. Next, go to your &quot;Add Plugins&quot; page in the WordPress admin control panel (the link is found in the Plugins sub-menu). Click the &quot;Upload&quot; link and select the .zip file of the new plugin on your computer. Finally, click &quot;Install Now&quot;, and WordPress will automatically upload and install the plugin to your blog. Visit the Plugin settings page to make adjustments.</small><br />
  <br />
  <small><strong>Directions for Older Versions / Manual Installation </strong>- Click the link of the stats package you would like to install. The link will open a download window that will save the plugin's zip file to your computer. Next, unzip the plugin's files on your computer. Finally, upload the unzipped folder and its contents to your WordPress plugins directory by FTP. Activate the plugin from your WordPress control panel. Visit the Plugin settings page to make adjustments.</small></p>
<!-- End Advanced Plugins List -->  

</div>
   <?php
   }
function cbb_d1al_stats()
{
$theteam = get_option('cbb_d1al_stats_team');
$textcolor = preg_replace('/#/', '', get_option('cbb_d1al_stats_color'));

$mydisplay = "http://www.ibet.ws/cbbd1a_stats_magpie_lite/int0-8-2/cbb_d1a_stats_magpie_ads.php?team=$theteam&textcolor=$textcolor";
// This is the Magpie Basic Command for Fetching the Stats URL
$url = $mydisplay;
$rss = cbb_d1al_fetch_rss( $url );
// Now to break the feed down into each item part
foreach ($rss->items as $item) 
		{
		// These are the individual feed elements per item
		$title = $item['title'];
		$description = $item['description'];
		// Assign Variables to Feed Results
		if ($title == 'adform')
			{
			$adform = $description;
			}
		}

echo $adform;
}
?>