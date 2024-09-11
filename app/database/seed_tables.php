<?php

// Popunjavamo tablice u bazi "probnim" podacima.
require_once __DIR__ . '/db.class.php';

seed_table_filmovi();
seed_table_korisnici();
seed_table_glumci();
seed_table_uloge();
seed_table_liste();
seed_table_zanrovi();
seed_table_film_zanr();
seed_table_administratori();
seed_table_komentari_ocjene();

// ------------------------------------------

function seed_table_komentari_ocjene()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO komentari_ocjene(film_id, user_id, komentar, ocjena) VALUES (:film_id, :user_id, :komentar, :ocjena)' );

		$st->execute( array( 'film_id' => '1', 'user_id' => '1', 'komentar' => 'Odlican film', 'ocjena' => 10));
		$st->execute( array( 'film_id' => '2', 'user_id' => '1', 'komentar' => 'Super', 'ocjena' => 9));
		
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_komentari_ocjene): " . $e->getMessage() ); }

	echo "Ubacio komentare i ocjene u tablicu komentari_ocjene.<br />";
}

function seed_table_administratori()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO administratori(admin_ime, admin_prezime, admin_email, admin_lozinka) VALUES (:admin_ime, :admin_prezime, :admin_email, :admin_lozinka)');

		$st->execute( array( 'admin_ime' => 'Doris', 'admin_prezime' => 'Divanovic', 'admin_email' => 'doris.divanovic@gmail.com', 'admin_lozinka' => password_hash( 'dorisinasifra', PASSWORD_DEFAULT )));
		$st->execute( array( 'admin_ime' => 'Ivana', 'admin_prezime' => 'Cirkovic', 'admin_email' => 'ivana.cirkovic@gmail.com', 'admin_lozinka' => password_hash( 'ivaninasifra', PASSWORD_DEFAULT )));
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_administratori): " . $e->getMessage() ); }

	echo "Ubacio admine u tablicu administratori.<br />";
}

function seed_table_korisnici()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO korisnici(korisnik_username, korisnik_ime, korisnik_prezime, korisnik_email, korisnik_lozinka, br_neprimjerenih_kom) VALUES (:korisnik_username, :korisnik_ime, :korisnik_prezime, :korisnik_email, :korisnik_lozinka, :br_neprimjerenih_kom)' );

		$st->execute( array( 'korisnik_username' => 'PerPer', 'korisnik_ime' => 'Pero', 'korisnik_prezime' => 'Perić', 'korisnik_email' => 'pero.peric@gmail.com', 'korisnik_lozinka' => password_hash( 'perinasifra', PASSWORD_DEFAULT ), 'br_neprimjerenih_kom' => '0') );
		$st->execute( array( 'korisnik_username' => 'Majno', 'korisnik_ime' => 'Maja', 'korisnik_prezime' => 'Novak', 'korisnik_email' => 'maja.novak@gmail.com', 'korisnik_lozinka' => password_hash( 'majinasifra', PASSWORD_DEFAULT ), 'br_neprimjerenih_kom' => '0') );
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_korisnici): " . $e->getMessage() ); }

	echo "Ubacio korisnike u tablicu korisnici.<br />";
}


function seed_table_filmovi()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO filmovi(film_naziv, film_godina, film_prosjecna_ocjena, film_redatelj) VALUES (:film_naziv, :film_godina, :film_prosjecna_ocjena, :film_redatelj)' );

		$st->execute( array( 'film_naziv' => 'The Shawshank Redemption', 'film_godina' => 1994 , 'film_prosjecna_ocjena' => 9.3 , 'film_redatelj' => 'Frank Darabont' ) );
		$st->execute( array( 'film_naziv' => 'The Godfather', 'film_godina' => 1972 , 'film_prosjecna_ocjena' => 9.2 , 'film_redatelj' => 'Francis Ford Coppola' ) );
		$st->execute( array( 'film_naziv' => 'The Dark Knight', 'film_godina' => 2008 , 'film_prosjecna_ocjena' => 9.0 , 'film_redatelj' => 'Christopher Nolan' ) );
		$st->execute( array( 'film_naziv' => 'The Godfather Part II', 'film_godina' => 1974 , 'film_prosjecna_ocjena' => 9.0 , 'film_redatelj' => 'Francis Ford Coppola' ) );
		$st->execute( array( 'film_naziv' => '12 Angry Men', 'film_godina' => 1957 , 'film_prosjecna_ocjena' => 9.0 , 'film_redatelj' => 'Sidney Lumet' ) );
		$st->execute( array( 'film_naziv' => 'Schindlers List', 'film_godina' => 1993 , 'film_prosjecna_ocjena' => 9.0 , 'film_redatelj' => 'Steven Spielberg' ) );
		$st->execute( array( 'film_naziv' => 'The Lord of the Rings: The Return of the King', 'film_godina' => 2003 , 'film_prosjecna_ocjena' => 9.0 , 'film_redatelj' => 'Peter Jackson' ) );
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_filmovi): " . $e->getMessage() ); }

	echo "Ubacio filmove u tablicu filmovi.<br />";
}


