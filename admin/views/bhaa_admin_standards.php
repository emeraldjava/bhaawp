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
                echo sprintf('<tr><td>%d</td><td>%d</td></tr>',
                    $row->standard,
                    $row->count);
            }
        }
        ?>
        </tbody>
    </table>
</div>