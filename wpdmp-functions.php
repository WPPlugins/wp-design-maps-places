<?php 
/*
 * Print Marker Manager Backend
 */

if ( !function_exists('wpdmp_print_marker_manager_view') ):
		function wpdmp_print_marker_manager_view()  {?>
		
			<div id="newaddress" class="wrap" >
				<input type='hidden' id='refpointimg' value='<?php echo WPDMP_PLUGIN_URL . 'images/refpoint.gif';?>'/>
            	<!--div id="col-container"-->
            		<?php

            		   $maps = wpdmp_get_maps();
            			// load first found map as default
            			if ($mapid == ''){            			   
            			   $map = $maps[0];
            			   $mapid = $maps[0]['id'];
            			}else{
            			   $map = wpdmp_get_map($mapid);            			   
            			}
            			$map_name = $map['map'];
            			$map_path = $map['map'];//print_map_url($map_name);
            			?>
            				<h1><?php _e('Preview map', 'wp-design-maps-and-places');?></h1>
            
            				<div class="form-field" style="background-color: #EFEFEF;padding: 10px;margin-bottom:10px;">
            					<label for="mappath"><?php _e('Map file:','wp-design-maps-and-places')?></label> 
            					<select size="1" name="mappath" id="mappath">
            						<?php
            						foreach ($maps as $m){ ?>
            						<option value="<?php echo $m['id']; ?>"
            						<?php if ($m['id']==$map['id']){echo ' selected="selected"';} ?>>
            							<?php echo $m['map']; ?>
            						</option>
            						<?php }?>
            					</select>
            					<input type='button' tabindex='2' value='<?php _e(' (RE)LOAD MAP ', 'wp-design-maps-and-places') ?>' id='loadmap' name='loadmap'            						    
                					onclick="reload_map(jQuery('#mappath option:selected').val(),'map',true,'backend_marker_manager');"        						
            						class='button-primary' style='width:150px;'/> <!-- onclick="reload_map($jq('#mapimage').attr('mapid'),'map',false,'backend_marker_manager');" -->
            					<script type="text/javascript">
            						//$jq('#loadmap').click(function(){reload_site_list(jQuery('#mapimage').attr('mapid'));});
            						$jq('#loadmap').click(function(){reload_map(jQuery('#mappath option:selected').attr('value'),'map',true,'backend_marker_manager');});
      							</script>
            				</div>            			
            				
            		<div id="col-right">
            				<!-- php wpdmp_print_address_find_form();? -->
            			<div id="map">            				
            			</div>
            		</div>									
				
         			<div id="col-left" style="width:33%">
         			</div>
				<!--/div-->
			</div>
<?php }
endif;

if ( !function_exists('wpdmp_print_map_manager') ):
function wpdmp_print_map_manager($mapid, $mode){
	
		if ($mode=="backend_map_manager_google"){
			wpdmp_print_google_calibrator($mapid); 
		}else{
		
			wpdmp_print_address_find_form();?>

	  	<div id="map" style="display:hidden;"></div>		
		<script type="text/javascript">
			<?php if($mapid != null) { ?>							
      			reload_map(<?php echo $mapid; ?>,'map',true,'backend_map_manager');
      		<?php } ?>
      		$jq('body').on('click','.new_map_button', function( event ){selectMapFile(event);});
		</script>
		<div id="dialog-confirm" title='<?php _e('Remove map including ALL markers?','wp-design-maps-and-places'); ?>' style="width: 100%;">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php _e('The map and all its markers will be permanently deleted and cannot be recovered. Are you sure?','wp-design-maps-and-places'); ?></p>
		</div>
		
<?php } 		
}
endif;


