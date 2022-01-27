<?php
    require_once('authorizeaccess.php');
    require_once('pagetitles.php');
    $pageTitle = EL_REMOVE_LOG_PAGE;
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
                    //getting required files
                    require_once("navmenu.php");
                    require_once('dbconnection.php');
                    require_once('queryutils.php');
                    
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                or trigger_error('Error connecting to MYSQL server for'
                                . DB_NAME, E_USER_ERROR);
                    //If delete log button was clicked
                    if (isset($_POST['delete_log_submission'], $_POST['Id'])) {
                        
                        //getting needed id into local variable
                        $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
                        
                        //query for deleting exercise log
                        $query = "DELETE FROM exerciseLog WHERE Id = ?";
                                        
                        $results = parameterizedQuery($dbc, $query, 'i', $id)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                        
                        //Redirect to the view profile page
                        header('Location: viewprofile.php');
                        exit;
                    //If do not delete log button was clicked
                    } elseif (isset($_POST['do_not_delete_log_submission'])) {
                    
                        //Redirect to the view profile page
                        header('Location: viewprofile.php');
                        exit;
                    //If trash can icon was clicked
                    } elseif (isset($_GET['id_to_remove'])) {
                        
                        //getting needed id into local variable
                        $id = filter_var($_GET['id_to_remove'], FILTER_SANITIZE_NUMBER_INT);
                        
                        //query for showing exercise log
                        $query = "SELECT * FROM exerciseLog WHERE Id = ?";
                                        
                        $results = parameterizedQuery($dbc, $query, 'i', $id)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                        
                        //If only 1 row is returned show table containing the row            
                        if (mysqli_num_rows($results) == 1) {
                            
                            $row = mysqli_fetch_array($results);
                            
                            $date = $row['date'];
                            $type = $row['exerciseType'];
                            $time = $row['timeInMinutes'];
                            $heartRate = $row['heartRate'];
                            $calories = $row['calories'];
                            
                    ?>
                            <div class="col-5">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="row">Date</th>
                                            <th scope="row">Type</th>
                                            <th scope="row">Time in Minutes</th>
                                            <th scope="row">Heart Rate</th>
                                            <th scope="row">Calories Burned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $date ?></td>
                                            <td><?= $type ?></td>
                                            <td><?= $time ?></td>
                                            <td><?= $heartRate ?></td>
                                            <td><?= $calories ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Form with just the delete and dont delete buttons,
                                    Buttons are styled with bootstrap (red and green)-->
                                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <button class="btn btn-danger" type="submit"
                                                    name="delete_log_submission">Delete Log
                                            </button>
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success" type="submit"
                                                    name="do_not_delete_log_submission">
                                                    Don't Delete Log
                                            </button>
                                        </div>
                                        <input type="hidden" name="Id" value="<?= $id ?>">
                                    </div>
                                </form>
                            </div>
                                    
                <?php   
                        //if an error occured     
                        } else {
                            echo "<h4><p class=text-danger>An error occured please "
                                    . "try again</p></h4>";
                        }
                        
                    
                    //Unintended page link
                    } else {
                    
                        //Redirect to the home page
                        header('Location: index.php');
                        exit;
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
