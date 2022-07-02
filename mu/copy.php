<html>
    <head>
	<style>
           body, html {
               margin: 0;
	       padding 0;
	       font-family: arial;
               background-color: #eef8f0;
	   }
	   #bar {
	       width: 100%;
	       position: fixed;
	       background-color: #6a836f;
	       color: #dddddd;
	       padding: 3px;
	       border-bottom: 1px solid #252525;
               z-index: 10;
	   }
	   #bar a {
	       color: #eeeeee;
	   }
	   #board {
	       display: grid;
	       grid-template-columns: repeat( auto-fill, minmax(270px, 1fr) );
	       padding: 5px;
	   }
	   #thread {
	       /* display: inline-block; */
               position: absolute;
	       padding: 5px;
	       width: auto;
               clear: both;
	   }
	   h2 {
               font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;
	       color: #990000;
               text-shadow: 1px 1px 1px;
           }
	   #post {
	       overflow: hidden;
               text-overflow: ellipsis;
	       padding: 5px;
	       background-color: #d6f0da;
	       font-family: serif;
	       border-bottom: 1px solid #cccccc;
	       margin: 5px;
	   }
           #board #post {
	       position: relative;
	   }
	   #thread #post {
	       margin-right: auto;
	       width: fit-content;
               display: block;
	   }
           .table {
               display: table;
	   }
	   .td {	       
	       display: table-cell;
               background-color: #d6f0da;
	       border: 1px solid #cccccc;
               vertical-align: middle;
           }
	   .tr {
               display: table-row;
	   }
           .table input {
               float: left;
	   }
           .table .btn1 {
               float: right;
	   }
	   #pt1 {
	       font-family: serif;
               background-color: #98c1a9;
	   }
        </style>
    </head>
    <body>
	<div id="bar">
	    <i><b>
                <font color="#bcaf4f">9</font>canal
            </b></i>&ensp;
	    [
            <?php
                $path = basename(getcwd());
                if (is_numeric($path))
		    $path = '../..';
                else if (!file_exists('lock.dat'))
		    $path = '..';
                else $path = '.';
		$files = scandir($path, 0);
		$i = 0;
		$fsize = count($files);
		foreach($files as $file) {
		    ++$i;
		    if (is_dir($path . '/' . $file)) {
			    echo '<a href="/' .  $file . '/">' . $file . '</a>&nbsp;';
			    if ($i != $fsize)
				echo '/&nbsp';
		    }
		}
            ?>
            ]
	</div>
        <br /><center>
        <?php
           if ($_POST) {
	       switch(file_exists("lock.dat")) {
               case 0: {
	           $trip = $_POST["trip"];
                   if ($trip == '')
		       $trip = "Anonymous";
                   $subject = $_POST["subject"];
		   $content = $_POST["content"];

		   $number = file_get_contents(realpath(__DIR__) . '/postnum.txt');
		   ++$number;
		   file_put_contents(realpath(__DIR__) . '/postnum.txt', @$number);
                   $ok = 0;
		   if (!is_numeric(basename(getcwd()))) {
		       // creare thread
		       mkdir($number);
		       symlink(realpath('./' . $number), '../all/' . $number);	
		       chdir($number);
		       symlink("../../index.php", "./index.php");
		       $ok = 1;
		   }
		   file_put_contents(($number . ".txt"), (htmlspecialchars($trip) . 
			                        PHP_EOL . htmlspecialchars($subject) . 
						PHP_EOL . str_replace(PHP_EOL, '<br />', (htmlspecialchars($content)))));
		   if ($ok)
		       chdir("../");
		   break;
	       } case 1: {
	           echo '<center><font color="red"><b>Select a board before posting!</b></font></center>';
                   break;
	       }}
	   }
	   $title = getcwd();
	   if (is_numeric(basename(getcwd())))
	      $title = $title . "/..";
	   $title = '/' . basename(realpath($title)) . '/ - ' . file_get_contents($title . "/title.txt");
	   echo '<h2>' . $title . '</h2>
	      <title>' . $title . ' | 9ch</title>'
        ?>
	<br />
        <div class="table">
		<form method="post">
                    <div class="tr">
			<div class="td" id="pt1">
			    <label for="trip">
                                <b>
				    Tripcode
                                </b>
			     </label>
                         </div>
                         <div class="td">
			     <input type="text" name="trip">
                             <br />
			 </div>
                    </div>
                    <div class="tr">
                        <div class="td" id="pt1">
			    <label for="subject">
                                <b>
			    	    Subject
                                </b>
                            </label>
                        </div>
                        <div class="td">
                            <?php
                                echo '<input type="text" name="subject" ';
	                        if (!is_numeric(basename(getcwd())))
 			            echo 'required';
		   	        echo '><br />';
                            ?>
                        </div>
		    </div>
                    <div class="tr">
                        <div class="td" id="pt1">
			    <label for="content">
                                <b>
				    Content
                                </b>
                            </label>
                        </div>
                        <div class="td">
                            <?php
                                echo '<textarea name="content"';
	                        if (!is_numeric(basename(getcwd())))
			            echo 'required';
			        echo '></textarea><br />';
                            ?>
                        </div>
		    </div>
                    <div class="tr">
                        <div class="td" id="pt1">
			    <label for="submit">
                                <b>
                                    Send 
                                    <?php
			   	        if (is_numeric(basename(getcwd())))
                                            echo 'reply';
				        else echo 'thread';
                                ?>
                                </b>
                            </label>
                        </div>
                        <div class="td">
                            <input type="submit" name="submit" value="Post!" class="btn1">
                        </div>
                    </div>
		</form>
            </div>
	</div>
	</center>
	<br />
        <?php
        function nl3br($text) {
	    return str_replace(array('\r\n', '\r', '\n', '\\r\\n', '\\r', '\\n'), '<br />', $text);
	}
	function green($text) {
	    return preg_replace('/(?:\n)?&gt;([^<]*)/m', '<font color="#789922">&gt;$1</font>', $text);
	}
	function make_links_clickable($text) {
            return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
        }
	$view = 0;
        if (!is_numeric(basename(getcwd()))) {
	    echo '<div id="board">';
            $view = 1;
	} else
	    echo '<div id="thread">';
	$files = scandir(getcwd());
	natsort($files);
        if ($view)
	    $files = array_reverse($files);
	foreach ($files as $file) {
	    if (is_numeric(basename($file, ".txt"))) {
	        $access = $file;    
	           if (is_dir($file))
		       $access = $file . '/' . $file . ".txt";
		   $lines = file($access);
		   $rest = implode('\n', array_splice($lines, 2));
		   $f2 = $lines;
		   echo '<div id="post">
			     <font color="#4f5071">
			         <b>#' . basename($file, ".txt") .
                           ' </font>
			     <font color="#0f0c5d"><i>' .
		                 $f2[1] . 
			    '</i></font>
			     <font color="#137844">
				~' . $f2[0] . '<a style="position:absolute;right:5px;top:5px;">';
                   if ($view) {
                       if (basename(getcwd()) == 'all')
		           echo '&gt;&gt;&gt;/' . basename(realpath($file . '/..')) . '/';
  		       echo ' 📁';
		   } echo '</a>';
			    echo '</font></b><br />' . 
               		     date("F d, Y @H:i:s.", filemtime($access))  .
			    '<hr />' . 
		 	     make_links_clickable(nl3br(green($rest)));
                   if (is_dir($file)) { 
		       $posts = count(scandir($file)) - 4;
		       echo '<form action="' . $file . '">
			         <button type="submit" style="float:right;position:absolute;bottom:5px;right:5px;">View thread</button>
			     </form>';
		       if ($posts) {
			   echo ' &nbsp;(+' . $posts . ' repl';
                           if ($posts == 1)
		               echo 'y';
			   else echo 'ies';
                           echo ')';
		       }
		   }
		   echo '</div>';
	       }
	}
	?></div><?php
            if (file_exists("lock.dat")) {
		echo '<h2><center>
		          <font color="#990000">
			      Server stats
			  </font>
		      </center></h2>
		      <br /><br /><pre style="background-color:black; color:lime;">'; 
		          passthru("(df && top -n 1) | ansifilter");
		echo '</pre>';
	    }
        ?>
    </body>
</html>
