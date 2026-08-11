<?php

namespace WPGO_Plugins\Simple_Sitemap;

/**
 * Plugin 'Welcome' settings page
 */
class Settings_Welcome {

	/**
	 * Common root paths/directories.
	 *
	 * @var array<string, string>
	 */
	protected $module_roots;

	/**
	 * Plugin data.
	 *
	 * @var array
	 */
	protected $plugin_data;

	/**
	 * Custom plugin data.
	 *
	 * @var Constants
	 */
	protected $custom_plugin_data;

	/**
	 * Freemius discount upgrade URL.
	 *
	 * @var string
	 */
	protected $freemius_discount_upgrade_url;

	/**
	 * New features URL.
	 *
	 * @var string
	 */
	protected $new_features_url;

	/**
	 * Welcome slug.
	 *
	 * @var string
	 */
	protected $welcome_slug;

	/**
	 * Main class constructor.
	 *
	 * @param array $module_roots Root plugin path/dir.
	 * @param array $plugin_data Plugin data.
	 * @param Constants $custom_plugin_data Custom plugin data.
	 */
	public function __construct( $module_roots, $plugin_data, $custom_plugin_data ) {
		$this->module_roots                  = $module_roots;
		$this->plugin_data                   = $plugin_data;
		$this->custom_plugin_data            = $custom_plugin_data;
		$this->freemius_discount_upgrade_url = $this->custom_plugin_data->freemius_discount_upgrade_url;
		$this->new_features_url              = $this->custom_plugin_data->new_features_url;
		$this->welcome_slug                  = $this->custom_plugin_data->settings_pages['welcome']['slug'];

		add_action( 'admin_menu', array( &$this, 'add_options_page' ) );
	}

	/**
	 * Add menu page.
	 */
	public function add_options_page() {
		$label = __( 'About', 'simple-sitemap' );
		if ( 'top' === $this->custom_plugin_data->menu_type || 'top-cpt' === $this->custom_plugin_data->menu_type ) {
			$label = __( 'About', 'simple-sitemap' );
		} elseif ( 'sub' === $this->custom_plugin_data->menu_type ) {
			$label = '<span class="fs-submenu-item fs-sub wpgo-plugins">About</span>';
		}

		add_submenu_page(
			'simple-sitemap-menu',
			sprintf(
				/* translators: %s: plugin name. */
				__( 'Welcome to %s', 'simple-sitemap' ),
				$this->custom_plugin_data->main_menu_label
			),
			$label,
			'manage_options',
			'simple-sitemap-menu-welcome', // $this->welcome_slug,
			array( &$this, 'render_sub_menu_form' )
		);
	}

