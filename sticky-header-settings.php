<?php
/**
 * Handles Customizer options.
 *
 * @package   Sticky_Dropdown
 * @author    engrmostafijur <engr.mostafijur@gmail.com>
 * @license   GPL-2.0+
 * @link      http://pixelsolution4it.com
 * @copyright 2014 engrmostafijur
 */

add_action( 'customize_register', 'pixl_Sticky_Dropdown_customize_register' );
/**
 * Registers all Customizer options.
 *
 * @since     1.0.0
 */
function pixl_Sticky_Dropdown_customize_register( $wp_customize ) {
	$pixl_plugin_slug = 'pixl-sticky-header';

	// Define Number custom control
	if ( class_exists( 'WP_Customize_Control') ) :
		class Sticky_Dropdown_Number_Control extends WP_Customize_Control {
			public $type = 'number';		
			public function render_content() { ?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<input class="small-text" type="number" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				</label>
			<?php }
		}
	endif;

	$wp_customize->add_section(
		'pixl_Sticky_Dropdown',
		array(
			'title'			=> __( ' Sticky Dropdown by pixelsolution4it', $pixl_plugin_slug ),
			'priority'		=> 1,
		) 
	);

	// Upload Sticky Header logo
	$wp_customize->add_setting(
		'pixl_Sticky_Dropdown[logo]',
		array(
			'default'			=> '',
			'sanitize_callback' => 'esc_url_raw',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'pixl_Sticky_Dropdown[logo]',
			array(
				'label'		=> __( 'Logo (image height should be 30px)', $pixl_plugin_slug ),
				'section'	=> 'pixl_Sticky_Dropdown',
				'settings'	=> 'pixl_Sticky_Dropdown[logo]',
			)
		)
	);

	// Sticky Header dropdown menu
	$menus = wp_get_nav_menus();
	if ( $menus ) :
		$choices = array( 0 => __( '&mdash; Select a menu &mdash;' ) );
		foreach ( $menus as $menu ) :
			$choices[ $menu->term_id ] = wp_html_excerpt( $menu->name, 40, '&hellip;' );
		endforeach;

		$wp_customize->add_setting(
			'pixl_Sticky_Dropdown[menu]',
			array(
				'sanitize_callback' => 'absint',
				'theme_supports'    => 'menus',
				'type'				=> 'option',
				'capability'		=> 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			'pixl_Sticky_Dropdown[menu]',
				array(
				'label'   	=> __( 'Menu', $pixl_plugin_slug ),
				'section' 	=> 'pixl_Sticky_Dropdown',
				'type'    	=> 'select',
				'choices' 	=> $choices,
				'priority'	=> 10
			)
		);
	endif;
	
	// Sticky Header background color
	$wp_customize->add_setting(
		'pixl_Sticky_Dropdown[background_color]',
		array(
			'default'			=> '#ffffff',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pixl_Sticky_Dropdown[background_color]',
			array(
				'label'		=> __( 'Background color', $pixl_plugin_slug ),
				'section'	=> 'pixl_Sticky_Dropdown',
				'settings'	=> 'pixl_Sticky_Dropdown[background_color]',
				'priority'	=> 20
			) 
		) 
	);

	// Sticky Header text color
	$wp_customize->add_setting(
		'pixl_Sticky_Dropdown[text_color]',
		array(
			'default'			=> '#333333',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pixl_Sticky_Dropdown[text_color]',
			array(
				'label'		=> __( 'Text color', $pixl_plugin_slug ),
				'section'	=> 'pixl_Sticky_Dropdown',
				'settings'	=> 'pixl_Sticky_Dropdown[text_color]',
				'priority'	=> 30
			) 
		) 
	);

	// Sticky Header inner width
	$wp_customize->add_setting(
		'pixl_Sticky_Dropdown[inner_width]',
		array(
			'default'			=> '',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new Sticky_Dropdown_Number_Control(
			$wp_customize,
			'pixl_Sticky_Dropdown[inner_width]',
			array(
				'label'		=> __( 'Sticky Header max width (in pixels)', $pixl_plugin_slug ),
				'section'	=> 'pixl_Sticky_Dropdown',
				'settings'	=> 'pixl_Sticky_Dropdown[inner_width]',
				'priority'	=> 40
			) 
		) 
	);

	// Sticky Header show at
	$wp_customize->add_setting(
		'pixl_Sticky_Dropdown[show_at]',
		array(
			'default'			=> '200',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new Sticky_Dropdown_Number_Control(
			$wp_customize,
			'pixl_Sticky_Dropdown[show_at]',
			array(
				'label'		=> __( 'Make visible when scrolled to (in pixels)', $pixl_plugin_slug ),
				'section'	=> 'pixl_Sticky_Dropdown',
				'settings'	=> 'pixl_Sticky_Dropdown[show_at]',
				'priority'	=> 50
			) 
		) 
	);

	// Sticky Header hide if narrower than
	$wp_customize->add_setting(
		'pixl_Sticky_Dropdown[hide_if_narrower]',
		array(
			'default'			=> '768',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new Sticky_Dropdown_Number_Control(
			$wp_customize,
			'pixl_Sticky_Dropdown[hide_if_narrower]',
			array(
				'label'		=> __( 'Hide if screen is narrower than (in pixels)', $pixl_plugin_slug ),
				'section'	=> 'pixl_Sticky_Dropdown',
				'settings'	=> 'pixl_Sticky_Dropdown[hide_if_narrower]',
				'priority'	=> 60
			) 
		) 
	);
}

/**
 * Returns plugin settings.
 *
 * @since     1.0.0
 *
 * @return    array    Merged array of plugin settings and plugin defaults.
 */
function pixl_Sticky_Dropdown_get_settings() {
	$plugin_defaults = array(
		'background_color'		=> '#ffffff',
		'text_color'			=> '#333',
		'show_at'				=> '200',
		'hide_if_narrower'		=> '768'
	);
	$plugin_settings = get_option( 'pixl_Sticky_Dropdown' );

	return wp_parse_args( $plugin_settings, $plugin_defaults );
}