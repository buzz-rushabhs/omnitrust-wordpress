<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style('hello-elementor-child-style',get_stylesheet_directory_uri() . '/style.css',['hello-elementor-theme-style'],HELLO_ELEMENTOR_CHILD_VERSION);
	wp_enqueue_style('bootstrap-style', get_stylesheet_directory_uri(). '/css/bootstrap.min.css');
	wp_enqueue_style('swiper-style', get_stylesheet_directory_uri(). '/css/swiper-bundle.min.css');
	wp_enqueue_style('intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/css/intlTelInput.css');
	//wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
	wp_enqueue_style('multi-css', 'https://unpkg.com/multiple-select@2.2.0/dist/multiple-select.min.css');
	wp_enqueue_style('slick-style', get_stylesheet_directory_uri() . '/slick/slick.css');
    wp_enqueue_style('slick-theme-style', get_stylesheet_directory_uri() . '/slick/slick-theme.css');
	wp_enqueue_style('custom-css',get_stylesheet_directory_uri() . '/css/style.css',[],HELLO_ELEMENTOR_CHILD_VERSION);
	wp_enqueue_style('responsive-css',get_stylesheet_directory_uri() . '/css/responsive.css',[],HELLO_ELEMENTOR_CHILD_VERSION);

	//wp_enqueue_script('popper-js',get_stylesheet_directory_uri() . '/js/popper.min.js',array('jquery-core'),array(),true);
    wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/slick/slick.min.js',array('jquery-core'),array(),true);
    wp_enqueue_script('intl-tel', get_stylesheet_directory_uri().'/js/intlTelInput.min.js',array('jquery-core'),array(),true);
    //wp_enqueue_script('multi-select', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',array('jquery-core'),array(),true);
    wp_enqueue_script('multi-select', 'https://unpkg.com/multiple-select@2.2.0/dist/multiple-select.min.js',array('jquery-core'),array(),true);
    wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.min.js',array('jquery-core'),array(),true);
    wp_enqueue_script('swiper-js',get_stylesheet_directory_uri() . '/js/swiper-bundle.min.js',array('jquery-core'),array(),true);
    wp_enqueue_script('custom-js',get_stylesheet_directory_uri() . '/js/custom.js',array('jquery-core'),array(),true);

	wp_localize_script('custom-js','js_config',array(
		'ajax_url' => admin_url('admin-ajax.php')
	));
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

function cc_mime_types($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['ico']  = 'image/ico';
    $mimes['woff'] = 'font/woff';
    $mimes['woff2'] = 'font/woff2';
    $mimes['pem'] = 'application/x-pem-file';
    $mimes['oer'] = 'application/octet-stream';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_pem_oer_mime_check($data, $file, $filename, $mimes) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if ($ext === 'pem') {
        $data['ext']  = 'pem';
        $data['type'] = 'application/x-pem-file';
    }

    if ($ext === 'oer') {
        $data['ext']  = 'oer';
        $data['type'] = 'application/octet-stream';
    }

    return $data;
}
add_filter('wp_check_filetype_and_ext', 'fix_pem_oer_mime_check', 10, 4);


add_filter('wpcf7_autop_or_not', '__return_false');

