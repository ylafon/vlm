<?php
if (isset($RSSLINK)) {
  header('Content-Type: application/xhtml+xml;charset=UTF-8');
} else {
  header('Content-Type: text/html;charset=UTF-8');
}
header('Cache-Control: max-age=1');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $PAGETITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="../style/status.css" />
    <link rel="icon" type="image/png" href="../images/site/favicon.png" />
    <?php
        if (isset($RSSLINK)) {
            echo '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$RSSLINK.'" />';
        }
    ?>
  </head>
  <body>
      <p class=".horizontalmenu">Available status : <a href="polar-status.php" rel="nofollow">Polar status</a>&nbsp;&middot;&nbsp;<a href="race-engine-status.php" rel="nofollow">Race engine status</a>&nbsp;&middot;&nbsp;<a href="windtable-status.php" rel="nofollow">Windtable status</a>&nbsp;&middot;&nbsp;<a href="module-status.php" rel="nofollow">Module status</a>&nbsp;</p>
    <h1><?php echo $PAGETITLE; ?></h1>
