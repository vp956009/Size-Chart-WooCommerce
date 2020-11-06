<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCSCW_menu')) {

    class OCSCW_menu {

        protected static $instance;
     
        function OCSCW_create_menu() {
            $post_type = 'size_chart';
            $singular_name = 'Size Chart';
            $plural_name = 'Size Charts';
            $slug = 'size_chart';
            $labels = array(
                'name'               => _x( $plural_name, 'post type general name', OCSCW_DOMAIN ),
                'singular_name'      => _x( $singular_name, 'post type singular name', OCSCW_DOMAIN ),
                'menu_name'          => _x( $singular_name, 'admin menu name', OCSCW_DOMAIN ),
                'name_admin_bar'     => _x( $singular_name, 'add new name on admin bar', OCSCW_DOMAIN ),
                'add_new'            => __( 'Add New', OCSCW_DOMAIN ),
                'add_new_item'       => __( 'Add New '.$singular_name, OCSCW_DOMAIN ),
                'new_item'           => __( 'New '.$singular_name, OCSCW_DOMAIN ),
                'edit_item'          => __( 'Edit '.$singular_name, OCSCW_DOMAIN ),
                'view_item'          => __( 'View '.$singular_name, OCSCW_DOMAIN ),
                'all_items'          => __( 'All '.$plural_name, OCSCW_DOMAIN ),
                'search_items'       => __( 'Search '.$plural_name, OCSCW_DOMAIN ),
                'parent_item_colon'  => __( 'Parent '.$plural_name.':', OCSCW_DOMAIN ),
                'not_found'          => __( 'No Table found.', OCSCW_DOMAIN ),
                'not_found_in_trash' => __( 'No Table found in Trash.', OCSCW_DOMAIN )
            );

            $args = array(
                'labels'             => $labels,
                'description'        => __( 'Description.', OCSCW_DOMAIN ),
                'public'             => false,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => $slug ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array( 'title','editor','thumbnail' ),
                'menu_icon'          => 'dashicons-chart-pie'
            );
            register_post_type( $post_type, $args );
        }

      
        function OCSCW_add_meta_box() {
            add_meta_box(
                'OCSCW_metabox',
                __( 'All Size Chart Settings', OCSCW_DOMAIN ),
                array($this, 'OCSCW_metabox_cb'),
                'size_chart',
                'normal'
            );
        }


        function OCSCW_metabox_cb( $post ) {
            // Add a nonce field so we can check for it later.
            wp_nonce_field( 'ocscw_meta_save', 'ocscw_meta_save_nounce' );
            ?> 
            <div class="ocscw-container">
                <ul class="tabs">
                    <li class="tab-link current" data-tab="tab-default">
                        <?php echo __( 'Chart', OCSCW_DOMAIN );?>
                    </li>
                    <li class="tab-link" data-tab="tab-general">
                        <?php echo __( 'Chart Show Settings', OCSCW_DOMAIN );?>
                    </li>
                    <li class="tab-link" data-tab="tab-table">
                        <?php echo __( 'Table Settings', OCSCW_DOMAIN );?>
                    </li>
                    <li class="tab-link" data-tab="tab-tab">
                        <?php echo __( 'Tab Settings', OCSCW_DOMAIN );?>
                    </li>
                </ul>
                <div id="tab-default" class="tab-content current">
                    <h2><?php echo __( "Create Chart", OCSCW_DOMAIN );?></h2>
                    <div class="ocscw_child_div">
                        <?php 
                            $table = get_post_meta( $post->ID, OCSCW_PREFIX.'size_chartdata', true); 
                            $table_array = unserialize($table);

                            if(!empty($table_array[0])){
                                $totalrow = get_post_meta( $post->ID, OCSCW_PREFIX.'totalrow', true);
                                $totalcol = get_post_meta( $post->ID, OCSCW_PREFIX.'totalcol', true);
                                echo '<table class="ocscw_chart_tbl">';
                                    echo '<input type="hidden" name="totalrow" value="'.$totalrow.'">';
                                    echo '<input type="hidden" name="totalcol" value="'.$totalcol.'">';

                                    $count = 0;

                                    /*first row create*/
                                    $tr = '<tr><td><a class="addcolumn"><img src= "'. OCSCW_PLUGIN_DIR . '/includes/images/plus.png"></a></td>';

                                        for($j=0;$j<$totalcol-1;$j++){
                                            $tr .='<td><a class="addcolumn"><img src= "'. OCSCW_PLUGIN_DIR . '/includes/images/plus.png"></a><a class="deletecolumn"><img src= "'. OCSCW_PLUGIN_DIR . '/includes/images/delete.png"></a></td>';   
                                        }
                                    $tr .= '<td></td></tr>';
                                    /*end first row create*/

                                    for($i=0;$i<$totalrow;$i++){
                                        
                                        $tr .= "<tr>";
                                            $td = "";

                                            for($j=0;$j<$totalcol;$j++){
                                                $td .='<td><input type="text" name="size_chartdata[]" value="'.$table_array[$count].'"></td>';
                                                $count++;
                                            }
                                            if($count == $totalcol){
                                                $td .='<td><a class="addrow"><img src= "'. OCSCW_PLUGIN_DIR . '/includes/images/plus.png"></a></td>';
                                            }else{
                                                $td .='<td><a class="addrow"><img src= "'. OCSCW_PLUGIN_DIR . '/includes/images/plus.png"></a><a class="deleterow"><img src= "'. OCSCW_PLUGIN_DIR . '/includes/images/delete.png"></a></td>';
                                            }
                                            
                                            $tr .= $td;
                                            
                                        $tr .= "</tr>";
                                    }
                                    echo $tr;
                                echo '</table>';
                            }else{
                                ?>
                                <table class="ocscw_chart_tbl">
                                    <input type="hidden" name="totalrow">
                                    <input type="hidden" name="totalcol">
                                    <tr>
                                        <td>
                                            <a class="addcolumn">
                                                <img src= " <?php echo OCSCW_PLUGIN_DIR . '/includes/images/plus.png' ?>">
                                            </a>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="size_chartdata[]"></td>
                                        <td>
                                            <a class="addrow">
                                                <img src= " <?php echo OCSCW_PLUGIN_DIR . '/includes/images/plus.png' ?>">
                                            </a>   
                                        </td>
                                    </tr>
                                </table> 
                                <?php
                            }
                            
                        ?>
                         
                    </div>
                </div>
                <div id="tab-general" class="tab-content">
                    <h2><?php echo __( "Show Setting", OCSCW_DOMAIN );?></h2>
                    <div class="ocscw_child_div">
                        <table>
                            <tr>
                                <th>Show Chart</th>
                                <?php $btn_show = get_post_meta( $post->ID, OCSCW_PREFIX.'btn_show', true); ?>
                                <td>
                                    <input type="radio" name="btn_show" value="tab" <?php if($btn_show == "tab"){ echo "checked"; } ?>>In Product Tab</br></br>
                                    <input type="radio" name="btn_show" value="popup" <?php if($btn_show == "popup"){ echo "checked"; } ?>>Popup
                                </td>
                            </tr>   
                            <tr>
                                <th>Sub Title</th>
                                <?php $sub_title = get_post_meta( $post->ID, OCSCW_PREFIX.'sub_title', true); ?>
                                <td>
                                    <input type="text" name="sub_title" value="<?php echo $sub_title; ?>">
                                </td>
                            </tr> 
                        </table>
                    </div>
                    <div class="ocscw_tab_div" style="display: none;">
                        <h2><?php echo __( "Product Tab Setting", OCSCW_DOMAIN );?></h2>
                        <div class="ocscw_child_div">
                            <table>
                                <tr>
                                    <th>Tab Label</th>
                                    <?php $tab_lbl = get_post_meta( $post->ID, OCSCW_PREFIX.'tab_lbl', true); ?>
                                    <td>
                                        <input type="text" name="tab_lbl" value="<?php echo $tab_lbl; ?>">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="ocscw_popup_div" style="display: none;">
                        <h2><?php echo __( "Popup Button Setting", OCSCW_DOMAIN );?></h2>
                        <div class="ocscw_child_div">
                            <table>
                                <tr>
                                    <th>Button Label</th>
                                    <?php $btn_lbl = get_post_meta( $post->ID, OCSCW_PREFIX.'btn_lbl', true); ?>
                                    <td>
                                        <input type="text" name="btn_lbl" value="<?php echo $btn_lbl; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Button Text color</th>
                                    <?php $btn_ft_clr = get_post_meta( $post->ID, OCSCW_PREFIX.'btn_ft_clr', true); ?>
                                    <td>
                                        <input type="color" name="btn_ft_clr" value="<?php echo $btn_ft_clr; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Button Border Radius</th>
                                    <?php $btn_brd_rd = get_post_meta( $post->ID, OCSCW_PREFIX.'btn_brd_rd', true); ?>
                                    <td>
                                        <input type="number" name="btn_brd_rd" value="<?php echo $btn_brd_rd; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Button background color</th>
                                    <?php $btn_bg_clr = get_post_meta( $post->ID, OCSCW_PREFIX.'btn_bg_clr', true); ?>
                                    <td>
                                        <input type="color" name="btn_bg_clr" value="<?php echo $btn_bg_clr; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Button Position</th>
                                    <?php $btn_pos = get_post_meta( $post->ID, OCSCW_PREFIX.'btn_pos', true); ?>
                                    <td>
                                        <input type="radio" name="btn_pos" value="before_add_cart" <?php if($btn_pos == "before_add_cart"){ echo "checked"; } ?>>Before Add To Cart</br></br>
                                        <input type="radio" name="btn_pos" value="after_add_cart" <?php if($btn_pos == "after_add_cart"){ echo "checked"; } ?>>After Add To Cart
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <h2><?php echo __( "User Setting", OCSCW_DOMAIN );?></h2>
                    <div class="ocscw_child_div">
                        <table>
                            <tr>
                                <th>Show Chart to Visiter User</th>
                                <?php $alw_gust_usr = get_post_meta( $post->ID, OCSCW_PREFIX.'alw_gust_usr', true); ?>
                                <td>
                                    <input type="checkbox" name="alw_gust_usr" <?php if($alw_gust_usr == "on"){ echo "checked"; } ?>>
                                    <strong>Show Size Chart For Visiter User.</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="tab-table" class="tab-content">
                    <h2><?php echo __( "table Setting", OCSCW_DOMAIN );?></h2>
                    <div class="ocscw_child_div">
                        <table>
                            <tr>
                                <th>Table Head Background Color</th>
                                <?php $tbl_head_bg_clr = get_post_meta( $post->ID, OCSCW_PREFIX.'tbl_head_bg_clr', true); ?>
                                <td>
                                    <input type="color" name="tbl_head_bg_clr" value="<?php echo $tbl_head_bg_clr; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>Table Head Font Color</th>
                                <?php $tbl_head_ft_clr = get_post_meta( $post->ID, OCSCW_PREFIX.'tbl_head_ft_clr', true); ?>
                                <td>
                                    <input type="color" name="tbl_head_ft_clr" value="<?php echo $tbl_head_ft_clr; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>Table Even Row Color</th>
                                <?php $tbl_even_row_clr = get_post_meta( $post->ID, OCSCW_PREFIX.'tbl_even_row_clr', true); ?>
                                <td>
                                    <input type="color" name="tbl_even_row_clr" value="<?php echo $tbl_even_row_clr; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>Table Odd Raw Color</th>
                                <?php $tbl_odd_row_clr = get_post_meta( $post->ID, OCSCW_PREFIX.'tbl_odd_row_clr', true); ?>
                                <td>
                                    <input type="color" name="tbl_odd_row_clr" value="<?php echo $tbl_odd_row_clr; ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="tab-tab" class="tab-content">
                    <h2><?php echo __( "Tab Setting", OCSCW_DOMAIN );?></h2>
                    <div class="ocscw_child_div">
                        <table>
                            <tr>
                                <th>Show Tab Wise Content</th>
                                <?php $show_tab = get_post_meta( $post->ID, OCSCW_PREFIX.'show_tab', true); ?>
                                <td>
                                    <input type="checkbox" name="show_tab" <?php if($show_tab == "on"){ echo "checked"; } ?>>
                                </td>
                            </tr>
                            <tr>
                                <th>Chart Tab Name</th>
                                <?php $chart_tab_name = get_post_meta( $post->ID, OCSCW_PREFIX.'chart_tab_name', true); ?>
                                <td>
                                    <input type="textbox" name="chart_tab_name" value="<?php echo $chart_tab_name; ?>">
                                </td>
                            </tr>
                            <tr>
                                <th>Description Tab Name</th>
                                <?php $dis_tab_name = get_post_meta( $post->ID, OCSCW_PREFIX.'dis_tab_name', true); ?>
                                <td>
                                    <input type="textbox" name="dis_tab_name" value="<?php echo $dis_tab_name; ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }


        function OCSCW_recursive_sanitize_text_field($array) {
         
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = $this->OCSCW_recursive_sanitize_text_field($value);
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
            return $array;
        }


        function OCSCW_meta_save( $post_id, $post ){
         
            if ($post->post_type != 'size_chart') {return;}
         
            if ( !current_user_can( 'edit_post', $post_id )) return;
            $is_autosave = wp_is_post_autosave($post_id);
            $is_revision = wp_is_post_revision($post_id);
            $is_valid_nonce = (isset($_POST['ocscw_meta_save_nounce']) && wp_verify_nonce( $_POST['ocscw_meta_save_nounce'], 'ocscw_meta_save' )? 'true': 'false');

            if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;

               
            $size_chartdata   = $this->OCSCW_recursive_sanitize_text_field( $_REQUEST['size_chartdata'] );
            $totalrow         = sanitize_text_field( $_REQUEST['totalrow'] );
            $totalcol         = sanitize_text_field( $_REQUEST['totalcol'] );
            $btn_show         = sanitize_text_field( $_REQUEST['btn_show'] );
            $sub_title        = sanitize_text_field( $_REQUEST['sub_title'] );
            $tab_lbl          = sanitize_text_field( $_REQUEST['tab_lbl'] );
            $btn_lbl          = sanitize_text_field( $_REQUEST['btn_lbl'] );
            $btn_ft_clr       = sanitize_text_field( $_REQUEST['btn_ft_clr'] );
            $btn_bg_clr       = sanitize_text_field( $_REQUEST['btn_bg_clr'] );
            $btn_pos          = sanitize_text_field( $_REQUEST['btn_pos'] );
            $alw_gust_usr     = sanitize_text_field( $_REQUEST['alw_gust_usr'] );
            $tbl_head_bg_clr  = sanitize_text_field( $_REQUEST['tbl_head_bg_clr'] );
            $tbl_head_ft_clr  = sanitize_text_field( $_REQUEST['tbl_head_ft_clr'] );
            $tbl_even_row_clr = sanitize_text_field( $_REQUEST['tbl_even_row_clr'] );
            $tbl_odd_row_clr  = sanitize_text_field( $_REQUEST['tbl_odd_row_clr'] );
            $show_tab         = sanitize_text_field( $_REQUEST['show_tab'] );
            $chart_tab_name   = sanitize_text_field( $_REQUEST['chart_tab_name'] );
            $dis_tab_name     = sanitize_text_field( $_REQUEST['dis_tab_name'] );
            $btn_brd_rd       = sanitize_text_field( $_REQUEST['btn_brd_rd'] );


            update_post_meta( $post_id, OCSCW_PREFIX.'size_chartdata', serialize($size_chartdata) );
            update_post_meta( $post_id, OCSCW_PREFIX.'totalrow', $totalrow );
            update_post_meta( $post_id, OCSCW_PREFIX.'totalcol', $totalcol );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_show', $btn_show );
            update_post_meta( $post_id, OCSCW_PREFIX.'sub_title', $sub_title );
            update_post_meta( $post_id, OCSCW_PREFIX.'tab_lbl', $tab_lbl );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_lbl', $btn_lbl );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_ft_clr', $btn_ft_clr );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_bg_clr', $btn_bg_clr );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_pos', $btn_pos );
            update_post_meta( $post_id, OCSCW_PREFIX.'alw_gust_usr', $alw_gust_usr );
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_head_bg_clr',$tbl_head_bg_clr);
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_head_ft_clr',$tbl_head_ft_clr);
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_even_row_clr',$tbl_even_row_clr);
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_odd_row_clr',$tbl_odd_row_clr);
            update_post_meta( $post_id, OCSCW_PREFIX.'show_tab',$show_tab);
            update_post_meta( $post_id, OCSCW_PREFIX.'chart_tab_name', $chart_tab_name);
            update_post_meta( $post_id, OCSCW_PREFIX.'dis_tab_name', $dis_tab_name);
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_brd_rd', $btn_brd_rd);
        }

 
        function OCSCW_add_pages() {
            add_submenu_page(
                'edit.php?post_type=size_chart',
                __( 'Setting', OCSCW_DOMAIN ),
                __( 'Setting', OCSCW_DOMAIN ),
                'manage_options',
                'ocscw-setting',
                array($this, 'OCSCW_pages_callback'),
                100
            );
        }


        function OCSCW_pages_callback(){
            $url = admin_url()."edit.php?post_type=size_chart&action=ocscwimport_chart";
            ?>
            <h2>Import Example Charts</h2>
            <a href="<?php echo $url; ?>" class="ocscw_import">Import</a>
            <?php
        }

        function OCSCW_create_chart(){
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == "ocscwimport_chart"){
                add_post(); 
            }      
        }


        function init() {
            add_action( 'init', array($this, 'OCSCW_create_menu'));
            add_action( 'init', array($this, 'OCSCW_create_chart'));
            add_action( 'add_meta_boxes', array($this, 'OCSCW_add_meta_box'));
            add_action( 'edit_post', array($this, 'OCSCW_meta_save'), 10, 2);
            add_action( 'admin_menu', array($this, 'OCSCW_add_pages'));    
        }


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }
    }
    OCSCW_menu::instance();
}


