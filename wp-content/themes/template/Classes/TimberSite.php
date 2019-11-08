<?php
namespace Digitalwerk;

/**
 * Class TimberSite
 * @package Digitalwerk
 */
class TimberSite extends \TimberSite
{

    /**
     * TemplateSite constructor.
     */
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'afterSetupTheme']);
        add_filter('timber/context', [$this, 'addToTimberContext']);
        add_filter('timber/twig', [$this, 'addToTwig']);
        add_action('wp_enqueue_scripts', [$this, 'addThemeStylesAndScripts']);
        add_action('init', [$this, 'wordpressInit']);
        add_action('acf/init', [$this, 'ACFInit']);
        add_action('admin_menu', [$this, 'adminMenu']);

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');

        parent::__construct();
    }

    /**
     * Wordpress initialization (init)
     */
    public function wordpressInit()
    {
    }

    /**
     * ACF initialization
     */
    public function ACFInit()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title'    => 'Theme General Settings',
                'menu_title'    => 'Theme Settings',
                'menu_slug'     => 'theme-general-settings',
                'capability'    => 'edit_posts',
            ]);
        }
    }

    /**
     * Theme setup
     */
    public function afterSetupTheme()
    {
        // add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('menus');
    }

    /**
     * Add Timber context
     *
     * @param array $context context['this'] Being the Twig's {{ this }}.
     * @return array
     */
    public function addToTimberContext($context)
    {
        $context['menu'] = [
            'main' => new \TimberMenu('main-menu'),
            'footer' => new \TimberMenu('footer-menu'),
            'meta' => new \TimberMenu('meta-menu'),
            'sitemap' => new \TimberMenu('footer-menu-sitemap')
        ];

        $context['site'] = $this;
        $context['RenderFCE'] = new \Timber\FunctionWrapper('RenderFCE');

        $context['options_page_header_logo'] = get_field('header_logo', 'option');
        $context['options_page_footer_logo'] = get_field('footer_logo', 'option');
        $context['options_page_header_number'] = get_field('number', 'option');

        return $context;
    }

    /**
     * Add to Twig
     *
     * @param \Twig\Environment $twig get extension.
     * @return \Twig\Environment
     */
    public function addToTwig($twig)
    {
        $twig->addExtension(new \Twig\Extension\StringLoaderExtension());
        $twig->addFilter(new \Twig\TwigFilter('secureContentEmails', [$this, 'secureContentEmails']));
        /** Function for fetch page option field */
        $twig->addFunction(new \Twig\TwigFunction('getOption', function($field) {
            return \get_field($field, 'option');
        }));
        return $twig;
    }

    /**
     * Add CSS styles and JS scripts
     */
    public function addThemeStylesAndScripts()
    {
        // Enqueue resources only for FE
        if (!is_admin()) {
            wp_enqueue_style('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css', [], '4.3.1');
            wp_enqueue_style('main-style', get_template_directory_uri() . '/style.css', ['bootstrap'], '0.0.1');
        }
    }

    /**
     * Modify WP admin menu
     */
    public function adminMenu()
    {
        // remove Posts
        remove_menu_page('edit.php');
        // remove Comments
        remove_menu_page('edit-comments.php');
    }

    /**
     * Encode email against boots
     *
     * @param string $content
     * @return string
     */
    public function secureContentEmails($content): string
    {
        $mail_regex = '[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+';

//        \var_dump(get_field('theme_settings__cookie_info_box__text','options'));

        if (preg_match_all('~(' . $mail_regex . ')~im', $content, $matches)) {
            foreach ($matches[ 1 ] as $match) {
                $content = str_replace($match, antispambot($match), $content);
            }
        }

        return $content;
    }
}
