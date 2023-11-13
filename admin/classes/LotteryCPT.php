<?php

namespace CnnPluginBr\Admin;

/** Prevent direct access */
if ( ! function_exists( 'add_action' ) ) {
    header( 'HTTP/1.0 401 Unauthorized' );
    exit;
}

class LotteryCPT {
    
    /**
     * Class instance
     *
     * @var object|null
     * @since 1.0.0
     */
    protected static ?object $instance = null;
    
    /**
     * Define custom post type unique key
     *
     * @var string $cpt_key
     * @since 1.0.0
     */
    public string $cpt_key;
    
    /**
     * Define custom post type name.
     *
     * @var string $cpt_name
     * @since 1.0.0
     */
    public string $cpt_name;
    
    /**
     * Define custom post type labels, keep empty to default labels
     *
     * @var array $cpt_labels
     * @since 1.0.0
     */
    public array $cpt_labels = [];
    
    /**
     * Define custom post type capabilities, keep empty to custom capabilities
     *
     * @var string
     * @since 1.0.0
     */
    public string $cpt_capabilities = '';
    
    /**
     * Set roles with access to post type, administrator always have permission
     *
     * @var array|string[]
     * @since 1.0.0
     */
    public array $add_caps_to_role = [];
    
    /**
     * Define custom post type labels, keep empty to default args
     *
     * @var array $cpt_args
     * @since 1.0.0
     */
    public array $cpt_args = [];
    
    /**
     * Define cpt url base
     *
     * @var string
     * @since 1.0.0
     */
    public string $rewrite = '';
    
    /**
     * LotteryCPT constructor.
     * Use the following attributes to configure your CPT:
     *
     * @var string $cpt_key Unique post type key. Eg.: gallery_cpt
     * @var string $cpt_name The post type name. Eg.: Gallery, News
     * @var array $cpt_labels The post type labels ($my_class_instance::$cpt_labels = ['featured_image' => 'Gallery Cover']...).
     * @var string $cpt_capabilities The post type capabilities type, use to clone capabilities from another post type.
     * @var array $add_caps_to_role Define roles to manage CPT, by default, administrator always have access. Eg.: $my_class_instance::$add_caps_to_role = ['editor', 'my_role']
     * @var array $cpt_args The post type args, to define custom args to your cpt, use: $my_class_instance::$cpt_args = ['menu_position' => 6];
     * @since 1.0.0
     */
    public function __construct() {
        /** Set post type capabilities to admin role */
        add_action( 'admin_init', [ $this, 'setCapsToRole' ] );
        /** Set custom input placeholder to post type edit form */
        add_action( 'enter_title_here', [ $this, 'customPostTypeInputTitlePlaceholder' ], 20);
    }
    
    /**
     * Init custom post type
     * @since 1.0.0
     */
    public function makeCpt(): void {
        register_post_type( $this->cpt_key, self::cptArgs() );
    }
    
    /**
     * Define custom post type default args
     *
     * @return array
     * @since 1.0.0
     */
    private function cptArgs(): array {
        $args = [
            'label'               => $this->cpt_name,
            'description'         => "",
            'labels'              => self::cptLabels(),
            'supports'            => [ 'title', 'editor', 'thumbnail' ],
            'taxonomies'          => [],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-arrow-right',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => self::cptRewrite()['slug'],
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => self::cptRewrite(),
            'show_in_rest'        => true,
            'rest_base'           => self::cptRewrite()['slug'],
        ];
        
        $args['capabilities'] = self::cptCaps();
        if ( $this->cpt_capabilities ) {
            unset( $args['capabilities'] );
            $args['capability_type'] = $this->cpt_capabilities;
        }
        
        return array_merge( $args, $this->cpt_args );
    }
    