if ( !function_exists('wpdmp_get_ref_points_help') ):
function wpdmp_get_ref_points_help($mapid){

	$map_type = wpdmp_get_map_type($mapid);
	if ($map_type === "freehand"){
		$class = "green";
		$text = __('Freehand Map. You can define your Places by clicking on the image in the "Maps &amp; Places"','wp-design-maps-and-places');		

	}else{

		$points = count(wpdmp_get_ref_points($mapid));
		
		if ($points==2){
			$class = "green";
			$text = __("Two reference points are defined. You can remove them and redefine them or go ahead and define your Places",'wp-design-maps-and-places');
			$btn = "";
		}else{
		   $class = "red";
		  // "The Map is not calibrated. Calibrate the Map either via Google Map or via Reference Points"
		   $text = sprintf(__('%d reference point(s) defined. You need to define two reference points. Click on the map at the point you know (a city you know). Tipp: take a point on a border or river.' .
		   	"<br/>Alternatively you can define the Map as <Strong>'Free Hand Map'</Strong> where you can place Markers without coordinates, just by clicking on the map.",'wp-design-maps-and-places'),$points);
		   $btn_value = __(' USE THE IMAGE WITHOUT GEO LOCATION ','wp-design-maps-and-places');
		   $btn = sprintf('<p><input id="freehandmap" class="button-primary" type="button" name="freehandmap" value="%s" onclick="mark_free_hand_map(%d);"/><p/>',$btn_value,$mapid);
		}
	}
		?>
		<p id="ref-help" class="<?php echo $class;?>"><?php echo $text;?>	
		</p>
		<?php echo $btn;
}
endif;

if ( !function_exists('wpdmp_print_maps_available') ):
function wpdmp_print_maps_available($maps){
	
	?>
	<div style="float:left;"><h1><?php _e('Maps available','wp-design-maps-and-places'); ?></h1></div>
	<div style="float:right;margin-top:14px;">
		<input type='button' class='new_map_button button-primary' tabindex='1' value='<?php _e(' ADD NEW MAP ','wp-design-maps-and-places'); ?>' id='laddmap' name='addmap' />
		<input type='hidden' id='curmapid'/>
	</div>
	<div style="clear:both;"></div>   		
	<?php
	foreach ($maps as $map){

		$map_name = $map['map'];
        $map_path = $map['map'];//print_map_url($map_name);
		?>
	<fieldset class="wpdmp-fieldset">
        <img id="map_<?php echo $map['id']; ?>" mapid="<?php echo $map['id']; ?>"
         	style="float: left;" width="100" src="<?php echo $map_path; ?>"/>
		<table id="mapinfo_<?php echo $map['id']; ?>">
        	<tr>
         		<td><?php _e('File:','wp-design-maps-and-places'); ?></td>
         		<td><?php echo $map_name;?></td>
         	</tr>
         	<tr>
         		<td><?php _e('Markers:','wp-design-maps-and-places'); ?></td>
         		<td><div class="marker_container"><?php 
         			foreach ($map['markers'] as $mr){?>
         				<img id="map_marker_<?php echo $mr['id']; ?>" attid="<?php echo $mr['attid']; ?>" class="markers_list" mapid="<?php echo $map['id']; ?>"
         					src="<?php echo $mr['markerimg']; ?>" title="<?php echo pathinfo($mr['markerimg'], PATHINFO_FILENAME);?>"/>
         			<?php }
         			?></div><a href="" id="add_remove_marker_<?php echo $map['id']; ?>" class="add_remove_markers"><?php _e('Add / Remove Markers','wp-design-maps-and-places'); ?></a></td>
         	</tr>
         	<tr>
         		<td><?php _e('Status:','wp-design-maps-and-places'); ?></td>         		
         		<td><div id="mapstatus-<?php echo $map['id'];?>"><?php echo wpdmp_get_map_status($map['id']);?></div>     
         		</td>
         	</tr>
         	<tr>
         		<td><?php _e('Sites:','wp-design-maps-and-places'); ?></td>
         		<td><?php echo count(wpdmp_get_map_places($map['id'])); ?></td>
         	</tr>
         	<tr>
         		<td><?php _e('Short code:','wp-design-maps-and-places'); ?></td>
         		<td><?php echo "[wpdmp-map id='" . $map['id'] . "' lang='your lang']"; ?></td>
         	</tr>
         	<tr>
         		<td><?php _e('Popup offset:','wp-design-maps-and-places'); ?></td>
         		<td>X:<input id="xoffset_<?php echo $map['id'];?>" mapid="<?php echo $map['id'];?>" class="wpdmp-setting-input wpdmp-map-offset" type="text" size="4" value="<?php echo $map['popupoffsetx'];?>">px&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         			Y:<input id="yoffset_<?php echo $map['id'];?>" mapid="<?php echo $map['id'];?>" class="wpdmp-setting-input wpdmp-map-offset" type="text" size="4" value="<?php echo $map['popupoffsety'];?>">px&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         			<input type='button'
         					onclick="save_popup_offset(<?php echo $map['id']; ?>,$jq('#xoffset_<?php echo $map['id'];?>').val(),$jq('#yoffset_<?php echo $map['id'];?>').val());"
         					class='button-primary' value='<?php _e(' SAVE ','wp-design-maps-and-places'); ?>' style='display:none;'
         					id='save_popup_offset_<?php echo $map['id'];?>' name='save_popup_offset_<?php echo $map['id'];?>' />
         		</td>
         	</tr>
         	<tr>
         		<td><?php _e('Popup location','wp-design-maps-and-places'); ?>:</td>
         		<td>
         			<select name="popup_loc" id="popup_loc_<?php echo $map['id'];?>" mapid="<?php echo $map['id'];?>">
         				<option value="0" <?php if($map['popuplocation']=='0'){?>selected="selected"<?php }?>><?php _e('Can be outside of the map','wp-design-maps-and-places'); ?></option>
         				<option value="1" <?php if($map['popuplocation']=='1'){?>selected="selected"<?php }?>><?php _e('Keep inside the map','wp-design-maps-and-places'); ?></option>         				
         			</select>
         			<input type='button'
         					onclick="save_popup_location(<?php echo $map['id']; ?>,$jq('#popup_loc_<?php echo $map['id'];?>').val());"
         					class='button-primary' value='<?php _e(' SAVE ','wp-design-maps-and-places'); ?>' style='display:none;'
         					id='save_popup_location_<?php echo $map['id'];?>' name='save_popup_location_<?php echo $map['id'];?>' />
         		</td>         		
         	</tr>
         	<?php do_action('wpdmp_add_map_custom_setting', $map);?>
         	<tr>
         		<td></td>
         		<td align="right">
         				<input type='button'
         					onclick="delete_map_dialog(<?php echo $map['id']; ?>);"
         					class='button-primary' value='<?php _e(' DELETE MAP ','wp-design-maps-and-places'); ?>'
         					id='deletemap' name='deletemap' />
         				<input type='button'
         					onclick="reload_map(<?php echo $map['id']; ?>,'map',true,'backend_map_manager');"
         					class='button-primary' value='<?php _e(' VIEW MAP ','wp-design-maps-and-places'); ?>'
         					id='loadmap' name='loadmap' />         			
         		</td>
         	</tr>
        </table>
	</fieldset>
	<script type="text/javascript">								
    	$jq('body').on('click','#add_remove_marker_<?php echo $map['id']; ?>', function( event ){selectMarkersFiles(event,<?php echo $map['id'];?>);});
	</script>
	<?php	
	}
	do_action( 'wpdmp_add_map_onchange_handler_pro' );
}
endif;

