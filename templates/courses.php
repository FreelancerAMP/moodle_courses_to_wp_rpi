
<article class="course-article">
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
</article>