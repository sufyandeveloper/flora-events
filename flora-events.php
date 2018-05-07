<?php
defined('ABSPATH') or die('No Script Kiddies Please!');

/*
Plugin Name: Flora Events
Plugin URI:  https://wpflora.com
Description: Create Page and Paste shortcode [flora-events]
Version:     1
Author:      Sufyan
Author URI:  https://sufyan.co
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset
*/

//Google Map API key
$googleMapApiKey = 'Insert Your Key Here';

//Custom Fields Work

add_action('add_meta_boxes', 'flora_events_metabox' );

function flora_events_metabox(){
    add_meta_box( 'flora-events-box', 'Flora Events', 'flora_events_metabox_html', array('floraevents'), 'normal', 'high' );
}

function flora_events_metabox_html($post){
    
    $eventDate = get_post_meta($post->ID, 'eventDate', true);
    $eventLocationLat = get_post_meta($post->ID, 'eventLocationLat', true);
    $eventLocationLng = get_post_meta($post->ID, 'eventLocationLng', true);
    $eventUrl = get_post_meta($post->ID, 'eventUrl', true);

    global $googleMapApiKey;
    ?>
    
    <table>
        <tr>
            <td><lable for="eventDate">Event Date</lable></td>
            <td><input type="text"  id="flora-datepicker" name="eventDate" value="<?=$eventDate?>" style="width:100%;" /></td>
        </tr>
        
        <tr>
            <td><lable for="eventLocation">Location</lable></td>
            <td>
                <input type="text"  name="eventLocationLat" value="<?=$eventLocationLat?>"  id="flora-lat" size="50" />
                <input type="text"  name="eventLocationLng" value="<?=$eventLocationLng?>"  id="flora-lng" size="50" />
            </td>
        </tr>
        
        <tr>
            <td><lable for="eventUrl">Url</lable></td>
            <td><input type="text"  name="eventUrl" value="<?=$eventUrl?>"  style="width:100%;" /></td>
        </tr>
    </table>
    
    <?php require_once 'flora-google-map.php'; ?>
    
    <script>
        jQuery(document).ready(function($){
    
            $('#flora-datepicker').datepicker();
            
        });
    </script>
    
   <?php
}

add_action('save_post', 'flora_events_save_custom_fields', 10, 2);

function flora_events_save_custom_fields($post_id, $post){
    
    if($post->post_type == 'floraevents'){

        if(!empty($_POST['eventDate'])){
            
            $eventDate = date('Y-m-d', strtotime($_POST['eventDate']));

            update_post_meta($post_id, 'eventDate', $eventDate);
        }
        
        if(!empty($_POST['eventLocationLat'])){
            
            $eventLocationLat = $_POST['eventLocationLat'];
            
            update_post_meta($post_id, 'eventLocationLat', $eventLocationLat);
        }
        
        if(!empty($_POST['eventLocationLng'])){
            
            $eventLocationLng = $_POST['eventLocationLng'];
            
            update_post_meta($post_id, 'eventLocationLng', $eventLocationLng);
        }
        
        if(!empty($_POST['eventUrl'])){
            
            $eventUrl = $_POST['eventUrl'];
            
            update_post_meta($post_id, 'eventUrl', $eventUrl);
        }
    }
    
}


//Events Post Type
add_action( 'init', 'flora_events_post_type' );

