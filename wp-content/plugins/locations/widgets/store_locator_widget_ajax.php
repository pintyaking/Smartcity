<?php
/*
This file is part of Locations.

Locations is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Locations is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Locations.  If not, see <http://www.gnu.org/licenses/>.

*/

class storeLocatorWidgetAJAX extends WP_Widget
{
	var $defaults = array();		
	
	function __construct(){
		$this->defaults = array(
			'title' => '',
			'caption' => '',
			'show_photos' => 'true',
			'id' => 'locations_pro_search_form',
			'class' => 'store_locator',
			'show_all_locations' => false,
			'show_all_nearby_locations' => true,
			'show_category_select' => true,
			'show_search_radius' => false,
			'show_search_results' => true,
			'link_search_results' => false,
			'map_width' => '100%',
			'map_height' => '400px',
			'map_class' => '',
			'caption_class' => 'store_locator_caption',
			'search_button_class' => 'btn btn-search',
			'search_button_label' => 'Search',
			'input_wrapper_class' => 'input_wrapper',
			'search_input_label' => 'Your Location:',
			'search_input_id' => 'your_location',
			'search_input_class' => '',
			'search_again_label' => 'Try Your Search Again:',
			'search_again_class' => 'search_again',
			'category_select_id' => 'location_category',
			'category_select_label' => 'Category:',
			'category_select_description' => 'Leave empty to show All Locations.',
			'allow_multiple_categories' => false,
			'radius_select_label' => 'Show Locations Within:',
			'radius_select_id' => 'search_radius',
			'search_box_location' => 'above',
			'default_latitude' => get_option('loc_p_default_latitude', '39.8282'), // defaults to USA
			'default_longitude' => get_option('loc_p_default_longitude', '-98.5795'), // defaults to USA
			'default_locations_to_show' => 'none',
			'search_results_style' => 'list',
			'store_locator_theme' => 'light_theme'
		);
		
		$widget_ops = array(
			'classname' => 'storeLocatorWidgetAJAX',
			'description' => __('A fixed-sized Store Locator, which loads its results via AJAX.','locations')
		);
		parent::__construct('storeLocatorWidgetAJAX', 'Locations - Store Locator', $widget_ops);
	}
	
	function storeLocatorWidget(){
		$this->__construct();
	}

