<?php
echo '<div class="wrap"><h2>Edit Race Results Template '.$_GET['id'].'</h2>'.Individual_Table::get_instance()->renderTable($_GET['id']).'</div>';
?>