// [custom_breadcrumbs]
function custom_breadcrumbs_shortcode() {
    // Bootstrap 5 Breadcrumb wrapper
    $separator = ''; // Bootstrap handles separators with CSS
    $home_title = 'Home';
    global $post;

    // Do not display on front page
    if (is_front_page()) {
        return '';
    }

    ob_start();
    ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html($home_title); ?></a>
            </li>

            <?php
            if (is_single()) {
                $post_type = get_post_type();

                if ($post_type !== 'post' && $post_type !== 'case-study' && $post_type !== 'press') {
                    $post_type_obj = get_post_type_object($post_type);
                    $slug = $post_type_obj->rewrite['slug'];
                    if($post_type != 'platforms'){
                    ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo esc_url(home_url('/' . $slug . '/')); ?>">
                            <?php echo esc_html($post_type_obj->labels->name); ?>
                        </a>
                    </li>
                    <?php
                	}
                    if($post_type == 'platforms'){
                    	$slug = 'platform';
                    	$post_type_obj->labels->singular_name = 'Platform';
                    	$terms = get_the_terms(get_the_ID(),'platform-category');
                    	echo '<li class="breadcrumb-item">
			                        <a href="'.esc_url(home_url('/platform/')).'">Platform</a>
			                    </li>';
                    	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						    $term = $terms[0];
						    $term_name = $term->name;
						    $sanitized = sanitize_title( $term_name );
						    echo '<li class="breadcrumb-item">
			                        <a href="'.esc_url(home_url('/' .$sanitized. '/')).'">'.esc_html($term_name).'</a>
			                    </li>';
						}
                    }
                }

                if ($post_type == 'press') {
                    echo '<li class="breadcrumb-item" aria-current="page"><a href="'.get_permalink(5731).'">Media & Press</a></li>';
                }

                if ($post_type == 'post') {
                    echo '<li class="breadcrumb-item" aria-current="page"><a href="/resources/">Resources</a></li>';
                    echo '<li class="breadcrumb-item" aria-current="page"><a href="'.get_permalink(2212).'">Blogs</a></li>';
                }

                if ($post_type == 'case-study') {
                    echo '<li class="breadcrumb-item" aria-current="page"><a href="/resources/">Resources</a></li>';
                    echo '<li class="breadcrumb-item" aria-current="page"><a href="'.get_permalink(2384).'">Case Stidues</a></li>';
                }
                ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo wp_trim_words(esc_html(get_the_title()), 5, '...'); ?>
                </li>
                <?php

            } elseif (is_post_type_archive()) {
				$post_type = get_query_var('post_type');
				if (is_array($post_type)) {
					$post_type = $post_type[0];
				}

				$post_type_obj = get_post_type_object($post_type);
				?>
				<li class="breadcrumb-item active" aria-current="page">
					<?php echo esc_html($post_type_obj->labels->singular_name); ?>
				</li>
				<?php
			}
			elseif (is_page()) {
                if ($post->post_parent) {
                    $parents = [];
                    $parent_id = $post->post_parent;

                    while ($parent_id) {
                        $page = get_page($parent_id);
                        $parents[] = '<li class="breadcrumb-item"><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
                        $parent_id = $page->post_parent;
                    }

                    $parents = array_reverse($parents);
                    foreach ($parents as $parent) {
                        echo $parent;
                    }
                }
                ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo esc_html(get_the_title()); ?>
                </li>
                <?php
            }
            elseif(is_tax('resources-type')){ 
            	$term = get_queried_object();
            	?>
            	<li class="breadcrumb-item" aria-current="page"><a href="/resources/">Resources</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="/resources/resource-library/">Resource Library</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $term->name; ?></li>
            <?php } elseif (is_category()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo single_cat_title('', false); ?>
                </li>
            <?php
            } elseif (is_tag()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    Tag: <?php echo single_tag_title('', false); ?>
                </li>
            <?php
            } elseif (is_day()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    Archives for <?php echo get_the_date(); ?>
                </li>
            <?php
            } elseif (is_month()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    Archives for <?php echo get_the_date('F Y'); ?>
                </li>
            <?php
            } elseif (is_year()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    Archives for <?php echo get_the_date('Y'); ?>
                </li>
            <?php
            } elseif (is_search()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    Search results for "<?php echo get_search_query(); ?>"
                </li>
            <?php
            } elseif (is_404()) { ?>
                <li class="breadcrumb-item active" aria-current="page">
                    404 Error
                </li>
            <?php } ?>
        </ol>
    </nav>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_breadcrumbs', 'custom_breadcrumbs_shortcode');

/*function home_industries_case_studies(){
	ob_start();
	?>
	<!-- Navigation Text Buttons -->
	<div class="custom-nav">
		<div class="nav-item active" data-slide="0">Aerospace & Defense</div>
		<div class="nav-item" data-slide="1">Infrastructure Provider</div>
		<div class="nav-item" data-slide="2">Auto Supplier</div>
		<div class="nav-item" data-slide="3">Avionics Manufacturer</div>
		<div class="nav-item" data-slide="4">Automotive OEM</div>
	</div>

	<!-- Swiper Carousel -->
	<div class="swiper mySwiper">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<div class="industry-box">
					<img src="/wp-content/uploads/2025/10/industry-min.jpg" alt="">
					<p>Aerospace & Defense OEM Employs Edge Encryption to Protect Software from Manufacture to Mission</p>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}*/