if ( !function_exists('wpdmp_get_map_status') ):
   function wpdmp_get_map_status($mapid)  {
		
		$map_type = wpdmp_get_map_type($mapid);
		if ($map_type == "freehand"){?>
			<span class="green"><?php  _e('freehand','wp-design-maps-and-places');?></span>
		<?php
		}else{
			$ref_points = wpdmp_get_ref_points($mapid);
			if (count($ref_points)==2){?>
				<span class="green"><?php _e('2 reference points are defined','wp-design-maps-and-places'); ?></span>
			<?php }else if (count($ref_points)==1){?>
				<span class="red"><?php _e('1 reference point is missing','wp-design-maps-and-places'); ?></span> 	  
			<?php }else{?>
				<span class="red"><?php _e('both of reference points are missing','wp-design-maps-and-places'); ?></span>
			<?php }
			if($map['type']!=='freehand'){?>
				<br/><a href="#" onclick="reload_map(<?php echo $mapid; ?>,'map',true,'backend_map_manager_google');"><?php _e('Calibrate via Google Map','wp-design-maps-and-places'); ?></a><br/>
				<!--a href="admin.php?page=wpdmp_map_manager&ui=refpoints&mapid=<?php echo $map['id'];?>" onclick="">Calibrate manually</a-->
         	<?php }
		}
   }
endif;

if ( !function_exists('wpdmp_get_map_status_callback') ):
   function wpdmp_get_map_status_callback()  {
      
      $mapid = $_POST['mapid'];      	
      ob_start();      
      wpdmp_get_map_status($mapid);      
      $content = ob_get_contents();
      ob_end_clean();
      echo $content;
      die();
      exit;
      
   }
endif;
 
