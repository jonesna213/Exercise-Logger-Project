<?php
    $pageTitle = isset($pageTitle) ? $pageTitle : '';
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
    <nav class="navbar sticky-top navbar-expand-md navbar-light"
            style="background-color: #FFFFFF;">
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                    <a class="nav-item nav-link<?= $pageTitle == EL_HOME_PAGE ? ' active' : '' ?>" 
                            href=<?= dirname($_SERVER['PHP_SELF']) ?>><u>Home</u></a>
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {?>
                    <a class="nav-item nav-link<?= $pageTitle == EL_LOG_EXERCISE_PAGE ? ' active' : '' ?>" 
                            href="logexercise.php"><u>Log Exercise</u></a>
                    <a class="nav-item nav-link<?= $pageTitle == EL_VIEW_PROFILE_PAGE ? ' active' : '' ?>" 
                            href="viewprofile.php"><u>View Profile</u></a>
                    <a class="nav-item nav-link<?= $pageTitle == EL_EDIT_PROFILE_PAGE ? ' active' : '' ?>" 
                            href="editprofile.php"><u>Edit Profile</u></a>
                    <a class="nav-item nav-link" 
                            href="logout.php"><u>Logout (<?=$_SESSION['user_name']?>)</u></a>
                <?php } else {?>
                    <a class="nav-item nav-link<?= $pageTitle == EL_SIGN_UP_PAGE ? ' active' : '' ?>" 
                            href="signup.php"><u>Sign Up</u></a>
                    <a class="nav-item nav-link<?= $pageTitle == EL_LOGIN_PAGE ? ' active' : '' ?>" 
                            href="login.php"><u>Login</u></a>
                <?php
                      }
                ?>
            </div>
        </div>
    </nav> 
            
