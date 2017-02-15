<?php
get_header();

echo '<div class="container_wrap main_color main">
    <div class="container template-blog template-single-blog">
    <div class="content units content">';
echo 'Edit Race Result : '.$_POST["race_result_id"].'.';
echo Bhaa_Mustache::get_instance()->loadTemplate('raceday-list')->render(
    array('$race_result_id' => $$race_result_id)
);
echo '</div></div></div>';

get_footer();
?>