<?php require_once __DIR__ . '/_header.php'; ?>


<!--<form action="<?php //echo __SITE_URL;?>/index.php?rt=messeges/userMesseges" method='post'>-->

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
                    echo $messege->content;
                    echo '<br> <button type="submit" name="palac" value="' . $messege->id .'"> &#x1F44D; '. $messege->thumbs_up.'</button>'; //&#x1F44D;
                echo '<br><span></li></div>';
            }//stringToColorCode
    ?>
</ul>
</div>
</article>

<article>
<div class="container">
<input type="text" name="poruka">
<br><br>
<input type="submit" name='send_messege' value="pošalji">
</div>
</form>
<?php
?>


<?php require_once __DIR__ . '/_footer.php'; ?>