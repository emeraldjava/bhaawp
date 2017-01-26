<div class="wrap">
    <h2>BHAA Missing Standards Page</h2>
    <table border="1">
        <tbody>
        <tr>
            <th>BHAA ID</th>
            <th>Athlete</th>
        </tr>
        <?php
        if ( ! empty( $user_query->results ) ) {
            foreach ( $user_query->results as $user ) {
                echo sprintf('<tr><td>%d</td><td><a href="%s" target="new">%s</a></td></tr>',
                    $user->ID,
                    add_query_arg(array('id'=>$user->ID),'/runner'),$user->display_name);
            }
        }
        ?>
        </tbody>
    </table>
</div>