	function form($instance){
		
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		
		//load the instance values and merge them with our defaults, then return and extract for displaying our form
		$final_vals = $this->get_final_values($instance);
		extract($final_vals, EXTR_SKIP);	
		
		?>
		<div class="gp_widget_form_wrapper">
			<p class="hide_in_popup"><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
									
			<div class="radio_text_input">
				<p><label for="<?php echo $this->get_field_id('map_width'); ?>">Width: <input class="widefat" id="<?php echo $this->get_field_id('map_width'); ?>" name="<?php echo $this->get_field_name('map_width'); ?>" type="text" value="<?php echo esc_attr($map_width); ?>" /></label></p>
				<p>
					<label for="<?php echo $this->get_field_id('map_height'); ?>">Height: <input class="widefat" id="<?php echo $this->get_field_id('map_height'); ?>" name="<?php echo $this->get_field_name('map_height'); ?>" type="text" value="<?php echo esc_attr($map_height); ?>" /></label>
					<span class="description">Does not include the search controls.</span>
				
				</p>	
			</div>

			<h4>Advanced Options:</h4>
			<fieldset class="radio_text_input">
				<legend>Theme:</legend>
				<p><label title="Light Theme">
					<input type="radio" value="light_theme" id="<?php echo $this->get_field_id('store_locator_theme'); ?>" name="<?php echo $this->get_field_name('store_locator_theme'); ?>" <?php if($store_locator_theme == 'light_theme'): ?>checked="CHECKED"<?php endif; ?>>
					<span>Light Theme</span>
				</label><br/>
				<label title="Dark Theme">
					<input type="radio" value="dark_theme" id="<?php echo $this->get_field_id('store_locator_theme'); ?>" name="<?php echo $this->get_field_name('store_locator_theme'); ?>" <?php if($store_locator_theme == 'dark_theme'): ?>checked="CHECKED"<?php endif; ?>>
					<span>Dark Theme</span>
				</label><br/>
			</fieldset>	
			
			<fieldset class="radio_text_input">
				<legend>Locations To Show Before Search:</legend>
				<p><label title="All Locations">
					<input type="radio" value="all" id="<?php echo $this->get_field_id('default_locations_to_show'); ?>" name="<?php echo $this->get_field_name('default_locations_to_show'); ?>" <?php if($default_locations_to_show == 'all'): ?>checked="CHECKED"<?php endif; ?>>
					<span>All Locations</span>
				</label><br/>
				<label title="Nearby Locations">
					<input type="radio" value="nearby" id="<?php echo $this->get_field_id('default_locations_to_show'); ?>" name="<?php echo $this->get_field_name('default_locations_to_show'); ?>" <?php if($default_locations_to_show == 'nearby'): ?>checked="CHECKED"<?php endif; ?>>
					<span>Nearby Locations</span>
				</label><br/>
				<label title="None">
					<input type="radio" value="none" id="<?php echo $this->get_field_id('default_locations_to_show'); ?>" name="<?php echo $this->get_field_name('default_locations_to_show'); ?>" <?php if($default_locations_to_show == 'none'): ?>checked="CHECKED"<?php endif; ?>>
					<span>None</span>
				</label><br/></p>
			</fieldset>		

			<fieldset class="radio_text_input">
				<legend>Search Form Options:</legend>		
				<p><input class="widefat" id="<?php echo $this->get_field_id('show_category_select'); ?>" name="<?php echo $this->get_field_name('show_category_select'); ?>" type="checkbox" value="1" <?php if($show_category_select){ ?>checked="CHECKED"<?php } ?>/><label for="<?php echo $this->get_field_id('show_category_select'); ?>">Show Category Select</label></p>
				<p><input class="widefat" id="<?php echo $this->get_field_id('show_search_radius'); ?>" name="<?php echo $this->get_field_name('show_search_radius'); ?>" type="checkbox" value="1" <?php if($show_search_radius){ ?>checked="CHECKED"<?php } ?>/><label for="<?php echo $this->get_field_id('show_search_radius'); ?>">Show Search Radius</label></p>
			</fieldset>

			<fieldset class="radio_text_input">
				<legend>Labels and Descriptions:</legend>	
				<p><label for="<?php echo $this->get_field_id('caption'); ?>">Caption: <input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo esc_attr($caption); ?>" /></label></p>		
				<p><label for="<?php echo $this->get_field_id('search_button_label'); ?>">Search Button Label: <input class="widefat" id="<?php echo $this->get_field_id('search_button_label'); ?>" name="<?php echo $this->get_field_name('search_button_label'); ?>" type="text" value="<?php echo esc_attr($search_button_label); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('search_input_label'); ?>">Search Input Label: <input class="widefat" id="<?php echo $this->get_field_id('search_input_label'); ?>" name="<?php echo $this->get_field_name('search_input_label'); ?>" type="text" value="<?php echo esc_attr($search_input_label); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('search_again_label'); ?>">Search Again Label: <input class="widefat" id="<?php echo $this->get_field_id('search_again_label'); ?>" name="<?php echo $this->get_field_name('search_again_label'); ?>" type="text" value="<?php echo esc_attr($search_again_label); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('category_select_label'); ?>">Category Select Label: <input class="widefat" id="<?php echo $this->get_field_id('category_select_label'); ?>" name="<?php echo $this->get_field_name('category_select_label'); ?>" type="text" value="<?php echo esc_attr($category_select_label); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('category_select_description'); ?>">Category Select Description: <input class="widefat" id="<?php echo $this->get_field_id('category_select_description'); ?>" name="<?php echo $this->get_field_name('category_select_description'); ?>" type="text" value="<?php echo esc_attr($category_select_description); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('radius_select_label'); ?>">Radius Select Label: <input class="widefat" id="<?php echo $this->get_field_id('radius_select_label'); ?>" name="<?php echo $this->get_field_name('radius_select_label'); ?>" type="text" value="<?php echo esc_attr($radius_select_label); ?>" /></label></p>
			</fieldset>	
			
			<fieldset class="radio_text_input">
				<legend>Advanced Options:</legend>
				<p><label for="<?php echo $this->get_field_id('default_latitude'); ?>">Default Latitude: <input class="widefat" id="<?php echo $this->get_field_id('default_latitude'); ?>" name="<?php echo $this->get_field_name('default_latitude'); ?>" type="text" value="<?php echo esc_attr($default_latitude); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('default_longitude'); ?>">Default Longitude: <input class="widefat" id="<?php echo $this->get_field_id('default_longitude'); ?>" name="<?php echo $this->get_field_name('default_longitude'); ?>" type="text" value="<?php echo esc_attr($default_longitude); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('caption_class'); ?>">Caption Class: <input class="widefat" id="<?php echo $this->get_field_id('caption_class'); ?>" name="<?php echo $this->get_field_name('caption_class'); ?>" type="text" value="<?php echo esc_attr($caption_class); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('id'); ?>">Form ID: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('class'); ?>">Form Class: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo esc_attr($class); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('map_class'); ?>">Map Class: <input class="widefat" id="<?php echo $this->get_field_id('map_class'); ?>" name="<?php echo $this->get_field_name('map_class'); ?>" type="text" value="<?php echo esc_attr($map_class); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('search_button_class'); ?>">Search Button Class: <input class="widefat" id="<?php echo $this->get_field_id('search_button_class'); ?>" name="<?php echo $this->get_field_name('search_button_class'); ?>" type="text" value="<?php echo esc_attr($search_button_class); ?>" /></label></p>				
				<p><label for="<?php echo $this->get_field_id('input_wrapper_class'); ?>">Input Wrapper Class: <input class="widefat" id="<?php echo $this->get_field_id('input_wrapper_class'); ?>" name="<?php echo $this->get_field_name('input_wrapper_class'); ?>" type="text" value="<?php echo esc_attr($input_wrapper_class); ?>" /></label></p>				
				<p><label for="<?php echo $this->get_field_id('search_input_id'); ?>">Search Input ID: <input class="widefat" id="<?php echo $this->get_field_id('search_input_id'); ?>" name="<?php echo $this->get_field_name('search_input_id'); ?>" type="text" value="<?php echo esc_attr($search_input_id); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('search_input_class'); ?>">Search Input Class: <input class="widefat" id="<?php echo $this->get_field_id('search_input_class'); ?>" name="<?php echo $this->get_field_name('search_input_class'); ?>" type="text" value="<?php echo esc_attr($search_input_class); ?>" /></label></p>				
				<p><label for="<?php echo $this->get_field_id('search_again_class'); ?>">Search Again Class: <input class="widefat" id="<?php echo $this->get_field_id('search_again_class'); ?>" name="<?php echo $this->get_field_name('search_again_class'); ?>" type="text" value="<?php echo esc_attr($search_again_class); ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('category_select_id'); ?>">Category Select ID: <input class="widefat" id="<?php echo $this->get_field_id('category_select_id'); ?>" name="<?php echo $this->get_field_name('category_select_id'); ?>" type="text" value="<?php echo esc_attr($category_select_id); ?>" /></label></p>				
				<p><label for="<?php echo $this->get_field_id('radius_select_id'); ?>">Radius Select ID: <input class="widefat" id="<?php echo $this->get_field_id('radius_select_id'); ?>" name="<?php echo $this->get_field_name('radius_select_id'); ?>" type="text" value="<?php echo esc_attr($radius_select_id); ?>" /></label></p>
			</fieldset>
			<input type="text" style="display:none" class="hide_in_popup" name="<?php echo $this->get_field_name('allow_multiple_categories'); ?>" value="0" />
			<input type="text" style="display:none" class="hide_in_popup" name="<?php echo $this->get_field_name('compact_view'); ?>" value="1" />
		</div>
		<?php
	}