function home_industries_case_studies() {

	ob_start();
	$post_ids = array(595, 593, 599,598,597);
	// WP Query
	$args = array(
		'post_type'      => 'industry',
		'posts_per_page' => -1,
		'post__in'       => $post_ids,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);
	$query = new WP_Query($args);

	?>
	
	<!-- Navigation Text Buttons -->
	<ul class="custom-nav">
		<?php 
		$slide_index = 0;
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				?>
				<li class="nav-item <?php echo $slide_index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $slide_index; ?>">
					<?php the_title(); ?>
				</li>
				<?php
				$slide_index++;
			endwhile;
		endif;
		?>
	</ul>

	<!-- Swiper Carousel -->
	<div class="swiper mySwiper">
		<div class="swiper-wrapper">
			<?php 
			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();

					$image = get_the_post_thumbnail_url(get_the_ID(), 'large');
					$acf_text = get_field('short_description'); // change "text_field" to your ACF field name
					?>
					
					<div class="swiper-slide">
						<div class="industry-box">
							<?php if($image): ?>
								<img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>">
							<?php endif; ?>

							<?php if($acf_text): ?>
								<p><?php echo esc_html($acf_text); ?></p>
							<?php endif; ?>
						</div>
					</div>

				<?php 
				endwhile;
			endif;
			?>
		</div>
	</div>

	<?php

	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode('home-industry-case-studies', 'home_industries_case_studies');


// add_action( 'elementor/query/acf_loop_posts', function( $queryss ) {
// 	// STOP inside Elementor Editor (REST edit mode)
//     if ( defined('REST_REQUEST') && REST_REQUEST ) {
//         return;
//     }

//     // STOP inside wp-admin (classic editor, ACF editor, Elementor backend)
//     if ( is_admin() || wp_doing_ajax() ) {
//         return;
//     }

//     // STOP inside Elementor preview iframe
//     if ( isset($_GET['elementor-preview']) ) {
//         return;
//     }
//     // Safely get current post ID (works inside Elementor editor too)
//     $post_id = get_the_ID();
//     if (!$post_id) {
//         $post_id = $_POST['editor_post_id'] ?? null;
//     }
//     $post_ids = [];
//     // Get ACF field safely
//     if ($post_id && function_exists('get_field')) {
//         $post_ids = get_field('related_solutions', 1172);
//     } else {
//         $post_ids = [];
//     }

//     if (!empty($post_ids)) {
//         $queryss->set('post__in', $post_ids);
//         $queryss->set('orderby', 'post__in');
//     } else {
//         $queryss->set('post__in', [0]);
//     }
// });

function handle_dynamic_related_posts( $queryss, $query_id ) {
    $map = [
        'related_solution_loop_posts'      => 'related_solutions',
        'related_case_studies_loop_posts'  => 'related_case_studies',
        'featured_industries_loop_posts' => 'featured_industries',
        'related_resources_loop_posts'  => 'related_resources',
        'supported_industries_loop_posts' => 'supported_industries'
    ];
    if ( ! isset( $map[$query_id] ) ) {
        return;
    }
	
    $acf_field = $map[$query_id];
    $post_id = get_the_ID();
    if ( ! $post_id && isset($_POST['editor_post_id']) ) {
        $post_id = intval($_POST['editor_post_id']);
    }
    if ( ! $post_id ) {
        return;
    }

    // Get ACF relationship values
    $related = get_field( $acf_field, $post_id );
    if ( empty($related) ) {
        $queryss->set( 'post__in', [0] );
        return;
    }
    if ( is_object($related[0]) ) {
        $related = wp_list_pluck( $related, 'ID' );
    }

    // Apply final query
    $queryss->set( 'post__in', $related );
    $queryss->set( 'orderby', 'post__in' );
}


/** REGISTER THE QUERY HOOKS **/
add_action( 'elementor/query/related_solution_loop_posts', function( $query ) {
    handle_dynamic_related_posts( $query, 'related_solution_loop_posts' );
});

add_action( 'elementor/query/related_case_studies_loop_posts', function( $query ) {
    handle_dynamic_related_posts( $query, 'related_case_studies_loop_posts' );
});

add_action( 'elementor/query/featured_industries_loop_posts', function( $query ) {
    handle_dynamic_related_posts( $query, 'featured_industries_loop_posts' );
});

add_action( 'elementor/query/supported_industries_loop_posts', function( $query ) {
    handle_dynamic_related_posts( $query, 'supported_industries_loop_posts' );
});

add_action( 'elementor/query/related_resources_loop_posts', function( $query ) {
    handle_dynamic_related_posts( $query, 'related_resources_loop_posts' );
});

function display_key_features_solution() {
    // Check if there are rows of data in the repeater field
    if( have_rows('key_features', get_the_ID()) ):
        $output = '<div class="key-features">'; // Open unordered list

        // Loop through the rows of data
        while( have_rows('key_features', get_the_ID()) ): the_row();
            $icon = get_sub_field('icon');
            $feature_title = get_sub_field('feature_title', get_the_ID());
            $features_description = get_sub_field('features_description', get_the_ID());
            
            $output .= '<div class="elementor-element elementor-element-684be6b elementor-position-right elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="684be6b" data-element_type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">
						        <div class="elementor-icon-box-content">
                                    <h4 class="elementor-icon-box-title">
						                <span>'.$feature_title.'</span>
					                </h4>
									<p class="elementor-icon-box-description">'.$features_description.'</p>
			                    </div>
		                    </div>
						</div>';
            
            /*$output .= '<div class="elementor-element elementor-element-759c822 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="759c822" data-element_type="widget" data-widget_type="icon-box.default">
							<div class="elementor-icon-box-wrapper">
						        <div class="elementor-icon-box-content">
									<h4 class="elementor-icon-box-title">
						                <span>'.$feature_title.'</span>
					                </h4>
									<p class="elementor-icon-box-description">'.$features_description.'</p>
			                    </div>
	                        </div>
						</div>';*/
        endwhile;

        $output .= '</div>'; // Close unordered list
        return $output;
    else:
    endif;
}
add_shortcode('display_key_features_solution', 'display_key_features_solution');

function acf_icon_list_shortcode($atts) {
    ob_start();

    if (have_rows('product_features')) : ?>
        <ul class="elementor-icon-list-items">
            <?php while (have_rows('product_features')) : the_row(); 
                $text = get_sub_field('feature');
            ?>
                <li class="elementor-icon-list-item">
                    <?php if ($link) : ?>
                        <a href="<?php echo esc_url($link); ?>">
                    <?php endif; ?>

                    <span class="elementor-icon-list-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none"><rect width="10" height="10" fill="#0073CF"></rect></svg>
                    </span>
                    <span class="elementor-icon-list-text">
                        <?php echo esc_html($text); ?>
                    </span>

                    <?php if ($link) : ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif;

    return ob_get_clean();
}
add_shortcode('acf_icon_list', 'acf_icon_list_shortcode');


function built_for_compliance_logos($atts) {
    ob_start();

    if (have_rows('compliance')) :
        echo '<div class="row">';
            while (have_rows('compliance')) : the_row(); 
                $logo = get_sub_field('logo');
                $title = get_sub_field('title');
                $description = get_sub_field('description');
            	echo '<div class="col-md-4">
	            	<div class="comp-box">
	            		<img src="'.$logo.'" alt="'.$title.'">
	            		<h6>'.$title.'</h6>
	            		<p>'.$description.'<p>
	            	</div>
	            </div>';
             endwhile;
        echo '</div>';

    endif;
    return ob_get_clean();
}
add_shortcode('built-for-compliance', 'built_for_compliance_logos');

/* Home Page Featured White Paper */
add_filter( 'wpcf7_form_elements', function( $form ) {
	$white_paper = get_field( 'select_white_paper', $post_id )[0];
	$related = $white_paper->post_title;
	$wp_url = get_field('white_paper_document',$white_paper->ID);

  	$form = str_replace( 'WHITE_PAPER', $related, $form );
  	$form = str_replace( 'WHITEPAPER_URL', $wp_url, $form );
  	$form = str_replace( 'CURRENT_TITLE', get_the_title(), $form );
  	$form = str_replace( 'POST_TYPE', get_post_type(), $form );
  	$form = str_replace( 'CURRENT_URL', get_permalink(), $form );
  	return $form;
});


add_filter('wpcf7_mail_components', function ($components, $contact_form) {

    // CHANGE THIS to your actual CF7 form ID
    $target_form_id = 445;
    print_r((int) $contact_form->id() !== $target_form_id);

    if ($contact_form->id() !== $target_form_id) {
    	return $components;
    }

    $post_type = $_POST['post-type'] ?? '';
    $current_url = $_POST['current-url'] ?? '';

    switch ($post_type) {

        case 'platforms':
            $components['subject'] = 'New Product Inquiry – Submitted from '.$current_url;
            break;

        case 'industry':
            $components['subject'] = 'New Inquiry Received via Website Contact Form';
            break;

        case 'solution':
            $components['subject'] = 'New Inquiry Received via Website Contact Form';
            break;

        default:
            $components['subject'] = 'New Inquiry Received via Website Contact Form';
            break;
    }

    return $components;

}, 10, 2);


function sync_related_post_image( $post_id ) {

    // Avoid autosave / revisions
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Get relationship field
    $related = get_field( 'select_white_paper', $post_id );

    if ( empty( $related ) || !is_array( $related ) ) {
        return;
    }

    $image_id = get_post_thumbnail_id($related[0]);

    if (!empty( $related ) ) {
        update_field( 'white_paper_image',$image_id, $post_id );
        return;
    }
}
add_action( 'acf/save_post', 'sync_related_post_image', 20 );

function homepage_featured_wp_title($atts){
	$related_posts = get_field('select_white_paper');

	if($atts['type'] == 'title'){
		return esc_html(get_the_title($related_posts[0]));
	}

	if($atts['type'] == 'image'){
		echo $img = get_the_post_thumbnail($related_posts[0]->ID);
	}
}
add_shortcode('featured-wp-title','homepage_featured_wp_title');

/* Home Page Featured White Paper End*/

function white_paper_loop_title(){
	$white_paper = get_field( 'select_white_paper', $post_id );
	$id = (!empty($white_paper)) ? $white_paper[0] : get_the_ID();
	return esc_html(get_the_title($id));
}
add_shortcode('white-paper-details-title','white_paper_loop_title');
function white_paper_loop_url(){
	$white_paper = get_field( 'select_white_paper', $post_id );
	$id = (!empty($white_paper)) ? $white_paper[0] : get_the_ID();
	$wp_url = get_field('white_paper_document',$id);
	return esc_html($wp_url);
}
add_shortcode('white-paper-details-url','white_paper_loop_url');
function white_paper_loop_image(){
	$white_paper = get_field( 'select_white_paper', $post_id );
	$id = (!empty($white_paper)) ? $white_paper[0] : get_the_ID();
	$wp_img = get_the_post_thumbnail_url($id);
	return esc_html($wp_img);
}
add_shortcode('white-paper-details-image','white_paper_loop_image');


function get_acf_date_range( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();

    $start = get_field( 'start_date', $post_id );
    $end   = get_field( 'end_date', $post_id );

    if ( ! $start && ! $end ) {
        return '';
    }

    // Convert to timestamps
    $start_ts = strtotime( $start );
    $end_ts   = strtotime( $end );

    // Same date
    if ( $start && $end && date('Ymd', $start_ts) === date('Ymd', $end_ts) ) {
        return date( 'd M Y', $start_ts );
    }

    // Both dates
    if ( $start && $end ) {
        return $start . ' – ' . $end;
    }

    // Only one date
    return $start
        ? date( 'd M Y', $start )
        : date( 'd M Y',$end);
}
add_shortcode('events-duration','get_acf_date_range');

function elementor_ajax_search_callback() {
    $keyword = sanitize_text_field( $_POST['keyword'] ?? '' );

    $args = [
        'post_type'      => 'event', // or industry CPT
        'posts_per_page' => 6,
        's'              => $keyword,
    ];

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            /**
             * IMPORTANT:
             * Use the SAME markup as your Loop Item
             */
            echo do_shortcode('[elementor-template id="4974"]');
        }
    } else {
        echo '<p>No results found</p>';
    }

    wp_die();
}
add_action( 'wp_ajax_elementor_ajax_search', 'elementor_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_elementor_ajax_search', 'elementor_ajax_search_callback' );


