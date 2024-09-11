<?php 
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/pretragaFilmova.php'; 
?>

<!-- ovo je samo test -->
<h2> Dobrodošao, <?php echo $ime; ?> ! </h2>
<br/>
<div id="korisnicka-lista"><div>

<script>
$(document).ready(function(){
    //Pocetno ucitavanje Top 5 lista 
    ajax_poziv('index.php?rt=Top5', {zahtjev: 'najnoviji', zanr: 'Drama', godina: 2000});

    $(document).on('click', '.year-button', function() {
        var godina = $(this).attr('id'); 
        var zanr = $('#pamtim_zanr').attr('name');
        console.log('Year button ' + godina);
        ajax_poziv('index.php?rt=Top5', {zahtjev: 'najnoviji', zanr: zanr, godina: godina});
        
    });

    $(document).on('click', '.genre-button', function() {
        var zanr = $(this).attr('id'); 
        var godina = $('#pamtim_godinu').attr('name');
        console.log('Genre button ' + zanr);
        ajax_poziv('index.php?rt=Top5', {zahtjev: 'najnoviji', zanr: zanr, godina: godina});
        
    });

    $(document).ready(function(){
    $(document).on('mouseenter', '.filmOkvir', function() {
        $(this).addClass('istakni');
    });

    $(document).on('mouseleave', '.filmOkvir', function() {
        $(this).removeClass('istakni');
    });
});
});

function ajax_poziv(url, data){
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log('Uspješan odgovor:', response); // Dodano za debugging
            ispisi_Top5(response);
        },
        error: function(xhr, status, error) {
            console.error('Došlo je do greške: ' + status + ' - ' + error);
            console.log(xhr.responseText); // Dodano za debugging
        }
    });
}

function ispisi_Top5(data) {
    var tablicaHtml = '<table border="1" style="width:100%">';
    
    // Red za najnovije filmove
    if (data.najnoviji && data.najnoviji.length > 0) {
        tablicaHtml += '<tr><th colspan="5">Najnoviji filmovi</th></tr><tr>';
        data.najnoviji.forEach(function(film) {
            console.log('Film slika:', film.slika); // Dodano za debugging
            tablicaHtml += '<td class="filmOkvir"><a href="index.php?rt=ProfilFilma&id=' + film.id + '">' +
                '<img src="' + film.slika + '" style="max-width: 200px; max-height: 300px;"></img><br>' + 
                '<strong>' + film.naziv + '</strong><br>' +
                'Redatelj: ' + film.redatelj + '<br>' +
                'Godina: ' + film.godina + '<br>' +
                'Ocjena: ' + film.ocjena + 
                '</td>';
        });
        tablicaHtml += '</tr>';
    }

    // Red za top 5 filmova po godini
    
        tablicaHtml += '<tr><th colspan="5" id="pamtim_godinu" name="' +  data.godina + '">Top 5 filmova u razdoblju ' + data.godina +'ih</th></tr><tr>';
        tablicaHtml += '<tr><td colspan="5">';
        
        tablicaHtml += '<button class="year-button" id="1940">1940e</button>';
        tablicaHtml += '<button class="year-button" id="1950">1950e</button>';
        tablicaHtml += '<button class="year-button" id="1960">1960e</button>';
        tablicaHtml += '<button class="year-button" id="1970">1970e</button>';
        tablicaHtml += '<button class="year-button" id="1980">1980e</button>';
        tablicaHtml += '<button class="year-button" id="1990">1990e</button>';
        tablicaHtml += '<button class="year-button" id="2000">2000e</button>';
        tablicaHtml += '<button class="year-button" id="2010">2010e</button>';
        tablicaHtml += '<button class="year-button" id="2020">2020e</button>';
        
        tablicaHtml += '</td></tr>';
        if (data.top5godina && data.top5godina.length > 0) {
        data.top5godina.forEach(function(film) {
            tablicaHtml += '<td class="filmOkvir"><a href="index.php?rt=ProfilFilma&id=' + film.id + '">' +
            '<img src="' + film.slika + '" style="max-width: 200px; max-height: 300px;"></img><br>' +
                '<strong>' + film.naziv + '</strong><br>' +
                'Redatelj: ' + film.redatelj + '<br>' +
                'Godina: ' + film.godina + '<br>' +
                'Ocjena: ' + film.ocjena + 
                '</td>';
        });}
        else {
        tablicaHtml += '<td> Nema filmova za prikaz</td>';
        }

        tablicaHtml += '</tr>';
    

    // Red za top 5 filmova po žanru
    
        tablicaHtml += '<tr><th colspan="5" id="pamtim_zanr" name="' +  data.zanr + '">Top 5 filmova žanra ' + data.zanr + '</th></tr><tr>';
        tablicaHtml += '<tr><td colspan="5">';
        
        tablicaHtml += '<button class="genre-button" id="Action">Akcijski</button>';
        tablicaHtml += '<button class="genre-button" id="Adventure">Avanturisticki</button>';
        tablicaHtml += '<button class="genre-button" id="Animation">Animirani</button>';
        tablicaHtml += '<button class="genre-button" id="Biography">Biografski</button>';
        tablicaHtml += '<button class="genre-button" id="Crime">Kriminalisticki</button>';
        tablicaHtml += '<button class="genre-button" id="Documentary">Dokumentarni</button>';
        tablicaHtml += '<button class="genre-button" id="Drama">Drama</button>';
        tablicaHtml += '<button class="genre-button" id="Fantasy">Fantasticni</button>';
        tablicaHtml += '<button class="genre-button" id="History">Povijesni</button>';
        tablicaHtml += '<button class="genre-button" id="Horror">Horor</button>';
        tablicaHtml += '<button class="genre-button" id="Mystery">Misterija</button>';
        tablicaHtml += '<button class="genre-button" id="Musical">Mjuzikl</button>';
        tablicaHtml += '<button class="genre-button" id="Romance">Romanticni</button>';
        tablicaHtml += '<button class="genre-button" id="Science Fiction">SF</button>';
        tablicaHtml += '<button class="genre-button" id="Thriller">Triler</button>';
        tablicaHtml += '<button class="genre-button" id="War">Ratni</button>';
        tablicaHtml += '<button class="genre-button" id="Western">Western</button>';
        tablicaHtml += '</td></tr>';
       if (data.top5zanr && data.top5zanr.length > 0) {
        data.top5zanr.forEach(function(film) {
            tablicaHtml += '<td class="filmOkvir"><a href="index.php?rt=ProfilFilma&id=' + film.id + '">' +
            '<img src="' + film.slika + '" style="max-width: 200px; max-height: 300px;"></img><br>' +
                '<strong>' + film.naziv + '</strong><br>' +
                'Redatelj: ' + film.redatelj + '<br>' +
                'Godina: ' + film.godina + '<br>' +
                'Ocjena: ' + film.ocjena + 
                '</td>';
        });}
        else {
        tablicaHtml += '<td> Nema filmova za prikaz</td>';
        }
         
    

    tablicaHtml += '</table>';

    // Prikaz tablice u odgovarajućem HTML elementu
    $('#korisnicka-lista').html(tablicaHtml);
}
</script>

<form method="post" action="index.php?rt=odjava">
    <input type="submit"
