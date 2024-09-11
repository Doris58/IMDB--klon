<?php
    require_once __DIR__ . '/../app/database/db.class.php';
    require_once __DIR__ . '/film.class.php';
    require_once __DIR__ . '/korisnik.class.php';
    require_once __DIR__ . '/glumac.class.php';
    require_once __DIR__ . '/uloga.class.php';
    require_once __DIR__ . '/lista.class.php';
    require_once __DIR__ . '/zanr.class.php';
    require_once __DIR__ . '/film_zanr.class.php';
    require_once __DIR__ . '/admin.class.php';

    /* PAZI --- NOVA KLASA */
    require_once __DIR__ . '/komentar_ocjena.class.php';

    class adminService
    {
        /* --- osnovni SQL upiti potrebni za view adminNaslovna, odnosno adminNaslovnaController  ------ */

        function getAllMovies() 
        {
            $db = DB::getConnection();      
            try
            {
                $st = $db->prepare("SELECT * FROM filmovi");              
                $st->execute(); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
          
            $filmovi = [];
            while($row = $st->fetch())
            {
                $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'], $row['film_prosjecna_ocjena']);
                $filmovi[] = $film;
            }

            return $filmovi;
        }

        function getAllGenres()
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM zanrovi");              
                $st->execute(); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
          
            $zanrovi = [];
            while($row = $st->fetch())
            {
                $zanr = new Zanr($row['zanr_id'], $row['zanr_naziv']);
                $zanrovi[] = $zanr;
            }

            return $zanrovi;
        }

        function getAllActors()
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM glumci");              
                $st->execute(); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
          
            $glumci = [];
            while($row = $st->fetch())
            {
                $glumac = new Glumac($row['glumac_id'], $row['glumac_ime']);
                $glumci[] = $glumac;
            }

            return $glumci;
        }

        function getMAXLastModifiedMovies()
        {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            try
            {
                $st = $db->prepare("SELECT MAX(film_lastmodified) AS maxfilmlastmodified FROM filmovi");
                $st->execute();
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $row = $st->fetch();
    
            $timestamp = strtotime($row['maxfilmlastmodified']);

            return $timestamp;
        }

        function getGenresByMovieId($film_id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM film_zanr WHERE film_id LIKE :film_id");                  
                $st->execute(array('film_id' => $film_id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $film_zanrovi = [];
            while($row = $st->fetch())
            { 
                $film_zanr = new Film_zanr($row['id'], $row['film_id'], $row['zanr_id']);
                $film_zanrovi[] = $film_zanr;
            }

            return $film_zanrovi;
        }

        function getGenreById($id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM zanrovi WHERE zanr_id LIKE :id");             
                $st->execute(array('id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $row = $st->fetch();
    
            $zanr = new Zanr($row['zanr_id'], $row['zanr_naziv']);

            return $zanr;
        }

        function getActorsByMovieId($film_id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM uloge WHERE film_id LIKE :film_id");                  
                $st->execute(array('film_id' => $film_id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $uloge = [];
            while($row = $st->fetch())
            { 
                $uloga = new Uloga($row['id'], $row['glumac_id'], $row['film_id']);
                $uloge[] = $uloga;
            }

            return $uloge;
        }

        function getActorById($id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM glumci WHERE glumac_id LIKE :id");             
                $st->execute(array('id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $row = $st->fetch();
    
            $glumac = new Glumac($row['glumac_id'], $row['glumac_ime']);

            return $glumac;
        }

        function getMovieByName($naziv)   /* Ovo je (zasad) samo radi provjere postoji li film s istim nazivom već u bazi, prije nego što dodamo novi. */
        {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            try
            {
                $st = $db->prepare("SELECT * FROM filmovi WHERE film_naziv LIKE :naziv");             
                $st->execute(array('naziv' => $naziv)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  
                return null;
    
            $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'],  $row['film_prosjecna_ocjena']);

            return $film;
        }

        function addMovie($naziv, $redatelj, $godina) 
        {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            try
            {
                $st = $db->prepare("INSERT INTO filmovi (film_naziv, film_redatelj, film_godina, film_prosjecna_ocjena, film_slika) VALUES (:naziv, :redatelj, :godina, :ocjena, :slika)");
                $ocjena = '0.0';    /* PAZI: string ili broj? */
                $slika = 'uploads/nophoto.jpg'; 
                $st->execute(array('naziv' => $naziv, 'redatelj' => $redatelj, 'godina' => $godina, 'ocjena' => $ocjena, 'slika' => $slika));
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function getGenreByName($naziv)  
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM zanrovi WHERE zanr_naziv LIKE :naziv");             
                $st->execute(array('naziv' => $naziv)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  
                return null;
    
            $zanr = new Zanr($row['zanr_id'], $row['zanr_naziv']);

            return $zanr;
        }
  
        /* NAPOMENA. Trenutno je odlučeno da je popis žanrova u bazi fiksan. */
        function addGenre($naziv)  
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("INSERT INTO zanrovi (zanr_naziv) VALUES (:naziv)");
                $st->execute(array('naziv' => $naziv)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function addMovieGenreLink($film_id, $zanr_id)   
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("INSERT INTO film_zanr (film_id, zanr_id) VALUES (:film_id, :zanr_id)");
                $st->execute(array('film_id' => $film_id,'zanr_id' => $zanr_id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function getActorByName($ime) 
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM glumci WHERE glumac_ime LIKE :ime");             
                $st->execute(array('ime' => $ime)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  
                return null;
    
            $glumac = new Glumac($row['glumac_id'], $row['glumac_ime']);

            return $glumac;
        }

        function addActor($ime)  
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("INSERT INTO glumci (glumac_ime) VALUES (:ime)");
                $st->execute(array('ime' => $ime)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function addRole($glumac_id, $film_id)     
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("INSERT INTO uloge (glumac_id, film_id) VALUES (:glumac_id, :film_id)");
                $st->execute(array('glumac_id' => $glumac_id, 'film_id' => $film_id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        /* ----- osnovni SQL upiti potrebni za view adminOsvrti, odnosno adminOsvrtiController  ---------------------------- */

        function getMovieById($id) 
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM filmovi WHERE film_id LIKE :id");                     
                $st->execute(array('id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  
                return null;
    
            $film = new Film($row['film_id'], $row['film_naziv'], $row['film_redatelj'], $row['film_godina'],  $row['film_prosjecna_ocjena']);

            return $film;
        }

        function getMAXLastModifiedCommentsAndRatings()
        {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            try
            {
                $st = $db->prepare("SELECT MAX(komentari_ocjene_lastmodified) AS maxcrlastmodified FROM komentari_ocjene");
                $st->execute();
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)
                return null;
    
            $timestamp = strtotime($row['maxcrlastmodified']);

            return $timestamp;
        }

        function getCommentsAndRatingsByMovieId($film_id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM komentari_ocjene WHERE film_id LIKE :film_id");                  
                $st->execute(array('film_id' => $film_id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $komentari_ocjene = [];
            while($row = $st->fetch())
            { 
                /* NAPOMENA. u tablici komentari_ocjene u bazi atribut je user_id, a ne korisnik_id */
                $komentar_ocjena = new Komentar_ocjena($row['id'], $row['film_id'], $row['user_id'], $row['komentar'], $row['ocjena']);
                $komentari_ocjene[] = $komentar_ocjena;
            }

            return $komentari_ocjene;
        }

        function getUserById($id) 
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM korisnici WHERE korisnik_id LIKE :id");              
                $st->execute(array('id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  
                return null;
    
            $korisnik = new Korisnik($row['korisnik_id'], $row['korisnik_username'], $row['korisnik_ime'], $row['korisnik_prezime'],
                                    $row['korisnik_email'], $row['korisnik_lozinka'], $row['br_neprimjerenih_kom']);

            return $korisnik;
        }

        function updateCommentById($id, $novi_komentar)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("UPDATE komentari_ocjene SET komentar = :novi_komentar WHERE id LIKE :id");               
                $st->execute(array('novi_komentar' => $novi_komentar, 'id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function getCommentAndRatingById($id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM komentari_ocjene WHERE id LIKE :id");                  
                $st->execute(array('id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            $row = $st->fetch();

            /* NAPOMENA. u tablici komentari_ocjene u bazi atribut je user_id, a ne korisnik_id */
            $komentar_ocjena = new Komentar_ocjena($row['id'], $row['film_id'], $row['user_id'], $row['komentar'], $row['ocjena']);
           
            return $komentar_ocjena;
        }

        function updateBadCommentsById($id, $broj)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("UPDATE korisnici SET br_neprimjerenih_kom = :broj WHERE korisnik_id LIKE :id");
                $st->execute(array('broj' => $broj, 'id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function deleteUserById($id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("DELETE FROM korisnici WHERE korisnik_id LIKE :id");                  
                $st->execute(array('id' => $id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        /* NAPOMENA. Kad brišemo korisnika, brišemo li i njegovu listu filmova iz baze? */
        /* AKO DA, ONDA POZIVAMO SLJEDEĆU METODU */
        function deleteListByUserId($korisnik_id)
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("DELETE FROM liste WHERE korisnik_id LIKE :korisnik_id");                  
                $st->execute(array('korisnik_id' => $korisnik_id)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        /* NAPOMENA. Kad brišemo korisnika, njegove ocjene i komentare ne brišemo. */
    }

?>