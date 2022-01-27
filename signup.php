<?php
    require_once('pagetitles.php');
    $pageTitle = EL_SIGN_UP_PAGE;
    
?>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
            integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
            crossorigin="anonymous">
        <title>Exercise-Logger-SignUp</title>
    </head>
    <body>
        <div class="card">
            <div class="card-body">
                <h1>Sign up for a Exercise Logger Account</h1>
                <hr>
                <?php
                    require_once("navmenu.php");
                    
                    $show_sign_up_form = true;
                    
                    //If the signup button was clicked
                    if (isset($_POST['signup_submission'])) {
                        //Get user info
                        $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
                        $password = isset($_POST['password']) ? $_POST['password'] : '';
                        $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
                        $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
                        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                        $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
                        $weight = isset($_POST['weight']) ? $_POST['weight'] : '';
                        
                        //If none of the fields are empty
                        if (!empty($user_name) && !empty($password)
                                && !empty($firstName) && !empty($lastName)
                                && !empty($gender) && !empty($birthdate)
                                && !empty($weight)) {
                        
                            require_once('dbconnection.php');
                            require_once('queryutils.php');
                            
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                                    or trigger_error('Error connecting to MYSQL server for'
                                    . DB_NAME, E_USER_ERROR);
                                    
                            //Sanitizing strings
                            $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
                            $password = filter_var($password, FILTER_SANITIZE_STRING);
                            $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
                            $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
                            $gender = filter_var($gender, FILTER_SANITIZE_STRING);
                            $birthdate = filter_var($birthdate, FILTER_SANITIZE_STRING);
                            $weight = filter_var($weight, FILTER_SANITIZE_NUMBER_INT);

                            //Check if user already exists
                            $query = "SELECT * FROM userLogins WHERE userName = ?";
                            
                            $results = parameterizedQuery($dbc, $query, 's', $user_name)
                                    or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                            
                            if (mysqli_num_rows($results) == 0) {
                                
                                $salted_hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                
                                //Query for userLogins table
                                $query = "INSERT INTO userLogins (userName, "
                                        . "passwordHash) VALUES (?, '$salted_hashed_password')";
                                
                                $results = parameterizedQuery($dbc, $query, 's', $user_name)
                                        or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                                
                                //Query for exerciseUser table
                                $queryTwo = "INSERT INTO exerciseUser "
                                        . "(firstName, lastName, gender, "
                                        . "birthdate, weight, login_id) "
                                        . "VALUES (?, ?, ?, ?, ?, (SELECT Id FROM userLogins WHERE userName = ?))";
                                
                                $resultsTwo = parameterizedQuery($dbc, 
                                        $queryTwo, 'ssssis', $firstName, 
                                        $lastName, $gender, $birthdate, 
                                        $weight, $user_name)
                                        or trigger_error(mysqli_error($dbc), E_USER_ERROR);
                                        
                                //Direct the user to the login page
                                echo "<h4><p class='text-success'>Thank you "
                                        . "for signing up <strong>$firstName "
                                        . "$lastName</strong>! Your new account"
                                        . " has been successfully created.<br>"
                                        . " You're now ready to <a href='login.php'>"
                                        . "log in</a>.</p></h4>";
                                
                                $show_sign_up_form = false;
                                
                            } else { 
                                //An account already exists for this user
                                echo "<h4><p class='text-danger'>An account "
                                        . "already exists for this username:"
                                        . "<span class='font-weight-bold'> "
                                        . "($user_name)</span>. Please use "
                                        . "a different user name.</p></h4><hr>";
                            }
                        } else {
                            //Output error message
                            echo "<h4><p class='text-danger'>You must enter "
                                    . "both a user name and password.</p></h4><hr>";
                        }
                    }
                    if ($show_sign_up_form) {
                ?>
                <form class="needs-validation" novalidate method="POST"
                        action="<?= $_SERVER['PHP_SELF'] ?>">
                    <h4>Personal Info -</h4><br>
                    <div class="form-group row">
                        <label for="firstName"
                                class="col-sm-2 col-form-label-lg">First Name:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                                    id="firstName" name="firstName" 
                                    placeholder="Enter a first name" required>
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
                                    placeholder="Enter a last name" required>
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
                                    name="gender" required>
                                <option value="" disabled selected>Gender
                                </option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Non Binary">Non Binary</option>
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
                                    required maxlength="10">
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
                                    placeholder="Enter a weight in pounds" required>
                            <div class="invalid-feedback">
                                Please provide a vaild weight.
                            </div>
                        </div>
                    </div>
                    <hr><h4>Login Info -</h4><br>
                    <div class="form-group row">
                        <label for="user_name"
                                class="col-sm-2 col-form-label-lg">User Name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" 
                                    id="user_name" name="user_name" 
                                    placeholder="Enter a user name" required>
                            <div class="invalid-feedback">
                                Please provide a vaild user name.
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password"
                                class="col-sm-2 col-form-label-lg">Password</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" 
                                    id="password" name="password" 
                                    placeholder="Enter a password" required>
                            <div class="form-group form-check">
                                <input type="checkbox"
                                        class="form-check-input"
                                        id="show_password_check"
                                        onclick="togglePassword()">
                                <label class="form-check-label"
                                        for="show_password_check">Show Password</label>
                            </div>
                            <div class="invalid-feedback">
                                Please provide a vaild password.
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit"
                            name="signup_submission">Sign Up</button>
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
                    // Toggles between showing and hiding the entered password
                    function togglePassword() {
                        var password_entry = document.getElementById("password");
                        if (password_entry.type === "password") {
                            password_entry.type = "text";
                        } else {
                            password_entry.type = "password";
                        }
                    }
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
