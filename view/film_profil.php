<?php
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/pretragaFilmova.php';

?>
<div id="slika-spremnik">
<img id="prikaz-slike" src="" alt="Slika" style="max-width: 100%; max-height: 400px; width: auto; height: auto;">
</div>
<div id="film-details">
    <h1 id="film-title"></h1>
    <button id="dodaj-na-listu">+ Dodaj na lfistu</button>
    <p>Redatelj: <span id="film-director"></span></p>
    <p>Godina: <span id="film-year"></span></p>
    <p>Ocjena: <span id="film-rating"></span></p>
    <p>Glumci: <span id="film-actors"></span></p>
    <p>Zanrovi: <span id="film-genres"></span></p>
</div>

<h2>Ocijeni film</h2>
<div id="medo-ocjene"></div>
<!--<input type="number" id="nova-ocjena" min="1" max="10">-->
<input type="text" id="novi-komentar" placeholder="Napiši komentar">
<button id="posalji-ocjenu-komentar">Pošalji ocjenu i komentar</button>

<h2>Komentari</h2>
<div id="komentari"></div>

<script>
    $(document).ready(function() {
        
        var urlParams = new URLSearchParams(window.location.search);
        var filmId = urlParams.get('id');
        
        for (var i = 0; i < 10; i++) { 
        var BijeliKrug = '<img class="bijelikrug" id="' + i + '"src="uploads/bijelikrug.png" alt="Slika" style="max-width: 40px; max-height: 40px;">';
        $('#medo-ocjene').append(BijeliKrug);
    }

        if (filmId) {
            ajax_poziv('index.php?rt=prikaziProfilFilma', { id: filmId });
        } else {
            console.error('Nije moguće dohvatiti ID filma.');
        }

        $('#medo-ocjene').on('mousemove', '.bijelikrug', function() {
            if ($('.kliknut').length === 0){
        $('.bijelikrug').attr('src', 'uploads/bijelikrug.png'); 
        var currentId = parseInt($(this).attr('id'));
        for (var j = 0; j <= currentId; j++) {
            $('#' + j).attr('src', 'uploads/medo.png'); 
        }
        }
        });

    
        $('#medo-ocjene').on('mouseleave', '.bijelikrug', function() {
            if ($('.kliknut').length === 0){
            $('.bijelikrug').attr('src', 'uploads/bijelikrug.png');
            }
        });

    
        $('#medo-ocjene').on('click', '.bijelikrug', function() {
            var currentId = parseInt($(this).attr('id'));
        
            
            
                $('.bijelikrug').attr('src', 'uploads/bijelikrug.png').removeClass('kliknut'); 
                for (var j = 0; j <= currentId; j++) {
                    $('#' + j).attr('src', 'uploads/medo.png').addClass('kliknut'); 
                }
            
        });

        $('#posalji-ocjenu-komentar').on('click', function() {
    var filmId = urlParams.get('id');

    if (!filmId) {
        console.error('Nije moguće dohvatiti ID filma za slanje ocjene i komentara.');
        return;
    }

    // Debugging: Provjerimo duljinu kliknutih elemenata
    console.log('Broj kliknutih elemenata:', $('.kliknut').length);

    let data = {
        ocjena: $('.kliknut').length,
        komentar: $('#novi-komentar').val(),
        id: filmId
    };

    // Debugging: Ispisujemo objekt 'data' koji šaljemo putem AJAX-a
    console.log('Data koji se šalje:', data);

    ajax_poziv('index.php?rt=posaljiOcjenuKomentar', data);
}); 

        $('#dodaj-na-listu').on('click', function() {
            var filmId = urlParams.get('id');

            if (!filmId) {
                console.error('Nije moguće dohvatiti ID filma za dodavanje na listu.');
                return;
            }

            ajax_poziv('index.php?rt=dodajNaListu', { id: filmId });
        });
    });

    function ajax_poziv(url, data) {
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: ispisi_film,
            error: function(xhr, status) {
                console.error('Došlo je do greške: ' + status);
            }
        });
    }

    function ispisi_film(data) {

        $('#film-title').html(data.film.naziv);
        $('#film-director').html(data.film.redatelj);
        $('#film-year').html(data.film.godina);
        
        $('#film-rating').empty();
        for (i = 0; i < data.film.ocjena; i++){ 
            var medoHtml = '<img id="medo" src="uploads/medo.png" alt="Slika" style="max-width: 40px; max-height: 40px;">';
            $('#film-rating').append(medoHtml);
        }

        $('#prikaz-slike').attr('src', data.film.slika);
        $('#film-actors').empty();

        if (data.glumci && data.glumci.length > 0) {
            var glumacHtml = ''
            data.glumci.forEach(function(glumac) {
                 glumacHtml += ' - ' + glumac.ime ;
                
            });
            glumacHtml += ' -';
            $('#film-actors').append(glumacHtml);
        }

        $('#film-genres').empty();

        if (data.zanrovi && data.zanrovi.length > 0) {
            var zanrHtml = ''
            data.zanrovi.forEach(function(zanr) {
                zanrHtml += '-' +  zanr.naziv;
                
            });
            zanrHtml += ' -';
            $('#film-genres').append(zanrHtml);
        }

        $('#komentari').empty();
        if (data.komentari && data.komentari.length > 0) {
            data.komentari.forEach(function(komentar) {
                var komentarHtml = '<div class="komentar">' +
                    '<p><strong>' + komentar.username + '</strong>: ' + komentar.komentar + '</p>' +
                    '<p>Ocjena: ' + komentar.ocjena + '</p>' +
                    '</div>';
                $('#komentari').append(komentarHtml);
            });
        }
    }
</script>

<?php
require_once __DIR__ . '/_footer.php';
?>
