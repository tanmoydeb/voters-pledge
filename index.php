<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>


<?php
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $emailError = null;
        $mobileError = null;
		$epicError = null;
		$msg = null;
        $result = null; 
		$totalcitizen = null;
		
        // keep track post values
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
		$epic = $_POST['epic'];
         
        // validate input
        $valid = true;
        if (empty($name)) {
            $nameError = 'Please enter Name';
            $valid = false;
        }
         
        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         
        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }
		
		if(empty($epic)){
			$epicError = 'Please enter EPIC Number';
			$valid = false;
		}
         
        // insert data
        if ($valid) {
			$db = pg_connect("host=localhost port=5452 dbname=pledge_db user=postgres password=sgl");
			$query = "SELECT * FROM tbl_citizen WHERE epic="."'".$epic."'";
			$result = pg_query($query); 
			$count = pg_num_rows($result);
			
			if($count!=0){
				$msg = "<H4>You have already taken the pledge. Thank You</H4>";
			}
            else{
				$query = "INSERT INTO tbl_citizen VALUES ('$epic','$name','$mobile','$email')";
				$result = pg_query($query); 
				$sql = "SELECT * FROM tbl_citizen";
				$res = pg_query($sql); 
				$count = pg_num_rows($res);
				$msg = "<H4>Thank You. So far ".$count." citizens have taken this pledge<H4>";
			}
			
        }
    }
?>


<body>
    <div class="container">
     
                <div class="span10 offset1">
					<div class="row" align="justify">
						<?php
							$db = pg_connect("host=localhost port=5452 dbname=pledge_db user=postgres password=sgl");
							$sql = "SELECT * FROM tbl_citizen";
							$res = pg_query($sql); 
							$totalcitizen = pg_num_rows($res);
						?>
                        <h3><?php echo $totalcitizen;?> Citizens have taken the Pledge. Be the next one.</h3>
                    </div>
                    <div class="row" align="justify">
                        <h3>I, the citizen of India, having abiding faith in democracy, hereby pledge to uphold the democratic traditions of our country and the dignity of free, fair and peaceful elections, and to vote in every election fearlessly and without being influenced by consideration of religion, race, caste, community, language or any inducement.</h3>
                    </div>
					
                    <form class="form-horizontal" action="create.php" method="post">
                      <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
                        <label class="control-label">Name</label>
                        <div class="controls">
                            <input name="name" type="text"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
                            <?php if (!empty($nameError)): ?>
                                <span class="help-inline"><?php echo $nameError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($epicError)?'error':'';?>">
                        <label class="control-label">EPIC</label>
                        <div class="controls">
                            <input name="epic" type="text"  placeholder="EPIC" value="<?php echo !empty($epic)?$epic:'';?>">
                            <?php if (!empty($epicError)): ?>
                                <span class="help-inline"><?php echo $epicError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
                        <label class="control-label">Email Address</label>
                        <div class="controls">
                            <input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
                            <?php if (!empty($emailError)): ?>
                                <span class="help-inline"><?php echo $emailError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
                        <label class="control-label">Mobile Number</label>
                        <div class="controls">
                            <input name="mobile" type="text"  placeholder="Mobile Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
                            <?php if (!empty($mobileError)): ?>
                                <span class="help-inline"><?php echo $mobileError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Submit</button>
                          <a class="btn" href="index.php">Back</a><BR><?php echo !empty($msg)?$msg:'' ?>
						</div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>
<?php
?>