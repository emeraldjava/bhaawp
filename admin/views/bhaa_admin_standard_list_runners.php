<div class="wrap">
    <h2>BHAA Standard '<?php echo $_GET['standard'];?>' Page</h2>
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
                        'page' => 'bhaa_admin_standard_list_members', // as defined in the hidden page
                        'year' => $row->standard
                    ),
                    admin_url('admin.php')
                );
                echo sprintf('<tr><td>%d</td><td>%d</td><td>%s</td></tr>',
                    $key+1,
                    $row->id,
                    $row->display_name);
            }
        }
        ?>
        </tbody>
    </table>
</div>