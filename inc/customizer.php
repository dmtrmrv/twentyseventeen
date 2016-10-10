<?php
/**
 * Twenty Seventeen Theme Customizer
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function twentyseventeen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	 * Add the Theme Options section
	 */
	$wp_customize->add_panel( 'options_panel', array(
		'title'       => __( 'Theme Options', 'twentyseventeen' ),
		'description' => __( 'Configure your theme settings', 'twentyseventeen' ),
	) );

	// Page Options.
	$wp_customize->add_section( 'page_options', array(
		'title'           => __( 'Single Page Layout', 'twentyseventeen' ),
		'active_callback' =>'twentyseventeen_is_page',
		'panel'           => 'options_panel',
	) );

	$wp_customize->add_setting( 'page_options', array(
		'default'           => 'two-column',
		'sanitize_callback' => 'twentyseventeen_sanitize_layout',
	) );

	$wp_customize->add_control( 'page_options', array(
		'label'       => __( 'Single Page Layout', 'twentyseventeen' ),
		'section'     => 'page_options',
		'type'        => 'radio',
		'description' => __( 'When no sidebar widgets are assigned, you can opt to display single pages with a one column or two column layout. When the two column layout is assigned, the page title is in one column and content is in the other.', 'twentyseventeen' ),
		'choices'     => array(
			'one-column' => __( 'One Column', 'twentyseventeen' ),
			'two-column' => __( 'Two Column', 'twentyseventeen' ),
		),
	) );

	for ( $panel = 1; $panel <= twentyseventeen_panel_count_max(); $panel++ ) :

	$wp_customize->add_section( 'panel_' . $panel , array(
		'title'           => sprintf( __( 'Panel %s', 'twentyseventeen' ), esc_html( $panel ) ),
		'active_callback' => 'is_front_page',
		'panel'           => 'options_panel',
		'description'     => __( 'Add an image to your panel by setting a featured image in the page editor. If you don&rsquo;t select a page, this panel will not be displayed.', 'twentyseventeen' ),
	) );

	$wp_customize->add_setting( 'panel_' . $panel, array(
		'default'           => false,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'panel_' . $panel, array(
		'label'   => __( 'Panel Content', 'twentyseventeen' ),
		'section' => 'panel_' . $panel,
		'type'    => 'dropdown-pages',
	) );

	endfor;
}
add_action( 'customize_register', 'twentyseventeen_customize_register' );

/**
 * Sanitize a radio button.
 */
function twentyseventeen_sanitize_layout( $input ) {
	$valid = array(
		'one-column' => __( 'One Column', 'twentyseventeen' ),
		'two-column' => __( 'Two Column', 'twentyseventeen' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}
	return '';
}

/**
 * Custom Active Callback to check for page.
 */
function twentyseventeen_is_page() {
	return ( is_page() && ! twentyseventeen_is_frontpage() );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function twentyseventeen_customize_preview_js() {
	wp_enqueue_script( 'twentyseventeen-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'twentyseventeen_customize_preview_js' );

/**
 * Some extra JavaScript to improve the user experience in the Customizer for this theme.
 */
function twentyseventeen_panels_js() {
	wp_register_script(
		'twentyseventeen-panel-customizer',
		get_template_directory_uri() . '/assets/js/panel-customizer.js',
		array(),
		'20151116',
		true
	);
	wp_enqueue_script( 'twentyseventeen-panel-customizer' );
	wp_localize_script( 'twentyseventeen-panel-customizer', 'twentyseventeenPanelCountMax', array( twentyseventeen_panel_count_max() ) );
}
add_action( 'customize_controls_enqueue_scripts', 'twentyseventeen_panels_js' );
