<?php require_once __DIR__ . '/_header.php'; ?>


<!--<form action="<?php //echo __SITE_URL;?>/index.php?rt=messeges/index" method='post'>-->



<form action="<?php echo __SITE_URL;?>/index.php?rt=messeges/send_messege" method='post'>
<div class="messeges">
<ul>
    

    <?php 
            foreach( $messegeList as $messege )
            {
                echo '<div class="messeges li" style="--c: #' . stringToColorCode($messege->username). '">';
                echo '<li><span>';
                    echo '<b>' . $messege->username. '   </b>';
                    echo '<i><small>' . $messege->date . '</small></i><br>';
                    echo  $messege->content;
                    echo '<br> <button type="submit" name="palac" value="' . $messege->id .'"> &#x1F44D; '. $messege->thumbs_up.'</button>'; //&#x1F44D;
                    //echo '<br>'. $messege->thumbs_up . '</td>';
                    echo '<div class="container">';
                    echo '<br><button name="channel" type="submit" value="'.$messege->id_channel.'">Prikaži kanal</button>';
                echo '</div></span><br></li></div>';
            }
    ?>
</ul>
</div>
</form>


<?php require_once __DIR__ . '/_footer.php'; ?>