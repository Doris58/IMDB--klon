<?php
    require_once __DIR__ . '/../model/enterService.class.php';

    class EnterController
    {
        function iscrtaj_ulaznu_formu($poruka)
        {
            /* $poruka se sada 'vidi' u donjem view-u */
            /* Samo iscrtava view s ulaznom formom. - nazvala sam ga ulaz.php */
            require_once __DIR__ . '/../view/ulaz.php';
        }

        function iscrtaj_formu_za_registraciju($poruka)
        {
            /* Samo iscrtava view s formom za reg. - nazvala sam ga registracija.php */
            require_once __DIR__ . '/../view/registracija.php';
        }

        function obradi_ulaznu_formu()
        {
            if(isset($_POST['btn_user_log_in']))
            {
                $this->prijavi_osobu('korisnik');
                return;
            }

            if(isset($_POST['btn_admin_log_in']))
            {
                $this->prijavi_osobu('administrator');
                return;
            }

            if(isset($_POST['btn_registration']))
            {
                /* korisnik je kliknuo da zeli registraciju */
                $this->iscrtaj_formu_za_registraciju('');
                return;
            }        
        }    
           
        function prijavi_osobu($uloga)
        {
            if($_POST['username'] === '' || $_POST['password'] === '')
            {
                /* osoba nije unijela sve potrebne podatke */
                /* ispisi poruku uz login formu i prikazi je */
                $this->iscrtaj_ulaznu_formu('Molimo, unesite sve potrebne podatke.');
                exit();
            }

            /* osoba je poslala podatke za prijavu (log in) */
            /* hocemo li za login provjeravati ima li username npr. od 2 do 20 slova? - to bi islo tu */

            $enterService = new EnterService();

            if($uloga === 'korisnik')
                $osoba = $enterService->getUserByUsername($_POST['username']);

            if($uloga === 'administrator')
                $osoba = $enterService->getAdminByUsername($_POST['username']); 
            
            if($osoba === null)
            {
                /* taj username ne postoji u bazi */
                /* ispisi poruku uz login formu i prikazi ju */
                $this->iscrtaj_ulaznu_formu('Uneseni username nije u bazi.');
                exit();
            }
           
            /* $osoba->lozinka je poziv gettera iz klase Korisnik ili Admin */
            if(password_verify($_POST['password'], $osoba->lozinka))
            {
                /* Login je uspio. --> spremamo tu "osobu" u session dok se ne odjavi */ 
                session_start();
                $_SESSION['username'] = $_POST['username'];  

                /* mozemo u session spremiti i npr. ime i prezime trenutno ulogirane osobe */
                    
                /* Preusmjeri osobu na odgovarajucu naslovnicu aplikacije s obzirom na ulogu. */
                if($uloga === 'korisnik')
                    header('Location: index.php?rt=korisnikNaslovna'); 
                if($uloga === 'administrator')
                    header('Location: index.php?rt=adminNaslovna'); 
            }
            else
            {
                /* Unesen je neispravni password. */
                /* ispisi poruku uz login formu i prikazi ju */
                $this->iscrtaj_ulaznu_formu('Uneseni password nije ispravan.');
            }
        }

        function registriraj_korisnika()
        {
            $atributi = array('ime', 'prezime', 'email', 'username', 'password');

            foreach($atributi as $atribut)
                if($_POST[$atribut] === '') /* nesto nije uneseno u fromu */
                {
                    $this->iscrtaj_formu_za_registraciju('Molimo, unesite sve potrebne podatke.');
                    exit(); 
                }

            /* Hocemo li ovdje provjeravati duljinu usernamea, imena i prezimena, oblik email adrese? */

            $enterService = new EnterService();

            $korisnik = $enterService->getUserByUsername($_POST['username']); 
            if($korisnik !== null)
            {
                /* taj korisnik vec postoji u bazi */
                /* ispisi poruku uz login formu i prikazi ju */
                $this->iscrtaj_ulaznu_formu('Već ste registrirani. Prijavite se.');
                exit();
            }
           
            /* poslat cemo sve podatke dobivene POST-om u polju podaci serviceu */
            /* moramo hashirati password i to poslati! */
            $podaci = [];
            foreach($atributi as $atribut)
            {
                if($atribut === 'password')
                    $podaci[$atribut] = password_hash($_POST[$atribut], PASSWORD_DEFAULT);
                else
                    $podaci[$atribut] = $_POST[$atribut];
            }
                    
            $enterService->createUser($podaci);

            /* Registracija je uspjela. --> spremamo tog "korisnika" u session dok se ne odjavi */ 
            session_start();
            $_SESSION['username'] = $_POST['username']; 

            /* mozemo u session spremiti i npr. ime i prezime trenutno ulogiranog korisnika */
                
            /* Preusmjeri korisnika na naslovnicu aplikacije. */
            header('Location: index.php?rt=korisnikNaslovna'); 
        }

        function odjavi_korisnika()
        {
            session_unset();
            session_destroy();

            /* Preusmjeri na ulaz aplikacije. */
            header('Location: index.php');
            /*NAPOMENA. Ne ovdje $this->iscrtaj_ulaznu_formu(''); jer bi ostala gore u url-u ruta rt=odjava? */
        }
    }

?>