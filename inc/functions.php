<?php

function get_journal_list() {

	include 'connection.php'; // Connection to the database

	/*
	This block of code tries to run a query against the entries database. If it's succesful we have an object $results with the retrieved results.
	*/
	try {
		$results = $db->query('SELECT id, title, date FROM entries');
	} catch (Exception $e) {
	    echo "Error!: " . $e->getMessage() . "</br>";
	    return array(); // The foreach loop is expecting an array. Instead of a boolean false we return an empty array
	}

	/*
	PDOStatement::fetchAll — Returns an array containing all of the result set rows
	PDO::FETCH_ASSOC: returns an array indexed by column name as returned in your result set
	*/
	$home = $results->fetchAll(PDO::FETCH_ASSOC);
	
	//Sorting entries by date. Comparision function and array sorting
	usort($home, function($a1, $a2) {
	   $v1 = strtotime($a1['date']);
	   $v2 = strtotime($a2['date']);
	   return $v2 - $v1; // $v1 - $v2 to reverse direction
	});  
	return($home);
}

function detail_array() {

	//Connection to the database.
	include("connection.php");

	/*
	Prepared statement. A prepared statement or a parameterized statement is used to execute the same statement repeatedly with high efficiency. 
	The prepared statement execution consists of two stages: prepare and execute. At the prepare stage a statement template is sent to the database server. The server performs a syntax check and initializes server internal resources for later use. 
	The MySQL server supports using anonymous, positional placeholder with ?.
	Prepare is followed by execute. During execute the client binds parameter values and sends them to the server. The server creates a statement from the statement template and the bound values to execute it using the previously created internal resources.
	A prepared statement can be executed repeatedly. Upon every execution the current value of the bound variable is evaluated and sent to the server. The statement is not parsed again. The statement template is not transferred to the server again.
	*/
	try {
		$results = $db->prepare(
			"SELECT * 
			FROM entries 
			WHERE id = ?"
		);
		$results->bindParam(1,$_GET['id'],PDO::PARAM_INT);
		$results->execute();
	} catch (Exception $e) {
	    echo "Bad query";
	    exit;
	}

	/*
	PDOStatement::fetchAll — Returns an array containing all of the result set rows
	PDO::FETCH_ASSOC: returns an array indexed by column name as returned in your result set
	*/
	$detail = $results->fetchAll(PDO::FETCH_ASSOC);
	
	return $detail;
}

/*
When accepting user data, it's important to use prepared statements when interacting with the database, to prevent SQL injection. We'll use a prepared statement to INSERT new journal entries into our database.
*/
function add_journal_entry($title, $date, $timeSpent, $whatILearned, $resourcesToRemember){
    include 'connection.php';
    
    $sql = 'INSERT INTO entries(title, date, time_spent, learned, resources) VALUES(?, ?, ?, ?, ?)';
    
    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $title, PDO::PARAM_STR);
        $results->bindValue(2, $date, PDO::PARAM_STR);
        $results->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $results->bindValue(4, $whatILearned, PDO::PARAM_STR);
        $results->bindValue(5, $resourcesToRemember, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        exit;
    }
    return $results;
}

/*
Function to retrieve all the table data for a particular id
*/
function get_entry($id){
    include 'connection.php';
    
    $sql = 'SELECT * FROM entries WHERE id = ?';
    
    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        exit;
    }
    return $results->fetch(PDO::FETCH_ASSOC);
}

/*
Function to edit a particular journal entry
*/
function edit_journal_entry($title, $date, $time_spent, $learned, $resources, $id){
  	include("connection.php");
  	
  	$sql = 'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';
  	
  	try{
	    $results = $db->prepare($sql);
	    $results->bindValue(1,$title,PDO::PARAM_STR);
	    $results->bindValue(2,$date,PDO::PARAM_STR);
	    $results->bindValue(3,$time_spent,PDO::PARAM_STR);
	    $results->bindValue(4,$learned,PDO::PARAM_STR);
	    $results->bindValue(5,$resources,PDO::PARAM_STR);
	    $results->bindValue(6,$id,PDO::PARAM_INT);
	    $results->execute();
  	} catch (Exception $e){
    	echo "Error: " . $e->getMessage() . "<br>";
        exit;
    }
    return $results;
}

/*
Function to delete all the table data for a particular id
*/
function delete_entry($id){
    include 'connection.php';
    
    $sql = 'DELETE FROM entries WHERE id = ?';
    
    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}