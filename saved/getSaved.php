<?php
	ini_set('display_errors', 1);
	if( isset( $_POST['saved']) ) {
		
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

		$maxResults = 200;	
		$savedString = $_POST['saved'];
		
		$saved = explode("|", $savedString);
		
		$queryBase = 'SELECT * FROM CARDS WHERE `code`=?';
		$queryParams = "";
//		$queryParams = join(' OR `code`=%%%%', $saved);
		for( $x=0; $x<count($saved)-1; $x++) {
			$queryParams .= ' OR `code`=?';
		}
		$queryParams .= ";";
		$query = $queryBase . $queryParams;
//		echo($query . "\n");
		
		$stmt = $db->prepare($query);
		
		for( $y=1; $y<=count($saved); $y++ ) {
//			echo("\n".$y."\n");
			$stmt->bindValue($y, $saved[$y-1], SQLITE3_TEXT);

		}
		
		$result = $stmt->execute();
		$nr = 0;
		
		$returnObj = array();

		while($row = $result->fetchArray(SQLITE3_ASSOC) ) {
			array_push($returnObj,$row);	
		}
   		
		$db->close();	
		
		echo json_encode($returnObj);
		
	} else {
		echo ("no saved cards.");
		
	}






















?>