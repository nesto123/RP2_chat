<?php require_once __DIR__ . '/_header.php'; ?>





<form action="<?php echo __SITE_URL;?>/index.php?rt=channel/startNew" method='post'>
Channel name: <br><br>
<div class="container">
<input type="text" name="imeKanala">
<br><br>
<input type="submit" name='submit' value="Kreiraj novi kanal">
</div>
</form>



<?php require_once __DIR__ . '/_footer.php'; ?>