function seed_table_glumci()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO glumci(glumac_ime) VALUES (:glumac_ime)' );

		$st->execute( array( 'glumac_ime' => 'Tim Robbins') );
		$st->execute( array( 'glumac_ime' => 'Morgan Freeman') );
		$st->execute( array( 'glumac_ime' => 'Bob Gunton') );
		$st->execute( array( 'glumac_ime' => 'Marlon Brando') );
		$st->execute( array( 'glumac_ime' => 'Al Pacino') );
		$st->execute( array( 'glumac_ime' => 'James Caan') );
		$st->execute( array( 'glumac_ime' => 'Christian Bale') );
		$st->execute( array( 'glumac_ime' => 'Heath Ledger') );
		$st->execute( array( 'glumac_ime' => 'Aaron Eckhart') );
		$st->execute( array( 'glumac_ime' => 'Robert de Niro') );
		$st->execute( array( 'glumac_ime' => 'Robert Duvall') );
		$st->execute( array( 'glumac_ime' => 'Henry Fonda') );
		$st->execute( array( 'glumac_ime' => 'Lee J. Cobb') );
		$st->execute( array( 'glumac_ime' => 'Martin Balsam') );
		$st->execute( array( 'glumac_ime' => 'Liam Neeson') );
		$st->execute( array( 'glumac_ime' => 'Ralph Fiennes') );
		$st->execute( array( 'glumac_ime' => 'Ben Kingsley') );
		$st->execute( array( 'glumac_ime' => 'Elijah Wood') );
		$st->execute( array( 'glumac_ime' => 'Viggo Mortensen') );
		$st->execute( array( 'glumac_ime' => 'Ian McKellen') );
		$st->execute( array( 'glumac_ime' => 'John Travolta') );
		$st->execute( array( 'glumac_ime' => 'Uma Thurman') );
		$st->execute( array( 'glumac_ime' => 'Samuel L. Jackson') );

	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_glumci): " . $e->getMessage() ); }

	echo "Ubacio glumce u tablicu glumci.<br />";
}

function seed_table_uloge()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO uloge(glumac_id, film_id) VALUES (:glumac_id, :film_id)' );

		$st->execute( array( 'glumac_id' => '1', 'film_id' => '1'));
		$st->execute( array( 'glumac_id' => '2', 'film_id' => '1'));
		$st->execute( array( 'glumac_id' => '3', 'film_id' => '1'));
		$st->execute( array( 'glumac_id' => '4', 'film_id' => '2'));
		$st->execute( array( 'glumac_id' => '5', 'film_id' => '2'));
		$st->execute( array( 'glumac_id' => '6', 'film_id' => '2'));
		$st->execute( array( 'glumac_id' => '7', 'film_id' => '3'));
		$st->execute( array( 'glumac_id' => '8', 'film_id' => '3'));
		$st->execute( array( 'glumac_id' => '9', 'film_id' => '3'));
		$st->execute( array( 'glumac_id' => '10', 'film_id' => '4'));
		$st->execute( array( 'glumac_id' => '11', 'film_id' => '4'));
		$st->execute( array( 'glumac_id' => '12', 'film_id' => '5'));
		$st->execute( array( 'glumac_id' => '13', 'film_id' => '5'));
		$st->execute( array( 'glumac_id' => '14', 'film_id' => '5'));
		$st->execute( array( 'glumac_id' => '15', 'film_id' => '6'));
		$st->execute( array( 'glumac_id' => '16', 'film_id' => '6'));
		$st->execute( array( 'glumac_id' => '17', 'film_id' => '6'));
		$st->execute( array( 'glumac_id' => '18', 'film_id' => '7'));
		$st->execute( array( 'glumac_id' => '19', 'film_id' => '7'));
		$st->execute( array( 'glumac_id' => '20', 'film_id' => '7'));
		$st->execute( array( 'glumac_id' => '5', 'film_id' => '4'));
		
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_uloge): " . $e->getMessage() ); }

	echo "Ubacio uloge u tablicu uloge.<br />";
}

