<?php
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
    private $url_prefix = 'https://moodle.rpi-virtuell.de';
    private $token_credentials = array(
        'username' => 'ssotester',
        'password' => 'ssotester'
    );

    function __construct()
    {
        add_shortcode('moodle category', array($this, 'moodleCourses'));
    }

    function moodleCourses($atts)
    {
        if (!is_wp_error($token = $this->getMoodleToken())) {
            $url = $this->url_prefix .
                '/webservice/rest/server.php?wstoken='. $token['token'] .'&wsfunction=core_course_get_courses&moodlewsrestformat=json';
            $response = wp_remote_get($url);
            if (!is_wp_error($response)){
                $courses = json_decode(wp_remote_retrieve_body($response));
                foreach ($courses as $course)
                {
                    if ($course->shortname == $atts)
                    {
                        $display_course = $course;
                    }

                    ?>
                    <div class=""
<?php
                }
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