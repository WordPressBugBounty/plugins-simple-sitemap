<?php

namespace WPGO_Plugins\Simple_Sitemap;

/**
 * Register ready-made sitemap layouts for the block inserter.
 */
class Block_Patterns {

	/**
	 * Register WordPress hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_patterns' ) );
	}

	/**
	 * Register the pattern category and bundled starter layouts.
	 */
	public function register_patterns() {
		if ( ! function_exists( 'register_block_pattern' ) || ! function_exists( 'register_block_pattern_category' ) ) {
			return;
		}

		register_block_pattern_category(
			'simple-sitemap',
			array(
				'label' => __( 'Simple Sitemap', 'simple-sitemap' ),
			)
		);

		foreach ( $this->get_patterns() as $name => $pattern ) {
			register_block_pattern( $name, $pattern );
		}
	}

	/**
	 * Return the bundled starter patterns.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	private function get_patterns() {
		$post_types = wp_json_encode(
			array(
				array(
					'value' => 'page',
					'label' => 'Pages',
				),
				array(
					'value' => 'post',
					'label' => 'Posts',
				),
			)
		);

		return array(
			'simple-sitemap/posts-and-pages'        => array(
				'title'       => __( 'Posts and Pages Sitemap', 'simple-sitemap' ),
				'description' => __( 'A visitor-friendly alphabetical sitemap containing posts and pages.', 'simple-sitemap' ),
				'categories'  => array( 'simple-sitemap' ),
				'blockTypes'  => array( 'wpgoplugins/simple-sitemap-block' ),
				'inserter'    => true,
				'content'     => $this->serialize_dynamic_block(
					'wpgoplugins/simple-sitemap-block',
					array( 'block_post_types' => $post_types )
				),
			),
			'simple-sitemap/tabbed-posts-and-pages' => array(
				'title'       => __( 'Tabbed Posts and Pages Sitemap', 'simple-sitemap' ),
				'description' => __( 'Posts and pages displayed in separate tabs.', 'simple-sitemap' ),
				'categories'  => array( 'simple-sitemap' ),
				'blockTypes'  => array( 'wpgoplugins/simple-sitemap-block' ),
				'inserter'    => true,
				'content'     => $this->serialize_dynamic_block(
					'wpgoplugins/simple-sitemap-block',
					array(
						'block_post_types' => $post_types,
						'render_tab'       => true,
					)
				),
			),
			'simple-sitemap/child-pages'            => array(
				'title'       => __( 'Child Pages Sitemap', 'simple-sitemap' ),
				'description' => __( 'A page hierarchy that starts at the current page.', 'simple-sitemap' ),
				'categories'  => array( 'simple-sitemap' ),
				'blockTypes'  => array( 'wpgoplugins/simple-sitemap-child-pages-block' ),
				'inserter'    => true,
				'content'     => $this->serialize_dynamic_block( 'wpgoplugins/simple-sitemap-child-pages-block' ),
			),
		);
	}

	/**
	 * Serialize a dynamic block without introducing saved inner markup.
	 *
	 * @param string               $name Block name.
	 * @param array<string, mixed> $attributes Block attributes.
	 * @return string
	 */
	private function serialize_dynamic_block( $name, $attributes = array() ) {
		$serialized_attributes = empty( $attributes ) ? '' : ' ' . wp_json_encode( $attributes );

		return '<!-- wp:' . $name . $serialized_attributes . ' /-->';
	}
}
