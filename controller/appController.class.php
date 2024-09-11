<?php
    /* kontroler za uporabu aplikacije od strane korisnika */
    
    require_once __DIR__ . '/../model/IMDBService.class.php';

    class AppController
    {
        function prikazi_naslovnu()
        {
            session_start();

            /* ovo je samo test */

            //echo 'Dobrodošao, ' . $_SESSION['username'] . '!';

            $ime = $_SESSION['username'];  /* $ime cemo kor. dalje u kodu iz donjeg view-a */

            require_once __DIR__ . '/../view/naslovna.php';

            // ...
        }

        function prikazi_tablicu_filmova()
        {

            session_start();
            $ime = $_SESSION['username'];
            $pf = new IMDBService;
            switch($_POST['filter'])
            {
                case 'svi':                 
                    $popisFilmova = $pf->getAllMovies($ime);                    
                    break;

                case 'zanr':
                    $popisFilmova = $pf->getMoviesByGenre($_POST['naziv'], $ime);
                    break;

                case 'godina':
                    $popisFilmova = $pf->getMoviesByYear($_POST['naziv'], $ime);
                    break;

                case 'redatelj':
                    $popisFilmova = $pf->getMoviesByDirector($_POST['naziv'], $ime);
                    break;

                case 'glumac':
                    $popisFilmova = $pf->getMoviesByActor($_POST['naziv'], $ime);
                    break;
           }
         
            require_once __DIR__ . '/../view/popis_filmova_index.php';
        }

        function profil_filma()
        {
            require_once __DIR__ . '/../view/film_profil.php';
            
            
            
        }

       /* function prikazi_profil_filma()
        {
            if(isset($_POST['ocjena']) && isset($_POST['komentar']) && isset($_POST['film'])){
                $this->ocijeni_film();
            }
            
            $this->dohvati_profil_filma();
        }*/

        function dodaj_na_listu()
        {
            session_start();
            
            
            if (isset($_POST['id'])) {
                $filmId = $_POST['id'];
                $username = $_SESSION['username'];
               
                $pf = new IMDBService();
                $pf->addOnWatchList($filmId, $username);
            }
                
                $this->prikazi_profil_filma();


        }

        function posalji_ocjenu_komentar()
        {
            session_start();
            
            if(isset($_POST['ocjena']) && isset($_POST['komentar']) && isset($_POST['id'])){
                $filmId = $_POST['id'];
                $ocjena = $_POST['ocjena'];
                $komentar = $_POST['komentar'];
                $username = $_SESSION['username'];
               
                $pf = new IMDBService();
                $pf->addCommentAndRating($filmId, $ocjena, $komentar, $username);
            }
                
                $this->prikazi_profil_filma();
            
        }

        function prikazi_profil_filma()
        {
           
            if (isset($_POST['id'])) {
               $filmId = $_POST['id'];
            
                $pf = new IMDBService();
                $film = $pf->getMovieById($filmId);
                
                $komentari = $pf->getCommentsAndRatings2($filmId); 
                $glumci = $pf->getActorsInMovie($filmId);
                $zanrovi = $pf->getGenresOfMovie($filmId);
                
                $message = [];
                $message['film'] = [
                    'naziv' => $film->naziv, 
                    'redatelj' => $film->redatelj, 
                    'godina' => $film->godina, 
                    'ocjena' => $film->ocjena,
                    'slika' => $film->slika
                ];
                
                $message['komentari']=[];
                foreach($komentari as $komentar){
                 $message['komentari'][] = 
                 ['komentar' => $komentar->komentar, 
                 'ocjena' => $komentar->ocjena, 
                 'username' => $komentar->korisnik_username
                 ];
                }

                $message['glumci']=[];
                foreach($glumci as $glumac){
                 $message['glumci'][] = 
                 ['ime' => $glumac->ime
                 ];
                }

                $message['zanrovi']=[];
                foreach($zanrovi as $zanr){
                 $message['zanrovi'][] = 
                 ['naziv' => $zanr->naziv
                 ];
                }
            
                $this->sendJSONandExit($message);
            }
            
        }

        



        function dohvati_listu_korisnika()
        {
            session_start();

            $message = [];

            if (isset($_POST['zahtjev']) && $_POST['zahtjev']==='vrati_na_listu' ){
                $username = $_SESSION['username'];
                $idFilm = $_POST['id'];
                $pf = new IMDBService();
                $pf->NijePogledao($username, $idFilm);
                
            }

            if (isset($_POST['zahtjev']) && $_POST['zahtjev']==='makni_s_liste' ){
                $username = $_SESSION['username'];
                $idFilm = $_POST['id'];
                $pf = new IMDBService();
                $pf->Pogledao($username, $idFilm);
          
            }

      
                $username = $_SESSION['username'];
                $pf = new IMDBService();
                $pogledao = 0; // prvo dohvacamo filmove s liste koji nisu pogledani
                $lista = $pf->getWatchList($username, $pogledao);  
                $pogledao = 1; // onda dohvacamo one koji su pogledani
                $lista_pogledanih = $pf->getWatchList($username, $pogledao);
                                
                $message['lista']=[];
                foreach($lista as $film){
                 $message['lista'][] = 
                 ['id' => $film->id,
                  'naziv' => $film->naziv, 
                 'redatelj' => $film->redatelj, 
                 'godina' => $film->godina,
                 'ocjena' => $film->ocjena
                 ];
                }  
                
                $message['lista_pogledanih']=[];
                foreach($lista_pogledanih as $film){
                 $message['lista_pogledanih'][] = 
                 ['id' => $film->id,
                  'naziv' => $film->naziv, 
                 'redatelj' => $film->redatelj, 
                 'godina' => $film->godina,
                 'ocjena' => $film->ocjena
                 ];
                }
                $this->sendJSONandExit($message);
             
        }

        function dohvatiTop5()
        {

            $message = [];

            if(isset($_POST['zahtjev']) && $_POST['zahtjev']==='najnoviji'){
                
            
                $pf = new IMDBService();
                $najnoviji = $pf->getLatestMovies();
                
                $message['najnoviji'] = [];
                foreach($najnoviji as $film){
                $message['najnoviji'][] = 
                 ['id' => $film->id,
                  'naziv' => $film->naziv, 
                 'redatelj' => $film->redatelj, 
                 'godina' => $film->godina,
                 'ocjena' => $film->ocjena,
                 'slika'  =>$film->slika
                 ];
            } 

            }
            if(isset($_POST['zanr'])){
                 
                $zanr = $_POST['zanr'];
                $message['zanr'] = $zanr;
                $pf = new IMDBService();
                $top5zanr = $pf->getTOP5MoviesByGenre($zanr);
                $message['top5zanr'] = [];
                foreach($top5zanr as $film){
                $message['top5zanr'][] = 
                 ['id' => $film->id,
                  'naziv' => $film->naziv, 
                 'redatelj' => $film->redatelj, 
                 'godina' => $film->godina,
                 'ocjena' => $film->ocjena,
                 'slika'  =>$film->slika
                 ];
                } 

            } 

            if(isset($_POST['godina'])){
                $godina = $_POST['godina'];
                $message['godina'] = $godina;
                $pf = new IMDBService();
                $top5godina = $pf->getTOP5MoviesByYear($godina);
                $message['top5godina'] = [];
                foreach($top5godina as $film){
                $message['top5godina'][] = 
                 ['id' => $film->id,
                  'naziv' => $film->naziv, 
                 'redatelj' => $film->redatelj, 
                 'godina' => $film->godina,
                 'ocjena' => $film->ocjena,
                 'slika'  =>$film->slika
                 ];
                } 
            }
            $this->sendJSONandExit($message);
        }

        function prikazi_profil_korisnika()
        {
            require_once __DIR__ . '/../view/korisnickiProfil.php';
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
