<?php require_once __DIR__ . '/_header.php'; ?>

<p>Svi kanali<p>

<table>
    <tr>
        <th>Id</th>
        <th>Id_user</th>
        <th>Name</th>
    </tr>

    <?php
        foreach( $channelList as $channel )
        {
            echo '<tr>';
                echo '<td>' . $channel->id . '</td>';
                echo '<td>' . $channel->id_user . '</td>';
                echo '<td>' . $channel->name . '</td>';
            echo '</tr>';
        }
    ?>

</table>


<?php require_once __DIR__ . '/_footer.php'; ?>