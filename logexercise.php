<?php
    require_once('authorizeaccess.php');
    require_once('pagetitles.php');
    $pageTitle = EL_LOG_EXERCISE_PAGE;
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
                <h1>Exercise Logger - Log New Exercise!</h1>
                <hr>
                <?php
                    require_once("navmenu.php");
                    
                    //If the log exercise button was clicked
                    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']) 
                            && isset($_POST['log_exercise_submission'])) {
                        
                        //Getting form fields and user_id    
                        $user_id = $_SESSION['user_id'];   
                        $type = isset($_POST['type']) ? $_POST['type'] : '';
                        $date = isset($_POST['date']) ? $_POST['date'] : '';
                        $time = isset($_POST['time']) ? $_POST['time'] : '';
                        $heartRate = isset($_POST['heartRate']) ? $_POST['heartRate'] : '';
                        
                        //checking that none of the fields are empty
                        if (!empty($type) && !empty($date) && !empty($time)
                                && !empty($heartRate)) {
                                
                            require_once('dbconnection.php');
                            require_once('queryutils.php');
                            require_once('caloriesburned.php');
                            
                            //Sanitizing inputs
                            $type = filter_var($type, FILTER_SANITIZE_STRING);
                            $date = filter_var($date, FILTER_SANITIZE_STRING);
                            $time = filter_var($time, FILTER_SANITIZE_NUMBER_INT);
                            $heartRate = filter_var($heartRate, FILTER_SANITIZE_NUMBER_INT);
                            
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                    or trigger_error('Error connecting to MYSQL server for'
                                    . DB_NAME, E_USER_ERROR);
                            
                            //Query to get birthdate, gender and weight
                            $query = "SELECT birthdate, weight, gender FROM exerciseUser "
                                    . "WHERE login_id = (SELECT Id FROM "
                                    . "userLogins WHERE Id = $user_id)";
                            
                            $results = mysqli_query($dbc, $query)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                            
                            $row = mysqli_fetch_array($results);
                            
                            $age = $row['birthdate'];
                            $weight = $row['weight'];
                            $gender = $row['gender'];
                            
                            //Calling function to calculate age in years
                            $age = calculateAgeInYears($age, $date);
                            
                            //Calling function to calculate calories burned
                            $calories = calculateCaloriesBurned($gender, $heartRate, $weight, $age, $time);
                            
                            //Checking if a number was returned
                            if (is_int($calories)) {
                            
                                //Query to insert data into exerciseLog table      
                                $queryTwo = "INSERT INTO exerciseLog (user_id, date, "
                                        . "exerciseType, timeInMinutes, heartRate, "
                                        . "calories) VALUES ((SELECT Id FROM "
                                        . "exerciseUser WHERE login_id = (SELECT "
                                        . "Id FROM userLogins WHERE Id = $user_id))"
                                        . ", ?, ?, ?, ?, ?)";
                                    
                                $resultsTwo = parameterizedQuery($dbc, $queryTwo
                                        , 'ssiii', $date, $type, $time, $heartRate, $calories)
                                        or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                ?>
                                <hr>
                                <h3 class="text-info">
                                    The Following Exercise Log Was Added To Your Exercise Logs:</h3><br> 
                                
                                <div class="row">
                                    <div class="col col-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Type</th>
                                                    <td><?= $type ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Date</th>
                                                    <td><?= $date ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Time (in minutes)</th>
                                                    <td><?= $time ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Average Heart Rate (BPM)</th>
                                                    <td><?= $heartRate ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Calories Burned</th>
                                                    <td><?= $calories ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <br>
                                <a class="btn btn-primary" 
                                        href="<?=$_SERVER['PHP_SELF']?>">Add Another Log</a>
                
                <?php                            
                                            
                            } else {    //Calories didnt calculate correctly
                                
                                echo "<h4><p class='text-danger'>Something "
                                        . "went wrong with calculating the "
                                        . "calories burned. Try again.</p></h4>";
                            }
                        } else {    //Some field wasnt entered in correctly
                        
                            echo "<h4><p class='text-danger'>You must fill in "
                                    . "ALL fields correctly to submit a new log.</p></h4>";
                        }
                        
                    } else {    //Output form
                ?>
                        <hr>
                        <h5>Log a New Exercise</h5>
                        <br>
                        <form class="needs-validation" novalidate method="POST"
                                action="<?= $_SERVER['PHP_SELF'] ?>">
                            <div class="form-group row">
                                <label for="type"
                                        class="col-sm-3 col-form-label-lg">Type:</label>
                                <div class="col-sm-4">
                                    <select class="custom-select" id="type"
                                            name="type" required>
                                        <option value="" disabled selected>
                                        Choose an Exercise Type</option>
                                        <option value="Running">Running</option>
                                        <option value="Walking">Walking</option>
                                        <option value="Swimming">Swimming</option>
                                        <option value="Weightlifting">Weightlifting</option>
                                        <option value="Yoga">Yoga</option>
                                        <option value="Sport">Sport</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please provide a vaild Type.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="date"
                                        class="col-sm-3 col-form-label-lg">Date:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control"
                                            id="date" name="date"
                                            placeholder="MM/DD/YYYY" 
                                            required maxlength="10">
                                    <div class="invalid-feedback">
                                        Please provide a vaild date.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="time"
                                        class="col-sm-3 col-form-label-lg">Time (in minutes):</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" 
                                            id="time" name="time" 
                                            placeholder="Enter a time in minutes" required>
                                    <div class="invalid-feedback">
                                        Please provide a vaild time.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="heartRate"
                                        class="col-sm-3 col-form-label-lg">Average Heart Rate (BPM):</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control"
                                            id="heartRate" name="heartRate"
                                            placeholder="Enter an average heart rate" required>
                                    <div class="invalid-feedback">
                                        Please provide a vaild heart rate.
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-secondary" type="submit"
                                    name="log_exercise_submission">Log Exercise</button>
                        </form>
                <?php
                    }
                ?>      
            </div>
        </div>
        <script>
                /* JavaScript for disabiling form submission if there 
                are invalid fields*/
                    (function() {
                        'use strict';
                        window.addEventListener('load', function() {
                        /*Fetch all the form fields we want to apply custom 
                        bootstrap validation styles to*/
                            var forms = document.getElementsByClassName('needs-validation');
                            //Loop over them and prevent submission
                            var validation = Array.prototype.filter.call(forms,
                                    function(form) {
                                form.addEventListener('submit', function(event) {
                                    if (form.checkValidity() === false) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                    form.classList.add('was-validated');
                                }, false);
                            });
                        }, false);
                    })();
        </script>
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
