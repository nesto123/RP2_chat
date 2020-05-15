<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?php echo __SITE_URL; ?>/css/style.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo __SITE_URL; ?>/css/chat.png" />
    
    <script>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>
    <title>Chat</title>

</head>
<body onload="startTime()">
<div class="header">
    <h1><?php echo $title; ?></h1>
</div>

<aside>
<h4> Notifications </h4>
     <div class="txt">
     <?php
    if( isset($errorFlag))
        if( $errorFlag )
            echo $errorMsg . '<br>';
    ?>
     <br> 
        Current time: <div id="txt"></div> <br>
   </div>
</aside>

<article>
<div class="container">
<p>After registration you will receive an e-mail confirmation and link to activate your account!</p>

<form action="<?php echo __SITE_URL; ?>/index.php?rt=index/register" method='post'>
    Chose username:
    <input type="text" name="username"><br>
    Chose password:
    <input type="password" name="password"><br>
    Input e-mail:
    <input type="email" name="email">
    <br><br>
    <input type="submit" value="Register">
</form>
</div>
</article>

</body>
</html>