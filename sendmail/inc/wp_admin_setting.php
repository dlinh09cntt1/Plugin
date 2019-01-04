<?php
if(!class_exists('SendMail_Setting_Admin')){
	class SendMail_Setting_Admin{
		public $fonts = false;
		public static $_instance = null;
		public static function instance(){
			if ( is_null( self::$_instance )) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		public function __construct(){
			$this->settings_group = 'sendmail_setting';
			add_action( 'admin_init', array( $this, 'sendmail_register_settings' ));
			add_action( 'admin_menu', array( $this, 'sub_menu_page_init' ));
		}
		public function init_sendmail_setting(){
			$this->settings = apply_filters( 'sendmail_settings',
				array(
					'sendmail_ok' => array(
						__( 'General Send Mail', 'sm' ),
						array(
							array(
								'name'        => 'email_personnel',
								'label'      => __( 'Email', 'sm' ),
								'desc'       => __( 'Input Email of text field. Copy the shortcode [sendmail_piala] into the post', 'sm'),
								'type'       => 'text',
								'std'        => 'dlinh09cntt1@gmail.com',
							)
						),
					),
				)
			);
		}
		public function sendmail_register_settings(){
			$this->init_sendmail_setting();
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
				'PIALA SendMail'
				, 'PIALA SendMail'
				, 'manage_options'
				, 'plugin_settings'
				, array($this, 'sendmail_settings_render')
				, null
				, null
			);
		}
		public function sendmail_settings_render() { 
		?>
        <div class="wrap sendmail-settings-wrap">
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
                                default :
                                    do_action( 'sendmail_admin_field_' . $option['type'], $option, $attributes, $value, $placeholder );
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
SendMail_Setting_Admin::instance();