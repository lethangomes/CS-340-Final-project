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

<?php
        //attempt to add deck
        $msg = "";
        if(isset($_POST['deckName']) && isset($_POST['format'])){
            if($_POST['deckName'] != "" && $_POST['format'] != ""){
                $sql = "INSERT INTO DECK (DeckName, Format, Username)
                        VALUES (?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "sss", $_POST['deckName'], $_POST['format'], $_SESSION['Username']); 
                    if(mysqli_stmt_execute($stmt)){
                        //redirect to user decks
                        header("location: viewUserDecks.php");
                    } else{
                        $msg = "Deck name already in use for this user";
                    }
                }
            }
            else{
                //print error message if fields empty.
                if($_POST['deckName'] == ""){
                    $msg = "Please insert deck name<br>";
                }
                if($_POST['format'] == ""){
                    $msg = $msg."\nPlease insert format";
                }
            }
        }
        mysqli_close($link);
?>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Add Deck</h2>
                    </div>
                </div>
            </div>
            <form method = "post">
                <div class="form-group">
                    <label for = "deckName">Name of deck</label> <br>
                    <input type="text" class="form-control" name="deckName"/> <br>
                </div>
                <div class="form-group">
                    <label for = "format">Deck Format</label> <br>
                    <input type="text" class="form-control" name="format"/>
                </div>
                <input type="submit" class="btn btn-primary" value="Add Deck"/>
                <small class = "help-block" style = "color:red"><?php echo $msg;?></small>
            </form>
            <a class="btn btn-primary" href = "viewUserDecks.php"> Back</a>
        </div>
    </div>
    

</body>