function flora_events_post_type() {
	$labels = array(
		'name'               => _x( 'Flora Event', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Flora Events', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Flora Events', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Flora Events', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Event', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Flora Event', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Event', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Event', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Flora Events', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Flora Events', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Events:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No events found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'floraevents' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'floraevents', $args );
	
    	
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Event Category', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Events', 'textdomain' ),
		'all_items'         => __( 'All Events Category', 'textdomain' ),
		'parent_item'       => __( 'Parent Event Cat', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Event Cat:', 'textdomain' ),
		'edit_item'         => __( 'Edit Event Cat', 'textdomain' ),
		'update_item'       => __( 'Update Event Cat', 'textdomain' ),
		'add_new_item'      => __( 'Add New Event Category', 'textdomain' ),
		'new_item_name'     => __( 'New Category Name', 'textdomain' ),
		'menu_name'         => __( 'Category', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'event-category' ),
	);

	register_taxonomy( 'eventcategory', array( 'floraevents' ), $args );
}



add_action( 'wp_enqueue_scripts', 'flora_event_enqueue_scripts' );
function flora_event_enqueue_scripts() {
    
    //js
    $js_url = plugin_dir_url(__FILE__) . 'js/flora-event.js';
	$js_path = plugin_dir_path(__FILE__) . 'js/flora-event.js';

	$floraCjsObj = array(
						'nonce'		=>	wp_create_nonce( "my-special-string" ),
						'url'		=>	admin_url( 'admin-ajax.php' )
				);

	wp_register_script('flora-event', $js_url, array('jquery'), filemtime($js_path), true);

	wp_localize_script('flora-event', 'floraCjsObj', $floraCjsObj);

	wp_enqueue_script('flora-event');
}

//datepicker
add_action( 'admin_enqueue_scripts', 'flora_event_enqueue_datepicker' );

function flora_event_enqueue_datepicker(){
    //datepicker
    wp_enqueue_script( 'jquery-ui-datepicker' );

    wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' ); 
}


//Shortcode
//shortcode
add_action('init', 'flora_events_init_short');

function flora_events_init_short(){

    add_shortcode('flora-events', 'flora_events_func');

}

function flora_events_func($atts){
    
    extract(
            shortcode_atts(
                array(
                    
                ),
                $atts
            )
        );
        
    
    ob_start();

    global $googleMapApiKey;

    $today = date('Y-m-d');
    $args = array(
                'post_type'     =>  'floraevents',
                'post_status'   =>  'publish'
        );
        
    $events = new WP_Query($args);

    if($events->have_posts()):
        
        
        ?>
        <style>
            .floramap{
                height:300px;
            }
        </style>
        
        <?php
        
        while($events->have_posts()):
            
            $events->the_post();
            
            ?>
                <div class="floraevents" data-id="<?=get_the_ID()?>">
                    
                    <h1 class="eventHead"><?php the_title(); ?></h1>
                    
                    <div class="eventbox">
                        
                        <div class="floraeventDetails">
                            
                            <b>Event Date: </b> <span><?php echo get_post_meta(get_the_ID(), 'eventDate', true); ?></span> <br/>
                            <b>Event Url: </b> <span><?php echo get_post_meta(get_the_ID(), 'eventUrl', true); ?></span><br/>
                            <?php
                                $eventLocationLat = get_post_meta(get_the_ID(), 'eventLocationLat', true);
                                $eventLocationLng = get_post_meta(get_the_ID(), 'eventLocationLng', true);
                            ?>
                            <b>Location: </b> Lat: <span id="lat-<?=get_the_ID()?>"><?=$eventLocationLat?></span> |  Lng: <span id="lng-<?=get_the_ID()?>"><?=$eventLocationLng?></span>
                            
                        </div>
                        
                        <div id="floramap-<?=get_the_ID()?>" class="floramap">
                            
                        </div>    
                        
                        
                        
                    </div>
                    
                </div>
            
            <?php
        
        endwhile;
        
    endif;

    ?>
    <script>
    function floraeventmap(){
        
        jQuery('.floraevents').each(function(k,v){
            
            var id = jQuery(this).attr('data-id');
            
            var lat = parseFloat(jQuery('#lat-'+id).text());
            
            var lng = parseFloat(jQuery('#lng-'+id).text());
            console.log('lat '+lat);
            show_map(lat, lng, id);
            
            
        });
        
        
        function show_map(lat,lng,id){
            
            var options = {
                zoom: 10,
                center: {lat: lat,lng: lng}
            }
            console.log(options);
            var map = new google.maps.Map( document.getElementById('floramap-'+id), options);
            
            //marker
            var marker = new google.maps.Marker({
               position: {lat:lat,lng:lng},
               map: map
            });
            
        }
        
    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?=$googleMapApiKey?>&callback=floraeventmap"></script>
    <?php

    $output = ob_get_contents();
    
    ob_get_clean();
    
    return $output;
    
}




