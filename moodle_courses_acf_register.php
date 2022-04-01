<?php
class moodleCoursesAcfRegister
{
    public function register_options_page() {

        if ( function_exists( 'acf_add_options_page' ) ) {

            acf_add_options_page( array(
                'page_title'  => 'Moodle Kurse to Wordpress Einstellungen',
                'menu_title'  => 'Moodle Kurse to wp Settings',
                'menu_slug'   => 'moodle_courses_to_wp',
                'capability'  => 'edit_posts',
                "position"    => "51",
                "parent_slug" => "options-general.php",
                'redirect'    => true,
                'post_id'     => 'options'
            ) );
        }
    }

    public function register_acf_fields() {
        if ( function_exists( 'acf_add_local_field_group' ) ) {
            acf_add_local_field_group( array(
                'title'                 => 'Moodle Kurse to WP',
                'fields'                => array(
                    array(
                        'label'                       => 'Moodle Kurse Login Username',
                        'name'                        => 'moodle_courses_login_username',
                        'type'                        => 'text',
                        'instructions'                => '',
                        'required'                    => 1,
                        'conditional_logic'           => 0,
                        'wrapper'                     => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        "frontend_admin_display_mode" => "edit",
                        "only_front"                  => 0,
                        "placeholder"                 => "",
                        "prepend"                     => "",
                        "append"                      => ""
                    ),
                    array(
                        'label'                       => 'Moodle Kurse Login Passwort',
                        'name'                        => 'moodle_courses_login_password',
                        'type'                        => 'password',
                        'instructions'                => '',
                        'required'                    => 1,
                        'conditional_logic'           => 0,
                        'wrapper'                     => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        "frontend_admin_display_mode" => "edit",
                        "only_front"                  => 0,
                        "placeholder"                 => "",
                        "prepend"                     => "",
                        "append"                      => ""
                    ),
                ),
                'location'              => array(
                    array(
                        array(
                            'param'    => 'options_page',
                            'operator' => '==',
                            'value'    => 'moodle_courses_to_wp',
                        ),
                    ),
                ),
                'menu_order'            => 0,
                'position'              => 'normal',
                'style'                 => 'default',
                'label_placement'       => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen'        => '',
                'active'                => 1,
                'description'           => '',
            ) );
        }
    }

}