	function update($new_instance, $old_instance){		
		$instance = $old_instance;
		foreach($this->defaults as $key => $default) {
			//update values that are set in the form
			//if any checkboxes have been unset, their values won't be set so we default them to 0
			$instance[$key] = isset($new_instance[$key]) ? $new_instance[$key] : 0;
		}
		
		//handle default locations to show radio buttons
		if(isset($new_instance['default_locations_to_show'])){			
			$default_locations_to_show = $new_instance['default_locations_to_show'];
			$instance['default_locations_to_show'] = $new_instance['default_locations_to_show'];
			
			if($default_locations_to_show == "all"){
				$instance['show_all_locations'] = true;
				$instance['show_all_nearby_locations'] = false;
			} else if($default_locations_to_show == "nearby"){
				$instance['show_all_locations'] = false;
				$instance['show_all_nearby_locations'] = true;
			} else {
				$instance['show_all_locations'] = false;
				$instance['show_all_nearby_locations'] = false;
			}
		}

		return $instance;
	}

	function widget($args, $instance){
		$gp_lp = new LocationsPlugin();
		
		//load the instance values and merge them with our defaults, then return and extract for outputting our shortcode
		$final_vals = $this->get_final_values($instance);
		$final_vals['compact_view'] = true;
		$final_vals['map_height'] = (intval($final_vals['map_height']) + 240) . 'px';
		$final_vals['search_results_style'] = '';
		$final_vals['search_box_location'] = 'above';
		extract($args, EXTR_SKIP);

		echo $before_widget;

		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}		
		
		echo $gp_lp->Store_Locator->store_locator_shortcode($final_vals);

		echo $after_widget;
	} 
	
	function get_final_values($instance)
	{
		$vals = array();
		foreach($this->defaults as $key => $default) 
		{
			if( isset($instance[$key]) ) {
				$vals[$key] = $instance[$key];
			} else {
				$vals[$key] = $default;				
			}
		}
		return $vals;
	}
}
?>