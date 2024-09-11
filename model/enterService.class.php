<?php
    require_once __DIR__ . '/../app/database/db.class.php';
    require_once __DIR__ . '/korisnik.class.php'; 
    require_once __DIR__ . '/admin.class.php'; 

    class EnterService
    {
        function getUserByUsername($username)  
        {
            $db = DB::getConnection();
            
            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /* ovo je vec postavljeno u fji getConn() u klasi DB */
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  /* ovo ne znam je li uopce potrebno */

            try
            {
                $st = $db->prepare("SELECT * FROM korisnici WHERE korisnik_username LIKE :username");                 
                $st->execute(array('username' => $username)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  /* ako nije nasao nijednog, ne vraca redak kao array, nego vraca false */
                return null;
    
            $korisnik = new Korisnik($row['korisnik_id'], $row['korisnik_username'], $row['korisnik_ime'], $row['korisnik_prezime'],
                                    $row['korisnik_email'], $row['korisnik_lozinka'], $row['br_neprimjerenih_kom']);

            return $korisnik;
        }

        function createUser($podaci)
        {
            $db = DB::getConnection();
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            try
            {
                $st = $db->prepare("INSERT INTO korisnici(korisnik_username, korisnik_ime, korisnik_prezime, korisnik_email, korisnik_lozinka, br_neprimjerenih_kom) VALUES (:username, :ime, :prezime, :email, :password, :neprimjereni_kom)");

                /* dodajem broj neprimjerenih komentara (na pocetku 0), jer on nije u podacima iz forme */
                $podaci['neprimjereni_kom'] = '0';
                $st->execute($podaci);
            }
            catch(PDOException $e){ exit($e->getMessage()); }
        }

        function getAdminByUsername($username) 
        {
            $db = DB::getConnection();
            try
            {
                $st = $db->prepare("SELECT * FROM administratori WHERE admin_ime LIKE :username");                 
                $st->execute(array('username' => $username)); 
            }
            catch(PDOException $e){ exit($e->getMessage()); }

            if(($row = $st->fetch()) === false)  /* ako nije nasao nijednog, ne vraca redak kao array, nego vraca false */
                return null;
    
            $administrator = new Admin($row['admin_id'], $row['admin_ime'], $row['admin_prezime'],
                                    $row['admin_email'], $row['admin_lozinka']);

            return $administrator;
        }    
    }

?>