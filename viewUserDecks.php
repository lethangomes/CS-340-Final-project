<?php
	session_start();
    // Include config file
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Decks</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
	   <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">View Decks</h2>
                    </div>
<?php

// Check existence of id parameter before processing further
if(isset($_GET["Username"]) && !empty(trim($_GET["Username"]))){
	$_SESSION["Username"] = $_GET["Username"];
}

if(isset($_GET["DeleteDeck"])){
    $sql = "DELETE FROM DECK WHERE DeckName = ? AND Username = ?";

    if($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss",  $param_DeckName, $param_Username);      
        // Set parameters
        $param_DeckName = $_GET["DeleteDeck"];
        $param_Username = $_SESSION["Username"];


        // execute update
        mysqli_stmt_execute($stmt);
    }
}

if(isset($_SESSION["Username"])){
    $param_Username = $_SESSION["Username"];
	
    // Prepare a select statement

    $sql = "SELECT DeckName, Format, DeckCost(DeckName, ?) AS deckCost, GetTotalCardsInDeck(DeckName, ?) AS cardsInDeck FROM DECK WHERE Username= ?" ;


	//$sql = "SELECT EUsername, Pno, Hours From WORKS_ON WHERE EUsername = ? ";   
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $param_Username, $param_Username,$param_Username);      
        

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
			echo"<h4> Decks for ".$param_Username."</h4><p>";
			if(mysqli_num_rows($result) > 0){
				echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Deckname</th>";
                            echo "<th>Format</th>";
                            echo "<th>Price</th>";
                            echo "<th>Cards in Deck</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";							
				// output data of each row
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td><a href='viewDeck.php?Username=" . $param_Username . "&DeckName=" .$row['DeckName']. "'>" . $row['DeckName'] . "</a></td>";
                        echo "<td>" . $row['Format'] . "</td>";
                        echo "<td>$" . $row['deckCost'] . "</td>";
                        echo "<td>" . $row['cardsInDeck'] . "</td>";
                        echo "<td><a href='viewUserDecks.php?DeleteDeck=".$row['DeckName']."' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";				
				mysqli_free_result($result);
			} else {
				echo "No decks found";
			}
        } else{
			// URL doesn't contain valid id parameter. Redirect to error page
            echo "<h2>User not found</h2>";
        }
    }     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>					                 					
	<p><a href="addDeck" class = "btn btn-primary">Add Deck</a>
    <a href="index.php" class="btn btn-primary">Back</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>