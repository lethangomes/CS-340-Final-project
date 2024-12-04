<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company DB</title>
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
		     <h2> Magic the Gathering Card Database</h2> 
                       <p> Project should include CRUD operations. In this website you can:
				<ol> 	<li> CREATE new employess and  dependents </li>
					<li> RETRIEVE all dependents and prjects for an employee</li>
                                        <li> UPDATE employeee and dependent records</li>
					<li> DELETE employee and dependent records </li>
				</ol>
		       <h2 class="pull-left">Cards</h2>
                        <a href="createEmployee.php" class="btn btn-success pull-right">Add New Employee</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select all employee query execution
					// *****
					// Insert your function for Salary Level
					/*
						$sql = "SELECT Ssn,Fname,Lname,Salary, Address, Bdate, PayLevel(Ssn) as Level, Super_ssn, Dno
							FROM EMPLOYEE";
					*/
                    $sql = "SELECT *
							FROM CARD";
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
                                    $sql_get_types = "  SELECT * 
                                            FROM SUPERTYPES AS T
                                            WHERE T.Card_NAME = ?";
                                    if($stmt = mysqli_prepare($link, $sql_get_types)){
                                        mysqli_stmt_bind_param($stmt, "s", $row['Name']);
                                        
                                        if(mysqli_stmt_execute($stmt)){
                                            $types = mysqli_stmt_get_result($stmt);
                                            if(mysqli_num_rows($types) > 0){
                                                while($type = mysqli_fetch_array($types)){
                                                    echo "" . $type['Typename'] . " ";
                                                }
                                            }
                                        }
                                    }

                                    $sql_get_types = "  SELECT * 
                                            FROM TYPES AS T
                                            WHERE T.Card_NAME = ?";
                                    if($stmt = mysqli_prepare($link, $sql_get_types)){
                                        mysqli_stmt_bind_param($stmt, "s", $row['Name']);
                                        
                                        if(mysqli_stmt_execute($stmt)){
                                            $types = mysqli_stmt_get_result($stmt);
                                            if(mysqli_num_rows($types) > 0){
                                                while($type = mysqli_fetch_array($types)){
                                                    echo "" . $type['Typename'] . " ";
                                                }
                                            }
                                        }
                                    }
                                    
                                    //get subtypes
                                    $sql_get_types = "  SELECT * 
                                            FROM SUB_TYPES AS T
                                            WHERE T.Card_NAME = ?";
                                    if($stmt = mysqli_prepare($link, $sql_get_types)){
                                        mysqli_stmt_bind_param($stmt, "s", $row['Name']);
                                        
                                        if(mysqli_stmt_execute($stmt)){
                                            $types = mysqli_stmt_get_result($stmt);
                                            if(mysqli_num_rows($types) > 0){
                                                while($type = mysqli_fetch_array($types)){
                                                    if($type['Typename'] != "Instant" && $type['Typename'] != "Artifact")
                                                    echo "" . $type['Typename'] . " ";
                                                }
                                            }
                                        }
                                    }
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
					
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>

</body>
</html>
