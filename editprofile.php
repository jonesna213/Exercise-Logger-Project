<?php
    require_once('authorizeaccess.php');
    require_once('pagetitles.php');
    $pageTitle = EL_EDIT_PROFILE_PAGE;
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
                    require_once("navmenu.php");
                    require_once('dbconnection.php');
                    require_once('queryutils.php');
                    
                    $user_id = $_SESSION['user_id'];
                             
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or trigger_error('Error connecting to MYSQL server for'
                                   . DB_NAME, E_USER_ERROR);
                    
                    //Query to get user info into a table
                    $query = "SELECT * FROM exerciseUser WHERE login_id = "
                            . "(SELECT Id FROM userLogins WHERE Id = $user_id)";
                            
                    $results = mysqli_query($dbc, $query)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                    
                    $row = mysqli_fetch_array($results);
                    
                    $firstName = $row['firstName'];
                    $lastName = $row['lastName'];
                    $gender = $row['gender'];
                    $birthdate = $row['birthdate'];
                    $weight = $row['weight'];
                    
                    //If the save profile button was clicked
                    if (isset($_POST['edit_profile_submission'])) {
                        
                        $user_id = $_SESSION['user_id'];
                        $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
                        $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
                        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                        $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
                        $weight = isset($_POST['weight']) ? $_POST['weight'] : '';
                        
                        if (!empty(!empty($firstName) && !empty($lastName)
                                && !empty($gender) && !empty($birthdate)
                                && !empty($weight))) {
                        
                            require_once('dbconnection.php');
                            require_once('queryutils.php');
                            
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                    or trigger_error('Error connecting to MYSQL server for'
                                    . DB_NAME, E_USER_ERROR);
                                    
                            //Sanitizing strings
                            $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
                            $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
                            $gender = filter_var($gender, FILTER_SANITIZE_STRING);
                            $birthdate = filter_var($birthdate, FILTER_SANITIZE_STRING);
                            $weight = filter_var($weight, FILTER_SANITIZE_NUMBER_INT);
                            
                            //Query to edit profile data
                            $query = "UPDATE exerciseUser "
                                    . "SET firstName = ?, lastName = ?, gender "
                                    . "= ?, birthdate = ?, weight = ? WHERE "
                                    . "login_id = (SELECT Id FROM userLogins "
                                    . "WHERE Id = $user_id)";
                            
                            $results = parameterizedQuery($dbc, $query, 'ssssi'
                                    , $firstName, $lastName, $gender
                                    , $birthdate, $weight)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                            
                            echo "<h4><p class='text-success'>Your profile was updated.</p></h4>";
                        }
                            
                        
                    }
                ?>
                <hr>
                <form class="needs-validation" novalidate method="POST"
                        action="<?= $_SERVER['PHP_SELF'] ?>">
                    <h5>Personal Information</h5><br>
                    <div class="form-group row">
                        <label for="firstName"
                                class="col-sm-2 col-form-label-lg">First Name:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                                    id="firstName" name="firstName" 
                                    placeholder="Enter a first name" 
                                    value = "<?= $firstName?>" required>
                            <div class="invalid-feedback">
                                Please provide a vaild first name.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lastName"
                                class="col-sm-2 col-form-label-lg">Last Name:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                                    id="lastName" name="lastName" 
                                    placeholder="Enter a last name" required
                                    value="<?= $lastName?>">
                            <div class="invalid-feedback">
                                Please provide a vaild last name.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gender"
                                class="col-sm-2 col-form-label-lg">Gender:
                        </label>
                        <div class="col-sm-4">
                            <select class="custom-select" id="gender"
                                    name="gender" 
                                    value="<?= $gender?>"required>
                                <option value="" disabled selected>Gender
                                </option>
                                <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Non Binary <?= $gender == 'Non Binary' ? 'selected' : '' ?>">Non Binary</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a vaild Gender.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="birthdate"
                                class="col-sm-2 col-form-label-lg">Birthdate:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control"
                                    id="birthdate" name="birthdate"
                                    placeholder="MM/DD/YYYY" 
                                    required maxlength="10"
                                    value="<?= $birthdate?>">
                            <div class="invalid-feedback">
                                Please provide a vaild birthdate.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="weight"
                                class="col-sm-2 col-form-label-lg">Weight (in pounds)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control"
                                    id="weight" name="weight"
                                    placeholder="Enter a weight in pounds" required
                                    value="<?= $weight?>">
                            <div class="invalid-feedback">
                                Please provide a vaild weight.
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-secondary" type="submit"
                            name="edit_profile_submission">Save Profile</button>
                </form>
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
