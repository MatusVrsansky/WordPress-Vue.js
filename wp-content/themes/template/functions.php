<?php
require_once get_template_directory().'/Classes/TimberSite.php';

/*
 *  CONSTANTS SECTION - define your constants here
 */
// define('SOME_PAGE_ID', 666);

/**
 * Timber site initialization
 */
new Digitalwerk\TimberSite();

/**
 * Renders Flexible content elements
 *
 * @param array $fce
 * @return string
 * @throws Exception
 */
function RenderFCE($fce) {
    if ($fce === NULL || !is_array($fce) || count($fce) <= 0) { return NULL; }
    $content = '';
    $context = Timber::get_context();
    $context['_post'] = new TimberPost();
    foreach ($fce as $key => $element) {
        if (isset($element['acf_fc_layout'])) {
            $context['fields'] = $element;
            $context['orderIndex'] = $key;
            $content .= Timber::compile('fce/' . $element['acf_fc_layout'] . '.twig', $context);
        }
    }
    return $content;
}

// css stylesheet
add_action('wp_enqueue_scripts', 'enqueue_base_scripts', 9);
function enqueue_base_scripts() {
    // CSS
    wp_enqueue_style('main-stylesheet', get_template_directory_uri() . '/resources/Public/Css/style.min.css', array(), '0.0.1');
    wp_enqueue_style('critical-stylesheet', get_template_directory_uri() . '/resources/Public/Css/critical.min.css', array(), '0.0.1');
    wp_enqueue_style('custom', get_template_directory_uri() . '/resources/Public/Css/custom.css', array(), '0.0.1');

    // JS
    wp_enqueue_script('script-app', get_template_directory_uri() . '/resources/Public/Js/app.js',  array(), '0.0.1', true);
    wp_enqueue_script('script-critical', get_template_directory_uri() . '/resources/Public/Js/critical.js',  array(), '0.0.1', true);


}

// Scripts for Ajax Filter Search
function studienFilterScript() {
    wp_enqueue_script( 'Game.js', get_template_directory_uri(). '/resources/Private/Js/Game.js', array('jquery'));
    wp_enqueue_script( 'Square.js', get_template_directory_uri(). '/resources/Private/Js/Square.js', array('jquery'));

}
add_action( 'wp_enqueue_scripts', 'studienFilterScript' );

//function size2Byte($size) {
//    $units = array('KB', 'MB', 'GB', 'TB');
//    $currUnit = '';
//    while (count($units) > 0  &&  $size > 1024) {
//        $currUnit = array_shift($units);
//        $size /= 1024;
//    }
//    return ($size | 0) . $currUnit;
//}

add_filter('show_admin_bar', '__return_false');

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = ceil(number_format($bytes / 1024, 2)) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function getFileType($file) {
    $path_parts = pathinfo($file);

    //file extension
    $fileExtension = $path_parts['extension'];
    return $fileExtension;
}

add_action( 'init', 'setFaqCategoriesToSidebar', 999);
function setFaqCategoriesToSidebar() {
    $tax = get_taxonomy('faq_category');
    $tax->meta_box_cb = 'post_categories_meta_box';
    $tax->hierarchical = true;
}


function getTaxonomyPosts($slug) {
    $query = array(
        'post_type' => 'faq',
        'post_status' => 'publish',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'faq_category',
                'field' => 'slug',
                'terms' => $slug
            )
        )
    );

    $wp_query = new WP_Query( $query );
    return $wp_query;
}

function getPostById($id) {
    $args = array(
        'p'         => $id, // ID of a page, post, or custom type
        'post_type' => 'products',
        'post_status' => 'publish',
        'order' => 'ASC'
    );

    $wp_query = new WP_Query($args);

    $price_list = array();
    // The Loop
    if ( $wp_query->have_posts() ) {
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            $price_list =  get_field('price_list');
        }
    } else {
        // no posts found
    }

   return $price_list;
}

function getRandomQuestions() {
    $args = array(
        'post_type' => 'questions',
        'orderby'   => 'rand',
    );

    $wp_query = new WP_Query( $args );

    $arrayCustomFields = array();

    if ( $wp_query->have_posts() ) {
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            $answer_a =  get_field('answer_a');
            $answer_b =  get_field('answer_b');
            $answer_c =  get_field('answer_c');
            $answer_d =  get_field('answer_d');
            $right_answer = get_field('right_answer');

            array_push($arrayCustomFields, array("answer_a"=>$answer_a,"answer_b"=>$answer_b,"answer_c"=>$answer_c, "answer_d" => $answer_d, "right_answer" => $right_answer));
        }
    } else {
        // no posts found
    }
    $my_json_str = json_encode($wp_query->posts);
    $customFields = json_encode($arrayCustomFields);


    wp_localize_script( 'script-critical', 'passed_object', $my_json_str );
    wp_localize_script( 'script-critical', 'cars_array', $customFields );

    return $wp_query;
}
