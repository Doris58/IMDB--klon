<?php
    /* trebam ukljuciti klase controller-a - mozda autoload svih? */
    require_once __DIR__ . '/controller/enterController.class.php';

    require_once __DIR__ . '/controller/appController.class.php';

    require_once __DIR__ . '/controller/adminNaslovnaController.class.php';
    require_once __DIR__ . '/controller/adminOsvrtiController.class.php';

    $enterController = new EnterController(); 
    $appController = new AppController(); 
    $adminNaslovnaController = new AdminNaslovnaController(); 
    $adminOsvrtiController = new AdminOsvrtiController();
    
    if(!isset($_GET['rt']))  
    {
        /* nema parametra rt u query stringu u url-u */
        /* direktnim pristupom ovoj skripti, pristupa se aplikaciji, tj. log in - formi. */

        $enterController->iscrtaj_ulaznu_formu('');
        exit(); 
    }

    switch($_GET['rt'])
    {
        case 'login':
            /* korisnik je kliknuo neki submit gumb na ulaznoj formi i "poslao" formu */
            $enterController->obradi_ulaznu_formu();
            break;
        
        case 'registerMe':
            /* korisnik je poslao podatke u formi za registraciju */
            $enterController->registriraj_korisnika();
            break;
    
        case 'korisnikNaslovna':
            /* prikazi naslovnu za korisnika */
            $appController->prikazi_naslovnu();  
            break;

        case 'adminNaslovna':
            /* prikazi naslovnu za administratora - view adminNaslovna */
            $adminNaslovnaController->prikazi_adminNaslovna();  
            break;

        case 'odjava':
            /* korisnik/administrator je kliknuo na gumb odjavi se */
            $enterController->odjavi_korisnika();
            break;

        case 'searchMovies':
            /*Prikazi tablicu sa filmovima*/
            $appController->prikazi_tablicu_filmova();
            break;
         
        /* ---- iz view - adminNaslovna.php ------------------ */

        case 'dohvatiPopisFilmova':
            $adminNaslovnaController->dohvati_popis_filmova();
            break;

        case 'dodajNoviFilm':
            $adminNaslovnaController->dodaj_novi_film();
            break;

        case 'adminOsvrti':
            /* prikazi view s osvrtima na film za administratora */
            $adminOsvrtiController->prikazi_adminOsvrti();
            break;

        /* --------------------------------------------------- */

        /* ---- iz view - adminOsvrti.php -------------------- */

        case 'dohvatiPopisOsvrta':
            $adminOsvrtiController->dohvati_popis_osvrta();
            break;

        case 'obrisiKomentar';
            $adminOsvrtiController->obrisi_komentar();
            break;

        case 'obrisiKorisnikovRacun';
            $adminOsvrtiController->obrisi_korisnikov_racun();
            break;

        /* --------------------------------------------------- */

        case 'prikaziProfilFilma':
            $appController->prikazi_profil_filma();
            break;

        case 'ProfilFilma':
            $appController->profil_filma();
            break;

        case 'posaljiOcjenuKomentar':
            $appController->posalji_ocjenu_komentar();
            break;

        case 'dodajNaListu':
            $appController->dodaj_na_listu();

        case 'prikaziListuFilmovaKorisnika':
            $appController->dohvati_listu_korisnika();
            break;

        case 'korisnickiProfil':
            $appController->prikazi_profil_korisnika();
            break;

        case 'Top5':
            $appController->dohvatiTop5();
            break;
        
    }
?>