if ( !function_exists('wpdmp_print_address_find_form') ):
		function wpdmp_print_address_find_form($dialog_id = 'modalCoordDialog', $hidden = true, $map = array())  {?>
	  <input type="hidden" name="started" id="started"/>
	  <input type="hidden" name="refX" id="refX" />
      <input type="hidden" name="refY" id="refY" />
      <input type="hidden" name="coordsFound" id="coordsFound" />
      <div id="<?php echo $dialog_id;?>" <?php if ($hidden) echo "style='display:none;'";?>>
      	
      	<div class="form-field">
      		<label for="f_address"><?php _e('Enter address of location (e.g. "Germany Munich"):','wp-design-maps-and-places')?></label> 
      		<input type="text" maxlength="2048"
      				tabindex="1" value="" name="f_address" id="f_address"
      				title="Address" class="" autocomplete="off" autocorrect="off" />
      	</div>      		
      	<?php 
      		$but_disabled = false;
      		if($dialog_id=="modalCoordDialog"){
      			$ref_mode = true;
      		}else{
      			$ref_mode = false;
      			if (sizeof($map['markers'])==0){
      				$but_disabled = true;
      			}
      		}
      	?>
      	<input type='button' class="button-primary"
      				onclick='getCoords("<?php if($ref_mode){echo ".ui-dialog ";}?>#f_address","<?php if($ref_mode){echo ".ui-dialog ";}?>#f_latitude","<?php if($dialog_id=="modalCoordDialog"){echo ".ui-dialog ";}?>#f_longitude",<?php if($ref_mode){echo "false";}else {echo "true";}?>);'
      				tabindex='2' value='<?php _e(' Get coordinates ','wp-design-maps-and-places');?>' id='getcoords' name='getcoords' <?php if ($but_disabled){echo "disabled='1'";} ?>/>
      	<?php if ($but_disabled){?>
      		<span class="red"><?php _e("No Markers defined for the Map. Please define first (in 'Map Manager').",'wp-design-maps-and-places'); ?></span>
      	<?php }?>      		
      	<div class="form-field">
      		<label for="f_latitude"><?php _e('Latitude:','wp-design-maps-and-places') ?></label> <input type="text"
      				maxlength="20" tabindex="3" value="" name="f_latitude" readonly="readonly"
      				id="f_latitude" title="Latitude" class="" autocomplete="off"
      				autocorrect="off" />
      	</div>
      	<div class="form-field">
      		<label for="f_longitude"><?php _e('Longitude:','wp-design-maps-and-places') ?></label> <input type="text"
      				maxlength="20" tabindex="4" value="" name="f_longitude" readonly="readonly"
      				id="f_longitude" title="Longitude" class="" autocomplete="off"
      				autocorrect="off" />
      	</div>
      	
      </div>
<?php }
endif;

