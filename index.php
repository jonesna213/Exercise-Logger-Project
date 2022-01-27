<?php
    require_once('pagetitles.php');
    $pageTitle = EL_HOME_PAGE;
?>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
            integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
            crossorigin="anonymous">
        <title><?= $pageTitle ?></title>
    </head>
    <body>
        <div class="card">
            <div class="card-body">
                <h1><?= $pageTitle ?></h1>
                <hr>
                <?php
                    require_once('navmenu.php');
                    require_once('dbconnection.php');
                    require_once('queryutils.php');
                    
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error('Error connecting to MYSQL server for'
                                . DB_NAME, E_USER_ERROR);
                    
                    //If a user is logged in        
                    if (isset($_SESSION['user_id'], $_SESSION['user_name'])) {
                    
                        $user_id = $_SESSION['user_id'];
                        
                        //Query to get the total calories burned by the user
                        $query = "SELECT SUM(calories) as totalCalories FROM "
                                . "exerciseLog WHERE user_id = (SELECT Id FROM "
                                . "exerciseUser WHERE login_id = $user_id)";
                            
                        $results = mysqli_query($dbc, $query)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                    
                        $row = mysqli_fetch_array($results);
                        
                        $totalCalories = $row['totalCalories'];
                        
                        if ($totalCalories < 1) {
                            $totalCalories = 0;
                        }
                        
                ?>
                        <hr>
                        <h4>Hello <?= $_SESSION['user_name'] ?> you burned a total of <?= $totalCalories ?> calories.</h4>
                        <h4>Good Job!</h4>
                <?php 
                    
                    //If the user is not logged in       
                    } else {
                        
                        //Query to get the total amount of users
                        $query = "SELECT COUNT(*) as totalUsers FROM exerciseUser";
                            
                        $results = mysqli_query($dbc, $query)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                    
                        $row = mysqli_fetch_array($results);
                ?>
                        <hr>
                        <h4>We currently have <?= $row['totalUsers'] ?> Exercise Logger customers.<h4>
                        <h4>If you would like to be next member click the Sign Up button above.</h4>
                        <h4>Otherwise you can click Sign In to sign in to your account.</h4>
                <?php
                    }
                ?>
                
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
                integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
                integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
                crossorigin="anonymous"></script>
    </body>
</html>
