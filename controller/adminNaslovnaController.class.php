<?php
    /* kontroler za uporabu aplikacije od strane administratora - ZA VIEW adminNaslovna / adminFilmovi */

    require_once __DIR__ . '/../model/adminService.class.php';

    class AdminNaslovnaController
    {
        function prikazi_adminNaslovna()  /* prikaži view adminNaslovna */
        {
            session_start();

            /* Sljedeće ćemo varijable koristiti u kodu donjeg view-a : */

            $ime = $_SESSION['username']; 

            $adminService = new AdminService();
            $zanrovi = $adminService->getAllGenres();  /* polje objekata tipa Zanr */

            require_once __DIR__ . '/../view/adminNaslovna.php';
        }

        function dohvati_popis_filmova()  
        {
            if(!isset($_POST['timestamp'])) /* Došli smo greškom. */
                exit();

            $adminTimestamp = (int)$_POST['timestamp'];

            $adminService = new AdminService();

            while(1)  /* LONG POLLING */
            {
                $timestamp = $adminService->getMAXLastModifiedMovies();
	
	            if($timestamp > $adminTimestamp)
                {
                    $message = [];
                    $message['timestamp'] = $timestamp;

                    $this->dohvati_i_posalji_popis($message);
                }   

                /* Odspavaj 20 milisekundi. */
	            usleep( 2000 );     
            }
        }
        
        function dohvati_i_posalji_popis($message)
        {
            $adminService = new AdminService();
            
            $message['filmovi'] = [];

            $filmovi = $adminService->getAllMovies(); /* $filmovi je polje objekata tipa (klase) Film */   
            foreach($filmovi as $film)
            {
                /* Dohvati sve žanrove konkretnog filma u polje $zanrovi */
                $film_zanrovi = $adminService->getGenresByMovieId($film->id);  /* polje objekata tipa Film_zanr */

                $zanrovi = [];
                foreach($film_zanrovi as $film_zanr)
                {
                    $zanr =  $adminService->getGenreById($film_zanr->zanr_id); /* objekt tipa Zanr */
                    $zanrovi[] = $zanr->naziv;
                }

                /* Dohvati sve glumce konkretnog filma u polje $glumci. */
                $uloge = $adminService->getActorsByMovieId($film->id); /* polje objekata tipa Uloga */

                $glumci = [];
                foreach($uloge as $uloga)
                {
                    $glumac =  $adminService->getActorById($uloga->glumac_id); /* objekt tipa Glumac */
                    $glumci[] = $glumac->ime;
                }

                /* Dodaj u $message['filmovi'] sve podatke o konkretnom filmu. */
                $message['filmovi'][] = ['id' => $film->id, 'naziv' => $film->naziv, 'redatelj' => $film->redatelj, 
                                                                'godina' => $film->godina, 'ocjena' => $film->ocjena, 
                                                                'zanrovi' => $zanrovi, 'glumci' => $glumci];
            }

            /* Pošaljimo u ovom istom odgovoru i aktualni popis imena svih glumaca u bazi. */ 
            $message['svi_glumci'] = [];
            $glumci = $adminService->getAllActors(); /* $glumci je polje objekata tipa (klase) Glumac */
            foreach($glumci as $glumac)
                $message['svi_glumci'][] = $glumac->ime;

            $this->sendJSONandExit($message);
        }

        function dodaj_novi_film()  /* Podaci o filmu doći će iz forme za dodavanje filma. */
        {
            $json = file_get_contents('php://input');

            $data = json_decode($json, true);

            if($data === null)
                $this->sendErrorAndExit('Invalid JSON data');
            
            /* Želimo dodati novi film s podacima poslanim od strane admina. */
            if(!isset($data['naziv']) || !isset($data['redatelj']) || !isset($data['godina']))
                $this->sendErrorAndExit('Neka greška.');  /* Došli smo greškom. */

            $atributi = array('naziv', 'redatelj', 'godina');

            foreach($atributi as $atribut)
                if($data[$atribut] === '') /* Nešto nije uneseno u textbox-ove. */
                    $this->sendErrorAndExit('Potrebno je unijeti sve podatke o filmu.');
                   
            /* Hoćemo li ovdje provjeravati oblik unesenih podataka? */
            /* NAPOMENA. Provjerava se na klijentskoj strani. */

            if(count($data['zanrovi']) === 0)  /* $data['zanrovi'] je polje svih žanrova kojima taj film pripada. */
                $this->sendErrorAndExit('Potrebno je odrediti barem jedan žanr kojemu film pripada.');

            $adminService = new AdminService();

            $film = $adminService->getMovieByName($data['naziv']); 
            if($film !== null)
            {
                /* Taj film već postoji u bazi */
                $this->sendErrorAndExit('Film s nazivom ' . $data['naziv'] . ' već postoji u bazi.');
            }
           
            $podaci = [];
            foreach($atributi as $atribut)
                    $podaci[$atribut] = $data[$atribut];

            $adminService->addMovie($podaci);

            $film = $adminService->getMovieByName($data['naziv']);

            $zanrovi_novog_filma = $data['zanrovi'];
            foreach($zanrovi_novog_filma as $zanr_novog_filma)
            {
                $zanr = $adminService->getGenreByName($zanr_novog_filma); // $zanr je objekt tipa Zanr 
                $adminService->addMovieGenreLink($film->id, $zanr->id);
            }

            if(count($data['glumci']) === 0)
                exit();
  
            $glumci_novog_filma = $data['glumci'];
            foreach($glumci_novog_filma as $glumac_novog_filma)
            {
                $glumac = $adminService->getActorByName($glumac_novog_filma); 
                if($glumac === null)
                {
                    // Taj glumac još ne postoji u bazi --> dodaj ga. 
                    $adminService->addActor($glumac_novog_filma);
                } 

                $glumac = $adminService->getActorByName($glumac_novog_filma); 
                $adminService->addRole($glumac->id, $film->id);
            }

            /* Nakon što se svi novi podaci dodaju u bazu, poslati svakako klijentu aktualni popis, da ne čeka nove izmjene baze. */
            $timestamp = $adminService->getMAXLastModifiedMovies();
            $message = [];
            $message['timestamp'] = $timestamp;
            $this->dohvati_i_posalji_popis($message);
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