if ( !function_exists('wpdmp_print_site_list') ):
function wpdmp_print_site_list($map)  {?>

	<form action='' name='newmarkerform' id='newmarkerform' method='post'>
		<input type="hidden" name="f_id" id="f_id" />
		<input type="hidden" name="freeX" id="freeX" />
      	<input type="hidden" name="freeY" id="freeY" />
		<h1><?php _e("Map new place",'wp-design-maps-and-places') ?></h1>
		
		<?php if ($map['type']==='freehand'){?>
			<div class="form-field">
      			<label><?php _e('Click on the map to create a new place.','wp-design-maps-and-places'); ?></label>      			
      		</div>	
		<?php }else{ 
				wpdmp_print_address_find_form('nonModal', false, $map); 
		}?>
	
		<div id="markerdesc">
		<?php if ($map['type']==='freehand'){?>
			<div class="form-field">
				<label for="f_address"><?php echo __("Name");?></label>
				<input type="text" name="f_address" id="f_address"/>
			</div>
		<?php } ?>
			<div class="form-field">
				<label for="f_text"><?php _e('Popup description, use HTML to format, e.g. &lt;br/&gt; for newline:','wp-design-maps-and-places') ?></label>
				<?php
				$descr = array();
				wpdmp_marker_tabs ('new',$descr);
				wpdmp_marker_flags($map);
				?>
			</div>
	
	
		</div>
		<p class="submit">
			<input type="button" onclick="add_marker();" class="button-primary" <?php if (sizeof($map['markers'])==0){echo "disabled='true'";}?>
				tabindex="7" value='<?php _e(' ADD MARKER ','wp-design-maps-and-places'); ?>' id="addmarker" name="addmarker" />
			<!-- onclick="add_marker(jQuery('#mapimage').attr('mapid'), jQuery('#f_address').val(),jQuery('#f_latitude').val(),jQuery('#f_longitude').val(),jQuery('#f_text').val(),'en',jQuery('input[name=marker]:checked').val());" -->
			<input type="button"
				onclick="save_marker(jQuery('#mapimage').attr('mapid'), jQuery('#f_id').val(),jQuery('#f_address').val(),jQuery('#f_latitude').val(),jQuery('#f_longitude').val(),jQuery('#f_text').val(),jQuery('#f_text_en').val(),jQuery('input[name=marker]:checked').val());"
				class="button-primary" tabindex="7" value='<?php _e(' SAVE MARKER ','wp-design-maps-and-places'); ?>'
				id="savemarker" name="savemarker" />
		</p>
	</form>
	<div id="markerslist">
		<!-- ?php wpdmp_print_site_list($mapid); ? -->
		<h1><?php _e('Places list','wp-design-maps-and-places'); ?></h1>
		<?php
		$markers = wpdmp_get_map_places($map['id']);
		if ($markers){
			foreach ($markers as $marker){
				wpdmp_marker_info($marker);
			}
		}
		do_action('wpmdp_list_visitor_locations',$map['id']);
		?>
		<script type="text/javascript">
	           jQuery(document).ready(function(){$jq('.markerprint').each(function(index){if (index % 2 == 0){$jq(this).addClass('gray');}})});
	      </script>
	</div>
	<div id="progressbar" style="background: url(<?php echo WPDMP_PLUGIN_URL . 'images/spin.gif';?>) center center no-repeat #fff"/></div>

<?php }
endif;

if ( !function_exists('wpdmp_print_map_b') ):
	function wpdmp_print_map_b($mapid,$printref,$cur_lang = '',$frontview = false)  {

		$map = wpdmp_get_map($mapid);
		$def_lang = get_option( 'wpdmp_default_lang');
		
		if ($cur_lang == ''){
			$cur_lang = $def_lang;
		}

		$ref_points = wpdmp_get_ref_points($map['id']);
		if (count($ref_points) > 0){
			foreach ($ref_points as $ref){
				$ref_in_attr = $ref_in_attr . '{"address":"' . $ref['address'] .
      	   	'","id":' . $ref['id'].
      	   	',"x":' . $ref['x']. 
      	   	',"y":' . $ref['y']. 
      	   	',"lat":' . $ref['lat']. 
      	   	',"lng":' . $ref['lng']. 
      	   	',"mapwidth":' . $map['mapwidth'].
      	   	',"mapheight":' . $map['mapheight'] . '},';      				     
         }
         $ref_in_attr = substr($ref_in_attr, 0, -1);
      }
      
	if (!$frontview){
      ?>	
	<p><?php _e('Map file: ','wp-design-maps-and-places'); ?><span id="mappath" mapid="<?php echo $map['id'];?>"><?php echo $map['map']; ?></span></p>
	<?php } ?>
	<div id="mapcontainer" <?php echo $frontview?'class="frontmap"':''?>>		
		<img id="mapimage" src='<?php echo $map['map']; ?>' mapid='<?php echo $map['id']; ?>' map='<?php echo $map['map']; ?>' refpoints='<?php echo $ref_in_attr; ?>' maptype='<?php echo $map['type']; ?>' cur_lang='<?php echo $cur_lang;?>' popupoffsetx='<?php echo $map['popupoffsetx']; ?>' popupoffsety='<?php echo $map['popupoffsety']; ?>' popuplocation='<?php echo $map['popuplocation']; ?>'/>
		<div id="mapoverlay" mapid="<?php echo $map['id']; ?>">
				
         <?php
			    
         if ($printref != false){			          
            if ($ref_points != false){
               foreach ($ref_points as $ref_point){      			   
      			   ?>
            		<img id="ref_<?php echo $ref_point['id'];?>" class="ref" x="<?php echo $ref_point['x'];?>" y="<?php echo $ref_point['y'];?>" lat="<?php echo $ref_point['lat'];?>" lng="<?php echo $ref_point['lng'];?>" src="<?php echo WPDMP_PLUGIN_URL . 'images/refpoint.gif';?>" style="display: none;"/>
            		<div class="ref_<?php echo $ref_point['id'];?>_mo mappopupwrap" style="display: none;">
   				      	<div id="#ref_<?php echo $ref_point['id'];?>_mo" class="mappopup"><a href="" onclick="delete_ref_point('<?php echo $ref_point['id'];?>');"><?php _e('Delete reference point','wp-design-maps-and-places'); ?></a><hr/><?php echo $ref_point['address'];?></div>
					</div>				   
	               <?php 
               }
            }
         }
			    
         $markers = wpdmp_get_map_places($map['id']);
         if ($markers != false){
            foreach ($markers as $marker){
            	$descr = $marker['descr'][$cur_lang]->descr;
            	if ( $descr == ''){
            		$descr = $marker['descr'][$def_lang]->descr;
            	}
            	?>
            	
      				
      				<div class="m_<?php echo $marker['id'];?>_mo mappopupwrap" style="display: none;" mrid="<?php echo $marker['id'];?>">
      					<div id="#m_<?php echo $marker['id'];?>_mo" class="mappopup" x="0">
      						<?php 
      						do_action('wpdmp_print_close_icon', $mapid);
      						if($frontview) {
      							do_action('wpdmp_print_description',$descr);
      						} 
      						else {
      							echo $descr;
      						}
      						 ?>
      					</div>
					</div>
					<img id="m_<?php echo $marker['id'];?>" class="ctrl" lat="<?php echo $marker['lat'];?>" mid="<?php echo $marker['id'];?>" lng="<?php echo $marker['lng'];?>" src="<?php echo $marker['markerimg'];?>" style="display: none;"/>				   
				
			<?php 
				do_action( 'wpdmp_add_gotolink_effects', $marker );
            }
         }
         if (get_option('wpdmp_link')=='yes'){?>
            <a id="aw_link" href="http://amazingweb.de/" title="WP Design Maps &amp; Places"><?php _e('Plugin by amazingweb.de','wp-design-maps-and-places'); ?></a>
         <?php }
         ?>
		</div>				
		<div id="mapprogressbar" style="background: url(<?php echo WPDMP_PLUGIN_URL . 'images/spin.gif';?>) center center no-repeat #fff"></div>
		<?php
		if(has_action('wpdmp_add_effects')) {
			do_action('wpdmp_add_effects',$mapid);
		}
		else { ?>
			<script type="text/javascript">
				add_effects();
			</script>
		<?php } ?>
	</div>
	<?php 
}
endif;

