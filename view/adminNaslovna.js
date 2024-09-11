    /* --- GLOBALNE VARIJABLE -------------------- */

    let timestamp = 0;
    let filmovi = null; /* za data.filmovi, radi event-handlera na tablici s popisom filmova */
    let svi_glumci = null;  /* za data.svi_glumci, radi eventualnog suggest-a imena glumaca */
    let zanrovi_novog_filma = []; /* za dodavanje svih zanrova novog filma */
    let glumci_novog_filma = [];   /* za dodavanje svih glumaca novog filma */

    /* -------------------------------------------------------------------------------- */

    $(document).ready(function()
    { 
        $('body').on('mouseenter', '.movie-tr', napravi_skocni_div);
        $('body').on('mouseleave', '.movie-tr', makni_skocni_div);

        /* Želimo dobiti trenutni popis filmova od servera, a potom ga ispisati. */
        dohvati_aktualni_popis_filmova();

        /* $('body').on('click', '.btn_movie', function(){ ajax_poziv('index.php?rt=administrirajOsvrteNaFilm&film=' + $(this).val(), {zahtjev: 'recenzije_za_film'}); }); */
       
        $('.genre-button').css('background-color', '#2498c9').on('click', dodaj_ili_makni_novi_zanr).css('height', '30px');

        $('#btn-add-new-movie-actors').on('click', omoguci_novog_glumca);
        $('body').on('input', '.new-movie-actor-box', predlozi_novog_glumca);
        $('body').on('click', '.add-new-movie-actor-btn', dodaj_novog_glumca);
        $('body').on('click', '.remove-new-movie-actor-btn', obrisi_novog_glumca);
 
        /* Želimo da server doda novi film u bazu. */
        $('#btn-add-new-movie').on('click', dodaj_novi_film);
    });

    function omoguci_novog_glumca()
    {
        let div = $('<div>').css('padding', '5px');
        let textbox = $('<input>').attr('type', 'text').attr('list', 'datalist').addClass('new-movie-actor-box');
        let button = $('<button>').html('Dodaj').addClass('add-new-movie-actor-btn');
        let datalist = $('<datalist>').attr('id', 'datalist');
        div.append(textbox).append('  ').append(button).append(datalist);
        
        $('#new-movie-actors-list-container').append(div);

        $(this).prop('disabled', true);
    }

    function predlozi_novog_glumca()
    {
        $('#datalist').html('');

        let unos = $(this).val();

        for(let i = 0; i < svi_glumci.length; i++)
        {
            if(svi_glumci[i].indexOf(unos) >= 0)
            {
                let option = $('<option>').val(svi_glumci[i]);
                $('#datalist').append(option);
            }
        }
    }

    function dodaj_novog_glumca()
    {
        let button = $(this);

        /* Nemoj dodati prazni string iz textbox-a. */
        let glumac = button.prev().val();
        if(glumac === '')
            return;

        /* dodaj glumca u popis za novi film, ako već nisi tog istog */
        let index = glumci_novog_filma.indexOf(glumac);
        if(index <= -1) 
            glumci_novog_filma.push(glumac);

        $('#datalist').remove();
        button.removeClass('add-new-movie-actor-btn').addClass('remove-new-movie-actor-btn').html('Poništi');

        $('#btn-add-new-movie-actors').prop('disabled', false);
    }

    function obrisi_novog_glumca()
    {
        let button = $(this);

        let glumac = button.prev().val();
        let index = glumci_novog_filma.indexOf(glumac);
        if(index > -1)  /* glumac je bio dodan, mičemo ga */
            glumci_novog_filma.splice(index, 1);

        button.parent().remove();
    }

    function dodaj_ili_makni_novi_zanr()
    {
        let button = $(this);

        let index = zanrovi_novog_filma.indexOf( button.val() );
        if(index > -1)  /* žanr je bio dodan, mičemo ga */
        {
            button.css('background-color', '#2498c9');
            zanrovi_novog_filma.splice(index, 1);
        }
        else   /* dodajemo novi žanr */
        {
            button.css('background-color', 'blue');
            zanrovi_novog_filma.push( button.val() );
        }

        /* ažuriramo popis žanrova za novi film */
        /*$('#new-movie-genres-list-container').html('');
        for(let i = 0; i < zanrovi_novog_filma.length; i++)
            $('#new-movie-genres-list-container').append('<p>' + zanrovi_novog_filma[i] +'</p>');*/
    }

    function dohvati_aktualni_popis_filmova()
    {
        $.ajax
        (
            {
                url: 'index.php?rt=dohvatiPopisFilmova',
                method: 'POST',
                data: { timestamp: timestamp },
                dataType: 'json',
                success: ispisi_popis_filmova,
                error: function( xhr, status )
                {
                    /* Ovo je kad se stvarno dogodi greška pri pozivu na serveru. */

                    if(status !== null)
                        $('#movie-list-table').html('Popis filmova nije dostupan. <br/><br/> Greška prilikom Ajax poziva: status = ' + status);

                    if(status === 'timeout') /* server nije ništa poslao neko vrijeme */
                        dohvati_aktualni_popis_filmova();
		        }
            }
        );
    }

    function dodaj_novi_film()
    {
        let unos_ok = provjeri_unos();
        if(!unos_ok)
            return;

        let data =
        {
            naziv: $('#new-movie-title-box').val(),
            redatelj: $('#new-movie-director-box').val(),
            godina: $('#new-movie-year-box').val(),
            zanrovi: zanrovi_novog_filma,
            glumci: glumci_novog_filma
        };
            
        $.ajax
        (
            {
                url: 'index.php?rt=dodajNoviFilm',
                method: 'POST',
                data: JSON.stringify(data),
                dataType: 'json',
                success: ispisi_popis_filmova,
                error: function( xhr, status )
                {
                    /* Ovo je kad se stvarno dogodi greška pri pozivu na serveru. */
                    //if(status !== null)
                       //alert('Dodavanje novog filma - greška prilikom Ajax poziva: status = ' + status + ' Pokušajte ponovno.');

                    /* PROVJERI OVO JOŠ ! */
		        }
            }
        );

        /* Bez obzira je li zahtjev bio uspješan ili ne. */
        /* Da se resetiraju na prazno svi inputi u formi za dodavanje filma. */

        $('#add-movie-form-container div input').val('');
        $('#new-movie-genres-list-container').html('');
        $('.genre-button').css('background-color', 'lightblue');
        zanrovi_novog_filma = [];
        $('#new-movie-actors-list-container').html('');
        glumci_novog_filma = [];
    }

    function ispisi_popis_filmova(data)
    {
        if(data.error !== undefined)  /* Za dodavanje filma - ako nisu dobri uneseni podaci za film ili je film već u bazi. */
        {
            alert(data.error);
            exit();
        }
            
        $('#movie-list-table').html('<tr> <th> Naziv filma </th> <th> Redatelj </th> <th> Godina </th> <th> Ocjena </th> <th> </th> </tr>');

        /* Server je poslao data.filmovi - polje objekata sa svojstvima id, naziv, redatelj, godina, (zanrovi, glumci) i ocjena. */
        for(let i = 0; i < data.filmovi.length; i++)   /* Može i s pomoću foreach metode. */
        {
            let redak = $('<tr>');
            if(i % 2 === 0)
                redak.addClass('parni-tr');
            else
                redak.addClass('neparni-tr');

            let cell_naziv = $('<td>').html(data.filmovi[i].naziv).attr('id', 'n' + i).addClass('movie-tr'); 
            let cell_redatelj = $('<td>').html(data.filmovi[i].redatelj).attr('id', 'r' + i).addClass('movie-tr');
            let cell_godina = $('<td>').html(data.filmovi[i].godina).attr('id', 'g' + i).addClass('movie-tr').addClass('td-broj');
            let cell_ocjena = $('<td>').html(data.filmovi[i].ocjena).attr('id', 'o' + i).addClass('movie-tr').addClass('td-broj');

            /* Kad kliknemo na button uz odgov. film, serveru će se poslati $_POST['recenzije_film_id'] = id tog filma */ 
            /* zato stavljamo name = 'recenzije_film_id' i value = data.filmovi[i].id */  /* ALT:  .addClass('btn_movie'), ali ne get-om slati id */ /* ALT: .attr('name', data.filmovi[i].id) */
            let button = $('<button>').attr('type', 'submit').attr('name', 'recenzije_film_id').attr('value', data.filmovi[i].id).html('Pogledaj osvrte').addClass('btn_rc_movie');  
            let cell_button = $('<td>').html(button);

            redak.append(cell_naziv).append(cell_redatelj).append(cell_godina).append(cell_ocjena).append(cell_button);  /* Ovo ulančavanje jQuery metoda je moguće. */

            $('#movie-list-table').append(redak);
        }  

        /* radi event handlera */
        filmovi = data.filmovi;
        svi_glumci = data.svi_glumci;  /* za eventualni suggest imena glumaca */

        /* Server je poslao i data.timestamp */
        timestamp = data.timestamp;

        dohvati_aktualni_popis_filmova();
    }  

    function napravi_skocni_div(event)
    {
        /* napravi div */
        let div = $('<div>').attr('id', 'pop-up-div').css('position', 'absolute').css('left', event.clientX).css('top', event.clientY)
                    .css({ 'border': '3px solid black', 'border-radius': '5px', 'background-color': 'gray', 'padding' : '10px' });

        /* Nađi podatke za taj film. */
        let celija = $(this);
        let id = celija.attr('id');   /* id = 'xBROJ' --> i = id.substring(1) */
        let i = id.substring(1);
       
        /* Napiši naziv filma. */
        let naziv = $('<h2>').html(filmovi[i].naziv);

        /* Popiši sve žanrove. */
        let naslov_zanrovi = $('<h3>').html('Žanrovi');

        let div_zanrovi = $('<div>');
        for(let j = 0; j < filmovi[i].zanrovi.length; j++)
            div_zanrovi.append(filmovi[i].zanrovi[j] + '<br/>');

        /* Popiši sve glumce */
        let naslov_glumci = $('<h3>').html('Glumci');

        let div_glumci = $('<div>');
        for(let j = 0; j < filmovi[i].glumci.length; j++)
            div_glumci.append(filmovi[i].glumci[j] + '<br/>');

        /* Dodaj sve u div */
        div.append(naziv).append(naslov_zanrovi).append(div_zanrovi).append(naslov_glumci).append(div_glumci);

        $('body').append(div);
    }

    function makni_skocni_div()
    {
        $('#pop-up-div').remove();
    }

    function provjeri_unos()
    {
        let naziv = $('#new-movie-title-box').val();
        let redatelj = $('#new-movie-director-box').val();
        let godina = $('#new-movie-year-box').val();

        if(naziv === '' || redatelj === '' || godina === '')
        {
            alert('Potrebno je unijeti sve tražene podatke o filmu.')
            return false;
        }

        let regex_naziv = /^[\w\s]{1,50}$/;
        if(!regex_naziv.test(naziv))
        {
            alert('Naziv filma može se sastojati od najviše 50 znakova');
            return false;
        }

        let regex_ime = /^[A-Za-z\s]{1,50}$/;
        if(!regex_ime.test(redatelj))
        {
            alert('Ime redatelja može se sastojati od najviše 50 slova');
            return false;
        }

        let regex_godina = /^\d{2,4}$/;
        if(!regex_godina.test(godina))
        {
            alert('Unesite ispravnu godinu.');
            return false;
        }

        if(zanrovi_novog_filma.length === 0)
        {
            alert('Potrebno je odrediti barem jedan žanr kojemu film pripada.');
            return false;
        }

        for(let i = 0; i < glumci_novog_filma.length; i++)
        {
            if(!regex_ime.test(glumci_novog_filma[i]))
            {
                alert('Ime glumca može se sastojati od najviše 50 slova.');
                return false;
            }
        }  

        /* Sve je u redu. */
        return true; 
    }
