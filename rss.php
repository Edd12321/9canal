<?php
  header('Content-type: text/xml');
  include realpath(__DIR__).'/config.php';

  $title = "9canal RSS";
  $link = "https://".$_SERVER["HTTP_HOST"]."$fakeroot/all";
  $desc = "cel mai cajpik imageboard :))";

  $allroot = "../all";
?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
    <channel>
      <title><?=$title?></title>
      <link><?=$link?></link>
      <description><?php echo $desc; ?></description>
      <atom:link href=<?php echo '"'.$link.'"'; ?> rel="self" type="application/rss+xml"/>
      <?php
        $files = array_splice(scandir("$root/all/"), 2);
        foreach ($files as $file) {
          $lines = file("$allroot/$file/$file.txt");
          if (is_numeric($file)) {
            echo "<item>
                    <title>".$lines[1]."</title>
                    <enclosure url=\"$allroot/$file\" length=\"".filesize("$allroot/$file/$file.txt")."\" />
                    <link>$link/$file</link>
                    <guid>$link/$file</guid>
                    <pubDate>".date(DATE_RSS, filectime("$allroot/$file/$file.txt"))."</pubDate>
		    <description>"; 
            $oo = count($lines);
            for ($k = 4; $k < $oo; ++$k) {
              echo $lines[$k].'<br />';
            }
            echo "</description>
                  </item>\n";
          }
        }
      ?>
  </channel>
</rss>
