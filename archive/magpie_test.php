<html><head><title>MagpieRSS Test</title></head><body>
<H1>SNOctopus Blog uses Atom</H1>

<?php
include('Magpie/rss_fetch.inc');

$url = "http://snoctopus.blogspot.com/rss.xml";

$feed = fetch_rss($url);

echo "<p><a href=" .$feed->items[0]['link']. ">"

.$feed->items[0]['title']. "</a></p>";

?>
<H1>CNN uses RSS</H1>

<?php

$url_CNN = "http://rss.cnn.com/rss/cnn_topstories.rss";

$feed_CNN = fetch_rss($url_CNN);

echo "<p><a href=" .$feed_CNN->items[0]['link']. ">".$feed_CNN->items[0]['title']. "</a></p>";
echo "<p><a href=" .$feed_CNN->items[1]['link']. ">".$feed_CNN->items[1]['title']. "</a></p>";

?>


</body></html>
