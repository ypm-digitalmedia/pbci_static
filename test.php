<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>test pbci</title>
	<style type="text/css">
	
		table {
			width: 100%;
			border-left: 1px #aaa solid;
			border-top: 1px #aaa solid;
		}
		
		table tr td, table tr th { 
			border-right: 1px #aaa solid;
			border-bottom: 1px #aaa solid;
			padding: 2px;
		}
		
	</style>
</head>
<body>
<?php
   function microtime_diff($start, $end = null)
	{
		if (!$end) {
			$end = microtime();
		}
		list($start_usec, $start_sec) = explode(" ", $start);
		list($end_usec, $end_sec) = explode(" ", $end);
		$diff_sec = intval($end_sec) - intval($start_sec);
		$diff_usec = floatval($end_usec) - floatval($start_usec);
		return floatval($diff_sec) + $diff_usec;
	}
	
   $startTime = microtime();
	
   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('../../../data/pbcards.db');
      }
   }
   
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "<p>Opened database: <strong>data/pbcards.db</strong></p>";
      echo "<table>";
	  echo "<tr>";
	  echo "<th>Code</th>";
	  echo "<th>Assets</th>";
      echo "<th>Genus</th>";
      echo "<th>Species</th>";
      echo "<th>Author</th>";
      echo "<th>Status</th>";
      echo "<th>Reference</th>";
      echo "<th>Age</th>";
      echo "<th>Formation</th>";
      echo "<th>Member</th>";
      echo "<th>Locality</th>";
      echo "<th>Remarks</th>";
      echo "<th>Synonym</th>";
	  echo "</tr>";

   }

   $sql =<<<EOF
      SELECT * from CARDS;
EOF;
   
   $nr = 0;
   $ret = $db->query($sql);
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
	  echo "<tr>";
      echo "<td>". $row['code'] . "</td>";
	  echo "<td><a href='http://images.peabody.yale.edu/ci/". $row['code'] . ".jpg' target='_blank'>full</a> <a href='http://images.peabody.yale.edu/ci/web/". $row['code'] . ".jpg' target='_blank'>thumb</a></td>";
      echo "<td>". $row['genus'] . "</td>";
      echo "<td>". $row['species'] . "</td>";
      echo "<td>". $row['author'] . "</td>";
      echo "<td>". $row['status'] . "</td>";
      echo "<td>". $row['reference'] . "</td>";
      echo "<td>". $row['age'] . "</td>";
      echo "<td>". $row['formation'] . "</td>";
      echo "<td>". $row['member'] . "</td>";
      echo "<td>". $row['locality'] . "</td>";
      echo "<td>". $row['remarks'] . "</td>";
      echo "<td>". $row['synonym'] . "</td>";
	  echo "</tr>";
	  $nr++;
   }
   echo "</table>";
   $endTime = microtime();
   $diff = microtime_diff($startTime, $endTime);
   echo "<p>Data loaded successfully: <strong>" . $nr . "</strong> rows. (" . $diff ." seconds)</p>";
   $db->close();
?>
</body>
</html>