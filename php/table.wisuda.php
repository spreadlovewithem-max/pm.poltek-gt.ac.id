<?php

/*
 * Editor server script for DB table wisuda
 * Created by http://editor.datatables.net/generator
 */

// DataTables PHP library and database connection
include( "lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// The following statement can be removed after the first run (i.e. the database
// table has been created). It is a good idea to do this to help improve
// performance.
$db->sql( "CREATE TABLE IF NOT EXISTS `wisuda` (
	`id` int(10) NOT NULL auto_increment,
	`seksi` varchar(255),
	`job` varchar(255),
	`pic` varchar(255),
	`persentase` varchar(255),
	`ket` TEXT,
	PRIMARY KEY( `id` )
);" );

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'wisuda', 'id' )
	->fields(
		Field::inst( 'seksi' ),
		Field::inst( 'job' ),
		Field::inst( 'pic' ),
		Field::inst('persentase')
			->setFormatter(function ($val, $data, $opts) {
				// Remove percentage symbol and trim spaces
				return str_replace('%', '', trim($val));
			})
			->validator(Validate::numeric(".", ValidateOptions::inst()->message("Data yang dimasukkan bukan angka")))
			->validator(Validate::minMaxNum(0, 100, ".",  ValidateOptions::inst()->message("Data yang dimasukkan harus 0-100"))),
		Field::inst('ket')
	
	)
	->process( $_POST )
	->json();
