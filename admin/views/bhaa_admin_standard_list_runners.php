<div class="wrap">
    <h2><?php echo sprintf('<a href="%s">%s</a>',$parentStandardsLink,"Standards");?> BHAA Standard '<?php echo $_GET['standard'];?>' Page</h2>
    <table border="1">
        <tbody>
        <tr>
            <th>No</th>
            <th>BHAA ID</th>
            <th>Athlete</th>
        </tr>
        <?php
        if ( ! empty( $runnersInStandard ) ) {
            foreach ( $runnersInStandard as $key=>$row ) {

                $link = add_query_arg(
                    array(
                        'id' => $row->id
                    ),
                    site_url('/runner')
                );
                echo sprintf('<tr><td>%d</td><td><a href="%s" target="_new">%d</a></td><td>%s</td></tr>',
                    $key+1,
                    $link,
                    $row->id,
                    $row->display_name);
            }
        }
        ?>
        </tbody>
    </table>
</div>