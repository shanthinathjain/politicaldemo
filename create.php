<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$party = $president = $symbol = "";
$party_err = $president_err = $symbol_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
    // Validate party
    $input_party = trim($_POST["party"]);
    if(empty($input_party)){
        $party_err = "Please enter a party name.";
    }else{
        $party = $input_party;
    }
    
    // Validate president
    $input_president = trim($_POST["president"]);
    if(empty($input_president)){
        $president_err = "Please enter an Name of president.";     
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

        // Prepare an insert statement    
    $sql = "INSERT into political ( party, president, symbol) VALUES (?, ?, ?)";
         if($stmt = mysqli_prepare($link, $sql)){
         
		 // Bind variables to the prepared statement as parameters
		  
            mysqli_stmt_bind_param($stmt, "sss", $param_party, $param_president, $param_symbol);
            
            // Set parameters
            $param_party = $party;
            $param_president = $president;
            $param_symbol = $symbol;
		 }
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                 // Records created successfully. Redirect to landing page
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

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add political parties record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Party Name</label>
                            <input type="text" name="party" class="form-control <?php echo (!empty($party_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $party; ?>">
							
                            <span class="invalid-feedback"><?php echo $party_err;?></span>
                        </div>
						
                        <div class="form-group">
                            <label>President</label>
                            <textarea name="president" class="form-control <?php echo (!empty($president_err)) ? 'is-invalid' : ''; ?>"><?php echo $president; ?></textarea>
                            <span class="invalid-feedback"><?php echo $president_err;?></span>
                        </div>
						
                        <div class="form-group">
                            <label>symbol</label>
                            <input type="text" name="symbol" class="form-control <?php echo (!empty($symbol_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $symbol; ?>">
                            <span class="invalid-feedback"><?php echo $symbol_err;?></span>
                        </div>
						
                        <input type="submit" class="btn btn-primary" value="Submit">
                        
						<a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>