    /**
     * Define custom post type labels
     *
     * @return array
     * @since 1.0.0
     */
    private function cptLabels(): array {
        $singular_name = $this->cpt_labels['singular_name'] ?? $this->cpt_name;
        $label_args = [
            'name'                  => $this->cpt_name,
            'singular_name'         => $this->cpt_name,
            'menu_name'             => $this->cpt_name,
            'name_admin_bar'        => $this->cpt_name,
            'archives'              => $this->cpt_name,
            'attributes'            => "Atributos do " . $this->cpt_name,
            'parent_item_colon'     => "Item pai:",
            'all_items'             => "Ver todos",
            'add_new_item'          => "Adicionar novo $singular_name",
            'add_new'               => "Adicionar novo",
            'new_item'              => "Novo $singular_name",
            'edit_item'             => "Editar $singular_name",
            'update_item'           => "Atualizar $singular_name",
            'view_item'             => "Ver $singular_name",
            'view_items'            => "Ver " . $this->cpt_name,
            'search_items'          => "Buscar " . $this->cpt_name,
            'not_found'             => "Nada encontrado",
            'not_found_in_trash'    => "Nada encontrado na lixeira",
            'featured_image'        => "Imagem destacada",
            'set_featured_image'    => "Definir imagem destacada",
            'remove_featured_image' => "Remover imagem destacada",
            'use_featured_image'    => "Usar como imagem destacada",
            'insert_into_item'      => "Inserir no $singular_name",
            'uploaded_to_this_item' => "Enviar para este item",
            'items_list'            => "Lista de items",
            'items_list_navigation' => "Navegar na lista de items",
            'filter_items_list'     => "Filtrar lista de items",
        ];
        
        return array_merge( $label_args, $this->cpt_labels );
    }
    
    /**
     * Define custom post type capabilities
     *
     * @return string[]
     * @since 1.0.0
     */
    private function cptCaps(): array {
        $cpt_key_label = $this->cpt_key;
        
        return [
            'edit_post'              => "edit_$cpt_key_label",
            'read_post'              => "read_$cpt_key_label",
            'delete_post'            => "delete_$cpt_key_label",
            'edit_posts'             => "edit_{$cpt_key_label}s",
            'edit_others_posts'      => "edit_others_{$cpt_key_label}s",
            'publish_posts'          => "publish_{$cpt_key_label}s",
            'delete_posts'           => "delete_{$cpt_key_label}s",
            'delete_private_posts'   => "delete_private_{$cpt_key_label}s",
            'delete_published_posts' => "delete_published_{$cpt_key_label}s",
            'delete_others_posts'    => "delete_others_{$cpt_key_label}s",
            'read_private_posts'     => "read_private_{$cpt_key_label}s",
            'edit_published_posts'   => "edit_published_{$cpt_key_label}s",
            'edit_private_posts'     => "edit_private_{$cpt_key_label}s",
        ];
    }
    
    /**
     * Set role permissions to custom post type
     * @since 1.0.0
     */
    public function setCapsToRole(): void {
        $roles = $this->add_caps_to_role;
        $roles[] = 'administrator';
        foreach ( $roles as $role ):
            foreach ( self::cptCaps() as $cap ):
                get_role( $role )->add_cap( $cap );
            endforeach;
        endforeach;
    }
    
    /**
     * Define a custom input placeholder to post type edit screen
     *
     * @param $input
     *
     * @return mixed|string
     * @since 1.0.0
     */
    public function customPostTypeInputTitlePlaceholder( $input ): ?string {
        global $post_type;
        if ( is_admin() && $this->cpt_key === $post_type ):
            return $this->cpt_args['input_placeholder'] ?? sprintf( __( "Digite o título do %s", "cnn-lottery" ), $this->cpt_name );
        endif;
        
        return $input;
    }
    
    /**
     * Define custom post type rewrite params
     *
     * @return array
     * @since 1.0.0
     */
    private function cptRewrite(): array {
        $rewrite = sanitize_title( $this->cpt_name );
        
        return [
            'slug'       => $this->rewrite ?? $rewrite,
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        ];
    }
}
