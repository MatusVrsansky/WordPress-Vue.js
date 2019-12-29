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

add_action('wp_ajax_getPostById', 'getPostById'); // add action for logged users
add_action( 'wp_ajax_nopriv_getPostById', 'getPostById' ); // add action for unlogged users

function getPostById() {
    $args = array(
        'p'         => $_POST['id'], // ID of a page, post, or custom type
        'post_type' => 'questions',
        'post_status' => 'publish',
        'order' => 'ASC'
    );

    $wp_query = new WP_Query($args);

    $arrayCustomFields = array();

    $taxonomy = get_the_terms($_POST['id'], 'question_category');

    if ( $wp_query->have_posts() ) {
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            $answer_a =  get_field('answer_a');
            $answer_b =  get_field('answer_b');
            $answer_c =  get_field('answer_c');
            $answer_d =  get_field('answer_d');
            $right_answer = get_field('right_answer');

            array_push($arrayCustomFields, array("answer_a"=>$answer_a,"answer_b"=>$answer_b,
                "answer_c"=>$answer_c, "answer_d" => $answer_d, "right_answer" => $right_answer, "category" => $taxonomy));
        }
    }

//    echo json_encode($arrayCustomFields);
    $finalArray = array();
    array_push($finalArray, $wp_query->posts);
    array_push($finalArray, $arrayCustomFields);

    echo json_encode($finalArray);



    wp_die();
}

// get admin URL
function myPluginAjaxUrl() {
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// Get my ajaxURL
add_action('wp_head', 'myPluginAjaxUrl');



add_action('wp_ajax_addToUserTable', 'addToUserTable'); // add action for logged users
add_action( 'wp_ajax_nopriv_addToUserTable', 'addToUserTable' ); // add action for unlogged users

// update Database Table for voting
function addToUserTable() {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $table = $_POST['table'];
    $count_good_answers = $_POST['good_answers'];
    $count_bad_answers = $_POST['bad_answers'];
    updateTopPlayersTable($name, $surname, $count_good_answers, $count_bad_answers, $table);
}

// update Database Table for voting
function updateTopPlayersTable($name, $surname, $count_good_answers, $count_bad_answers, $table) {
    global $wpdb;
    $wpdb->insert($table, array('name' => $name, 'surname' => $surname,
        'count_good_answers' => $count_good_answers, 'count_bad_answers' => $count_bad_answers));
}

add_action('wp_ajax_addNewQuestion', 'addNewQuestion'); // add action for logged users
add_action( 'wp_ajax_nopriv_addNewQuestion', 'addNewQuestion' ); // add action for unlogged users

// add new question function
function addNewQuestion() {
    header('Content-Type: application/html;charset=utf-8');

    // Create post object
    $my_post = array(
        'post_title'    => $_POST['name'],
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
        'post_type' => 'questions',
    );


    $post_id = wp_insert_post( $my_post);

    wp_set_object_terms($post_id, $_POST['category'], 'question_category');



    // Insert the post into the database
    add_post_meta($post_id, 'answer_a', $_POST['answer_a']);
    add_post_meta($post_id, 'answer_b', $_POST['answer_b']);
    add_post_meta($post_id, 'answer_c', $_POST['answer_c']);
    add_post_meta($post_id, 'answer_d', $_POST['answer_d']);
    add_post_meta($post_id, 'right_answer', $_POST['right_answer']);

    //wp_set_post_categories( $post_id, $post_categories );

    // echo json_encode($uploadedImages);
    wp_die();
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
    $limit = 2; //per page
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
            <th scope="col">Správne odpovede</th>
            <th scope="col">Nesprávne odpovede</th>
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
            <td><?php echo $data_print[$i]->count_good_answers?></td>
            <td><?php echo $data_print[$i]->count_bad_answers?></td>
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

function getQuestionsCategories() {
    $categories = get_terms( array(
        'taxonomy' => 'question_category',
        'hide_empty' => false) );

    $my_json_str = json_encode($categories);

    wp_localize_script( 'script-app', 'categories', $my_json_str );

    return $categories;
}

add_action('wp_ajax_addNewCategory', 'addNewCategory'); // add action for logged users
add_action( 'wp_ajax_nopriv_addNewCategory', 'addNewCategory' ); // add action for unlogged users

// add new category function
function addNewCategory() {
    $category = $_POST['category'];
    $slug = strtolower($category);
    wp_insert_term(

        $category, // the term
        'question_category', // the taxonomy
        array(
            'slug' => $slug,
        )
    );

    wp_die();
}

add_action('wp_ajax_getRandomQuestions', 'getRandomQuestions'); // add action for logged users
add_action( 'wp_ajax_nopriv_getRandomQuestions', 'getRandomQuestions' ); // add action for unlogged users

function getRandomQuestions() {
    $args = array(
        'post_type' => 'questions',
        'orderby'   => 'rand',
        "posts_per_page" => 5,
        'relation' => "AND"
    );


    $category = $_POST['category'];

    $args['tax_query'][0]['taxonomy'] = 'question_category';
    $args['tax_query'][0]['field']    = 'slug';
    $args['tax_query'][0]['terms']    = strtolower($category);


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

    // final JSON


//    $json = json_encode($wp_query->posts);
//    $json[1] = json_encode($arrayCustomFields);

//    $jsonRandomQuestionsTitles = json_encode($wp_query->posts);
//    $jsonRandomQuestionsAnswers = json_encode($arrayCustomFields);

//    wp_localize_script( 'script-app', 'randomQuestionsTitles', $jsonRandomQuestionsTitles );
//    wp_localize_script( 'script-app', 'randomQuestionsAnswers', $jsonRandomQuestionsAnswers );

    echo json_encode(array('data1' => $wp_query->posts, 'data2' => $arrayCustomFields));
    wp_die();
}


function getAllQuestions() {
    $args = array(
        'post_type' => 'questions',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
        }
    }

    $json = json_encode($query->posts);
    wp_localize_script( 'script-app', 'all_questions', $json );
}

add_action('wp_ajax_editAdvertisement', 'editAdvertisement'); // add action for logged users
add_action( 'wp_ajax_nopriv_editAdvertisement', 'editAdvertisement' ); // add action for unlogged users

function editAdvertisement() {
    header('Content-Type: application/html;charset=utf-8');
    $uploadDir = wp_upload_dir();

    // Create post object
    $my_post = array(
        'ID' => $_POST['id'],
        'post_title'    => $_POST['name'],
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
        'post_type' => 'questions'
    );


    $post_id = wp_update_post( $my_post);


    // remove all Categories from current post
    $categories = get_terms( array(
        'taxonomy' => 'question_category',
        'hide_empty' => false,
    ) );

    // wp_set_object_terms(); this function can add new Category
    wp_set_post_terms($_POST['id'], $_POST['category'], 'question_category');
    // wp_set_post_terms($post_id, array(22,23), 'custom_taxonomy'); <-- if you will assign more Categories, simply get all IDs

    // Insert the post into the database
    update_post_meta($post_id, 'answer_a', $_POST['answer_a']);
    update_post_meta($post_id, 'answer_b', $_POST['answer_b']);
    update_post_meta($post_id, 'answer_c', $_POST['answer_c']);
    update_post_meta($post_id, 'answer_d', $_POST['answer_d']);
    update_post_meta($post_id, 'right_answer', $_POST['right_answer']);


    //echo json_encode($arrayUploadedId);
    echo get_permalink($post_id);

    // echo json_encode($uploadedImages);

    wp_die();
}