if ( !function_exists('wpdmp_print_description') ):
	function wpdmp_print_description($descr)  {
		echo $descr;
	}
endif;
	
/*if ( !function_exists('wpdmp_get_map_files') ):
   function wpdmp_get_map_files()  {
      $markerDir = WPDMP_PLUGIN_DIR . "/images/maps/";
   
      if (is_readable($markerDir)) {
   
         if ($dir = opendir($markerDir)) {
   
            $files = array();
            $ret = array();
   
            while ($files[] = readdir($dir));
            sort($files);
            closedir($dir);
   
            $extensions = array("png", "jpg", "gif", "jpeg");
   
            foreach ($files as $file){
               $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                
               if (!in_array($ext, $extensions)) {
      			   continue;
      			}   
      			$f = array('name'=>$file,'path'=>WPDMP_PLUGIN_URL.'/images/maps/'.$file);
      			array_push($ret, $f);
            }
         }
      }
      return $ret;
   }
endif; */

if ( !function_exists('wpdmp_marker_info') ):
		function wpdmp_marker_info($marker)  {?>
		
		<div class="markerprint" id="markerprint<?php echo $marker['id'];?>">
			<!--?php echo $marker['id'] . '&nbsp;&nbsp; ' ?-->
			<p><img src="<?php echo $marker['markerimg']; ?>" class="markerimage"/>&nbsp;
			<strong id="address_<?php echo $marker['id'];?>" style="overflow: hidden"><?php echo $marker['address'] ?></strong>
			<br/>(Lat: <?php echo $marker['lat'] ?> &nbsp;
			Long: <?php echo $marker['lng'] ?>)
			<span class="dashicons dashicons-arrow-down-alt2" id="slidericon-<?php echo $marker['id'];?>" onclick="expandMarkerInfo(<?php echo $marker['id'];?>)" style='float:right'></span></p>
			<?php do_action( 'wpdmp_print_visitor_details', $marker['id'], $marker['status'] ); ?>
			<div id="markerprint_slider-<?php echo $marker['id'];?>" class="markerprint_slider" style="display: none">
				<?php wpdmp_marker_tabs($marker['id'],$marker['descr']);
				
				$map = wpdmp_get_map($marker['mapid']);
				wpdmp_marker_flags($map, $marker['markerimg'],$marker['id']);
				do_action('wpdmp_print_place_url',$marker['id']);
				do_action( 'wpdmp_print_onclick_link', $marker['id'], $marker['onclick'], $marker['onclick_link'], $marker['onclick_target'] );
				if( has_action( 'wpdmp_print_save_remove_buttons' ) ) {
					do_action( 'wpdmp_print_save_remove_buttons', $marker['id'], $marker['status'] );
				} else { ?>
					<input type='button' class="button-primary"onclick='remove_marker(<?php echo $marker['id']; ?>);' value=' <?php _e('REMOVE','wp-design-maps-and-places');?> ' id='removemarker' name='removemarker' style='float:left'/>
	      			<input type="button" class="savemarker button-primary" onclick="save_marker(<?php echo $marker['id']; ?>);" value= '<?php _e('SAVE','wp-design-maps-and-places'); ?>'  id="savemarker-<?php echo $marker['id']; ?>" name="savemarker-<?php echo $marker['id']; ?>" style='float:right;display: none;' />
				<?php } ?>
	      		<div style="clear:both"></div>
	      	</div>
	      	<?php do_action('wpdmp_print_moderate_submission_buttons',$marker['status'],$marker['id']);?>
		</div>		
<?php }
endif;

