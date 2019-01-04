<?php
if(!class_exists('Ajax_Post_Setting_Admin')){
	class Ajax_Post_Setting_Admin{
		public static $_instance = null;
		public static function instance(){
			if ( is_null( self::$_instance )) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		public function __construct(){
			$this->settings_group = 'ajax_pagination_setting';
			add_action( 'admin_init', array( $this, 'ajax_pagination_register_settings' ));
			add_action( 'admin_menu', array( $this, 'sub_menu_page_init' ));
		}
		public function init_ajax_pagination_setting(){
			$this->settings = apply_filters( 'ajax_pagination_settings',
				array(
					'ajax_pagination_ok' => array(
						__( 'General Ajax Pagination(copy shortcode [psa_pagination_blog] paste to page, post)', 'ajp' ),
						array(
							array(
								'name'        => 'ajp_per_page',
								'label'      => __( 'Post per page', 'ajp' ),
								'desc'       => __( 'Input post per page.ex 3', 'ajp'),
								'type'       => 'text',
								'std'        => '3',
							),
							array(
								'name'    => 'show_image',
								'label'   => __('Show or Hide post image','ajp'),
								'desc'    => __('checked show or hide of post image'),
								'type'    => 'checkbox',
								'std'     => '1',
								'attributes' => array()
							),
							array(
								'name'        => 'choice_pagination',
								'label'      => __( 'Style Pagination', 'ajp' ),
								'desc'       => __( 'please choice pagination', 'ajp'),
								'type'       => 'select',
								'std'        => 'piala_load',
								'options' => array(
									'piala_load' => 'Load More',
									'piala_pagination' => 'Pagination',
								)
							),
							array(
								'name'        => 'size_image',
								'label'      => __( 'Style Layer', 'ajp' ),
								'desc'       => __( 'please choice style layer', 'ajp'),
								'type'       => 'select',
								'std'        => 'gird',
								'options' => array(
									'ajp_gird' => 'Gird',
									'ajp_list' => 'List',
								)
							),
						),
					),
				)
			);
		}
		public function ajax_pagination_register_settings(){
			$this->init_ajax_pagination_setting();
			foreach ( $this->settings as $section ) {
				foreach ( $section[1] as $option ) {
					if ( isset( $option['std'] ) )
						add_option( $option['name'], $option['std'] );
					register_setting( $this->settings_group, $option['name'] );
				}
			}
		}
		public function sub_menu_page_init() {
			add_menu_page(
				'Post Ajax Slider'
				, 'Post Ajax Slider'
				, 'manage_options'
				, 'plugin_settings'
				, array($this, 'ajax_pagination_settings_render')
				, null
				, null
			);
		}
		public function ajax_pagination_settings_render() { 
		?>
        <div class="wrap ajax_pagination-settings-wrap">
            <form method="post" action="options.php">
                <?php settings_fields( $this->settings_group ); ?>
                <h2 class="nav-tab-wrapper">
                    <?php
					$i=0;
					foreach ( $this->settings as $key => $section ) {?>
						<a href="#settings-<?php echo sanitize_title( $key );?>" class="nav-tab <?php if($i==0) echo 'nav-tab-active';?>"><?php echo esc_html($section[0]);?></a>
					<?php $i++;}?>
                </h2>
					<?php
                    if ( ! empty( $_GET['settings-updated'] ) ) {
                        flush_rewrite_rules();
                        echo '<div class="updated job-manager-updated"><p>' . __( 'Settings successfully saved', 'sm' ) . '</p></div>';
                    }
                    foreach ( $this->settings as $key => $section ) {
                        echo '<div id="settings-' . sanitize_title( $key ) . '" class="settings_panel">';
                        echo '<table class="form-table">';
                        foreach ( $section[1] as $option ) {
                            $placeholder    = ( ! empty( $option['placeholder'] ) ) ? 'placeholder="' . $option['placeholder'] . '"' : '';
                            $class          = ! empty( $option['class'] ) ? $option['class'] : '';
                            $value          = get_option( $option['name'] );
                            $option['type'] = ! empty( $option['type'] ) ? $option['type'] : '';
                            $attributes     = array();
                            if ( ! empty( $option['attributes'] ) && is_array( $option['attributes'] ) )
                                foreach ( $option['attributes'] as $attribute_name => $attribute_value )
                                    $attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';
                            echo '<tr valign="top" class="' . $class . '"><th scope="row"><label for="setting-' . $option['name'] . '">' . $option['label'] . '</a></th><td>';
                            switch ( $option['type'] ) {
                                case "text" :
                                    ?><input id="setting-<?php echo $option['name']; ?>" class="<?php echo $option['name']; ?>a" type="text" name="<?php echo $option['name']; ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> style="width:20%"/><?php
                                    if ( $option['desc'] ) {
                                        echo ' <p class="description">' . $option['desc'] . '</p>';
                                    }
                                break;
								case "checkbox" :
                                    ?><label><input id="setting-<?php echo $option['name']; ?>" name="<?php echo $option['name']; ?>" type="checkbox" value="1" <?php echo implode( ' ', $attributes ); ?> <?php checked( '1', $value ); ?> /></label><?php
                                    if ( $option['desc'] )
                                        echo ' <span class="description">' . $option['desc'] . '</span>';
                                break;
                                case "select" :
                                    ?><select id="setting-<?php echo $option['name']; ?>" class="<?php echo $option['name']; ?>a" name="<?php echo $option['name']; ?>" <?php echo implode( ' ', $attributes ); ?>><?php
                                        foreach( $option['options'] as $key => $name )
                                            echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $name ) . '</option>';
                                    ?></select><?php
                                    if ( $option['desc'] ) {
                                        echo ' <p class="description">' . $option['desc'] . '</p>';
                                    }
                                break;
                                default :
                                    do_action( 'ajax_pagination_admin_field_' . $option['type'], $option, $attributes, $value, $placeholder );
                                break;
                            }
                            echo '</td></tr>';
                        }
                        echo '</table></div>';
                    }
                ?>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'sm' ); ?>" />
                </p>
            </form>
        </div>
		<?php }
	}
}
Ajax_Post_Setting_Admin::instance();