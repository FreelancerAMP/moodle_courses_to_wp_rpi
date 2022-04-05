<?php
include_once 'moodle_courses_to_wp_rpi.php';

class moodleCoursesAcfRegister
{
    public function register_options_page()
    {
        if (function_exists('acf_add_options_page')) {

            acf_add_options_page(array(
                'page_title' => 'Moodle Kurse to Wordpress Einstellungen',
                'menu_title' => 'Moodle Kurse to wp Settings',
                'menu_slug' => 'moodle_courses_to_wp',
                'capability' => 'edit_posts',
                "position" => "51",
                "parent_slug" => "options-general.php",
                'redirect' => true,
                'post_id' => 'options'
            ));
        }
    }

    public function register_acf_fields()
    {
        if (function_exists('acf_add_local_field_group')) {

            acf_add_local_field_group(array(
                'title' => 'Moodle Kurse to WP',
                'fields' => array(
                    array(
                        'key' => 'field_moodle_courses_url',
                        'label' => 'Moodle Kurse URL',
                        'name' => 'moodle_courses_url',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        "frontend_admin_display_mode" => "edit",
                        "only_front" => 0,
                        "placeholder" => "",
                        "prepend" => "",
                        "append" => ""
                    ),
                    array(
                        'key' => 'field_moodle_courses_login_username',
                        'label' => 'Moodle Kurse Login Username',
                        'name' => 'moodle_courses_login_username',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        "frontend_admin_display_mode" => "edit",
                        "only_front" => 0,
                        "placeholder" => "",
                        "prepend" => "",
                        "append" => ""
                    ),
                    array(
                        'key' => 'field_moodle_courses_login_password',
                        'label' => 'Moodle Kurse Login Passwort',
                        'name' => 'moodle_courses_login_password',
                        'type' => 'password',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        "frontend_admin_display_mode" => "edit",
                        "only_front" => 0,
                        "placeholder" => "",
                        "prepend" => "",
                        "append" => ""
                    ),
                    array(
                        'key' => 'field_moodle_courses_shortcode_generator',
                        'label' => 'Moodle Kurse Shortcode Generator',
                        'name' => 'moodle_courses_shortcode_generator',
                        'type' => 'message',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => moodleCoursesAcfRegister::get_generator_content(),
                        'new_lines' => 'wpautop',
                        'esc_html' => 0,
                    )
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'moodle_courses_to_wp',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));
        }
    }

    static function get_generator_content()
    {
        if (get_option('options_moodle_courses_url')) {
            $content = "";
            $token = MoodleCoursesToRpi::getMoodleToken();
            $url = 'https://moodle.rpi-virtuell.de/webservice/rest/server.php?wstoken=ba7e938d2b0588260dd53455ff536935&wsfunction=core_course_get_categories&moodlewsrestformat=json';
            $url = get_option('options_moodle_courses_url') . '/webservice/rest/server.php?wstoken='
                . $token->token . '&wsfunction=core_course_get_categories&moodlewsrestformat=json';
            $response = wp_remote_get($url);
            if (!is_wp_error($response)) {
                $content .= '<div class="moodle_company_collection">';
                $categories = json_decode(wp_remote_retrieve_body($response));
                foreach ($categories as $category) {
                    $content .= '<li id="' . $category->id . '" class = "moodle_company button">' . $category->name . '</li> </n>';
                }
                $content .= '</div>';
                $content .= '<div class="shortcode_frame"> <p id="shortcode_frame_name">[/]  Shortcode</p>';
                $content .= '<p id="shortcode-output" > </p>';

            }
        } else {
            $content = '<b>Es wurde noch keine Moodle Kurse URL festgelegt. </n> Speichern sie um einen Shortcode generieren zu können</b>';
        }
        return $content;
    }

}