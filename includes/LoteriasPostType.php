<?php
// Registra o post type "Loterias"
function createPostType() {
    register_post_type('loterias',
        array(
            'labels' => array(
                'name' => __('Loterias'),
                'singular_name' => __('Loteria')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
            'rewrite' => array('slug' => 'loterias'),
        )
    );
}
add_action('init', 'createPostType');
?>
