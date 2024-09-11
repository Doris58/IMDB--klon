<!-- 
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.js"></script>

    <style>
        #admin-profile-block { height: 30px; width: 400px; padding: 5px; text-align: center; border: 2px solid black; border-radius: 5px; background-color: violet; }
    
        /* table{ width: 100%; } */
        .btn_delete_comment, .btn_delete_user { background-color: darkRed; color: white; font-weight: bold; height: 30px; }
        .btn_rc_movie { background-color: darkgreen; color: white; font-weight: bold; height: 30px; }
        th { text-align: center; font-size: 16pt; }
        .td-broj { text-align: center; }
    </style>
-->
    <!-- Mogu koristiti PHP u dijelu koji će se generirati na početku? -->
    <form method = "post" action = "index.php?rt=odjava">
        <div id = "admin-profile-block">
            <span style = "display: inline-block; margin: auto;"> ADMINISTRATOR: <?php echo $ime; ?> </span> <button type = "submit" name = "btn_odjava"> Odjavi se </button>
        </div>
    </form>

