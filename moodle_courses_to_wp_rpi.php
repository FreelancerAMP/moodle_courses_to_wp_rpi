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
    }

    private $url_prefix = 'https://moodle.rpi-virtuell.de';
    private $token_credentials = array(
        'username' => 'ssotester',
        'password' => 'ssotester'
    );


    function moodleCourses($atts)
    {
        if (!is_wp_error($token = $this->getMoodleToken()) && isset($atts['category'])) {
            $url = $this->url_prefix . '?field=category&value='
                . $atts['category'] . '&wstoken='
                . $token->token . '&wsfunction=core_course_get_courses_by_field&moodlewsrestformat=json';

            $url = 'https://moodle.rpi-virtuell.de/webservice/rest/server.php?field=category&value=2&wstoken=ba7e938d2b0588260dd53455ff536935&wsfunction=core_course_get_courses_by_field&moodlewsrestformat=json';
            $response = wp_remote_get($url);
            if (!is_wp_error($response)) {
                $courses = json_decode(wp_remote_retrieve_body($response));

                ?>
                <div class="course-grid">
                    <?php
                    foreach ($courses->courses as $course) {
                        ?>
                        <div class="course-article">
                            <?php if (!empty($course->overviewfiles)) {
                                ?>
                                <div class="course-image">
                                    <img src="<?php echo $course->overviewfiles[0]->fileurl . '?token=' . $token->token ?>"
                                         alt="">
                                </div>
                            <?php } ?>
                            <div class="course-description">
                                <?php echo $course->fullname ?><br>
                                <?php echo $course->categoryname ?><br>
                                <?php echo $course->summary ?><br>
                            </div>
                            <a class="course-button" href="">Zum Kurs</a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }

    }

    function getMoodleToken()
    {
        $url = $this->url_prefix .
            '/login/token.php?username=' . $this->token_credentials['username'] .
            '&password=' . $this->token_credentials['password'] . '&service=moodle_mobile_app';
        $response = wp_remote_get($url);
        if (!is_wp_error($response)) {
            $response = json_decode(wp_remote_retrieve_body($response));
        }
        return $response;
    }
}

new MoodleCoursesToRpi();