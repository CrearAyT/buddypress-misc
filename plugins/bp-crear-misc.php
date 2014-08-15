<?php
/**
 * @package Crear-BuddyPress
 * @version 0.0.1
 */
/*
Plugin Name: Crear BuddyPress Varios
Plugin URI: http://elarteylatecnologia.com.ar
Description: Detalles varios de la integración BuddyPress.
Author: Adrián Pardini, Crear - Arte y Tecnología
Version: 0.0.1
Author URI: http://elarteylatecnologia.com.ar
*/

function bp_crear_add_muestreo_button()
{
    global $bp, $members_template;
    $uid = bp_displayed_user_id();
    $target = get_author_posts_url($uid);
    $button = array(
        'id'                => 'crear-muestreo-'.$uid,
        'component'         => 'core',
        'must_be_logged_in' => false,
        'block_self'        => false,
        'wrapper_id'        => 'crear-button-muestreo-' . $uid,
        'link_href'         => $target,
        'link_text'         => 'Muestreos',
        'link_title'        => 'Muestreos',
        //'link_id'           => '',
        //'link_class'        => '',
        //'wrapper'           => '',
    );

    // Filter and return the HTML button
    echo bp_get_button( $button );
    return;
}
add_action( 'bp_member_header_actions', 'bp_crear_add_muestreo_button', 20 );

/**
 * Ver Restrict Author Posting
 */
function bp_crear_restrict_category($user_id)
{
    update_user_meta( $user_id, '_access', 2 );
}

add_action( 'user_register', 'bp_crear_restrict_category', 10 );

/**
 * Una chacaleada total.
 * Ver en http://buddypress.org/support/topic/bp_loggedin_user_id-bp_get_member_user_id-always-true/
 */
function bp_crear_null_members_template()
{
    global $members_template;
    global $crear_members_template;
    if (isset($crear_members_template)) {
        return;
    }

    $members_template = NULL;
    $crear_members_template = $members_template;
}

function bp_crear_restaura_members_template()
{
    global $members_template;
    global $crear_members_template;
    if (isset($crear_members_template)) {
        $members_template = $crear_members_template;
        $crear_members_template = NULL;
        return;
    }
}


add_action( 'bp_member_header_actions', 'bp_crear_null_members_template', 0 );
add_action( 'bp_member_header_actions', 'bp_crear_restaura_members_template', 100 );

?>
