<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>test pbci</title>
	<style type="text/css">
		
		body {
			font-family: Verdana, Geneva, sans-serif;
		}
		
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

$anyPost = false;
	
$data = $_POST['Cardindex'];
	
if( !empty($data['code']) || 
 	!empty($data['genus']) || 
 	!empty($data['species']) || 
 	!empty($data['author']) || 
 	!empty($data['status']) || 
 	!empty($data['age']) || 
 	!empty($data['formation']) || 
 	!empty($data['locality']) || 
 	!empty($data['synonym']) || 
 	!empty($data['refnum']) || 
 	!empty($data['bibliography']) 
   ) {
	$anyPost = true;
}

	echo "<pre>";
	echo print_r($data);
	echo "</pre>";
	
//echo '<pre>' . var_export($_POST['Slideindex'], true) . '</pre>';

	echo "<table>";
	
	echo "<tr><td>Code: </td><td><strong>" . $data['code'] . "</strong></td></tr>";
 	echo "<tr><td>Genus: </td><td><strong>" . $data['genus'] . "</strong></td></tr>";
 	echo "<tr><td>Species: </td><td><strong>" . $data['species'] . "</strong></td></tr>";
 	echo "<tr><td>Author: </td><td><strong>" . $data['author'] . "</strong></td></tr>";
 	echo "<tr><td>Status: </td><td><strong>" . $data['status'] . "</strong></td></tr>";
 	echo "<tr><td>Reference: </td><td><strong>" . $data['reference'] . "</strong></td></tr>";
 	echo "<tr><td>Age: </td><td><strong>" . $data['age'] . "</strong></td></tr>";
 	echo "<tr><td>Formation: </td><td><strong>" . $data['formation'] . "</strong></td></tr>";
 	echo "<tr><td>Member: </td><td><strong>" . $data['member'] . "</strong></td></tr>";
 	echo "<tr><td>Locality: </td><td><strong>" . $data['locality'] . "</strong></td></tr>";
 	echo "<tr><td>Remarks: </td><td><strong>" . $data['remark'] . "</strong></td></tr>";
 	echo "<tr><td>Synonym: </td><td><strong>" . $data['synonym'] . "</strong></td></tr>";
   
	echo "</table>";

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
         $this->open('../../data/pbcards.db');
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

	// ==================================================================
	// build query
	// ==================================================================
	
	if( $anyPost === false ) {
		
		$query = 'SELECT * FROM CARDS ORDER BY `code`;';
		
	} else {
	
		$query = 'SELECT * FROM CARDS WHERE ';

		$queryText = array();

		if( !empty($data['code']) ) { array_push($queryText,'code LIKE :code'); } 
		if( !empty($data['genus']) ) { array_push($queryText,'genus LIKE :genus'); }  
		if( !empty($data['species']) ) { array_push($queryText,'species LIKE :species'); }  
		if( !empty($data['author']) ) { array_push($queryText,'author LIKE :author'); }  
		if( !empty($data['status']) ) { array_push($queryText,'status LIKE :status'); }  
		if( !empty($data['reference']) ) { array_push($queryText,'reference LIKE :reference'); }  
		if( !empty($data['age']) ) { array_push($queryText,'age LIKE :age'); }  
		if( !empty($data['formation']) ) { array_push($queryText,'formation LIKE :formation'); }  
		if( !empty($data['member']) ) { array_push($queryText,'member LIKE :member'); }  
		if( !empty($data['locality']) ) { array_push($queryText,'locality LIKE :locality'); }  
		if( !empty($data['remark']) ) { array_push($queryText,'remarks LIKE :remarks'); }  
		if( !empty($data['synonym']) ) { array_push($queryText,'synonym LIKE :synonym'); }  

		$queryParams = join(' AND ', $queryText);

		$query .= $queryParams;

		$query .= ' ORDER BY `code`;';
	
	}
	
	
	echo "<h4>" . $query . "</h4>";
	$queryTextFull = $query;
	
	// ==================================================================
	
	$stmt = $db->prepare($query);
	
	// ==================================================================
	// bindings
	// ================================================================== 
	
	if( !empty($data['code']) ) { $stmt->bindValue(':code', "%".$data['code']."%", SQLITE3_TEXT); } 
 	if( !empty($data['genus']) ) { $stmt->bindValue(':genus', $data['genus'], SQLITE3_TEXT); }  
 	if( !empty($data['species']) ) { $stmt->bindValue(':species', $data['species'], SQLITE3_TEXT); }  
 	if( !empty($data['author']) ) { $stmt->bindValue(':author', $data['author'], SQLITE3_TEXT); }  
 	if( !empty($data['status']) ) { $stmt->bindValue(':status', $data['status'], SQLITE3_TEXT); }  
 	if( !empty($data['reference']) ) { $stmt->bindValue(':reference', $data['reference'], SQLITE3_TEXT); }  
 	if( !empty($data['age']) ) { $stmt->bindValue(':age', $data['age'], SQLITE3_TEXT); }  
 	if( !empty($data['formation']) ) { $stmt->bindValue(':formation', $data['formation'], SQLITE3_TEXT); }  
 	if( !empty($data['member']) ) { $stmt->bindValue(':member', $data['member'], SQLITE3_TEXT); }  
 	if( !empty($data['locality']) ) { $stmt->bindValue(':locality', $data['locality'], SQLITE3_TEXT); }  
 	if( !empty($data['remark']) ) { $stmt->bindValue(':remarks', $data['remark'], SQLITE3_TEXT); }  
 	if( !empty($data['synonym']) ) { $stmt->bindValue(':synonym', $data['synonym'], SQLITE3_TEXT); }  

	
	// ==================================================================
	// string replace (debug)
	// ================================================================== 
	
	$queryTextFull = str_replace(':code', $data['code'], $queryTextFull);
 	$queryTextFull = str_replace(':genus', $data['genus'], $queryTextFull);
 	$queryTextFull = str_replace(':species', $data['species'], $queryTextFull);
 	$queryTextFull = str_replace(':author', $data['author'], $queryTextFull);
 	$queryTextFull = str_replace(':status', $data['status'], $queryTextFull);
 	$queryTextFull = str_replace(':reference', $data['reference'], $queryTextFull);
 	$queryTextFull = str_replace(':age', $data['age'], $queryTextFull);
 	$queryTextFull = str_replace(':formation', $data['formation'], $queryTextFull);
 	$queryTextFull = str_replace(':member', $data['member'], $queryTextFull);
 	$queryTextFull = str_replace(':locality', $data['locality'], $queryTextFull);  
 	$queryTextFull = str_replace(':remarks', $data['remark'], $queryTextFull);
 	$queryTextFull = str_replace(':synonym', $data['synonym'], $queryTextFull);
	
	echo "<h4>" . $queryTextFull . "</h4>";
	
	// ==================================================================
	
	$result = $stmt->execute();
	
   $nr = 0;
   while($row = $result->fetchArray(SQLITE3_ASSOC) ) {
	  echo "<tr>";
      echo "<td>". $row['code'] . "</td>";
	  echo "<td><a href='http://images.peabody.yale.edu/ci/". $row['code'] . ".jpg' target='_blank'>image</a></td>";
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