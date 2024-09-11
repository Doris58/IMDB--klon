<?php 
    require_once __DIR__ . '/_header.php';
    require_once __DIR__ . '/_adminHeader.php'; 
?>

<table id = "divs-parent-table" style = "width: 100%; padding: 2px;">  

<tr>
<td style = "width: 60%; vertical-align: top; horizontal-align: center; margin: auto;">
<form method = "post" action = "index.php?rt=adminOsvrti">  <!-- klikom na neki gumb iz ove forme PRELAZIMO NA VIEW s osvrtima za film -->
    <!-- PROVJERI OVO JOŠ! - klikom na gumb šalje se naziv filma => može i method = "get" => vidjet će se naziv filma u url-u gore? -->
    <div id = "movie-list-table-container" style = "vertical-align: top; horizontal-align: center; margin: auto;">  <!-- style = "float: left; width: 40%; padding: 5px;" -->
        <table id = "movie-list-table" style = "text-align: center;">
            <!-- Popis filmova nije dostupan. Zašto ovo "ne radi" ? -->
        </table>
    </div>
</form>
</td>

<td style = "width: 40%">
<table style = "width: 100%; border: 2px solid black; border-radius: 20px; padding: 5px;">
<tr>
<td colspan = "2">
    <div id = "add-movie-form-container" > <!--  width: 40%; float: right;  -->

        <h2 style = "color: darkred; padding: 10px;"> Dodavanje novog filma u bazu </h2>

        <div id ="basic-movie-data-container" style ="padding: 5px;">
            <div>
                <h3> Naziv filma: </h3>
                 <input type = "text"  id = "new-movie-title-box"/> 
            </div> 

            <div>
                 <h3> Redatelj: </h3>
                 <input type = "text" id = "new-movie-director-box" /> 
            </div>

            <div>
                <h3> Godina: </h3>
                <input type = "text" id = "new-movie-year-box" /> 
            </div>
        </div>
    </div>
</td>
</tr>

<tr>
    <td>
        <div id = "new-movie-genres-container" style = "padding: 10px;">
            <h3> Žanrovi: <h3/>
            <div id = "all-genres-list-container" style = "padding: 2px;"> <!-- width: 40%; float: left; -->
            <?php 
                for($i = 0; $i < count($zanrovi); $i++)
                {
                    echo '<button class = "genre-button" value ="' . $zanrovi[$i]->naziv . '"> '.  $zanrovi[$i]->naziv . ' </button>';

                    if($i%3 === 0)
                        echo '<br/><br/>';
                    else
                        echo '  ';
                }    
            ?>
        </div>
        <div id = "new-movie-genres-list-container" style = "font-weight: bold; padding: 2px;"></div> <!-- width: 40%; float: right; -->
    </td>
    <!-- <br/>  -->
    <td>
        <div id = "new-movie-actors-container" style= "padding: 10px;">
            <button id = "btn-add-new-movie-actors" style= "padding: 5px;"> Dodaj glumca </button>
        
            <div id = "new-movie-actors-list-container"></div>
        </div>
           
        <br/>

        <div style= "padding: 5px;">
            <button id = "btn-add-new-movie" style = "width: 200px; height 50px; padding: 5px; border-radius: 15px; background-color: green; border: 2px solid darkgreen;"> Dodaj film </button> 
        </div>
    </td>
</tr>
</table>

</td> <!-- parent-table end tags --> 
</tr>
</table> 

<script src = "view/adminNaslovna.js"> </script>

<?php require_once __DIR__ . '/_footer.php'; ?></button>