<?php 
require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/pretragaFilmova.php';
?>

<!--<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IME NAŠE APLIKACIJE</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #sakrij-odgledane {
            display: none;
        }
    </style>
</head>
<body>-->
<div id="korisnicka-lista">
    <!-- Ovdje će se ispisati popis filmova korisnika -->
</div>
<button id="prikazi-odgledane">Prikaži odgledane filmove</button>
<button id="sakrij-odgledane">Sakrij pogledane filmove</button>
<div id="korisnicka-lista-odgledanih" name="sakrij">
    <!-- Ovdje će se ispisati popis odgledanih filmova korisnika -->
</div>
<script>
$(document).ready(function() {
    // Početno učitavanje liste filmova korisnika
    ajax_poziv('index.php?rt=prikaziListuFilmovaKorisnika', { });

    // Prikaz odgledanih filmova
    $('#prikazi-odgledane').click(function() {
        $('#korisnicka-lista-odgledanih').attr('name', 'prikazi');
        ajax_poziv('index.php?rt=prikaziListuFilmovaKorisnika', { });
        $('#prikazi-odgledane').hide(); // Sakrij gumb "Prikaži odgledane filmove"
        $('#sakrij-odgledane').show(); // Prikaži gumb "Sakrij pogledane filmove"
    });

    // Sakrivanje odgledanih filmova
    $('#sakrij-odgledane').click(function() {
        
        $('#korisnicka-lista-odgledanih').attr('name', 'sakrij');
        $('#korisnicka-lista-odgledanih').html('');
        $('#sakrij-odgledane').hide(); // Sakrij gumb "Sakrij pogledane filmove"
        $('#prikazi-odgledane').show(); // Prikaži gumb "Prikaži odgledane filmove"
    });

    // Funkcija za uklanjanje filma s liste
    $(document).on('click', '.makni', function() {
        var filmId = $(this).attr('id');
        console.log('Gumb makni ' + filmId);
        ajax_poziv('index.php?rt=prikaziListuFilmovaKorisnika', { zahtjev: 'makni_s_liste', id: filmId });
    });

    // Funkcija za vraćanje filma na listu za gledanje
    $(document).on('click', '.vrati', function() {
        var filmId = $(this).attr('id');
        console.log('Gumb Vrati ' + filmId);
        ajax_poziv('index.php?rt=prikaziListuFilmovaKorisnika', { zahtjev: 'vrati_na_listu', id: filmId });
        
    });
});

// AJAX funkcija za dohvaćanje popisa filmova
function ajax_poziv(url, data) {
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log('Uspješan odgovor:', response); // Dodano za debugging
            ispisi_listu(response);
        },
        error: function(xhr, status, error) {
            console.error('Došlo je do greške: ' + status + ' - ' + error);
            console.log(xhr.responseText); // Dodano za debugging
        }
    });
}


// Funkcija za ispis popisa filmova korisnika
function ispisi_listu(data) {
    var naslovHtml = '<h2>Moj popis filmova</h2>';
    var listaHtml = naslovHtml + '<ul>';
    if (data.lista && data.lista.length > 0) {
        data.lista.forEach(function(film) {
            listaHtml += '<li>' +
                '<strong>' + film.naziv + '</strong><br>' +
                'Redatelj: ' + film.redatelj + '<br>' +
                'Godina: ' + film.godina + '<br>' +
                'Ocjena: ' + film.ocjena + '<br>' +
                '<button class="makni" id="' + film.id + '">- Makni s liste</button>' +
                '</li>';
        });
        listaHtml += '</ul>';
        $('#korisnicka-lista').html(listaHtml);
    } else {
        $('#korisnicka-lista').html('<p>Nema filmova u vašoj listi.</p>');
    }
    if ($('#korisnicka-lista-odgledanih').attr('name') === 'prikazi')
    {ispisi_listu2(data);}
     
}

// Funkcija za ispis popisa odgledanih filmova korisnika
function ispisi_listu2(data) {
    var naslovHtml = '<h2>Odgledani filmovi</h2>';
    var listaHtml = naslovHtml + '<ul>';
    if (data.lista_pogledanih && data.lista_pogledanih.length > 0) {
        data.lista_pogledanih.forEach(function(film) {
            listaHtml += '<li>' +
                '<strong>' + film.naziv + '</strong><br>' +
                'Redatelj: ' + film.redatelj + '<br>' +
                'Godina: ' + film.godina + '<br>' +
                'Ocjena: ' + film.ocjena + '<br>' +
                '<button class="vrati" id="' + film.id + '">+ Vrati na listu</button>' +
                '</li>';
        });
        listaHtml += '</ul>';
        $('#korisnicka-lista-odgledanih').html(listaHtml);
        $('#sakrij-odgledane').show(); // Prikaži gumb za sakrivanje
        $('#prikazi-odgledane').hide(); // Sakrij gumb za prikazivanje
    } else {
        $('#korisnicka-lista-odgledanih').html('<p>Nema filmova u vašoj listi.</p>');
        $('#sakrij-odgledane').hide();
        $('#prikazi-odgledane').show(); // Prikaži gumb za prikazivanje
    }
}
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
</body>
</html>
