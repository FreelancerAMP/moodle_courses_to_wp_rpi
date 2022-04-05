<?php
include_once 'moodle_courses_acf_register.php';

/**
 * Plugin Name: Moodle Courses to Wordpress RPI
 * Plugin URI: https://github.com/rpi-virtuell/moodle_courses_to_wp_rpi
 * Description: Plugin zum Anzeigen von Moodle Kursen
 * Version: 1.0.0
 * Author: Daniel Reintanz
 * Licence: GPLv3
 */
class MoodleCoursesToRpi
{
    function __construct()
    {
        add_shortcode('moodle_courses', array($this, 'moodleCourses'));
        add_action('admin_menu', array('moodleCoursesAcfRegister', 'register_acf_fields'));
        add_action('admin_menu', array('moodleCoursesAcfRegister', 'register_options_page'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_footer', array($this, 'add_generator_script'));
    }


    function moodleCourses($atts)
    {
        if (!is_wp_error($token = $this->getMoodleToken()) && isset($atts['category'])) {

            if (get_option('options_moodle_courses_url')) {

                $url = get_option('options_moodle_courses_url') . '/webservice/rest/server.php?field=category&value='
                    . $atts['category'] . '&wstoken='
                    . $token->token . '&wsfunction=core_course_get_courses_by_field&moodlewsrestformat=json';

                //$url = 'https://moodle.rpi-virtuell.de/webservice/rest/server.php?field=category&value=2&wstoken=ba7e938d2b0588260dd53455ff536935&wsfunction=core_course_get_courses_by_field&moodlewsrestformat=json';

                $response = wp_remote_get($url);
                if (!is_wp_error($response)) {
                    $courses = json_decode(wp_remote_retrieve_body($response));
                    ob_start();
                    ?>
                    <div class="course-grid">
                        <?php
                        foreach ($courses->courses as $course) {
                            if ($course->endate < time() && !in_array( 'self' , $course->enrollmentmethods))
                                continue;
                            ?>
                            <div class="course-article">
                                <div class="course-content">
                                    <h2 class="course-name">
                                        <?php echo $course->displayname ?>
                                    </h2>
                                    <?php if (!empty($course->overviewfiles)) {
                                        ?>
                                        <div class="course-image boundless-image">
                                            <img src="<?php echo $course->overviewfiles[0]->fileurl . '?token=' . $token->token ?>"
                                                 alt="">
                                        </div>
                                    <?php } ?>
                                    <?php  if($course->fullname != $course->displayname) {?>
                                    <p class="course-name"> <?php echo $course->fullname ?> </p>
                                    <?php } ?>
                                    <p class="course-company">
                                        <?php echo $course->categoryname ?>
                                    </p>
                                    <div class="course-summary">
                                        <?php echo $course->summary ?>
                                    </div>
                                    <div class="course_flexer"></div>
                                    <div style="clear: both"></div>
                                    <a class="course-button" target="_blank"
                                       href="<?php echo get_option('options_moodle_courses_url') . '/course/view.php?id=' . $course->id ?>">Zum
                                        Kurs</a>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    return ob_get_clean();
                }
            }
        }
        return null;
    }

    function enqueue_scripts()
    {
        wp_enqueue_style('moodle-courses-style', plugin_dir_url(__FILE__) . 'css/style.css');
    }

    static function getMoodleToken()
    {
        $url = get_option('options_moodle_courses_url', true) .
            '/login/token.php?username=' . get_option('options_moodle_courses_login_username', true) .
            '&password=' . get_option('options_moodle_courses_login_password', true) . '&service=moodle_mobile_app';
        $response = wp_remote_get($url);
        if (!is_wp_error($response)) {
            $response = json_decode(wp_remote_retrieve_body($response));
        }
        return $response;
    }

    function add_generator_script()
    {
        if ($_GET['page'] === 'moodle_courses_to_wp') {
            echo '<style>
                             .moodle_company
                             {
                             list-style-type: none;
                             } 
                             .moodle_company_collection
                             {
                             max-height: 400px;
                             overflow: auto;
                             }
                             .shortcode_frame{
                             display: none;
                             border-style: solid;
                             border-width: 1px;
                             }
                             #shortcode_frame_name{
                             user-select: none;
                             margin: 15px 10px;
                             font-weight: bold;
                             }
                             #shortcode-output
                             {
                             margin: 10px 7px;
                             padding: 5px;
                             border-style: solid;
                             border-width: 1px;
                             }
                    </style>';
            echo '<script> jQuery(document).ready(function($){
                        $(".moodle_company").
                        on("click", function(e){
                            $("#shortcode-output").html("[moodle_courses category=\""+ e.target.id+"\"]");
                            $(".shortcode_frame").show();
                        })
                            })
                        </script>';
        }
    }
}

new MoodleCoursesToRpi();