	/**
	 * Display the sub menu page.
	 */
	public function render_sub_menu_form() {

		$tab_classes  = SITEMAP_FREEMIUS_NAVIGATION === 'tabs' ? ' fs-section fs-full-size-wrapper' : ' no-tabs';
		$is_premium   = $this->custom_plugin_data->is_premium;
		$plugin_lbl   = $this->custom_plugin_data->main_menu_label;
		$new_page_url = add_query_arg(
			'post_type',
			'page',
			admin_url( 'post-new.php' )
		);
		?>
		<div class="wrap <?php echo esc_attr( $tab_classes ); ?> about-wrap">
			<div class="wpgo-settings-inner about-con">
				<header class="header">
					<div class="header-img">
						<img src="<?php echo esc_url( $this->module_roots['uri'] . '/lib/assets/images/simple-sitemap.svg' ); ?>" alt="" />
					</div>
					<div class="sub-header">
						<div class="sub-header-title">
							<h1 class="title">
								<?php
								/* translators: %s: plugin name. */
								printf( esc_html__( 'Welcome to %s', 'simple-sitemap' ), esc_html( $plugin_lbl ) );
								?>
							</h1>
							<div>
								<span class="dashicons dashicons-flag" aria-hidden="true"></span>
								<span class="title-ver">v<?php echo esc_html( $this->plugin_data['Version'] ); ?></span>
							</div>
						</div>
						<div class="title-desc">
							<p><?php esc_html_e( 'Create a visitor-friendly HTML sitemap with blocks or shortcodes. Existing sitemaps keep their saved settings automatically.', 'simple-sitemap' ); ?></p>
						</div>
					</div>
				</header>

				<main class="plugin-desc-con">
					<section id="quick-start" class="simple-sitemap-quick-start plugin-des-item">
						<h2 class="sub-title"><?php esc_html_e( 'Quick Start', 'simple-sitemap' ); ?></h2>
						<p><?php esc_html_e( 'Build a useful sitemap in three steps:', 'simple-sitemap' ); ?></p>
						<ol class="simple-sitemap-quick-start__steps">
							<li>
								<strong><?php esc_html_e( 'Create a page', 'simple-sitemap' ); ?></strong>
								<span><?php esc_html_e( 'Give it a clear title such as “Sitemap”.', 'simple-sitemap' ); ?></span>
							</li>
							<li>
								<strong><?php esc_html_e( 'Insert a starter pattern', 'simple-sitemap' ); ?></strong>
								<span><?php esc_html_e( 'Open the block inserter, choose Patterns → Simple Sitemap, then select a ready-made layout. You can also search for “sitemap” to add an individual block.', 'simple-sitemap' ); ?></span>
							</li>
							<li>
								<strong><?php esc_html_e( 'Preview and publish', 'simple-sitemap' ); ?></strong>
								<span><?php esc_html_e( 'Use the block sidebar to adjust content, ordering, exclusions, labels, and pagination.', 'simple-sitemap' ); ?></span>
							</li>
						</ol>
						<div class="simple-sitemap-quick-start__actions">
							<a class="button button-primary" href="<?php echo esc_url( $new_page_url ); ?>"><?php esc_html_e( 'Create Sitemap Page', 'simple-sitemap' ); ?></a>
							<a class="button" href="<?php echo esc_url( $this->custom_plugin_data->main_settings_url ); ?>"><?php esc_html_e( 'View Settings', 'simple-sitemap' ); ?></a>
							<a class="button" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Read Documentation', 'simple-sitemap' ); ?></a>
						</div>
						<p class="simple-sitemap-shortcode-fallback">
							<?php esc_html_e( 'Using a classic editor or page builder?', 'simple-sitemap' ); ?>
							<code>[simple-sitemap]</code>
							<?php esc_html_e( 'displays a page sitemap with the same established output as previous versions.', 'simple-sitemap' ); ?>
						</p>
					</section>

					<section class="plugin-des-item">
						<h2 class="sub-title"><?php esc_html_e( 'Included in the Free Plugin', 'simple-sitemap' ); ?></h2>
						<p><?php esc_html_e( 'Create post and page sitemaps, grouped sitemaps, and child-page hierarchies. You can choose content, include or exclude individual items, change ordering and labels, and enable linked pagination without upgrading.', 'simple-sitemap' ); ?></p>
					</section>

					<?php if ( ! $is_premium ) : ?>
						<aside class="premium-tbl simple-sitemap-upgrade-notice">
							<div>
								<strong><?php esc_html_e( 'Need specialist sitemaps and deeper styling controls?', 'simple-sitemap' ); ?></strong>
								<p><?php esc_html_e( 'Pro adds custom post types, taxonomy terms, navigation menus, archives, WooCommerce products, and advanced presentation options.', 'simple-sitemap' ); ?></p>
								<small><?php esc_html_e( 'The displayed 30% discount applies to the first year; renewals are charged at the normal rate.', 'simple-sitemap' ); ?></small>
							</div>
							<a class="button upgrade-txt-pc" href="<?php echo esc_url( $this->freemius_discount_upgrade_url ); ?>"><?php esc_html_e( 'Compare Pro — 30% Off', 'simple-sitemap' ); ?></a>
						</aside>
					<?php endif; ?>

					<section class="new-con plugin-des-item">
						<h2 class="sub-title">
							<?php
							/* translators: %s: plugin version. */
							printf( esc_html__( 'What’s New in %s?', 'simple-sitemap' ), esc_html( $this->plugin_data['Version'] ) );
							?>
						</h2>
						<p><?php esc_html_e( 'Review the release highlights, documentation, and working sitemap examples.', 'simple-sitemap' ); ?></p>
						<div class="simple-sitemap-quick-start__actions">
							<a class="button button-primary" href="<?php echo esc_url( $this->new_features_url ); ?>"><?php esc_html_e( 'New Features', 'simple-sitemap' ); ?></a>
							<a class="button" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Plugin Documentation', 'simple-sitemap' ); ?></a>
							<a class="button" href="https://demo.wpgothemes.com/flexr/simple-sitemap-pro-demo/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Live Demo', 'simple-sitemap' ); ?></a>
						</div>
					</section>

					<section class="coming-soon-con plugin-des-item">
						<h2 class="sub-title"><?php esc_html_e( 'Recent and Planned Improvements', 'simple-sitemap' ); ?></h2>
						<p><?php esc_html_e( 'This update includes:', 'simple-sitemap' ); ?></p>
						<ul class="coming-soon-ul">
							<li><?php esc_html_e( 'Visual content pickers, clearer block controls, and dynamic editor previews.', 'simple-sitemap' ); ?></li>
							<li><?php esc_html_e( 'Child Pages, Taxonomy Terms, Navigation Menu, Archive Links, and WooCommerce Product Sitemap blocks.', 'simple-sitemap' ); ?></li>
							<li><?php esc_html_e( 'Optional SEO noindex and current-language integrations.', 'simple-sitemap' ); ?></li>
							<li><?php esc_html_e( 'Linked pagination and large-site performance controls.', 'simple-sitemap' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'Next priorities include front-end search and filtering, enhanced pagination, more integration providers, and additional theme-aware presentation options.', 'simple-sitemap' ); ?></p>
						<a class="button" href="<?php echo esc_url( $this->custom_plugin_data->contact_us_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Suggest a Feature', 'simple-sitemap' ); ?></a>
					</section>
				</main>
			</div>
		</div>
		<?php
	}
} /* End class definition */
