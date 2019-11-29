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
    wp_enqueue_script( 'Quiz.js', get_template_directory_uri(). '/resources/Private/Js/Quiz.js', array('jquery'));

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


    wp_localize_script( 'script-critical', 'questions', $my_json_str );
    wp_localize_script( 'script-critical', 'answers', $customFields );

    return $wp_query;
}

// get admin URL
function myPluginAjaxUrl() {
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// Get my ajaxURL
add_action('wp_head', 'myPluginAjaxUrl');

// update Database Table for voting
function updatePoolTable()
{
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    updateTopPlayersTable($name, $surname);
}

add_action('wp_ajax_updatePoolTable', 'updatePoolTable'); // add action for logged users
add_action( 'wp_ajax_nopriv_updatePoolTable', 'updatePoolTable' ); // add action for unlogged users

// update Database Table for voting
function updateTopPlayersTable($name, $surname)
{
    global $wpdb;
    $wpdb->insert('top_players', array('name' => $name, 'surname' => $surname));
}

function getAllHighscoreUsers() {
    global $wpdb;

    $users = $wpdb->get_results( "SELECT * FROM top_players");


    return $users;
}

add_action( 'init', 'change_room_term_to_checkbox', 999);

function change_room_term_to_checkbox() {
    $tax = get_taxonomy('question_category');
    $tax->meta_box_cb = 'post_categories_meta_box';
    $tax->hierarchical = true;
}

function wpbeginner_numeric_posts_nav() {

   return 'test';

}

function paginationHighScore($tableName) {
    global $wpdb;
    $users = $wpdb->get_results( "SELECT * FROM $tableName");


// Array here.
    $data_print = $users;
//**************************
//
// Pagination
//
//**************************
    $page = ! empty( $_GET['cpage'] ) ? (int) $_GET['cpage'] : 1;
    $total = count($data_print); //total items in array
    $limit = 8; //per page
    $totalPages = ceil( $total/ $limit ); //calculate total pages
    $page = max($page, 1); //get 1 page when $_GET['cpage'] <= 0
    $page = min($page, $totalPages); //get last page when $_GET&#91;'cpage'&#93; > $totalPages
    $offset = ($page - 1) * $limit;
    if( $offset < 0 ) $offset = 0;
//**************************
//
// Pagination
//
//**************************
// offset array
    ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Meno</th>
            <th scope="col">Priezvisko</th>
            <th scope="col">E-mail</th>
        </tr>
        </thead>
        <tbody>
    <?php
    $data_print = array_slice( $data_print, $offset, $limit );
    for($i = 0; $i < count($data_print); $i++){
        ?>
        <tr>
            <th scope="row"><?php echo $data_print[$i]->id?></th>
            <td><?php echo $data_print[$i]->name?></td>
            <td><?php echo $data_print[$i]->surname?></td>
            <td>@mdo</td>
        </tr>
        <?php
    }
    ?>
        </tbody>
    </table>
<?php
// Show pagination here
    if($totalPages > 1){
        $arr_params = array ('cpage' => '%#%');
        $customPagHTML     =  '<div class="cs_pagination">'.paginate_links( array(
                'base' => add_query_arg( $arr_params ),
                'format' => '',
                'prev_text' =>  '<span class="cs_page_number" aria-hidden="true">&laquo;</span>',
                'next_text' => ' <span class="cs_page_number" aria-hidden="true">&raquo;</span>',
                'total' => $totalPages,
                'current' => $page
            )).'</div>';
        echo $customPagHTML;
    }
}
