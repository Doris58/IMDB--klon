<?php
    require_once __DIR__ . '/../app/database/db.class.php';
    require_once __DIR__ . '/film.class.php';
    require_once __DIR__ . '/film2.class.php';
    require_once __DIR__ . '/korisnik.class.php';
    require_once __DIR__ . '/glumac.class.php';
    require_once __DIR__ . '/uloga.class.php';
    require_once __DIR__ . '/lista.class.php';
    require_once __DIR__ . '/zanr.class.php';
    require_once __DIR__ . '/film_zanr.class.php';
    require_once __DIR__ . '/admin.class.php';
    require_once __DIR__ . '/komentari_ocjene2.class.php';
    require_once __DIR__ . '/filmikom.class.php';

    //require_once __DIR__ . '/../model/korisnikService.class.php';

    class IMDBService
    {
        function getAllMovies($username)  /* ova fja dohvaca sve filmove*/
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                // Dohvati ID korisnika na temelju korisničkog imena
                $st = $db->prepare("SELECT korisnik_id FROM korisnici WHERE korisnik_username = :username");
                $st->execute(array('username' => $username));
                $user = $st->fetch(PDO::FETCH_ASSOC);

                // Provjeri je li korisnik pronađen
                if (!$user) {
                    echo 'Greška - korisnik s tim korisničkim imenom nije pronađen.';
                    exit();
                }
                
                $id_korisnik = $user['korisnik_id'];

                $st = $db->prepare("SELECT filmovi.film_id, filmovi.film_naziv, filmovi.film_redatelj, filmovi.film_godina, 
                    filmovi.film_prosjecna_ocjena, komentari_ocjene.komentar, komentari_ocjene.ocjena
                    FROM filmovi
                    LEFT JOIN komentari_ocjene ON filmovi.film_id = komentari_ocjene.film_id 
                    AND komentari_ocjene.user_id = :id_korisnik");
                    $st->execute([':id_korisnik' => $id_korisnik]);
                            
                $st->execute(); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new FilmIKom($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena'], $row['komentar'], $row['ocjena']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getLatestMovies()  /* ova fja dohvaca najnovijih 5 filmova*/
        {
            $db = DB::getConnection();


            try
            {
                $st = $db->prepare("SELECT * FROM filmovi
                                    ORDER BY film_id DESC
                                    LIMIT 5");
                            
                $st->execute(); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film2($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena'], $row['film_slika']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getMoviesByDirector($redatelj)  /* ova fja dohvaca sve filmove odredenog redatelja*/
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT * FROM filmovi WHERE film_redatelj LIKE :redatelj");
                            
                $st->execute(array('redatelj' => $redatelj)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getMoviesByYear($godina)  /* ova fja dohvaca sve filmove odredenog redatelja*/
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT * FROM filmovi WHERE film_godina LIKE :godina");
                            
                $st->execute(array('godina' => $godina)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getMoviesByGenre($zanr)  /* ova fja dohvaca sve filmove odredenog redatelja*/
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT filmovi.film_id, film_naziv, film_redatelj, film_godina, film_prosjecna_ocjena
                                    FROM filmovi, film_zanr, zanrovi WHERE filmovi.film_id = film_zanr.film_id AND film_zanr.zanr_id = zanrovi.zanr_id AND zanrovi.zanr_naziv LIKE :zanr");
                            
                $st->execute(array('zanr' => $zanr)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getTOP5MoviesByGenre($zanr)  /* ova fja dohvaca sve filmove odredenog redatelja*/
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT DISTINCT filmovi.film_id, film_naziv, film_redatelj, film_godina, film_prosjecna_ocjena, film_slika
                    FROM filmovi
                    JOIN film_zanr ON filmovi.film_id = film_zanr.film_id
                    JOIN zanrovi ON film_zanr.zanr_id = zanrovi.zanr_id
                    WHERE zanrovi.zanr_naziv = :zanr
                    ORDER BY filmovi.film_prosjecna_ocjena DESC
                    LIMIT 5");

                            
                $st->execute(array('zanr' => $zanr)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film2($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena'], $row['film_slika']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getTOP5MoviesByYear($godina) 
        {
            $db = DB::getConnection();
            

            try
            {
                $st = $db->prepare("SELECT filmovi.film_id, film_naziv, film_redatelj, film_godina, film_prosjecna_ocjena, film_slika
                                    FROM filmovi WHERE filmovi.film_godina >= :donja_granica AND filmovi.film_godina < :gornja_granica
                                    ORDER BY filmovi.film_prosjecna_ocjena DESC
                                    LIMIT 5");
                            
                $st->execute(array('donja_granica' => $godina,
                                    'gornja_granica' => $godina + 10)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film2($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena'], $row['film_slika']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getMoviesByActor($glumac)  /* ova fja dohvaca sve filmove odredenog redatelja*/
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT filmovi.film_id, film_naziv, film_redatelj, film_godina, film_prosjecna_ocjena
                                    FROM filmovi, uloge, glumci WHERE filmovi.film_id = uloge.film_id AND uloge.glumac_id = glumci.glumac_id AND glumci.glumac_ime LIKE :glumac");
                            
                $st->execute(array('glumac' => $glumac)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getAllActors()  /* ova fja dohvaca sve glumce */
        {
            $db = DB::getConnection();

            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT * FROM glumci");
                            
                $st->execute(); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška: ' . $e->getMessage();
                exit(); 
            }

            $glumci = [];
            while($row = $st->fetch())
            {
                $glumac = new Glumac($row['gumac_id'], $row['glumac_ime']);
                $glumci[] = $glumac;
            }

            return $glumci;
        }

        function addMovie($podaci)  /* za admina */
        {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            try
            {
                $st = $db->prepare("INSERT INTO filmovi (film_naziv, film_redatelj, film_godina, film_prosjecna_ocjena) VALUES (:naziv, :redatelj, :godina, :ocjena)");

                $podaci['ocjena'] = '0.0';    /* PAZI: string ili broj? */
                $st->execute($podaci);
            }
            catch(PDOException $e) 
            {
                echo 'Greška - pokušaj dodavanja novog filma u bazu: ' . $e->getMessage();
                exit();
            }
        }

        function getGenresOfMovie($id_filma)
        {
            $db = DB::getConnection();

            try
            {
                $st = $db->prepare("SELECT zanrovi.zanr_naziv, zanrovi.zanr_id FROM zanrovi, film_zanr
                                    WHERE zanrovi.zanr_id = film_zanr.zanr_id AND film_zanr.film_id = :id");
                
                /* $st = $db->prepare("SELECT zanrovi.zanr_id, zanrovi.zanr_naziv FROM zanrovi
                            INNER JOIN film_zanr ON zanrovi.zanr_id = film_zanr.zanr_id
                            WHERE film_zanr.film_id = :id");    */              
                $st->execute(array(':id' => $id_filma)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - dohvat podataka o filmu: ' . $e->getMessage();
                exit(); 
            }
           
    
            $zanrovi = [];
            while($row = $st->fetch())  
            {
                $zanrovi[] = new Zanr($row['zanr_id'], $row['zanr_naziv']);
            }

            
            
            return $zanrovi;
        }

        function getActorsInMovie($id_filma)
        {
            $db = DB::getConnection();

            try
            {
                $st = $db->prepare("SELECT glumci.glumac_id, glumci.glumac_ime FROM glumci, uloge 
                                    WHERE glumci.glumac_id = uloge.glumac_id AND uloge.film_id = :id");
                            
                $st->execute(array(':id' => $id_filma)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - dohvat podataka o filmu: ' . $e->getMessage();
                exit(); 
            }
           
    
            $glumci = [];
            while($row = $st->fetch())  
            {
                $glumci[] = new Glumac($row['glumac_id'], $row['glumac_ime']);
            }

            
            
            return $glumci;
        }

        function getMovieByName($naziv_filma)   
        {
            $db = DB::getConnection();

            try
            {
                $st = $db->prepare("SELECT * FROM filmovi WHERE film_naziv LIKE :naziv");
                            
                $st->execute(array('naziv' => $naziv_filma)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - dohvat podataka o filmu: ' . $e->getMessage();
                exit(); 
            }

            /* ako nade, mora vratiti samo jednog, tj. jedan redak? */
            if(($row = $st->fetch()) === false)  
                return null;
    
            $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'],  $row['film_prosjecna_ocjena']);

            return $film;
        }

        function getCommentsAndRatings($naziv_filma)
        {
            /* razmisliti jos o ovome i prepraviti! */

            if(($film = $this->getMovieByName($naziv_filma)) === null)  /* kad se ovo dogodi? */
                exit();
            
            /* getter klase Film */
            $film_id = $film->id;

            $db = DB::getConnection();

            try
            {
                $st = $db->prepare("SELECT korisnik_id, komentar, ocjena FROM lista WHERE film_id LIKE :film_id");
                                
                $st->execute(array('film_id' => $film_id)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - dohvat osvrta na film: ' . $e->getMessage();
                exit(); 
            }

            while(($row = $st->fetch()) === false) 
                $retci[] = $row; // ovo dodaje novi array row na kraj arraya retci

            $korisnikService = new Korisnikservice();
            foreach($retci as $redak)  //svaki $redak je array isti kao sto je gore bio $row
            {
                //umjesto ida korisnika prikazivat cemo username korisnika

                if(($korisnik = $korisnikService->getUserById($redak['korisnik_id'])) === null)  /* kad se ovo dogodi? */
                exit();
             
                unset($redak['korisnik_id']);
                $redak['korisnik_username'] = $korisnik->username; /* getter klase Korisnik */
            }

            return $retci;  /* ovo cemo prikazati adminu u tablici */
        }

        function deleteComment($naziv_filma, $username)
        {
            if(($film = $this->getMovieByName($naziv_filma)) === null)  /* kad se ovo dogodi? */
                exit();
            
            /* getter klase Film */
            $film_id = $film->id;

            $korisnikService = new Korisnikservice();
            if(($korisnik = $korisnikService->getUserByUsername($username)) === null)  /* kad se ovo dogodi? */
                exit();
            
            /* getter klase Korisnik */
            $korisnik_id = $korisnik->id;

            $umjesto_kom = 'Komentar je obrisan jer je bio neprimjeren.';

            try
            {
                $st = $db->prepare("UPDATE lista SET komentar = :umjesto_kom WHERE film_id LIKE :film_id AND korisnik_id LIKE :korisnik_id");
                            
                $st->execute(array('umjesto_kom' => $umjesto_kom, 'film_id' => $film_id, 'korisnik_id' => $korisnik_id)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - pokušaj brisanja neprimjerenog komentara: ' . $e->getMessage();
                exit(); 
            }
        }

        function getMovieById($id_filma)   
        {
            $db = DB::getConnection();

            try
            {
                $st = $db->prepare("SELECT * FROM filmovi WHERE film_id LIKE :id");
                            
                $st->execute(array('id' => $id_filma)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - dohvat podataka o filmu: ' . $e->getMessage();
                exit(); 
            }

            
            if(($row = $st->fetch()) === false)  
                return null;
    
            $film = new Film2($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'],  $row['film_prosjecna_ocjena'], $row['film_slika']);

            return $film;
        }

        function getCommentsAndRatings2($id_filma){
            $db = DB::getConnection();

            try
            {
                $st = $db->prepare("SELECT komentar, ocjena, korisnik_username FROM korisnici, komentari_ocjene 
                                    WHERE korisnici.korisnik_id = komentari_ocjene.user_id AND komentari_ocjene.film_id = :id");
                            
                $st->execute(array('id' => $id_filma)); 
            }
            catch(PDOException $e) 
            {
                echo 'Greška - dohvat podataka o filmu: ' . $e->getMessage();
                exit(); 
            }
           
    
            $komentari_ocjene = [];
            while($row = $st->fetch())  
            {
                $komentari_ocjene[] = new Komentari_ocjene2($row['komentar'], $row['ocjena'], $row['korisnik_username']);
            }

            
            
            return $komentari_ocjene;
            
        }

        function addCommentAndRating($id_film, $ocjena, $komentar, $username) {
            $db = DB::getConnection();

            try {
                // Dohvati ID korisnika na temelju korisničkog imena
                $st = $db->prepare("SELECT korisnik_id FROM korisnici WHERE korisnik_username = :username");
                $st->execute(array('username' => $username));
                $user = $st->fetch(PDO::FETCH_ASSOC);

                // Provjeri je li korisnik pronađen
                if (!$user) {
                    echo 'Greška - korisnik s tim korisničkim imenom nije pronađen.';
                    exit();
                }
                
                $id_korisnik = $user['korisnik_id'];
               // $id_korisnik = 1;
                // Umetni komentar i ocjenu u tablicu komentari_ocjene
                $st = $db->prepare("INSERT INTO komentari_ocjene (film_id, user_id, komentar, ocjena) VALUES (:film_id, :user_id, :komentar, :ocjena)");
                $st->execute(array(
                    'film_id' => $id_film,
                    'user_id' => $id_korisnik,
                    'komentar' => $komentar,
                    'ocjena' => $ocjena
                ));

                'Komentar i ocjena uspješno dodani!';
                $st = $db->prepare("SELECT AVG(ocjena) AS prosjek FROM komentari_ocjene WHERE film_id = :film_id");
                $st->execute(array(
                    'film_id' => $id_film
                ));
                $prosjek = $st->fetch(PDO::FETCH_ASSOC)['prosjek'];

                // Ažuriraj prosječnu ocjenu filma u tablici filmovi
                $st = $db->prepare("UPDATE filmovi SET film_prosjecna_ocjena = :film_prosjecna_ocjena WHERE film_id = :film_id");
                $st->execute(array(
                    'film_id' => $id_film,
                    'film_prosjecna_ocjena' => $prosjek
                ));

            } catch(PDOException $e) {
                echo 'Greška - dodavanje komentara i ocjene: ' . $e->getMessage();
                exit();
            }
        }

        function addOnWatchList($id_film, $username) {
    $db = DB::getConnection();

    try {
        // Dohvati ID korisnika na temelju korisničkog imena
        $st = $db->prepare("SELECT korisnik_id FROM korisnici WHERE korisnik_username = :username");
        $st->execute(array('username' => $username));
        $user = $st->fetch(PDO::FETCH_ASSOC);

        // Provjeri je li korisnik pronađen
        if (!$user) {
            echo 'Greška - korisnik s tim korisničkim imenom nije pronađen.';
            exit();
        }

        $id_korisnik = $user['korisnik_id'];
        
        // Provjeri je li film već na listi korisnika
        $st = $db->prepare("SELECT * FROM liste WHERE korisnik_id = :korisnik_id AND film_id = :id_film");
        $st->execute(array('korisnik_id' => $id_korisnik, 'id_film' => $id_film));
        
        if ($st->rowCount() === 0) {
            $gledao = 0;
            // Umetni film u tablicu liste
            $st = $db->prepare("INSERT INTO liste (film_id, korisnik_id, pogledao_film) VALUES (:film_id, :korisnik_id, :pogledao_film)");
            $st->execute(array(
                'film_id' => $id_film,
                'korisnik_id' => $id_korisnik,
                'pogledao_film' => $gledao
            ));
            echo 'Film je uspješno dodan na listu.';
        } else {
            echo 'Film je već na listi korisnika.';
        }
        
    } catch(PDOException $e) {
        echo 'Greška - dodavanje filma na listu: ' . $e->getMessage();
        exit();
    }
}

        function getWatchList($username, $gledao)
        {
            $db = DB::getConnection();

            try 
            {
                // Dohvati ID korisnika na temelju korisničkog imena
                $st = $db->prepare("SELECT korisnik_id FROM korisnici WHERE korisnik_username = :username");
                $st->execute(array('username' => $username));
                $user = $st->fetch(PDO::FETCH_ASSOC);

                // Provjeri je li korisnik pronađen
                if (!$user) {
                    echo 'Greška - korisnik s tim korisničkim imenom nije pronađen.';
                    exit();
                }
                
                $id_korisnik = $user['korisnik_id'];
                
                
               
                // dohvati listu korisnika
                $st = $db->prepare("SELECT filmovi.film_id, filmovi.film_naziv, filmovi.film_redatelj, filmovi.film_godina, filmovi.film_prosjecna_ocjena 
                                    FROM liste, filmovi WHERE filmovi.film_id = liste.film_id AND liste.korisnik_id = :id_korisnik AND liste.pogledao_film = :pogledao");

                $st->execute(array(
                    'id_korisnik' => $id_korisnik,
                    'pogledao' => $gledao
                ));
                $filmovi = [];
                while($row = $st->fetch())
                {
                    $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena']);
                    $filmovi[] = $film;
                }

                return $filmovi;

                
            } catch(PDOException $e) {
                echo 'Greška - dodavanje komentara i ocjene: ' . $e->getMessage();
                exit();
            }
        }

        function NijePogledao($username, $idFilm) {
            $db = DB::getConnection();

            try {
                // Dohvati ID korisnika na temelju korisničkog imena
                $st = $db->prepare("SELECT korisnik_id FROM korisnici WHERE korisnik_username = :username");
                $st->execute(array('username' => $username));
                $user = $st->fetch(PDO::FETCH_ASSOC);

                // Provjeri je li korisnik pronađen
                if (!$user) {
                    echo 'Greška - korisnik s tim korisničkim imenom nije pronađen.';
                    exit();
                }
                
                $id_korisnik = $user['korisnik_id'];
                
                // Umetni komentar i ocjenu u tablicu komentari_ocjene
                $st = $db->prepare("UPDATE liste SET pogledao_film = 0 WHERE film_id = :film_id AND korisnik_id = :korisnik_id");
                $st->execute(array(
                    'film_id' => $idFilm,
                    'korisnik_id' => $id_korisnik,
                    
                    
                ));

                
            } catch(PDOException $e) {
                echo 'Greška - dodavanje komentara i ocjene: ' . $e->getMessage();
                exit();
            }
        }

        function Pogledao($username, $idFilm) {
            $db = DB::getConnection();

            try {
                // Dohvati ID korisnika na temelju korisničkog imena
                $st = $db->prepare("SELECT korisnik_id FROM korisnici WHERE korisnik_username = :username");
                $st->execute(array('username' => $username));
                $user = $st->fetch(PDO::FETCH_ASSOC);

                // Provjeri je li korisnik pronađen
                if (!$user) {
                    echo 'Greška - korisnik s tim korisničkim imenom nije pronađen.';
                    exit();
                }
                
                $id_korisnik = $user['korisnik_id'];
                
                // Umetni komentar i ocjenu u tablicu komentari_ocjene
                $st = $db->prepare("UPDATE liste SET pogledao_film = 1 WHERE film_id = :film_id AND korisnik_id = :korisnik_id");
                $st->execute(array(
                    'film_id' => $idFilm,
                    'korisnik_id' => $id_korisnik,
                    
                    
                ));

                
            } catch(PDOException $e) {
                echo 'Greška - dodavanje komentara i ocjene: ' . $e->getMessage();
                exit();
            }
        }
        
    }

?>