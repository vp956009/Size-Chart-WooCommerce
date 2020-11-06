<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCSCW_front')) {

    class OCSCW_front {

        protected static $instance;
     
        function headererrr(){       
            if(is_product()){

                $OCSCW_object = get_queried_object();
                $product_id   = $OCSCW_object->ID;
                $product      = wc_get_product( $product_id );

                $chart_ids    = get_post_meta( $product_id, OCSCW_PREFIX.'product_sizechart', true );
                
                foreach ($chart_ids as $key => $chart_id) {
                    $btn_tab      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_show', true );
                    $btn_pos      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_pos', true );
                    $alw_gust_usr = get_post_meta( $chart_id, OCSCW_PREFIX.'alw_gust_usr', true );

                    //echo $chart_id.'---'.$btn_tab.'---'.$btn_pos.'---'.$alw_gust_usr;
                    if($alw_gust_usr == "on"){
                        if($btn_tab == "tab"){
                            add_filter( 'woocommerce_product_tabs', array( $this, 'ocscw_add_tab' ) ); 
                        }else{
                            if($btn_pos == "after_add_cart" ){
                            
                                if( ! $product->is_in_stock()){
                                    add_action('woocommerce_product_meta_start', array( $this, 'ocscw_button_after' ),0);
                                }else{
                                    add_action('woocommerce_after_add_to_cart_form', array( $this, 'ocscw_button_after' ));
                                }      
                                  
                            }elseif($btn_pos == "before_add_cart"){

                                if( ! $product->is_in_stock()){
                                    add_action('woocommerce_single_product_summary', array( $this, 'ocscw_button_before' ));
                                }else{
                                    if ( $product->is_type( 'variable' ) ) {
                                        add_action('woocommerce_single_variation', array( $this, 'ocscw_button_before' ));
                                    }else{
                                        add_action('woocommerce_before_add_to_cart_form', array( $this, 'ocscw_button_before' ));
                                    }   
                                }   
                            }
                        }     
                    }else{
                        if(is_user_logged_in()){
                            if($btn_tab == "tab"){
                                add_filter( 'woocommerce_product_tabs', array( $this, 'ocscw_add_tab' ) ); 
                            }else{
                                if($btn_pos == "after_add_cart" ){
                                
                                    if( ! $product->is_in_stock()){
                                        add_action('woocommerce_product_meta_start', array( $this, 'ocscw_button_after' ),0);
                                    }else{
                                        add_action('woocommerce_after_add_to_cart_form', array( $this, 'ocscw_button_after' ));
                                    }      
                                      
                                }elseif($btn_pos == "before_add_cart"){

                                    if( ! $product->is_in_stock()){
                                        add_action('woocommerce_single_product_summary', array( $this, 'ocscw_button_before' ));
                                    }else{
                                        if ( $product->is_type( 'variable' ) ) {
                                            add_action('woocommerce_single_variation', array( $this, 'ocscw_button_before' ));
                                        }else{
                                            add_action('woocommerce_before_add_to_cart_form', array( $this, 'ocscw_button_before' ));
                                        }   
                                    }   
                                }
                            }     
                        }
                    }
                }   
            }
        }


        function ocscw_add_tab( $tabs ) {
            $product_id = get_the_ID();
            $product    = wc_get_product( $product_id );
            $chart_ids   = get_post_meta( $product_id, OCSCW_PREFIX.'product_sizechart', true );

            foreach ($chart_ids as $key => $chart_id) {
                $btn_tab      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_show', true );
                if($btn_tab == "tab"){
                    $tab_lbl    = get_post_meta( $chart_id, OCSCW_PREFIX.'tab_lbl', true );
                    $tabs['desc_tab'] = array(
                        'title'     => __( $tab_lbl, 'woocommerce' ),
                        'priority'  => 50,
                        'callback'  => array( $this, 'ocscw_tab_content' )
                    );
                }
            }
            return $tabs;
        }



        function ocscw_tab_content(){
            $product_id       = get_the_ID();
            $product          = wc_get_product( $product_id );
            $chart_ids         = get_post_meta( $product_id, OCSCW_PREFIX.'product_sizechart', true );
            foreach ($chart_ids as $key => $chart_id) {
                $btn_tab      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_show', true );
                if($btn_tab == "tab"){
                    $chart_title      = get_post_meta( $chart_id, OCSCW_PREFIX.'sub_title', true );
                    $size_chartdata   = get_post_meta( $chart_id, OCSCW_PREFIX.'size_chartdata', true );
                    $totalrow         = get_post_meta( $chart_id, OCSCW_PREFIX.'totalrow', true );
                    $totalcol         = get_post_meta( $chart_id, OCSCW_PREFIX.'totalcol', true );
                    $show_tab         = get_post_meta( $chart_id, OCSCW_PREFIX.'show_tab', true );
                    $chart_tab_name   = get_post_meta( $chart_id, OCSCW_PREFIX.'chart_tab_name', true);
                    $dis_tab_name     = get_post_meta( $chart_id, OCSCW_PREFIX.'dis_tab_name', true);
                    $tbl_head_bg_clr  = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_head_bg_clr', true);
                    $tbl_head_ft_clr  = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_head_ft_clr', true);
                    $tbl_even_row_clr = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_even_row_clr', true);
                    $tbl_odd_row_clr  = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_odd_row_clr', true);
                    $table_array      = unserialize($size_chartdata);
                    echo '<div class="ocscw_tableclass">';
                        echo '<div class="ocscw_sizechart_tab_content">';
                            echo '<div class="ocscw_tab_header">';
                                echo '<h1>'.$chart_title.'</h1>';
                            echo '</div>';
                            echo '<div class="ocscw_tab_body">';
                                echo '<div class="ocscw_tab_data">';
                                    echo '<div class="ocscw_tab_padding_div">';
                                        if($show_tab == "on"){
                                            ?>
                                                <ul class="ocscw_front_tabs">
                                                    <li class="tab-link current" data-tab="tab-default">
                                                        <?php echo __( $chart_tab_name, OCSCW_DOMAIN );?>
                                                    </li>
                                                    <li class="tab-link" data-tab="tab-general">
                                                        <?php echo __( $dis_tab_name , OCSCW_DOMAIN );?>
                                                    </li>
                                                </ul>
                                                <div id="tab-default" class="ocscw_front_tab_content current">
                                                    <div class="ocscw_child_div">
                                                        <?php
                                                            echo '<table>';
                                                                $count = 0;
                                                                for($i=0;$i<$totalrow;$i++){
                                                                    echo "<tr>";
                                                                        
                                                                        for($j=0;$j<$totalcol;$j++){
                                                                            echo "<td>".$table_array[$count]."</td>";
                                                                            $count++;
                                                                        }

                                                                    echo "</tr>";
                                                                }
                                                            echo '</table>'; 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div id="tab-general" class="ocscw_front_tab_content">
                                                    <div class="ocscw_child_div">
                                                        <?php echo get_post_field('post_content', $chart_id);
                                                         
                                                         ?> 
                                                        <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                                    </div>
                                                </div>
                                            <?php
                                        }else{
                                            echo get_post_field('post_content', $chart_id);
                                            ?>
                                                <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                            <?php
                                            echo '<table>';
                                                $count = 0;
                                                for($i=0;$i<$totalrow;$i++){
                                                    echo "<tr>";

                                            
                                                        for($j=0;$j<$totalcol;$j++){
                                                            echo "<td>".$table_array[$count]."</td>";
                                                            $count++;
                                                        }

                                                    echo "</tr>";
                                                }
                                            echo '</table>';
                                        } 
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                    ?>
                    <style type="text/css">

                        .ocscw_tableclass tr:nth-child(even) {
                            background: <?php echo $tbl_even_row_clr; ?>;
                        }
                        .ocscw_tableclass tr:nth-child(odd) {
                            background: <?php echo $tbl_odd_row_clr; ?>;;
                        }
                        .ocscw_tableclass tr:nth-child(1), .ocscw_tableclass tr td:nth-child(1) {
                            background: <?php echo $tbl_head_bg_clr; ?>;
                            color: <?php echo $tbl_head_ft_clr; ?>;
                            font-weight: 700;
                            text-transform: capitalize;
                            padding-left: 10px;
                        }
                    </style>
                    <?php
                }
            }
        }


        function ocscw_button_after(){
            $product_id = get_the_ID();
            $product    = wc_get_product( $product_id );
            $chart_ids   = get_post_meta( $product_id, OCSCW_PREFIX.'product_sizechart', true );
            foreach ($chart_ids as $key => $chart_id) {
                $btn_tab      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_show', true );
                $btn_pos      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_pos', true );
                if($btn_tab == "popup" && $btn_pos == "after_add_cart"){
                    $btn_lbl    = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_lbl', true );
                    $btn_ft     = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_ft_clr', true );
                    $btn_bg     = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_bg_clr', true );
                    $btn_brd_rd = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_brd_rd', true );
                    $style      = "color:".$btn_ft.";background-color:".$btn_bg.";border-radius:".$btn_brd_rd."px;";
                    ?>
                    <div class="ocscw_btn">
                        <button class="ocscw_open" data-id="<?php echo $product_id; ?>" data-cid="<?php echo $chart_id; ?>" style="<?php echo $style; ?>">
                            <?php echo $btn_lbl; ?>
                        </button>
                    </div>
                    <?php
                }
            }
        }

        function ocscw_button_before(){
            $product_id = get_the_ID();
            $product    = wc_get_product( $product_id );
            $chart_ids   = get_post_meta( $product_id, OCSCW_PREFIX.'product_sizechart', true );
            foreach ($chart_ids as $key => $chart_id) {
                $btn_tab      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_show', true );
                $btn_pos      = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_pos', true );
                if($btn_tab == "popup" && $btn_pos == "before_add_cart"){
                    $btn_lbl    = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_lbl', true );
                    $btn_ft     = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_ft_clr', true );
                    $btn_bg     = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_bg_clr', true );
                    $btn_brd_rd = get_post_meta( $chart_id, OCSCW_PREFIX.'btn_brd_rd', true );
                    $style      = "color:".$btn_ft.";background-color:".$btn_bg.";border-radius:".$btn_brd_rd."px;";
                    ?>
                    <div class="ocscw_btn">
                        <button class="ocscw_open" data-id="<?php echo $product_id; ?>" data-cid="<?php echo $chart_id; ?>" style="<?php echo $style; ?>">
                            <?php echo $btn_lbl; ?>
                        </button>
                    </div>
                    <?php
                }
            }
        }


       
        function popup_div_footer(){
            ?>
            <div id="ocscw_sizechart_popup" class="ocscw_sizechart_main">
            </div>
            <?php
        }



        function ocscw_sizechart() {
            $product_id       = sanitize_text_field($_REQUEST['product_id']);
            $product          = wc_get_product( $product_id );
            $chart_id         = sanitize_text_field($_REQUEST['chart_id']);
           

            $chart_title      = get_post_meta( $chart_id, OCSCW_PREFIX.'sub_title', true );
            $size_chartdata   = get_post_meta( $chart_id, OCSCW_PREFIX.'size_chartdata', true );
            $totalrow         = get_post_meta( $chart_id, OCSCW_PREFIX.'totalrow', true );
            $totalcol         = get_post_meta( $chart_id, OCSCW_PREFIX.'totalcol', true );
            $show_tab         = get_post_meta( $chart_id, OCSCW_PREFIX.'show_tab', true );
            $chart_tab_name   = get_post_meta( $chart_id, OCSCW_PREFIX.'chart_tab_name', true);
            $dis_tab_name     = get_post_meta( $chart_id, OCSCW_PREFIX.'dis_tab_name', true);
            $tbl_head_bg_clr  = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_head_bg_clr', true);
            $tbl_head_ft_clr  = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_head_ft_clr', true);
            $tbl_even_row_clr = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_even_row_clr', true);
            $tbl_odd_row_clr  = get_post_meta( $chart_id, OCSCW_PREFIX.'tbl_odd_row_clr', true);
            $table_array      = unserialize( $size_chartdata );


            echo '<div class="ocscw_tableclass">';
                echo '<div class="ocscw_sizechart_content">';
                    echo '<div class="ocscw_popup_header">';
                        echo '<h1>'.$chart_title.'</h1>';
                        echo '<span class="ocscw_popup_close">&times;</span>';
                    echo '</div>';
                    echo '<div class="ocscw_popup_body">';
                        echo '<div class="ocscw_popup_data">';
                            echo '<div class="ocscw_popup_padding_div">';
                                if($show_tab == "on"){
                                    ?>
                                        <ul class="ocscw_front_tabs">
                                            <li class="tab-link current" data-tab="tab-default">
                                                <?php echo __( $chart_tab_name, OCSCW_DOMAIN );?>
                                            </li>
                                            <li class="tab-link" data-tab="tab-general">
                                                <?php echo __( $dis_tab_name , OCSCW_DOMAIN );?>
                                            </li>
                                        </ul>
                                        <div id="tab-default" class="ocscw_front_tab_content current">
                                            <div class="ocscw_child_div">
                                                <?php
                                                    echo '<table>';
                                                        $count = 0;
                                                        for($i=0;$i<$totalrow;$i++){
                                                            echo "<tr>";
                                                                
                                                                for($j=0;$j<$totalcol;$j++){
                                                                    echo "<td>".$table_array[$count]."</td>";
                                                                    $count++;
                                                                }

                                                            echo "</tr>";
                                                        }
                                                    echo '</table>'; 
                                                ?>
                                            </div>
                                        </div>
                                        <div id="tab-general" class="ocscw_front_tab_content">
                                            <div class="ocscw_child_div">
                                                <?php echo get_post_field('post_content', $chart_id); ?> 
                                                <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                            </div>
                                        </div>
                                    <?php
                                }else{
                                    echo get_post_field('post_content', $chart_id);
                                    ?>
                                        <img src="<?php echo get_the_post_thumbnail_url($chart_id ,'full'); ?>" />
                                    <?php
                                    echo '<table>';
                                        $count = 0;
                                        for($i=0;$i<$totalrow;$i++){
                                            echo "<tr>";

                                    
                                                for($j=0;$j<$totalcol;$j++){
                                                    echo "<td>".$table_array[$count]."</td>";
                                                    $count++;
                                                }

                                            echo "</tr>";
                                        }
                                    echo '</table>';
                                } 
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            ?>
            <style type="text/css">
                .ocscw_tableclass tr:nth-child(even) {
                    background: <?php echo $tbl_even_row_clr; ?>;
                }
                .ocscw_tableclass tr:nth-child(odd) {
                    background: <?php echo $tbl_odd_row_clr; ?>;;
                }
                .ocscw_tableclass tr:nth-child(1), .ocscw_tableclass tr td:nth-child(1) {
                    background: <?php echo $tbl_head_bg_clr; ?>;
                    color: <?php echo $tbl_head_ft_clr; ?>;
                    font-weight: 700;
                    text-transform: capitalize;
                    padding-left: 10px;
                }
            </style>
            <?php
            
            exit();
        }


        function init() {
            add_action( 'wp_head', array( $this, 'headererrr' ));
            add_action( 'wp_footer', array( $this, 'popup_div_footer' ));
            add_action( 'wp_ajax_ocscw_sizechart', array( $this, 'ocscw_sizechart' ));
            add_action( 'wp_ajax_nopriv_ocscw_sizechart', array( $this, 'ocscw_sizechart' ));
        }

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }

    }

    OCSCW_front::instance();
}

