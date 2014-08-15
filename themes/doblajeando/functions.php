<?php

add_action('show_flat_credits', '__return_false');

function doblajeando_login_logo() { ?>
    <style type="text/css">
body.login div#login h1 a {
    background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo_pantone_grunge.png);
    background-size: auto;
    width: 320px;
    height: 125px;
}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'doblajeando_login_logo' );

function doblajeando_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'doblajeando_login_logo_url' );

function doblajeando_login_logo_url_title() {
    return 'Doblajeando';
}
add_filter( 'login_headertitle', 'doblajeando_login_logo_url_title' );