if ( !function_exists('wpdmp_marker_tabs') ):
   function wpdmp_marker_tabs($mid, $descr) {
      
      $supported_langs = get_option('wpdmp_langs');?>
           
		<div id="tabs-<?php echo $mid;?>" class="markertabcontainer">
			<ul>
	            <?php foreach ($supported_langs as $sl){?>
				<li><a href="#tabs-<?php echo $mid . "-" . $sl;?>"><?php echo $sl;?></a></li>
               <?php }?>
			</ul>
                <?php foreach ($supported_langs as $sl){?>
                <div id="tabs-<?php echo $mid . "-" . $sl;?>">
                    <textarea cols="35" rows="4" value="" name="f_text-<?php echo $mid . "-" . $sl;?>" id="f_text-<?php echo $mid . "-" . $sl;?>" lang="<?php echo $sl;?>" title="Descr" class="descr_langs-<?php echo $mid ;?>" autocomplete="off" autocorrect="off"><?php echo $descr[$sl]->descr; ?></textarea>
                </div>   
                <?php }?>                                 
		</div>
<?php }
endif;

if ( !function_exists('wpdmp_marker_flags') ):
function wpdmp_marker_flags($map, $current_image = '',$mid = 'new') {?>
<div class="form-field">
	<label><?php _e('Select place marker:','wp-design-maps-and-places'); ?></label>
	<fieldset>
	<?php
		$first = true;
		foreach ($map['markers'] as $mr){?>
	    	<label class="selectmarker"> <img src="<?php echo $mr['markerimg']; ?>" class="markers_list"/><br />
				<input type="radio" name="marker-<?php echo $mid; ?>" tabindex="5" value="<?php echo $mr['id']; ?>" 
					<?php if ($mr['markerimg'] == $current_image || ($mid=='new' && $first)){?>checked=""<?php }?>></input>			
			</label>			
		<?php	
			$first = false;
	    }
	    
	    if ($first){
	    ?>
	    	<span class="red"><?php _e("No Markers defined for the Map. Please define first (in 'Map Manager').",'wp-design-maps-and-places'); ?></span>
	    <?php } ?>
	    
	</fieldset>
</div>
<?php }
endif;

/*
if ( !function_exists('wpdmp_get_new_markerid') ):
	function wpdmp_get_new_markerid($markers) {
	
		if ($markers == false) return 0;
	
		$maxid = 0;
		foreach($markers as $marker){
			$maxid = $marker['id']>$maxid?$marker['id']:$maxid;
		}		
		
		return $maxid+1;
	}
endif;
*/

if ( !function_exists('wpdmp_edit_marker_callback') ):
	function wpdmp_edit_marker_callback() {	
		
		$marker = wpdmp_get_marker($_POST['id']);
		
		if (sizeof($marker)>0){
			echo json_encode($marker);			
		}
				
		die();
		exit;
	}
