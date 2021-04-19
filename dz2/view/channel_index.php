<?php require_once __DIR__ . '/_header.php'; ?>


<form action="<?php echo __SITE_URL;?>/index.php?rt=messeges/index" method='post'>

<table align="center">
    <!--<tr>
        <th>Channel name   </th>
        <th>Link</th>
    </tr>-->

    <?php
            foreach( $channelList as $channel )
            {
                echo '<tr>';
                    //echo '<td>' . $channel->id . '</td>';
                    //echo '<td>' . $channel->id_user . '</td>';
                    echo '<td>' . $channel->name . '   </td>';
                    echo '<td><button name="channel" type="submit" value="'.$channel->id.'">Prikaži poruke</button></td>';
                echo '</tr>';
            }
    ?>

</table>
</form>


<?php require_once __DIR__ . '/_footer.php'; ?>