function seed_table_liste()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO liste(film_id, korisnik_id, pogledao_film) VALUES (:film_id, :korisnik_id, :pogledao_film)' );

		$st->execute( array( 'film_id' => '1', 'korisnik_id' => '1' , 'pogledao_film' => 1));
		$st->execute( array( 'film_id' => '2', 'korisnik_id' => '1' , 'pogledao_film' => 1));
		$st->execute( array( 'film_id' => '3', 'korisnik_id' => '1' , 'pogledao_film' => 0));
		$st->execute( array( 'film_id' => '4', 'korisnik_id' => '1' , 'pogledao_film' => 0));
		$st->execute( array( 'film_id' => '5', 'korisnik_id' => '1' , 'pogledao_film' => 1));
		$st->execute( array( 'film_id' => '3', 'korisnik_id' => '2' , 'pogledao_film' => 1));
		$st->execute( array( 'film_id' => '4', 'korisnik_id' => '2' , 'pogledao_film' => 1));
		$st->execute( array( 'film_id' => '5', 'korisnik_id' => '2' , 'pogledao_film' => 0));
		$st->execute( array( 'film_id' => '6', 'korisnik_id' => '2' , 'pogledao_film' => 0));
		$st->execute( array( 'film_id' => '7', 'korisnik_id' => '2' , 'pogledao_film' => 0));
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_liste): " . $e->getMessage() ); }

	echo "Ubacio liste u tablicu liste.<br />";
}

function seed_table_zanrovi()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO zanrovi(zanr_naziv) VALUES (:zanr_naziv)' );

		$st->execute( array( 'zanr_naziv' => 'Action'));  //1
		$st->execute( array( 'zanr_naziv' => 'Adventure'));   //2
		$st->execute( array( 'zanr_naziv' => 'Animation')); //3
		$st->execute( array( 'zanr_naziv' => 'Biography')); //4
		$st->execute( array( 'zanr_naziv' => 'Crime')); //5
		$st->execute( array( 'zanr_naziv' => 'Documentary')); //6
		$st->execute( array( 'zanr_naziv' => 'Drama')); //7
		$st->execute( array( 'zanr_naziv' => 'Fantasy')); //8
		$st->execute( array( 'zanr_naziv' => 'History')); //9
		$st->execute( array( 'zanr_naziv' => 'Horror')); //10
		$st->execute( array( 'zanr_naziv' => 'Mystery')); //11
		$st->execute( array( 'zanr_naziv' => 'Musical')); //12
		$st->execute( array( 'zanr_naziv' => 'Romance')); //13
		$st->execute( array( 'zanr_naziv' => 'Science Fiction')); //14
		$st->execute( array( 'zanr_naziv' => 'Thriller')); //15
		$st->execute( array( 'zanr_naziv' => 'War')); //16
		$st->execute( array( 'zanr_naziv' => 'Western')); //17
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_zanrovi): " . $e->getMessage() ); }

	echo "Ubacio zanrove u tablicu zanrove.<br />";
}

function seed_table_film_zanr()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO film_zanr(film_id, zanr_id) VALUES (:film_id, :zanr_id)' );

		$st->execute( array( 'film_id' => 1, 'zanr_id' => 7 ));
		$st->execute( array( 'film_id' => 2, 'zanr_id' => 5 ));
		$st->execute( array( 'film_id' => 2, 'zanr_id' => 7 ));
		$st->execute( array( 'film_id' => 3, 'zanr_id' => 1 ));
		$st->execute( array( 'film_id' => 3, 'zanr_id' => 5 ));
		$st->execute( array( 'film_id' => 3, 'zanr_id' => 7 ));
		$st->execute( array( 'film_id' => 4, 'zanr_id' => 5 ));
		$st->execute( array( 'film_id' => 4, 'zanr_id' => 7 ));
		$st->execute( array( 'film_id' => 5, 'zanr_id' => 5 ));
		$st->execute( array( 'film_id' => 5, 'zanr_id' => 7 ));
		$st->execute( array( 'film_id' => 6, 'zanr_id' => 5 ));
		$st->execute( array( 'film_id' => 6, 'zanr_id' => 7 ));
		$st->execute( array( 'film_id' => 6, 'zanr_id' => 9 ));
		$st->execute( array( 'film_id' => 7, 'zanr_id' => 1 ));
		$st->execute( array( 'film_id' => 7, 'zanr_id' => 2 ));
		$st->execute( array( 'film_id' => 7, 'zanr_id' => 7 ));
	}
	catch( PDOException $e ) { exit( "PDO error (seed_table_film_zanr): " . $e->getMessage() ); }

	echo "Ubacio zanrove filmova u tablicu film_zanr.<br />";
}

?>
