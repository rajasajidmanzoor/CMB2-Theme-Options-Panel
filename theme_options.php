<?php /**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class MyTheme_Admin {  
	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'mytheme_options';  
	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'mytheme_option_metabox'; 
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = array(); 
	/**
	 * Holds an instance of the object
	 *
	 * @var Myprefix_Admin
	 **/
	private static $instance = null; 
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Theme Options', 'mytheme' );
	}

  /**CMB array **/
  protected $cmb=array();
  
	/**
	 * Returns the running object
	 *
	 * @return Myprefix_Admin
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
  // add_action( 'cmb2_admin_init', array( $this, 'add_counters_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );  
		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {  
      $option_tabs = self::add_options_page_metabox(); //get all option tabs
    	$tab_forms = array();       
      //echo '<pre>';
          //   print_r($option_tabs);
       //echo '</pre>';
      
		?>
    <link rel='stylesheet' id='theme_options-css'  href='<?php echo get_stylesheet_directory_uri();  ?>/css/theme_options.css' type='text/css' media='all' />
    <?php //Put theme css file in your theme directory ?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2> 			
      <!--Accordions-->
      <?php foreach($option_tabs as $option_tab) { ?>
         <?php 
      
          ?>
          <button class="accordion"><?php echo  $option_tab->meta_box['title'];  ?></button>
          <div class="panel">
            
            <?php $tab_forms[] = $option_tab; 
					cmb2_metabox_form( $option_tab->meta_box['id'], $option_tab->meta_box['show_on']['key'] );               
                ?>
            <div class="clear"></div>
          </div>          
      <?php } ?>
      

      <!--Accordions-->
      
      <script type="text/javascript">
                var acc = document.getElementsByClassName("accordion");
                var i;
                
                for (i = 0; i < acc.length; i++) {
                    acc[i].onclick = function(){
                        this.classList.toggle("active");
                        this.nextElementSibling.classList.toggle("show");
                    }
                }
      </script>
      
      
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {
  
  
   // Only need to initiate the array once per page-load
        if ( ! empty( $this->option_metabox ) ) {
            return $this->option_metabox;
        }        



		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 ); 
		
    $this->cmb[0] = new_cmb2_box( array(
			'id'         => $this->metabox_id,
      'title'=> 'Header Options',
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key )
			),
		) );

		// Set our CMB2 fields
    
	$this->cmb[0]->add_field( array(
          'name'    => 'Website title',
          'desc'    => 'Enter Website title',
          'default' => '',
          'id'      => 'site_title',
          'type'    => 'text',
		) );
	
	$this->cmb[0]->add_field( array(
          'name'    => 'Website Logo',
          'desc'    => 'Enter Website logo',
          'default' => '',
          'id'      => 'site_logo',
          'type'    => 'file',
		) );	
		
    
    $group_field_id = $this->cmb[0]->add_field( array(
    'id'          => 'social_icons',
    'type'        => 'group',
    'description' => __( 'Please add/delete Languages below', 'cmb2' ),
    // 'repeatable'  => false, // use false if you want non-repeatable group
    'options'     => array(
        'group_title'   => __( 'Social Icon {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Social Iocn', 'cmb2' ),
        'remove_button' => __( 'Remove Social icon', 'cmb2' ),
        'sortable'      => true, // beta
        'closed'     => true, // true to have the groups closed by default
    ),
    ) );

      // Id's for group's fields only need to be unique for the group. Prefix is not needed.
      $this->cmb[0]->add_group_field( $group_field_id, array(
          'name' => 'Icon Title',
          'id'   => 'title',
          'type' => 'text',
          // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
      ) );
      
      $this->cmb[0]->add_group_field( $group_field_id, array(
          'name' => 'Icon Hyperlink Url',
          'id'   => 'url',
          'type' => 'text'         
      ) );
      
      $this->cmb[0]->add_group_field( $group_field_id, array(
          'name' => 'Icon Image',
          'id'   => 'image',
          'type' => 'file',
      ) );
      
      
      

      
      /*** General Info ***/
		$this->cmb[1] = new_cmb2_box( array(
			'id'         => 'generl info',
			'title' =>'General Information',
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'general_info', )
			),
		) );
       
	   
	    $this->cmb[1]->add_field( array(
          'name'    => 'Content Maximum Width',
          'desc'    => 'Enter maxium width',
          'default' => '',
          'id'      => 'max_width',
          'type'    => 'text',
		) );
		
		
		$this->cmb[1]->add_field( array(
          'name'    => 'Body Background Color',
          'desc'    => 'body bg color',
          'default' => '',
          'id'      => 'bg_color',
          'type'    => 'colorpicker',
		) );
		
		$this->cmb[1]->add_field( array(
          'name'    => 'Body Background Image',
          'desc'    => 'body bg image',
          'default' => '',
          'id'      => 'bg_image',
          'type'    => 'file',
		) );
	 
	 
	 /*** General Info ***/
       
     /*** Footer Info ***/ 
     
      
      
    $this->cmb[2] = new_cmb2_box( array(
			'id'         => 'footer_contact_info',
      'title' =>'Contact and footer Information',
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'footer_info', )
			),
	   	) );
      
      $this->cmb[2]->add_field( array(
          'name'    => 'Address',
          'desc'    => 'Enter address for footer, Feel free to use html tags.',
          'default' => '',
          'id'      => 'address',
          'type'    => 'textarea',
      ) );
      
      
      $this->cmb[2]->add_field( array(
          'name'    => 'Phone Number',
          'desc'    => 'Enter Phone Number',
          'default' => '',
          'id'      => 'phone',
          'type'    => 'text',
      ) );
      
      $this->cmb[2]->add_field( array(
          'name'    => 'Fax',
          'desc'    => 'Enter Fax',
          'default' => '',
          'id'      => 'fax',
          'type'    => 'text',
      ) );
      
      $this->cmb[2]->add_field( array(
          'name'    => 'Email Address',
          'desc'    => 'Enter Email Address',
          'default' => '',
          'id'      => 'email',
          'type'    => 'text',
      ) );
      
      $this->cmb[2]->add_field( array(
          'name'    => 'Website Address',
          'desc'    => 'Enter Website Address',
          'default' => '',
          'id'      => 'website',
          'type'    => 'text',
      ) );
      
      
      $this->cmb[2]->add_field( array(
          'name'    => 'Copyright Statement',
          'desc'    => 'Enter Copyright Statement. Feekl free to use HTML',
          'default' => '',
          'id'      => 'copyright',
          'type'    => 'textarea',
      ) );

		


    /**Add BOXES HERE ***/
     return $this->cmb;
      
	}
  
  

  
  

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'mytheme' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
    
     if ( 'cmb' === $field ) {
            return $this->option_fields();
        }

		throw new Exception( 'Invalid property: ' . $field );
	}
  
  
   /**
     * Returns the option key for a given field id
     * @since  0.1.0
     * @return array
     */
    public function get_option_key($field_id) {
    	$option_tabs = $this->add_options_page_metabox();
    	foreach ($option_tabs as $option_tab) { //search all tabs
    		foreach ($option_tab->meta_box['fields'] as $field) { //search all fields
    			if ($field['id'] == $field_id) {
    				return $option_tab->meta_box['id'];
          //  print_r($option_tab->meta_box['id']);
    			}
    		}
    	}
    	return $this->key; //return default key if field id not found
    }
    
    

}



/**
 * Helper function to get/return the Myprefix_Admin object
 * @since  0.1.0
 * @return Myprefix_Admin object
 */
function mytheme_admin() {
	return MyTheme_Admin::get_instance();
}




/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
 //$my_Admin = new MyTheme_Admin();
function mytheme_get_option( $key = '' ) {
//print_r(mytheme_admin()->key);
	//return cmb2_get_option( mytheme_admin()->key, $key, true );   
    return cmb2_get_option(mytheme_admin()->get_option_key($key), $key);        
}

// Get it started
mytheme_admin();
