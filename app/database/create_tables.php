<?php

// Stvaramo tablice u bazi (ako već ne postoje od ranije).
require_once __DIR__ . '/db.class.php';

create_table_filmovi();
create_table_korisnici();
create_table_glumci();
create_table_uloge();
create_table_liste();
create_table_zanrovi();
create_table_film_zanr();
create_table_administratori();
create_table_komentari_ocjene();

// ------------------------------------------

// ------------------------------------------

function create_table_komentari_ocjene()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS komentari_ocjene (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'film_id int NOT NULL,' .
			'user_id int NOT NULL,' .
			'komentar VARCHAR(300),' .
			'ocjena int,' .
			'komentari_ocjene_lastmodified timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_komentari_ocjene): " . $e->getMessage() ); }

	echo "Napravio tablicu komentari_ocjene.<br />";
}

function create_table_administratori()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS administratori (' .
			'admin_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'admin_ime varchar(10) NOT NULL,' .
			'admin_prezime varchar(10) NOT NULL,' .
			'admin_email varchar(30) NOT NULL,' .
			'admin_lozinka varchar(255) NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_administratori): " . $e->getMessage() ); }

	echo "Napravio tablicu administratori.<br />";
}

function create_table_filmovi()
{
	$db = DB::getConnection();

	// Stvaramo tablicu filmovi.
	// Svaki film ima svoj id (automatski će se povećati za svakog novoubačenog korisnika), naziv,  godinu, prosjecnu ocjenu i redatelja.
	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS filmovi(' .
			'film_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'film_naziv varchar(50) NOT NULL,' .
			'film_redatelj varchar(50),' .
			'film_godina year,' .
			'film_prosjecna_ocjena decimal,' .
			'film_lastmodified timestamp on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_filmovi): " . $e->getMessage() ); }

	echo "Napravio tablicu filmovi.<br />";
}

function create_table_korisnici()
{
	$db = DB::getConnection();

	// Stvaramo tablicu korisnici.
	
	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS korisnici (' .
			'korisnik_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'korisnik_username varchar(10) NOT NULL,' .
			'korisnik_ime varchar(10) NOT NULL,' .
			'korisnik_prezime varchar(10) NOT NULL,' .
			'korisnik_email varchar(30) NOT NULL,' .
			'korisnik_lozinka varchar(255) NOT NULL,' .
			'br_neprimjerenih_kom int)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_korisnici): " . $e->getMessage() ); }

	echo "Napravio tablicu korisnici.<br />";
}


function create_table_glumci()
{
	$db = DB::getConnection();

	// Stvaramo tablicu glumci.

	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS glumci (' .
			'glumac_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'glumac_ime varchar(30) NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_glumci): " . $e->getMessage() ); }

	echo "Napravio tablicu glumci.<br />";
}

function create_table_uloge()
{
	$db = DB::getConnection();

	// Stvaramo tablicu uloge.
	
	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS uloge (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'glumac_id int NOT NULL,' .
			'film_id int NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_uloge): " . $e->getMessage() ); }

	echo "Napravio tablicu uloge.<br />";
}


function create_table_liste()
{
	$db = DB::getConnection();

	// Stvaramo tablicu liste.
	
	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS liste (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'film_id int NOT NULL,' .
			'korisnik_id int NOT NULL,' .
			'pogledao_film BOOLEAN)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_liste): " . $e->getMessage() ); }

	echo "Napravio tablicu liste.<br />";
}

function create_table_zanrovi()
{
	$db = DB::getConnection();

	// Stvaramo tablicu zanrovi.
	
	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS zanrovi (' .
			'zanr_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'zanr_naziv VARCHAR(20) NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_zanrovi): " . $e->getMessage() ); }

	echo "Napravio tablicu zanrovi.<br />";
}

function create_table_film_zanr()
{
	$db = DB::getConnection();

	// Stvaramo tablicu film_zanr.
	
	try
	{
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS film_zanr (' .
			'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
			'film_id int NOT NULL,' .
			'zanr_id int NOT NULL)'
		);

		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error (create_table_film_zanr): " . $e->getMessage() ); }

	echo "Napravio tablicu film_zanr.<br />";
}



?>
