<?php

    session_start();
    $error = "";

    if(array_key_exists('logout', $_GET)){
        
        session_destroy();
        setcookie("id", "", time());
        $_COOKIE["id"] = "";
        header("Location: index.php"); 
        exit();
        
    }
    else if (array_key_exists('id', $_SESSION) OR array_key_exists('id', $_COOKIE)) {
        
        
        header("Location: diary.php");
        
    }

    if(array_key_exists("submit", $_POST)){
        
        include("connection.php");

        if(!$_POST['email']){
            
            $error .= "An email address is required!<br>";
            
        }
        
        if(!$_POST['password']){
            
            $error .= "A password is required!<br>";
        
        }
        
        if ($error != ""){
            
            $error = "<p>There were error(s) in your form:</p>".$error;
        
        } else{ 
//VALIDATIONS ARE OK  
            if ($_POST['signUp'] == '1'){ 
//SIGNUP
          

                $query = "SELECT id FROM diary WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if(mysqli_num_rows($result) > 0){

                    $error = "That email address is already taken!";

                } else {

                    $query = "INSERT into diary (email, password) VALUES(
                    '".mysqli_real_escape_string($link, $_POST['email'])."', 
                    '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if(mysqli_query($link, $query)){

                        $storeID = mysqli_insert_id($link);
                        $query = "UPDATE diary SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        $_SESSION['id'] = $storeID;

                        if (array_key_exists('stayLoggedIn', $_POST)){
                            
                            if ($_POST['stayLoggedIn'] == '1'){

                                setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

                            }
                        }

                        $error = "Signed up!";
                        header("Location: diary.php");

                    } else {

                        $error = "<p>Could not sign you up. There was some error!</p>";

                    }
                }
            } else {
//LOGIN              
                $query = "SELECT * FROM diary WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                $result = mysqli_query($link, $query);
                
                $row = mysqli_fetch_array($result);
                
                if (isset($row)){
                    
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);
                    
                    if($hashedPassword == $row['password']){
                        
                        $_SESSION['id'] = $row['id'];
                        
                        if (array_key_exists('stayLoggedIn', $_POST)){
                            if ($_POST['stayLoggedIn'] == '1'){

                                    setcookie("id", $row['id'], time() + 60*60*24*365);

                                }
                        }
                        
                        header("Location: diary.php");

                    } else {
                    
                    $error = "That email/password combination could not be found.";
                    
                }
                    
                } else {
                    
                    $error = "That email/password combination could not be found.";
                    
                }
                
            }
        }
    }
?>


<?php include("header.php"); ?>

    <div class="container" id="homePageContainer">
      
        <h1 id="top-element">Secret Diary</h1>
        <p><b>Store your thoughts permanently and securely.</b><p>
        
        <div id="error"><?php if ($error != ""){echo '<div class="alert alert-danger" role="alert">'.$error."</div>";} ?></div>

        <form method="post" id="signupForm">
            
            <p>Interested? Sign up now.</p>
            <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </fieldset>
            <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password">
            </fieldset>
            
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="stayLoggedIn" value=1> Stay Logged In
                </label>
            </div>
            
            
            
            <fieldset class="form-group">
                <input type="hidden" name="signUp" value="1">
                <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
            </fieldset>
            
            <p class="bottom-element toggleForms">Log In</p>

        </form>

        <form method="post" id="loginForm">

            <p>Log in using your email and password.</p>
            <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </fieldset>
            <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password">
            </fieldset>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="stayLoggedIn" value=1> Stay Logged In
                </label>
            </div>
            
            
            
            <fieldset class="form-group">
                <input type="hidden" name="signUp" value="0">
                <input class="btn btn-success" type="submit" name="submit" value="Log In">
            </fieldset>
            
            <p class="bottom-element toggleForms">Sign Up</p>
            
        </form>
        
        
        
    </div>

   <?php include("footer.php"); ?>


