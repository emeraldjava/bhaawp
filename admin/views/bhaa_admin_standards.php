<div class="wrap">
    <h2>BHAA Standard Page</h2>
    <table border="1">
        <tbody>
        <tr>
            <th>Standard</th>
            <th>Count</th>
        </tr>
        <?php
        if ( ! empty( $memberStandardProfile ) ) {
            foreach ( $memberStandardProfile as $row ) {
                // generate url link to hidden page
                $link = add_query_arg(
                    array(
                        'page' => 'bhaa_admin_standard_list_members',
                        'standard' => $row->standard
                    ),
                    admin_url('admin.php')
                );
                echo sprintf('<tr><td><a href="%s">%d</a></td><td>%d</td></tr>',
                    $link,
                    $row->standard,
                    $row->count);
            }
        }
        ?>
        </tbody>
    </table>
</div>