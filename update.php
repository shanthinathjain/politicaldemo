<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$party = $president = $symbol = "";
$party_err = $president_err = $symbol_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate party
  $input_party = trim($_POST["party"]);
    if(empty($input_party)){
        $party_err = "Please enter a Party name.";
    }else{
        $party = $input_party;
    }
    
    
    // Validate address president
     $input_president = trim($_POST["president"]);
    if(empty($input_president)){
        $president_err = "Please enter an president Name.";     
    } else{
        $president = $input_president;
    }
    
    // Validate symbol
      $input_symbol = trim($_POST["symbol"]);
    if(empty($input_symbol)){
        $symbol_err = "Please enter the symbol.";     
    }  else{
        $symbol = $input_symbol;
    }
    
    // Check input errors before inserting in database
    if(empty($party_err) && empty($president_err) && empty($symbol_err)){
        // Prepare an update statement
        $sql = "UPDATE political SET president=?, symbol=? WHERE party=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_president, $param_symbol,$param_party);
            
            // Set parameters
            $param_party = $party;
            $param_president = $president;
            $param_symbol = $symbol;
            //$param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT party, president, symbol FROM political WHERE party = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $party = $row["party"];
                    $president = $row["president"];
                    $symbol = $row["symbol"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-flui/d">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the political party record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Party Name </label>
                            <input type="text" name="party" class="form-control <?php echo (!empty($party_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $party; ?>">
                            <span class="invalid-feedback"><?php echo $party_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>president</label>
                            <textarea name="president" class="form-control <?php echo (!empty($president_err)) ? 'is-invalid' : ''; ?>"><?php echo $president; ?></textarea>
                            <span class="invalid-feedback"><?php echo $president_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>symbol</label>
                            <input type="text" name="symbol" class="form-control <?php echo (!empty($symbol_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $symbol; ?>">
                            <span class="invalid-feedback"><?php echo $symbol_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>