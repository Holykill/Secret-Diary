<?php

    session_start();

    $diaryContent = "";

    if (array_key_exists("id", $_COOKIE)){
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)){
        
        include("connection.php");
        
        $query = "SELECT diary FROM diary WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        
        $diaryContent = $row['diary'];
        
        
    } else {
        
        header("Location: index.php");
        
    }


    include("header.php");
?>

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
        <a class="navbar-brand" href="#">Secret Diary</a>
        <ul class="nav navbar-nav ml-auto" id="logout-button">
          <li class="nav-item active">
            <a href='index.php?logout=1'><button class="btn btn-primary" type="submit">Logout</button></a>
          </li>
        </ul>
    </nav>

    <div class="container-fluid">

        <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>

    </div>




<?php

    include("footer.php");

?>