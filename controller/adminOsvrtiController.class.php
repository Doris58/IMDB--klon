<?php
    /* kontroler za uporabu aplikacije od strane administratora - ZA VIEW adminOsvrti */

    require_once __DIR__ . '/../model/adminService.class.php';

    class AdminOsvrtiController
    {
        function prikazi_adminOsvrti()  /* prikaži view adminOsvrti */
        {
            session_start();

            /* Sljedeće ćemo varijable koristiti u donjem view-u */ 

            $ime = $_SESSION['username'];  /* ime administratora je i dalje spremljeno u SESSION-u */

            if(!isset($_POST['recenzije_film_id']))  /* PAZITI je li method bio get ili post (PROVJERITI ŠTO KAD JE GET) */
            {
                /* nemamo film za kojeg treba prikazati osvrte --> na ovu smo stranicu došli nekom greškom */
                header('Location: index.php?rt=adminNaslovna'); /* vraćamo se na view adminNaslovna */
                exit();
            } 
 
            /* id filma za kojeg treba prikazati osvrte dobili smo kao $_POST['recenzije_film_id'] */

            $adminService = new adminService();
            $film = $adminService->getMovieById($_POST['recenzije_film_id']);

            /* Dohvati sve žanrove konkretnog filma u polje $zanrovi */
            $film_zanrovi = $adminService->getGenresByMovieId($_POST['recenzije_film_id']);  /* polje objekata tipa Film_zanr */

            $zanrovi = [];
            foreach($film_zanrovi as $film_zanr)
            {
                $zanr =  $adminService->getGenreById($film_zanr->zanr_id); /* objekt tipa Zanr */
                $zanrovi[] = $zanr->naziv;
            }

            /* Dohvati sve glumce konkretnog filma u polje $glumci. */
            $uloge = $adminService->getActorsByMovieId($_POST['recenzije_film_id']); /* polje objekata tipa Uloga */

            $glumci = [];
            foreach($uloge as $uloga)
            {
                $glumac =  $adminService->getActorById($uloga->glumac_id); /* objekt tipa Glumac */
                $glumci[] = $glumac->ime;
            }

            require_once __DIR__ . '/../view/adminOsvrti.php';
        }

        function dohvati_popis_osvrta()
        {
            /*if(!isset($_POST['timestamp']) ||*/ if(!isset($_POST['film_id'])) /* došli smo greškom */
                exit();  
            
            //$adminTimestamp = (int)$_POST['timestamp'];

            $adminService = new AdminService();

            //while(1)  /* LONG POLLING */
            //{
                //$cRTimestamp = $adminService->getMAXLastModifiedCommentsAndRatings();
                //if($CRtimestamp  === null)  /* Treba li ovo? */
                    //$CRtimestamp = $adminTimestamp + 1;  //0

                /* Trebalo bi i $userTimestamp za tablicu s korisnicima, pa usporediti $timestamp s oba. */
	
	            //if($timestamp > $adminTimestamp)
	            //{
                    $message = [];
                    //$message['timestamp'] = $timestamp;

                    $message['osvrti'] = [];

                    $komentari_ocjene = $adminService->getCommentsAndRatingsByMovieId($_POST['film_id']); /* $komentari_ocjene je polje objekata tipa (klase) Komentar_ocjena */   
                    foreach($komentari_ocjene as $komentar_ocjena)
                    {
                        /* Dohvati korisnika koji je ostavio ovaj osvrt (komentar i ocjenu). */
                        $korisnik = $adminService->getUserById($komentar_ocjena->korisnik_id);  
                        if($korisnik === null)  /* Korisnik s ovim id-om je obrisan iz baze. */
                        {
                            $username = 'obrisani korisnik';
                            $neprimjereni_kom = 0;
                        }
                        else /* $korisnik je objekt tipa Korisnik */
                        {
                            $username = $korisnik->username;
                            $neprimjereni_kom = $korisnik->neprimjereni_kom;
                        }

                        /* Dodaj u $message['osvrti'] sve podatke o konkretnom osvrtu - komentar, ocjenu i username pripadnog korisnika. */
                        /* Dodaj (da bude dostupan) i broj neprimjerenih komentara pripadnog korisnika. */
                        $message['osvrti'][] = ['id' => $komentar_ocjena->id, 'film_id' => $komentar_ocjena->film_id, 'korisnik_id' => $komentar_ocjena->korisnik_id,
                                                'username' => $username, 'komentar' => $komentar_ocjena->komentar, 'ocjena' => $komentar_ocjena->ocjena,
                                                'neprimjereni_kom' => $neprimjereni_kom];
                    }
                            
                    $this->sendJSONandExit($message);
                //}
                        
                /* Odspavaj 10 miliseknudi. */
	            //usleep( 10000 );     
            //}
        }

        function obrisi_komentar()  
        {
            if(!isset($_POST['obrisi_komentar_id'])) /* došli smo greškom */
                exit();

            $adminService = new AdminService();

            /* "Brisanje" komentara. */
            $novi_komentar = 'Komentar je obrisan jer je bio neprimjeren.';
            $adminService->updateCommentById($_POST['obrisi_komentar_id'], $novi_komentar);

            /* Pribrajanje jednog neprimjerenog komentara odgovarajućem korisniku. */

            $komentar_ocjena = $adminService->getCommentAndRatingById($_POST['obrisi_komentar_id']);

            $korisnik = $adminService->getUserById($komentar_ocjena->korisnik_id);
            if($korisnik === null)  /* Korisnik s ovim id-om je obrisan iz baze. */
                exit();

            /* $korisnik je objekt tipa Korisnik */
            $novi_broj = (int)$korisnik->neprimjereni_kom + 1;
            $adminService->updateBadCommentsById($korisnik->id, $novi_broj);
        }

        function obrisi_korisnikov_racun()
        {
            /* Korisnika će se moći obrisati kad se u bazi vidi da ima previše neprimj. komentara, */
            /* u view-u za analizu osvrta na film. */

            if(!isset($_POST['obrisi_korisnik_id'])) /* došli smo greškom */
                exit();

            $adminService = new AdminService();

            /* Za svaki slučaj ovo provjeriti ! */
            if($adminService->getUserById($_POST['obrisi_korisnik_id']) === null) /* Taj korisnik je već obrisan. */
                exit();

            /* Brisanje (računa) korisnika. */
            $adminService->deleteUserById($_POST['obrisi_korisnik_id']);  

            /* Brisanje watch-liste tog korisnika iz baze. */
            $adminService->deleteListByUserId($_POST['obrisi_korisnik_id']);
        }

        function sendJSONandExit($message)
        {
            // Kao izlaz skripte pošalji $message u JSON formatu i prekini izvođenje.
            header('Content-type:application/json;charset=utf-8');
            echo json_encode($message);
            flush();
            exit(0);
        }

        function sendErrorAndExit($messageText)
        {
	        $message = [];
	        $message['error'] = $messageText;
	        $this->sendJSONandExit($message);
        }
    }
?>