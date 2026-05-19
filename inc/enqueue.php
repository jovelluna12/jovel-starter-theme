<?php

function theme_enqueue_assets() {

    $theme = wp_get_theme();

    $is_dev = defined('WP_DEBUG') && WP_DEBUG;

    if ($is_dev) {

        wp_enqueue_script(
            'theme-main',
            'http://localhost:5173/src/js/index.js',
            [],
            null,
            true
        );

        add_filter('script_loader_tag', function ($tag, $handle, $src) {

            if ($handle === 'theme-main') {
                return '<script type="module" src="' . esc_url($src) . '"></script>';
            }

            return $tag;

        }, 10, 3);

    } else {

        $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

        if (!file_exists($manifest_path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (!isset($manifest['js/index.js'])) {
            return;
        }

        $main = $manifest['js/index.js'];

        wp_enqueue_script(
            'theme-main',
            get_template_directory_uri() . '/dist/' . $main['file'],
            [],
            $theme->get('Version'),
            true
        );

        if (!empty($main['css'])) {
            foreach ($main['css'] as $css_file) {

                wp_enqueue_style(
                    'theme-style',
                    get_template_directory_uri() . '/dist/' . $css_file,
                    [],
                    $theme->get('Version')
                );
            }
        }
    }
}

add_action('wp_enqueue_scripts', 'theme_enqueue_assets');