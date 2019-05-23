<?php

//$card = (string)filter_input(INPUT_GET, 'slide');

if (!isset($_GET['card'])) {
    $card = null;
	$anyGet = false;
} elseif (!is_string($_GET['card'])) {
    $card= null;
	$anyGet = false;
} else {
    $card = $_GET['card'];
	
	// ==================================================================
	// connect to DB
	// ==================================================================	
	
	
	class MyDB extends SQLite3 {
	  function __construct() {
		 $this->open('../../../data/pbcards.db');
	  }
	}
   
	$db = new MyDB();

	if(!$db) {
	  echo $db->lastErrorMsg();
	}				
				
	// ==================================================================
	// build query
	// ==================================================================
	$query = 'SELECT * FROM CARDS WHERE `code`=:code LIMIT 1';
	
	//	echo "<h4>" . $query . "</h4>";
	$queryTextFull = $query;
	
	// ==================================================================
	
	$stmt = $db->prepare($query);
	
	// ==================================================================
	// bindings
	// ================================================================== 
	
	$stmt->bindValue(':code', $card, SQLITE3_TEXT);
	
	// ==================================================================
	// string replace (debug)
	// ================================================================== 
	
	$queryTextFull = str_replace(':code', $card, $queryTextFull);
	//	echo "<h4>" . $queryTextFull . "</h4>";
	
	// ==================================================================
	
	$result = $stmt->execute();
	
	$nr = 0;
	
	$data = array();
	while($row = $result->fetchArray(SQLITE3_ASSOC) ) {

		
		$data = $row;
		
		$nr++;
	}
	$db->close();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Paleobotany Card Search</title>
<link href="../public/css/style.css" rel="stylesheet" type="text/css" />
<link href="../public/css/prettyPhoto.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../public/js/jquery/jquery.js"></script>
<script type="text/javascript" src="../public/js/jquery/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="../public/js/jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="../public/js/pages/prettyPhoto.js"></script>
<script type="text/javascript" src="../public/js/pages/saveResult.js"></script>
<script type="text/javascript" src="../public/js/lodash.min.js"></script>
<script type="text/javascript" src="../public/js/main.js"></script>
</head>
<body>
	<div class="bodycontent">
    	<div class="sidebar">
        	<img src="../public/images/sidebar.png" width="248" height="1034" border="0" />
        </div>
    	<img src="../public/images/header.jpg" width="1000" height="228" border="0" />
	</div>
    <div class="menu">
    	<div class="bodycontent menulinks">
        	<a href="../">Home</a> | 
        	<a  href="../user-guide">User Guide</a> | 
        	<a href="../search">Search</a> | 
        	<a href="../saved">Saved Results</a> | 
        	<a href="../contact-us">Contact Us</a>
        </div>
    </div>
	    	<div class="bodycontent">
	    	<div class="contentarea">
	        	<div id="content">
					
				<?php if( $nr == 1) : ?>
					
					<div id="pretty_photo">
						<div class="info">

							<h2>Card: <?=$data['code'] ?></h2>
							<p>&nbsp;</p>
							<p><strong>Genus: </strong><?=$data['genus'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Species: </strong><?=$data['species'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Author: </strong><?=$data['author'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Status: </strong><?=$data['status'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Reference: </strong><?=$data['reference'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Age: </strong><?=$data['age'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Formation: </strong><?=$data['formation'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Member: </strong><?=$data['member'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Locality: </strong><?=$data['locality'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Remarks: </strong><?=$data['remarks'] ?></p>
							<p>&nbsp;</p>
							<p><strong>Synonym: </strong><?=$data['synonym'] ?></p>
							<p>&nbsp;</p>
						 	<p>
								<a href="http://images.peabody.yale.edu/ci/<?=$data['code'] ?>.jpg" rel="prettyphoto[]">
									<img src="http://images.peabody.yale.edu/ci/web/<?=$data['code'] ?>.jpg" alt="<?=$data['code'] ?>" />
								</a>
							</p>

						</div>
					</div>
					
				<?php else : ?>
					
					<h2>Invalid card number.</h2>
					<p><button class='btnsmall submit' onclick='document.location="../search";'>New Search</button></p>
					
				<?php endif; ?>
					
				</div><!-- content -->
	        	</div>
	        </div>
			
	<div class="bodycontent">
		<div class="footer">
			<div class="footerlogo">
				<img src="../public/images/yale.png" width="58" height="25" border="0" />
			</div>
			<div class="footertext">
				Copyright 2019 Peabody Museum of Natural History, Yale University. All rights reserved.
			</div>
		</div>
	</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-3250139-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
