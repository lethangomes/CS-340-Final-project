<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Card</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>
<body>
    <?php
        // Include config file
        require_once "config.php";
//		include "header.php";
	?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
		       <h2 class="pull-left">Cards</h2>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    function get_Types($table, $link, $cardName){
                        $ret_types = "";
                        $sql_get_types = "  SELECT * 
                                                FROM ".$table." AS T
                                                WHERE T.Card_NAME = ?";
                        if($stmt = mysqli_prepare($link, $sql_get_types)){
                            mysqli_stmt_bind_param($stmt, "s", $cardName);
                            
                            if(mysqli_stmt_execute($stmt)){
                                $types = mysqli_stmt_get_result($stmt);
                                if(mysqli_num_rows($types) > 0){
                                    while($type = mysqli_fetch_array($types)){
                                        $ret_types = $ret_types . $type['Typename'] . " ";
                                    }
                                }
                            } 
                        } 

                        return $ret_types;
                    }
                    
                    // show all cards that can be added to a deck
                    if(isset($_SESSION['Username']) && isset($_SESSION['DeckName'])){
                        $sql = "SELECT *
                                FROM CARD
                                WHERE CARD.Name NOT IN (SELECT CardName FROM INCLUDES WHERE Username='".$_SESSION['Username']."' AND DeckName= '".$_SESSION['DeckName']."') ";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo "<table class='table table-bordered table-striped'>";
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th >Name</th>";
                                            echo "<th >Type</th>";
                                            echo "<th >Abilities</th>";
                                            echo "<th >Flavor</th>";
                                            echo "<th >Power</th>";
                                            echo "<th >Toughness</th>";
                                            echo "<th >Artist</th>";
                                            echo "<th >Price</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                            echo "<td>" . $row['Name'] . "</td>";
                                            echo "<td>";
                                        
                                            //get types
                                            echo get_Types("SUPERTYPES", $link, $row["Name"]);
                                            echo get_Types("TYPES", $link, $row["Name"]);
                                            echo get_Types("SUB_TYPES", $link, $row["Name"]);

                                            echo "</td>";

                                            echo "<td><ul>";
                                            //get abilities
                                            $sql_get_abilities = "  SELECT * 
                                                                FROM ABILITY AS A
                                                                WHERE A.id in (
                                                                    SELECT id
                                                                    FROM HASABILITY
                                                                    WHERE Name = ?)";
                                            if($stmt = mysqli_prepare($link, $sql_get_abilities)){
                                                mysqli_stmt_bind_param($stmt, "s", $row['Name']);
                                                
                                                if(mysqli_stmt_execute($stmt)){
                                                    $abilities = mysqli_stmt_get_result($stmt);
                                                    if(mysqli_num_rows($result) > 0){
                                                        while($ability = mysqli_fetch_array($abilities)){
                                                            echo "<li><b>" . $ability['Name'] . "</b> - " . $ability['Description'] . "</li>";
                                                        }
                                                    }
                                                }
                                            }
                                            echo "</ul></td>";
                                            echo "<td>" . $row['Flavor'] . "</td>";
                                            echo "<td>" . $row['Power'] . "</td>";
                                            echo "<td>" . $row['Toughness'] . "</td>";									
                                            echo "<td>" . $row['Artist'] . "</td>";
                                            echo "<td>$" . $row['Price'] . "</td>";	
                                            echo "<td><a href='viewDeck.php?CardNameAdd=".$row['Name']."' class='btn btn-primary'>Add</a></td>";									
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                mysqli_free_result($result);
                            } else{
                                echo "<p class='lead'><em>No records were found.</em></p>";
                            }
                        } else{
                            echo "ERROR: Could not able to execute $sql. <br>" . mysqli_error($link);
                        }
                    }
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                <a href="viewDeck.php" class="btn btn-primary">Back</a>
                </div>

</body>
</html>
