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

/*
 * Campos para el bp-profile-search se vean bien con los de xprofile
 * Ver doc en http://dontdream.it/bp-profile-search/custom-profile-field-types/
 */
function bp_crear_bps_custom ($field_type, $field)
{
    switch ($field->type) {
    case 'richtext':
        $field_type = 'textarea';
        break;
    case 'birthdate':
        $field_type = 'datebox';
        break;
    /* para que lo tome el filtro y ponemos clases nuestras */
/*
 *   case 'checkbox':
 *       $field_type = 'checkbox_crear';
 *       break;
 */
    }
    return $field_type;

}
add_filter ('bps_field_validation_type', 'bp_crear_bps_custom', 10, 2);
add_filter ('bps_field_html_type', 'bp_crear_bps_custom', 10, 2);
add_filter ('bps_field_criteria_type', 'bp_crear_bps_custom', 10, 2);
add_filter ('bps_field_query_type', 'bp_crear_bps_custom', 10, 2);

/*
 * Poner los checkbox dentro de un div editfield y con un label propio.
 */
function bp_crear_bps_checkbox ($html, $field, $label, $range)
{
    if ($field->type == 'checkbox') {
        $id = $field->id;
        $fname = 'field_'. $id;

        $html = "<div class='editfield'>";
        $html .= "<label for='$fname'>$label</label>";

        $posted = isset ($_REQUEST[$fname])? $_REQUEST[$fname]: array ();
        $options = bps_get_options ($id);
        foreach ($options as $option) {
            $option = trim ($option);
            $value = esc_attr (stripslashes ($option));
            $selected = (in_array ($option, $posted))? "checked='checked'": "";
            $html .= "<input $selected type='checkbox' name='{$fname}[]' value='$value'>$value";
        }
        $html .= '</div>';
    }

    return $html;
}
/* add_filter ('bps_field_html', 'bp_crear_bps_checkbox', 10, 4); */

/* para el plugin user-switching */
add_action('init', 'no_bp_redirect_on_user_switching', 9);
function no_bp_redirect_on_user_switching() {
    if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'switch_to_user' ) {
        remove_filter( 'login_redirect', 'bp_login_redirect' );
    }
}

/* filtrar campos de xprofile para que anden los embed */
function bp_crear_xprofile_filter( $field_value, $field_type = 'textbox' ) {
    $xpost = get_post();

    /*
     * WP_Embed precisa el post actual para cachear resultados, entre otras cosas.
     * Si no hay uno seteamos el primero que exista.
     */
    if ( ($xpost == null) || $xpost->ID == 0 ){
        $args = array(
            'posts_per_page'   => 1,
            'offset'           => 0,
            'category'         => '',
            'orderby'          => 'post_date',
            'order'            => 'ASC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'post',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => '',
            'suppress_filters' => true );

        global $post;
        $post = get_posts($args)[0];
    }

    $content = $field_value;
    $content = apply_filters('the_content', $content);
    return $content;
}
add_filter('bp_get_the_profile_field_value', 'bp_crear_xprofile_filter', 10, 2);

?>