function product_datasheets_documents($atts) {

    $atts = shortcode_atts([
        'post_id' => get_the_ID()
    ], $atts);

    $post_id = intval($atts['post_id']);

    if (!have_rows('datasheet_lists', $post_id)) {
        return '';
    }

    ob_start();
    echo '<div class="datasheet-media-grid row">';

    while (have_rows('datasheet_lists', $post_id)) {
        the_row();

        $file_id = get_sub_field('datasheet');

        if (!$file_id) {
            continue;
        }

        $file_url  = wp_get_attachment_url($file_id);
        //$file_name = get_the_title($file_id);
        $file_name = basename( get_attached_file( $file_id ) );
        $file_size = size_format(filesize(get_attached_file($file_id)));

        // Get PDF thumbnail
        $thumb = wp_get_attachment_image(
            $file_id,
            'medium',
            false,
            ['class' => 'datasheet-thumb']
        );

        echo '<div class="col-md-4">';
            echo '<div class="datasheet-thumb-wrap"><a href="' . esc_url($file_url) . '" target="_blank" class="datasheet-item">' . $thumb . '</a></div>';
            echo '<div class="datasheet-meta">';
                echo '<h4 href="' . esc_url($file_url) . '" target="_blank" class="datasheet-title"><a href="' . esc_url($file_url) . '" target="_blank">' .$file_name. '</a></h4>';
            echo '</div>';
        echo '</div>';
    }

    echo '</div>';

    return ob_get_clean();
}
add_shortcode('product-datasheets', 'product_datasheets_documents');


