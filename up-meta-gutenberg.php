<?php
/**
 * Plugin Name: UP Meta Gutenberg
 * Description: Bloc Gutenberg pour l'affichage et le filtrage de métadonnées
 * Version: 1.0.0
 * Author: UP
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: up-meta-gutenberg
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('UP_META_GUTENBERG_VERSION', '1.0.0');
define('UP_META_GUTENBERG_PATH', plugin_dir_path(__FILE__));
define('UP_META_GUTENBERG_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class UP_Meta_Gutenberg {
    /**
     * Instance unique de la classe
     */
    private static $instance = null;

    /**
     * Constructeur
     */
    private function __construct() {
        add_action('init', [$this, 'init']);
    }

    /**
     * Singleton pattern
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialisation du plugin
     */
    public function init() {
        // Chargement des traductions
        load_plugin_textdomain('up-meta-gutenberg', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Enregistrement du bloc
        register_block_type(
            UP_META_GUTENBERG_PATH . 'build',
            [
                'render_callback' => [$this, 'render_block']
            ]
        );
    }

    /**
     * Render callback for the block
     */
    public function render_block($attributes, $content, $block) {
        $render_file = UP_META_GUTENBERG_PATH . 'build/render.php';
        if (file_exists($render_file)) {
            include_once $render_file;
            if (function_exists('render_meta_display')) {
                return render_meta_display($attributes, $content, $block);
            }
        }
        return '';
    }
}

// Initialisation du plugin
function up_meta_gutenberg_init() {
    return UP_Meta_Gutenberg::get_instance();
}

// Démarrage du plugin
add_action('plugins_loaded', 'up_meta_gutenberg_init');
