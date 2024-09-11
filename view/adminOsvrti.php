<?php 
    require_once __DIR__ . '/_header.php';
    require_once __DIR__ . '/_adminHeader.php'; 
?>

<table style ="width: 100%; background-color: gray; padding: 2px;">
    <tr>
        <td>
            <div id = "movie-data-container">
                <input id = "movie-id" type = "hidden" value = "<?php echo $film->id; ?>" /> <!-- imat ćemo: id filma = $('#movie-id').val() -->
                <!-- spremamo vrijednost PHP-varijable dobivene od controller-a u html el. da bismo je mogli dohvaćati u JavaScript-u. -->

                <div id = "movie-name" style = "color: darkred;"> 
                     <h1> <?php echo $film->naziv; ?> </h1>
                </div>

                <div id = "movie-director"> 
                    <p> <b>Redatelj:</b> <?php echo $film->redatelj; ?>  </p>
                </div>

                <div id = "movie-year"> 
                    <p> <b>Godina:</b> <?php echo $film->godina; ?>  </p>
                </div>

                <div id = "movie-rating"> 
                    <p> <b>Ocjena:</b> <?php echo $film->ocjena; ?>  </p>
                </div>
            </div>
        </td>
        <td>
            <div id = "movie-genres"> 
                <h3> Žanrovi: </h3>
                <div id = "all-genres-list-container">
                    <?php 
                        foreach($zanrovi as $zanr)
                        {
                            echo $zanr . ' <br/>';
                        }    
                    ?>
                </div>
            </div>
        </td>
        <td>
            <div id = "movie-actors"> 
                <h3> Glumci: </h3>
                <div id = "all-actors-list-container">
                    <?php 
                        foreach($glumci as $glumac)
                        {
                            echo $glumac . ' <br/>';
                        }    
                    ?>
                </div>
            </div>
        </td>
    </tr>
</table>

<br/> 

<form method = "post" action = "index.php?rt=adminNaslovna"> <!-- klikom na gumb vraćamo se NA VIEW s popisom filmova -->
    <div id = "go-back-button-container">
        <button type = "submit" name = "btn_go_back" style ="width: 100%; height: 50px; font-size: 24pt;"> Povratak na popis filmova </button>
    </div>
</form>

<br/> 

<div id = "movie-recensions-table-container">
    <table id = "movie-recensions-table" style ="width: 100%;">
        <!-- Popis osvrta nije dostupan. Zašto ovo "ne radi" ? -->
    </table>
</div>

<script src = "view/adminOsvrti.js"> </script>