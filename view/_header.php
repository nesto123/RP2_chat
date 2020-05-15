<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="<?php echo __SITE_URL; ?>/css/style.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo __SITE_URL; ?>/css/chat.png" />

    <title>Chat</title>
    <!--<style>
    table { border-collapse: collapse;}
    td, th { border:solid 1px black}
    </style>-->
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

</head>

<body onload="startTime()">
<div id="page-container">
   <div id="content-wrap">

<div class="header">
    <h1 id="vrhStranice">Chat app</h1>
</div>

<div class="menu">
    <ul>
        <a href="<?php echo __SITE_URL; ?>/index.php?rt=channel/index"><li>My channels</li></a>
        <a href="<?php echo __SITE_URL; ?>/index.php?rt=channel/AllChannels"><li>All channels</li></a>
        <a href="<?php echo __SITE_URL; ?>/index.php?rt=channel/newInit"><li>Start new channel</li></a>
        <a href="<?php echo __SITE_URL; ?>/index.php?rt=messeges/MyMesseges"><li>My messeges</li></a>
        <a href="<?php echo __SITE_URL; ?>/index.php?rt=index/logout"><li>Logout</li></a>
    </ul>
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
<h3 style="text-align:center"><?php echo $title; ?></h3>