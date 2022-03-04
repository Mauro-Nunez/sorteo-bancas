<?php
/**
 * Created by PhpStorm.
 * User: matias
 * Date: 03/09/18
 * Time: 11:59
 */


?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Script para el sorteo de bancas">
    <meta name="author" content="HCD Posadas">
    <link rel="icon" href="favicon.png">

    <title>Sorteo de Bancas</title>

    <!-- Bootstrap core CSS -->
    <!--    <link href="http://localhost/sorteo-bancas/dist/bundle.js" rel="stylesheet">-->

    <script src="./dist/bundle.js"></script>

    <style>
        /*borrar despues de usar el ledsote*/
        body {
            font-size: 1.2rem;
            font-weight: 900;
        }

        .text-muted {
            color: #000 !important;
        }
    </style>

</head>

<body class="bg-light">

<div class="container">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="./logo.png" alt="logo">
        <h2>Sorteo de Bancas</h2>
        <p class="lead">Para el Parlamento Estudiantil Inclusivo
        </p>
        <p>
            (si desea subir una planilla este es el formato válido )<br>
            <a href="example.xlsx" class="btn btn-primary">
                <i class="fa fa-file-excel-o"></i> Descargar Ejemplo</a>
        </p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form method="post" name="sorteo" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="archivo">Archivo</label>
                            <div class="custom-file">
                                <input type="file" name="archivo" class="custom-file-input" id="archivo" lang="es">
                                <label class="custom-file-label" for="archivo">Seleccionar Archivo</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input required type="number" name="total" class="form-control" id="total"
                                   aria-describedby="total"
                                   value="<?php ( isset( $_POST['total'] ) ) ? print $_POST['total'] : print '0' ?>">
                            <small id="totalHelpBlock" class="form-text text-muted">
                                Si se sube la planilla este valor será ignorado.
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="titulares">Titulares</label>
                            <input required type="number" name="titulares" class="form-control" id="titulares"
                                   value="<?php ( isset( $_POST['titulares'] ) ) ? print $_POST['titulares'] : print '14' ?>">

                        </div>
                        <div class="form-group">
                            <label for="suplentes">Suplentes</label>
                            <input required type="number" name="suplentes" class="form-control" id="suplentes"
                                   value="<?php ( isset( $_POST['suplentes'] ) ) ? print $_POST['suplentes'] : print '14' ?>">

                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-lg btn-block" type="submit">Realizar Sorteo</button>
            </form>
        </div>
    </div>


	<?php

	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Csv;
	use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$concejales = [
		1  => 'Martinez, Horacio',
		2  => 'Haysler, Marlene',
		3  => 'Jimenez, Maria Eva',
		4  => 'Vancsik, Daniel',
		5  => 'Martinez, Ramon',
		6  => 'Dachary, Mariela',
		7  => 'Mazal, Malena',
		8  => 'Argañaraz, Pablo',
		9  => 'Koch, Santiago',
		10 => 'Perie, Florentino',
		11 => 'Lopez Sartori, Facundo',
		12 => 'Fonseca, Francisco',
		13 => 'De Arrechea, Rodrigo',
		14 => 'Velázquez, Pablo',
	]
	?>

    <div class="row mt-1">
        <div class="col-md-6 mb-6">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Referencia</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Banca</span>
                    <strong>Concejal</strong>
                </li>
				<?php
				foreach ( $concejales as $n => $concejal ) {

					if ( $n > 7 ) {
						break;
					}

					print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
					print( '<div><h3 class="my-0">#' . $n . '</h3></div>' );
					print( '<span class="text-muted">' . $concejal . '</span>' );

					print( '</li>' );

					unset( $concejales[ $n ] );
				}
				?>
            </ul>
        </div>
        <div class="col-md-6 mb-6">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Referencia</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Banca</span>
                    <strong>Concejal</strong>
                </li>
				<?php
				foreach ( $concejales as $n => $concejal ) {

					print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
					print( '<div><h3 class="my-0">#' . $n . '</h3></div>' );
					print( '<span class="text-muted">' . $concejal . '</span>' );

					print( '</li>' );
				}
				?>
            </ul>
        </div>
    </div>

    <div class="row mt-1">
		<?php


		$total            = $_POST['total'];
		$sheetData        = null;
		$etiquetaColumna2 = 'Nº Orden';

		$file_mimes = [
			'text/x-comma-separated-values',
			'text/comma-separated-values',
			'application/octet-stream',
			'application/vnd.ms-excel',
			'application/x-csv',
			'text/x-csv',
			'text/csv',
			'application/csv',
			'application/excel',
			'application/vnd.msexcel',
			'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		];

		if ( isset( $_FILES['archivo']['name'] ) && in_array( $_FILES['archivo']['type'], $file_mimes ) ) {

			$arr_file  = explode( '.', $_FILES['archivo']['name'] );
			$extension = end( $arr_file );

			if ( 'csv' == $extension ) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$spreadsheet = $reader->load( $_FILES['archivo']['tmp_name'] );

			$sheetData = $spreadsheet->getActiveSheet()->toArray();

			unset( $sheetData[0] );

//			dejo fijo a miravet
			$miravet = $sheetData[1];
//			dejo fijo a nuñez
			$nunez = $sheetData[2];
//			dejo fijo a castillo
			$castillo = $sheetData[3];
//			dejo fijo a fores
			$fores = $sheetData[4];

//			elimino a miravet
			unset( $sheetData[1] );
//			elimino a nuñez
			unset( $sheetData[2] );
//			elimino a castillo
			unset( $sheetData[3] );
//			elimino a fores
			unset( $sheetData[4] );

			shuffle( $sheetData );

//			dejo fijo a miravet
//			array_unshift($sheetData , $miravet);
//			dejo fijo a nunez
//			array_unshift($sheetData , $nunez);

			$total            = count( $sheetData );
			$etiquetaColumna2 = 'Nombre, Apellido y DNI';

		}


		$titulares = $_POST['titulares'];
		$suplentes = $_POST['suplentes'];

		$input = range( 1, $total );

		if ( ( $titulares + $suplentes ) <= $total ) {

			// 2 defensoras del pueblo (titular y suplente)
			// 2 secretarias (titular y suplente)
			// 2 prosecretarias legislativas (titular y suplente)
			// 2 prosecretarias administrativas (titular y suplente)
			$cantidadExtra = 8;

			$cantidadAleatorios = $titulares + $suplentes + $cantidadExtra;

			$rand_keys = array_rand( $input, $cantidadAleatorios - 4 );

//			elimino a miravet de randkeys

//			if ( ( $key = array_search( 0, $rand_keys ) ) !== false ) {
//				unset( $rand_keys[ $key ] );
//			}
//
////			elimino a nunez de randkeys
			if ( ( $key = array_search( 1, $rand_keys ) ) !== false ) {
				unset( $rand_keys[ $key ] );
			}
//
//			if ( ( $key = array_search( 2, $rand_keys ) ) !== false ) {
//				unset( $rand_keys[ $key ] );
//			}
//
			if ( ( $key = array_search( 3, $rand_keys ) ) !== false ) {
				unset( $rand_keys[ $key ] );
			}


			$i = 1;

//			7 concejales titulares masculinos y 7 femeninos
			$sonCatorce     = 0;
			$femenino       = 0;
			$masculino      = 0;
			$keysConcejales = [];
			$index          = 5;
			while ( $sonCatorce < 11 ) {

				if ( $sheetData[ $index ][3] == 'Femenino' && $femenino < 5 ) {
					$femenino ++;
					$keysConcejales[] = $index;
				}

				if ( $sheetData[ $index ][3] == 'Masculino' && $masculino < 6 ) {
					$masculino ++;
					$keysConcejales[] = $index;
				}

//				unset( $rand_keys[ $index ] );

				$sonCatorce = $femenino + $masculino;
				$index ++;
			}
			shuffle( $keysConcejales );

////			dejo fijo a miravet
//			array_unshift( $keysConcejales, 0 );
			array_unshift( $keysConcejales, 2 );

			$parasacar          = $keysConcejales[9];
			$keysConcejales[9]  = 1;
			$keysConcejales[]   = $parasacar;
			$keysConcejales[13] = 0;
			array_unshift( $sheetData, $castillo );


			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Titulares</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $keysConcejales as $key => $rand_key ) {
				if ( $i > $titulares ) {
					break;
				}
				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					if ( $i == 9 ) {
						print( '<span class="text-muted">' . ucfirst( $miravet[0] ) . ', ' . ucfirst( $miravet[1] ) . ' - ' . $miravet[2] . '</span>' );
					} else {
						print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
					}
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

//				unset( $rand_keys[ $key ] );

				if ( ( $uKey = array_search( $rand_key, $rand_keys ) ) !== false ) {
					unset( $rand_keys[ $uKey ] );
				}

				$i ++;

			}

			unset($sheetData[0]);

			print( '</ul>' );
			print( '</div>' );

//				suplentes
//			array_unshift( $keysConcejales, 1 );
//			array_unshift( $keysConcejales, 4 );

			$parasacarSup          = $rand_keys[23];
			$rand_keys[23]  = 2;
			$rand_keys[]   = $parasacarSup;
			$rand_keys[] = 1;
			$rand_keys = array_unique($rand_keys);
			array_unshift( $sheetData, $fores );
//            $ra

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Suplentes</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				if ( $i > ( $suplentes + $titulares ) ) {
					break;
				}

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					if ( $i == 23 ) {
						print( '<span class="text-muted">' . ucfirst( $nunez[0] ) . ', ' . ucfirst( $nunez[1] ) . ' - ' . $nunez[2] . '</span>' );
					} else {
						print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
					}
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;
			}


			print( '</ul>' );
			print( '</div>' );

			// Secretarias

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Secretaría</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;

				break;
			}


			print( '</ul>' );
			print( '</div>' );

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Secretario Suplente</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;

				break;
			}


			print( '</ul>' );
			print( '</div>' );

			// Defensoras del pueblo

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Defensor del Pueblo</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;

				break;
			}


			print( '</ul>' );
			print( '</div>' );

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Defensor del Pueblo Suplente</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;

				break;
			}


			print( '</ul>' );
			print( '</div>' );

			// Prosecretarias

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Pro Secretaría Legislativa</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;

				break;
			}


			print( '</ul>' );
			print( '</div>' );

			print( '<div class="col-md-6 mb-6">' );
			print( '<h4 class="d-flex justify-content-between align-items-center mb-3">' );
			print( '<span class="text-muted">Pro Secretario Administrativa</span>' );
			print( '</h4>' );
			print( '<ul class="list-group mb-3">' );
			print( '
				<li class="list-group-item d-flex justify-content-between">
                    <span>Posición</span>
                    <strong>' . $etiquetaColumna2 . '</strong>
                </li>
				' );

			foreach ( $rand_keys as $key => $rand_key ) {

				print( '<li class="list-group-item d-flex justify-content-between lh-condensed">' );
				print( '<div><h6 class="my-0">#' . $i . '</h6></div>' );
				if ( $sheetData ) {

					print( '<span class="text-muted">' . ucfirst( $sheetData[ $rand_key ][0] ) . ', ' . ucfirst( $sheetData[ $rand_key ][1] ) . ' - ' . $sheetData[ $rand_key ][2] . '</span>' );
				} else {

					print( '<span class="text-muted">' . $input[ $rand_key ] . '</span>' );
				}

				print( '</li>' );

				unset( $rand_keys[ $key ] );

				$i ++;

				break;
			}


			print( '</ul>' );
			print( '</div>' );


		}
		//			if principal

		}
		?>

    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2021 HCD Posadas</p>
    </footer>
</div>

</body>
</html>
