<?php

namespace Gonzo\Slider\Slides;

/**
 * Declare hooks.
 */
function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\register_slides', 0 );
	add_action( 'cmb2_admin_init', __NAMESPACE__ . '\\slide_button_metabox' );
	add_action( 'cmb2_admin_init', __NAMESPACE__ . '\\slide_align_metabox' );
}

/**
 * Register Custom Post Type for slides.
 */
function register_slides() {

	$labels = array(
		'name'                  => _x( 'Slides', 'Post Type General Name', 'gonzo-slider' ),
		'singular_name'         => _x( 'Slide', 'Post Type Singular Name', 'gonzo-slider' ),
		'menu_name'             => __( 'Slides', 'gonzo-slider' ),
		'name_admin_bar'        => __( 'Slides', 'gonzo-slider' ),
		'archives'              => __( 'Latest Slides', 'gonzo-slider' ),
		'parent_item_colon'     => __( 'Parent Item:', 'gonzo-slider' ),
		'all_items'             => __( 'All slides', 'gonzo-slider' ),
		'add_new_item'          => __( 'Add New Slide', 'gonzo-slider' ),
		'add_new'               => __( 'Add New', 'gonzo-slider' ),
		'new_item'              => __( 'New Slide', 'gonzo-slider' ),
		'edit_item'             => __( 'Edit Slide', 'gonzo-slider' ),
		'update_item'           => __( 'Update Slide', 'gonzo-slider' ),
		'view_item'             => __( 'View Slide', 'gonzo-slider' ),
		'search_items'          => __( 'Search Slides', 'gonzo-slider' ),
		'not_found'             => __( 'Not found', 'gonzo-slider' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gonzo-slider' ),
		'featured_image'        => __( 'Featured Image', 'gonzo-slider' ),
		'set_featured_image'    => __( 'Set featured image', 'gonzo-slider' ),
		'remove_featured_image' => __( 'Remove featured image', 'gonzo-slider' ),
		'use_featured_image'    => __( 'Use as featured image', 'gonzo-slider' ),
		'insert_into_item'      => __( 'Insert into slide', 'gonzo-slider' ),
		'uploaded_to_this_item' => __( 'Uploaded to this slide', 'gonzo-slider' ),
		'items_list'            => __( 'Items list', 'gonzo-slider' ),
		'items_list_navigation' => __( 'Items list navigation', 'gonzo-slider' ),
		'filter_items_list'     => __( 'Filter items list', 'gonzo-slider' ),
	);
	$args = array(
		'label'                 => __( 'Slide', 'gonzo-slider' ),
		'description'           => __( 'Slides portfolio', 'gonzo-slider' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'excerpt', 'thumbnail', 'revisions' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'menu_icon'             => 'dashicons-welcome-view-site',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'slide', $args );

}

/**
 * Define the metabox and field configurations for CTA button fields.
 */
function slide_button_metabox() {

	/**
	 * Initiate the metabox.
	 */
	$cmb = new_cmb2_box(
		array(
			'id'           => 'slide_cta',
			'title'        => __( 'Slide CTA', 'gonzo-slider' ),
			'object_types' => array( 'slide' ), // Post type.
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Button Text', 'gonzo-slider' ),
			'id'   => '_slide_cta_text',
			'type' => 'text',
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Button URL', 'gonzo-slider' ),
			'id'   => '_slide_cta_url',
			'type' => 'text_url',
		)
	);

}

/**
 * Define the metabox and field configurations for slide alignment.
 */
function slide_align_metabox() {

	/**
	 * Initiate the metabox.
	 */
	$cmb = new_cmb2_box(
		array(
			'id'           => 'slide_alignment',
			'title'        => __( 'Slide Alignment', 'gonzo-slider' ),
			'object_types' => array( 'slide' ), // Post type.
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Slide alignment', 'gonzo-slider' ),
			'id'   => '_slide_alignment',
			'type' => 'radio',
			'options'          => array(
				'left'   => __( 'Left', 'cmb2' ),
				'center' => __( 'Center', 'cmb2' ),
				'right'  => __( 'Right', 'cmb2' ),
			),
			'default' => 'left',
		)
	);

}
