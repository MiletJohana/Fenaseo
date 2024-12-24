<?php

/**
 * WooCommerce B2B Admin settings page
 *
 * @version 3.3.3
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="wcb2b-settings">
    <div class="wcb2b-card">
        <div class="wcb2b-card-body">
            <p><img src="<?php echo WCB2B_PLUGIN_URI . 'assets/images/logo.png'; ?>" height="80px"></p>
            <ul class="list-inline">

                <?php
                    printf( '<li><a href="%s" %s>%s</a></li>',
                        admin_url( 'admin.php?page=wc-settings&tab=' . $this->id ),
                        ( empty( $current_section ) ? 'class="active"' : false ),
                        esc_html__( 'Settings', 'woocommerce-b2b' )
                    );
                    printf( '<li><a href="%s" %s>%s</a></li>',
                        admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=status' ),
                        ( 'status' == $current_section ? 'class="active"' : false ),
                        esc_html__( 'Status', 'woocommerce-b2b' )
                    );
                    printf( '<li><a href="%s" %s>%s</a></li>',
                        admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=tools' ),
                        ( 'tools' == $current_section ? 'class="active"' : false ),
                        esc_html__( 'Tools', 'woocommerce-b2b' )
                    );
                ?>

                <li><a href="https://woocommerce-b2b.com/documentation/" target="_blank">Docs</a></li>
                <li><a href="https://woocommerce-b2b.com/#faq/" target="_blank">FAQs</a></li>
                <li><a href="https://woocommerce-b2b.com/blog/category/snippets/" target="_blank">Snippets</a></li>
                <li><a href="https://support.woocommerce-b2b.com/" target="_blank">Support</a></li>
            </ul>
            <hr />

            <?php include sprintf( WCB2B_ABSPATH . 'includes/views/settings/%s.php', $page ); ?>
    
        </div>
    </div>
</div>