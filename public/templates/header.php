<?php
if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
show_admin_bar( false );
//wp_head();
//get_header();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?></title>
<!--    <link rel="stylesheet" type="text/css" href="--><?php //bloginfo('stylesheet_url'); ?><!--" />-->
    <?php wp_head(); ?>
</head>

<body role="document">
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-static-top">

    <div class="container btn-group btn-group-justified">
        <ul class="nav nav-pills">
            <li><a class="btn btn-primary" href="/raceday-register" role="button">Members</a></li>
            <li><a class="btn btn-info" href="/raceday-newmember">New Member</a></li>
            <li><a class="btn btn-success" href="/raceday-prereg">Pre-Reg</a></li>
            <li><a class="btn btn-warning" href="/raceday-list">List</a></li>
            <li><a class="btn btn-danger" href="/wp-admin/admin.php?post_type=race&action=bhaa_raceday_export">Export</a></li>
        </ul>
    </div>
</nav>
<div class="container theme-showcase" role="main">
    <div class="panel panel-default">