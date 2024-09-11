    /* globalne varijable */
    let timestamp = 0;
    let osvrti = null; /* za data.osvrti, radi eventualnih event-handlera. */

    $(document).ready(function()
    {
        /* Želimo od servera dobiti trenutni popis osvrta na ovaj film, a potom ga ispisati. */
        dohvati_aktualni_popis_osvrta();

        /* button-i klase .btn_delete_comment na stranici se stvaraju dinamički, pa ih */
        /* na događaj moramo pretplatiti unaprijed kao 'potomke' elementa body */
        $('body').on('click', '.btn_delete_comment', obrisi_komentar); 

        /* button-i klase .btn_delete_user na stranici se stvaraju dinamički, pa ih */
        /* na događaj moramo pretplatiti unaprijed kao 'potomke' elementa body */
        $('body').on('click', '.btn_delete_user', obrisi_korisnikov_racun); 
    });

    function dohvati_aktualni_popis_osvrta()
    {
        $.ajax
        (
            {
                url: 'index.php?rt=dohvatiPopisOsvrta',
                method: 'POST',
                data:
                { 
                    //timestamp: timestamp,
                    film_id: $('#movie-id').val()    
                },
                dataType: 'json',
                success: ispisi_popis_osvrta,
                error: function(xhr, status, error)
                {
                    /* Ovo je kad se stvarno dogodi greška pri pozivu na serveru. */

                    if(status !== null)
                        $('#movie-recensions-table').html('Popis osvrta na ovaj film nije dostupan. <br/><br/> Greška prilikom Ajax poziva: status = ' + status);

                    if(status === 'timeout') /* server nije ništa poslao neko vrijeme */
                        dohvati_aktualni_popis_osvrta();
		        }
            }
        );
    }

    function ispisi_popis_osvrta(data)
    { 
        if(data.osvrti.length === 0)    
            $('#movie-recensions-table').html('ZA OVAJ FILM TRENUTNO NEMA DOSTUPNIH OSVRTA.');  
        else 
            $('#movie-recensions-table').html('<tr> <th></th> <th> USERNAME </th> <th> KOMENTAR </th> <th> OCJENA </th> <th></th> <th></th> </tr>');

        /* Server je poslao data.osvrti - polje objekata sa svojstvima id, (film_id,) korisnik_id, username, komentar, ocjena, neprimjereni_kom (broj za tog korisnika). */
        for(let i = 0; i < data.osvrti.length; i++)   /* Može i s pomoću foreach metode. */
        {
            let redak = $('<tr>');

            /* Ukoliko je broj neprimjerenih komentara korisnika >= 3, pojavljuje se pored button za brisanje tog korisnika. */
            /* Klikom na takav button poziva se event-handler obrisi_korisnikov_racun, u kojem će se */ 
            /* Ajax-om serveru poslati $_POST['obrisi_korisnik_id']: $(this).val(), što je id tog korisnika */ 
            /* zato stavljamo value = data.osvrti[i].korisnik_id */   

            let cell_button_del_usr = $('<td>').html('');
            let del_usr_message = '';
            if(Number(data.osvrti[i].neprimjereni_kom) >= 3)
            {
                del_usr_message = 'Korisnik ' + data.osvrti[i].username + ' ima ' + data.osvrti[i].neprimjereni_kom + ' neprimjerenih komentara';
                let button_del_usr = $('<button>').addClass('btn_delete_user').attr('value', data.osvrti[i].korisnik_id).html('OBRIŠI KORISNIKOV RAČUN');
                cell_button_del_usr.html(button_del_usr);
            }
 
            let cell_username = $('<td>').html(data.osvrti[i].username);
            let cell_komentar = $('<td>').html(data.osvrti[i].komentar);
            let cell_ocjena = $('<td>').html(data.osvrti[i].ocjena).addClass('td-broj');

            /* Ukoliko komentar iz osvrta nije već obrisan, pojavljuje se pored button za brisanje komentara. */
            /* Klikom na takav button poziva se event-handler obrisi_komentar, u kojem će se */
            /* Ajax-om serveru poslati $_POST['obrisi_komentar_id']: $(this).val(), što je id tog komentara_ocjene */ 
            /* zato stavljamo value = data.osvrti[i].id */

            let cell_button_del_com = $('<td>').html('');
            if(data.osvrti[i].komentar !== 'Komentar je obrisan jer je bio neprimjeren.')  /* Neka bolja provjera? */
            {
                let button_del_com = $('<button>').addClass('btn_delete_comment').attr('value', data.osvrti[i].id).html('OBRIŠI KOMENTAR');
                cell_button_del_com.html(button_del_com);
            }

            /* Na kraju retka dodaje se eevntualna preporuka za brisanje korisnika. */
            let cell_del_usr_message = $('<td>').html(del_usr_message);

            /* Ovo ulančavanje jQuery metoda je moguće. */
            redak.append(cell_button_del_usr).append(cell_username).append(cell_komentar).append(cell_ocjena).append(cell_button_del_com).append(cell_del_usr_message); 

            $('#movie-recensions-table').append(redak);
        }  

        /* radi eventualnih event-handlera */
        osvrti = data.osvrti;

        /* Server je poslao i data.timestamp */
        //timestamp = data.timestamp;

        //dohvati_aktualni_popis_osvrta();  /* samo ovime bi doslovno stalno dohvaćao popis osvrta, a ne bi čekao izmjenu tablice, ni naše brisanje komentara ili korisnika. */
    }  

    function obrisi_komentar()
    {
        let data = { obrisi_komentar_id: $(this).val() };

        ajax_poziv_brisanje('index.php?rt=obrisiKomentar', data, 'komentar');
    }

    function obrisi_korisnikov_racun()
    {
        let data = { obrisi_korisnik_id: $(this).val() };

        ajax_poziv_brisanje('index.php?rt=obrisiKorisnikovRacun', data, 'korisnik');
    }

    function ajax_poziv_brisanje(url, data, stoBrisem)
    {
        $.ajax
        (
            {
                url: url,
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(data){ /* eventualno poruka o grešci */ },  
                error: function(xhr, status)
                {
                    /* Ovo je kad se stvarno dogodi greška pri pozivu na serveru. */
                    //if(status !== null)
                        //alert('Brisanje ' + stoBrisem + 'a - greška prilikom Ajax poziva: status = ' + status + ' Pokušajte ponovno.');

                    /* PROVJERI OVO JOŠ! */
		        }
            }
        );

        dohvati_aktualni_popis_osvrta();  /* dohvaćamo dakle popis kad učitamo stranicu, i kad mi obrišemo neki komentar ili korisnika */
    }