function add_post($valuet=false){

    $url = admin_url()."edit.php?post_type=size_chart";    
    $post_array = array(
            "Women's Kurti Size Chart Example" => array(
                'content' => '<ul>
                               <li><strong>Bust :</strong>
                            Measure under your arms, around the fullest part of the your chest.</li>
                               <li><strong>Waist :</strong>
                            Measure around your natural waistline, keeping the tape a bit loose.</li>
                               <li><strong>Hips :</strong>
                            Measure around the fullest part of your body at the top of your leg.</li>
                         </ul>',
                'chart'  => Array(
                           'size','Bust','Waist','Hip','Length',
                           'XS'  ,'34'  ,'32'   ,'40' ,'54',
                           'S'   ,'36'  ,'34'   ,'42' ,'54',
                           'M'   ,'38'  ,'36'   ,'44' ,'54',
                           'L'   ,'40'  ,'38'   ,'46' ,'54',
                           'XL'  ,'42'  ,'40'   ,'48' ,'54',
                           '2XL' ,'44'  ,'42'   ,'50' ,'54',
                           '3XL' ,'46'  ,'44'   ,'52' ,'54',
                           '4XL' ,'48'  ,'46'   ,'54' ,'54',
                           '5XL' ,'50'  ,'48'   ,'56' ,'54',
                           '6XL' ,'52'  ,'50'   ,'58' ,'54',
                           '7XL' ,'54'  ,'52'   ,'60' ,'54',

                        ) ,
                'totalrow'  => '12',
                'totalcol'  => '5', 
                'btn_show'=> 'popup',
                'sub_title'=> "Women's chart",
                'tab_lbl'=> 'Size Chart',
                'btn_lbl'=> "Women's chart",
                'btn_ft_clr'=> "#ffffff",
                'btn_bg_clr'=> "#000000",
                'btn_pos'=> "before_add_cart",
                'alw_gust_usr'=> "on",
                'tbl_head_bg_clr'=> "#e9ebed",
                'tbl_head_ft_clr'=> "#000000",
                'tbl_even_row_clr'=> "#d6d8db",
                'tbl_odd_row_clr'=> "#e9ebed",
                'show_tab'=> "on",
                'chart_tab_name'=>  "Size Chart",
                'dis_tab_name'=>  "How to Measure",
                'btn_brd_rd'=>  "5",
                'image'=>'women_kurti.png'
            ),
            "Men's Round Neck Half Sleeve Chart Example" => array(
                'content' => '<ul>
                               <li><strong>Collar :</strong>
                            Measure around neck base where shirt fits.
                               <li><strong>Chest :</strong>
                            Measure just under your arms, around the fullest part of the your chest,Holding one finger under the top for comfort.</li>
                               <li><strong>Waist :</strong>
                            Measure around your natural waistline over your shirt,Holding one finger under the top for comfort.</li>
                               <li><strong>Sleeve :</strong>
                            Measure from the collar, along the shoulders and down the outer arm to the hem.</li>
                         </ul>',
                'chart'  => Array(
                               'size'         ,'S'    ,'M'    ,'L'   ,'XL'   ,'2XL'  ,'3XL'  ,'4XL',
                               'Collar'       ,'15.5' ,'15.75','16'  ,'16.5' ,'17'   ,'17.75','18.50',
                               'Chest'        ,'38'   ,'40'   ,'42'  ,'44'   ,'46'   ,'48'   ,'50',
                               'Length'       ,'26'   ,'27'   ,'28'  ,'29'   ,'30'   ,'31'   ,'32',
                               'Shoulder'     ,'15.5' ,'16.5' ,'17.5','18.5' ,'19.5' ,'20.5' ,'21.5',
                               'Sleeve Length','7.5'  ,'8'    ,'8'   ,'8.5'  ,'9'    ,'9.5'  ,'10',
                               'Sleeve Open'  ,'5.5'  ,'6'    ,'6.5' ,'7'    ,'7.5'  ,'8'    ,'8.5',
                               

                            ) ,
                'totalrow' => '6',
                'totalcol' => '8',
                'btn_show' => 'tab' ,
                'sub_title'=>"Men’s T-Shirt",
                'tab_lbl'  =>'Size Chart',
                'btn_lbl'  =>"Men’s T-Shirt",
                'btn_ft_clr'=>"#ffffff",
                'btn_bg_clr'=>"#000000" ,
                'btn_pos'    => "before_add_cart" ,
                'alw_gust_usr'=>"on" ,
                'tbl_head_bg_clr'=>"#e9ebed",
                'tbl_head_ft_clr'=>"#000000",
                'tbl_even_row_clr'=>"#d6d8db",
                'tbl_odd_row_clr'=>"#e9ebed",
                'show_tab'=>"on",
                'chart_tab_name'=>"Size Chart",
                'dis_tab_name'=>"How to Measure",
                'btn_brd_rd'=> "5",
                'image'=>'men_tshirt.png'
            ),
            "Men's Formal Pant Chart Example" => array(
                'content' => '<ul>
                              
                               <li><strong>Waist :</strong>
                            Measure around your natural waistline over your shirt,Holding one finger under the top for comfort.</li>
                               <li><strong>Hip :</strong>
                            Stand feet together and measure around the fullest part of hips, holding the tap measure horizontally.</li>
                               <li><strong>Length :</strong>
                            Measure along the inside of your leg, from just below the crotch to about one inch below the ankle</li>
                              
                         </ul>',
                'chart'  => Array(
                               'size' ,'Waist' ,'Length'  ,'Hip'   ,'Thigh' ,'Bottom' ,
                               'X'    ,'30'    ,'41','16' ,'19'    ,'22.5'  ,'18' ,
                               'S'    ,'32'    ,'41.5'    ,'19.5'  ,'23.5'  ,'18' ,
                               'M'    ,'34'    ,'42'      ,'20.5'  ,'24.5'  ,'18' ,
                               'L'    ,'36'    ,'42.5'    ,'21.5'  ,'25'    ,'18' ,
                            ) ,
                'totalrow' => '5',
                'totalcol' => '6',
                'btn_show' => 'tab' ,
                'sub_title'=>"Men’s Formal Pant",
                'tab_lbl'  =>'Size Chart',
                'btn_lbl'  =>"Men’s Formal Pant",
                'btn_ft_clr'=>"#ffffff",
                'btn_bg_clr'=>"#000000" ,
                'btn_pos'    => "before_add_cart" ,
                'alw_gust_usr'=>"on" ,
                'tbl_head_bg_clr'=>"#e9ebed",
                'tbl_head_ft_clr'=>"#000000",
                'tbl_even_row_clr'=>"#d6d8db",
                'tbl_odd_row_clr'=>"#e9ebed",
                'show_tab'=>"on",
                'chart_tab_name'=>"Size Chart",
                'dis_tab_name'=>"How to Measure",
                'btn_brd_rd'=> "5",
                'image'=>'men_formalpant.png'
            ),
    );

    foreach ($post_array as $key => $value) {
        if( (null == get_page_by_title( $key , OBJECT, 'size_chart') && $valuet==true) ||  $valuet==false){

            $new_post = array(
               'post_title'   => $key,
               'post_status'  => 'publish',
               'post_type'    => 'size_chart',
               'post_content' => $value['content']
            );
            $post_id = wp_insert_post($new_post);
            
         
            update_post_meta( $post_id, OCSCW_PREFIX.'size_chartdata', serialize($value['chart']) );
            update_post_meta( $post_id, OCSCW_PREFIX.'totalrow', $value['totalrow'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'totalcol', $value['totalcol'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_show', $value['btn_show'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'sub_title', $value['sub_title'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'tab_lbl', $value['tab_lbl'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_lbl', $value['btn_lbl'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_ft_clr', $value['btn_ft_clr'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_bg_clr', $value['btn_bg_clr'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_pos', $value['btn_pos'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'alw_gust_usr', $value['alw_gust_usr'] );
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_head_bg_clr',$value['tbl_head_bg_clr']);
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_head_ft_clr',$value['tbl_head_ft_clr']);
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_even_row_clr',$value['tbl_even_row_clr']);
            update_post_meta( $post_id, OCSCW_PREFIX.'tbl_odd_row_clr',$value['tbl_odd_row_clr']);
            update_post_meta( $post_id, OCSCW_PREFIX.'show_tab',$value['show_tab']);
            update_post_meta( $post_id, OCSCW_PREFIX.'chart_tab_name', $value['chart_tab_name']);
            update_post_meta( $post_id, OCSCW_PREFIX.'dis_tab_name', $value['dis_tab_name']);
            update_post_meta( $post_id, OCSCW_PREFIX.'btn_brd_rd', $value['btn_brd_rd']);

            
            $IMGFileName = $value['image'];
            $dirPath = OCSCW_PLUGIN_AB_PATH."includes/images/";
            $IMGFilePath = $dirPath.$IMGFileName;
            $upload = wp_upload_bits($IMGFileName , null, file_get_contents($IMGFilePath, FILE_USE_INCLUDE_PATH));
            $imageFile = $upload['file'];
            $wpFileType = wp_check_filetype($imageFile, null);
            $attachment = array(
                'post_mime_type' => $wpFileType['type'],  // file type
                'post_title' => sanitize_file_name($imageFile),  // sanitize and use image name as file name
                'post_content' => '',  // could use the image description here as the content
                'post_status' => 'inherit'
            );
            // insert and return attachment id
           
            $attachmentId = wp_insert_attachment( $attachment, $imageFile, $post_id );
            $success = set_post_thumbnail( $post_id, $attachmentId );
        }
    }
    if($valuet==false){
        wp_redirect($url);
        exit; 
    }    
}