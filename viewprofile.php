<?php
    require_once('authorizeaccess.php');
    require_once('pagetitles.php');
    $pageTitle = EL_VIEW_PROFILE_PAGE;
?>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
            integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
            crossorigin="anonymous">
        <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
          crossorigin="anonymous">
        <title><?= $pageTitle ?></title>
    </head>
    <body>
        <div class="card">
            <div class="card-body">
                <h1>Exercise Logger - View Profile</h1>
                <hr>
                <?php
                    require_once("navmenu.php");
                    require_once('dbconnection.php');
                    require_once('queryutils.php');
                    
                    //Getting session variables into local variables
                    $user_id = $_SESSION['user_id'];
                    $user_name = $_SESSION['user_name'];
                    
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error('Error connecting to MYSQL server for'
                                . DB_NAME, E_USER_ERROR);
                    
                    //Query for Personal information
                    $query = "SELECT * FROM exerciseUser WHERE login_id = "
                            . "(SELECT Id FROM userLogins WHERE Id = $user_id)";
                            
                    $results = mysqli_query($dbc, $query)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                    
                    $row = mysqli_fetch_array($results);
                    
                ?><!-- Personal Information Table -->
                    <hr>  
                    <br>        
                    <div class="row">
                        <div class="col-2">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">User Name:</th>
                                        <td><?= $user_name ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">First Name:</th>
                                        <td><?= $row['firstName'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Last Name:</th>
                                        <td><?= $row['lastName'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Gender:</th>
                                        <td><?= $row['gender'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Birthdate:</th>
                                        <td><?= $row['birthdate'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Weight:</th>
                                        <td><?= $row['weight'] ?> lbs</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-3">
                        </div>
                        <div class="col-5">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="row">Date</th>
                                        <th scope="row">Type</th>
                                        <th scope="row">Time in Minutes</th>
                                        <th scope="row">Heart Rate</th>
                                        <th scope="row">Calories Burned</th>
                                        <th scope="row"></th>
                                    </tr>
                                </thead>
                                <tbody>
                <?php
                    //Query for exercise logs
                    $queryTwo = "SELECT * FROM exerciseLog WHERE user_id = "
                            . "(SELECT Id FROM exerciseUser WHERE login_id = "
                            . "$user_id) ORDER BY Id DESC LIMIT 15";
                            
                    $resultsTwo = mysqli_query($dbc, $queryTwo)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);

                    while ($rowTwo = mysqli_fetch_assoc($resultsTwo)) {  
                        
                        $Id = $rowTwo['Id'];
                        $date = $rowTwo['date'];
                        $type = $rowTwo['exerciseType'];
                        $time = $rowTwo['timeInMinutes'];
                        $heartRate = $rowTwo['heartRate'];
                        $calories = $rowTwo['calories'];
                        
                        //Table for Exercise Logs
                        echo "<tr><td>$date</td><td>$type</td><td>$time</td>"
                                . "<td>$heartRate</td><td>$calories</td><td>"
                                . "<a class='nav-link' href='removelog.php?"
                                . "id_to_remove=$Id'><i class='fas "
                                . "fa-trash-alt'></i></a></td></tr>";
                    }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <p>Would you like to <a href="editprofile.php">edit your profile</a>?</p>
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