add_action('wp_ajax_load_related_shortcode', 'load_related_shortcode');
add_action('wp_ajax_nopriv_load_related_shortcode', 'load_related_shortcode');

function load_related_shortcode() {

    if ( empty($_POST['post_id']) ) {
        wp_die();
    }

    $post_id = intval($_POST['post_id']);

    echo do_shortcode('[product-datasheets post_id="' . $post_id . '"]');

    wp_die();
}


function check_related_case_studies(){
	$case_studies = get_field('related_case_studies');
	if(empty($case_studies)){
		return 'd-none';
	}
}
add_shortcode('hide-section','check_related_case_studies');


function layers_page_loop_id(){
	return esc_html(get_the_ID());
}
add_shortcode('product-id','layers_page_loop_id');


add_action('acf/save_post', 'rl_set_pdf_thumbnail_as_featured_image', 20);
function rl_set_pdf_thumbnail_as_featured_image($post_id) {

    // Avoid autosave / revisions
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // Run only for post type: resource-library
    if (get_post_type($post_id) !== 'resource-library') {
        return;
    }

    // Check taxonomy term: resources-type = white-papers
    if (!has_term('white-papers', 'resources-type', $post_id)) {
        return;
    }

    // Do not override existing featured image
    if (has_post_thumbnail($post_id)) {
        return;
    }

    // ACF file field name (CHANGE THIS)
    $field_name = 'white_paper_document';

    $file = get_field($field_name, $post_id);

    if (!$file) {
        return;
    }

    // Get attachment ID
    $attachment_id = is_array($file) ? $file['ID'] : $file;

    // Ensure it's a PDF
    if (get_post_mime_type($attachment_id) !== 'application/pdf') {
        return;
    }

    // Get PDF metadata (WordPress auto-generated thumbnail)
    $metadata = wp_get_attachment_metadata($attachment_id);
    if (empty($metadata['sizes']['full']['file'])) {
        return;
    }


    $pdf_path   = get_attached_file($attachment_id);
    $thumb_file = dirname($pdf_path) . '/' . $metadata['sizes']['full']['file'];

    if (!file_exists($thumb_file)) {
        return;
    }

    // Create image attachment
    $thumb_attachment = [
        'post_mime_type' => 'image/jpeg',
        'post_title'     => get_the_title($attachment_id) . ' Thumbnail',
        'post_status'    => 'inherit'
    ];

    $thumb_id = wp_insert_attachment($thumb_attachment, $thumb_file, $post_id);
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $thumb_meta = wp_generate_attachment_metadata($thumb_id, $thumb_file);
    wp_update_attachment_metadata($thumb_id, $thumb_meta);

    // Set featured image
    set_post_thumbnail($post_id, $thumb_id);
}
