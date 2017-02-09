<?php
get_header();

echo '<div class="container_wrap main_color main">
    <div class="container template-blog template-single-blog">
    <div class="content units content">';
echo Individual_Table::get_instance()->renderTable(get_the_ID());
echo '</div></div></div>';

get_footer();
?>