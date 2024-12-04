<?php
	session_start();
    // Include config file
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Deck</title>
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
                        <h2 class="pull-left">View Deck</h2>
                    </div>
<?php

// Check existence of id parameter before processing further
if(isset($_GET["Username"]) && !empty(trim($_GET["Username"]))){
	$_SESSION["Username"] = $_GET["Username"];
}

if(isset($_GET["DeckName"]) && !empty(trim($_GET["DeckName"]))){
	$_SESSION["DeckName"] = $_GET["DeckName"];
}

if(isset($_GET["CardName"]) && isset($_GET["NumCopies"])) {

    // Prepare a select statement
    $sql = "SELECT CardName, NumCopies FROM INCLUDES WHERE Username= ? AND DeckName= ?" ;

    if($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_CardName, $param_NumCopies);      
        // Set parameters
        $param_CardName = $_SESSION["CardName"];
        $param_NumCopies = $_SESSION["NumCopies"];

        // execute update
        mysqli_stmt_execute($stmt);
    }
}

if(isset($_SESSION["Username"]) && isset($_SESSION["DeckName"])){
    // Prepare a select statement
    $sql = "SELECT CardName, NumCopies FROM INCLUDES WHERE Username= ? AND DeckName= ?" ;

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_Username, $param_DeckName);      
        // Set parameters
       $param_Username = $_SESSION["Username"];
       $param_DeckName = $_SESSION["DeckName"];
    
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
			echo"<h4>".$param_DeckName."</h4><p>";
			if(mysqli_num_rows($result) > 0){
				echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>Card</th>";
                            echo "<th>Copies</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";							
				// output data of each row
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . $row['CardName'] . "</td>";
                        echo "<td>" . $row['NumCopies'] . "</td>";
                        echo "<td>";
                            echo "<a href='viewDeck.php?CardName=". $row['CardName']."&NumCopies=". ($row['NumCopies'] + 1) ."' title='Increase Copies' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";

                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";				
				mysqli_free_result($result);
			} else {
				echo "No cards. ";
			}
//				mysqli_free_result($result);
        } else{
			// URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
            exit();
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
	<p><a href="viewUserDecks.php" class="btn btn-primary">Back</a></p>
    </div>
   </div>        
  </div>
</div>
</body>
</html>