endif;

if ( !function_exists('wpdmp_reload_site_list_callback') ):
	function wpdmp_reload_site_list_callback() {	
	      
      $mapid = basename ($_POST['mapid']);	
      ob_start();
      
      $map=wpdmp_get_map($mapid);
      wpdmp_print_site_list($map);
      
      $content = ob_get_contents();
      ob_end_clean();
      echo $content;
      die();
      exit;
   }
endif;

if ( !function_exists('wpdmp_add_markers_to_map_callback') ):
	function wpdmp_add_markers_to_map_callback() {	
		
		$mapid = basename ($_POST['mapid']);
		$attachmentids = basename ($_POST['attachmentids']);
		$att_ids = explode(',',$attachmentids);
		
		$result = wpdmp_set_markers_available($mapid,$att_ids);
		
		$maps = wpdmp_get_maps();
      	ob_start();
            
      	if (is_array($result)){
	      	wpdmp_print_maps_available($maps);
	      	
	      	if (sizeof($result)>0){?>
	      		<div class="ajaxmessage"><?php printf(__('%d markers could not be removed because they are used.'),sizeof($result));?></div>
	      	<?php }
      	}else{
      		return json_encode($result);
      	}
      
      	$content = ob_get_contents();
      	ob_end_clean();
      	echo json_encode($content);
      	die();
      	exit;
		
}
endif;

if ( !function_exists('wpdmp_print_maps_available_callback') ):
	function wpdmp_print_maps_available_callback() {	

	  $maps = wpdmp_get_maps();
      ob_start();
            
      wpdmp_print_maps_available($maps);
      
      $content = ob_get_contents();
      ob_end_clean();
      echo $content;
      die();
      exit;
   }
endif;

if ( !function_exists('wpdmp_reload_map_callback') ):
	function wpdmp_reload_map_callback() {	
	
		$mapid = $_POST['mapid'];	
		$mode = $_POST['mode'];	

	    ob_start();
	    
	    if ($mode=="backend_map_manager"){
	       wpdmp_get_ref_points_help($mapid);
	       
	       $map_type = wpdmp_get_map_type($mapid);
	       if ($map_type != 'freehand' && !get_option('wpdmp_gooapikey')){?>
	       		<p class="red"><?php _e('Google Map might not work without an API key! Please define this one under Settings!','wp-design-maps-and-places'); ?></p>		
	       	<?php }
	       
	       wpdmp_print_map_b($mapid, true);
	       
	    }else if ($mode=="backend_marker_manager"){
			$map_type = wpdmp_get_map_type($mapid);
			if ($map_type != 'freehand' && !get_option('wpdmp_gooapikey')){?>
				<p class="red"><?php _e('Google Map might not work without an API key! Please define this one under Settings!','wp-design-maps-and-places'); ?></p>		
			<?php }
	       wpdmp_print_map_b($mapid, false);
	       
	    }else if ($mode=="backend_map_manager_google"){
	    	
	    	$map_type = wpdmp_get_map_type($mapid);
	    	if ($map_type != 'freehand' && !get_option('wpdmp_gooapikey')){?>
	    					<p class="red"><?php _e('Google Map might not work without an API key! Please define this one under Settings!','wp-design-maps-and-places'); ?></p>		
	    				<?php }
	    	
	       wpdmp_print_map_manager($mapid, $mode);
	    }else if ($mode =="front_end_reload"){
	    	wpdmp_print_map_b($mapid, false,'',true);
	    }
	    
	    $content = ob_get_contents();
	    ob_end_clean();
	    echo $content;
		die();
		exit;
	}
endif;

if ( !function_exists('wpdmp_print_css_and_effects') ):
	function wpdmp_print_css_and_effects() {
		?>
		<style fff="wpdmp_css" type="text/css"><?php echo stripslashes(get_option("wpdmp_css"));?></style>
		<script type="text/javascript"><?php echo stripslashes(get_option("wpdmp_effects"));?></script>
		<?php 
	}
endif;

if ( !function_exists('wpdmp_print_popup_dialog') ):
function wpdmp_print_popup_dialog() {
	?>
	<div id="wpdmp_popup_dialog" style="display: none; width:100%;">
		<p></p>
	</div>
	<?php
	}
endif;

?>