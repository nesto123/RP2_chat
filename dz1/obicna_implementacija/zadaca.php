<?php

    class Rokudoku{
        protected $ime;
        protected $broj_pokusaja;
        protected $polje;           //  pamtimo stanje na ploči, -1 --za neinicijalizirano
        protected $game_over;
        protected $errorFlag;
        protected $errorMsg;
        protected $boje;            //0 --na početku postavljeno - crno, -1 -- ne inicijalizirano, 1 -- uneseno od korisnika tocno -plavo, -2 -- uneseno od korisnika krivo -crveno


        //  ----------------------

        function __construct()
        {
            $this->ime = false;
            $this->broj_pokusaja = 0;
            $this->game_over = false;
            $this->postavi_error(false, '');
            /*$this->polje = array(   array(  -1, -1, 4,  -1, -1, -1),
                                    array(  -1, -1, -1,  2,  3,  -1),
                                    array(  3,  -1, -1,  -1, 6,  -1),
                                    array(  -1, 6,  -1, -1, -1, 2),
                                    array(  -1, 2,  1,  -1, -1, -1),
                                    array(  -1, -1, -1, 5,  -1, -1) );*/

            $this->load_table();       
        }

        function run()
        {
            $this->postavi_error( false, '');
            if( $this->ime_igraca() === false ){ //nije jos prosla pocetna forma
                $this->pocetna_forma();
                return;
            }
            else{       //znamo ime tj. prosla je pocetna forma
                $this->obradi_unos();

                //var_dump($this->polje);
                $this->game_over = $this->dobio();
                if( $this->game_over )          //  dobio igru
                    $this->ispisi_cestitku();
                else
                    $this->ispisi_igru();
            }
        }

        function ispisi_cestitku()
        {
            $this->ispisi_header();
            echo '<h4>Bravo, dobio si ! </h4>';
            $this->ispisi_footer();
            session_unset();
            session_destroy();
        }
        
        function obradi_unos()
        {
            if( !isset( $_POST['akcija'] ) )    //  nije nista odabrano, ponovo ispisujemo tablicu
                return false;
            elseif( $_POST['akcija'] === 'unesi_broj' ){
                $this->unesi_broj();
            }
            elseif( $_POST['akcija'] === 'obrisi' )
                $this->obrisi($_POST['red_za_obrisat'],$_POST['stupac_za_obrisat']);
            elseif( $_POST['akcija'] === 'reset' ){
                $name = $this->ime;
                $this->__construct();
                $this->ime = $name;
            }
            // vrati true obrađen unos
            return true;
        }

        function dobio()            // vrati true kada je igra gotova
        {
            for( $i = 0; $i < 6; ++$i )
                for( $j = 0; $j < 6; ++$j )
                    if( $this->boje[$i][$j] < 0)    /// onda je ili još nesto neinicijalizirano ili krivo uneseno
                        return false;
echo 'Slikat stanje i javit kad dode do ovog!<br>';
var_dump($this->boje);
            return true;
        }

        function obrisi( $i, $j)
        {
            if( $this->boje[$i][$j] === 0 ){
                $this->postavi_error(true, 'Greška! Pokušaj brisanja inicijalnog broja.');
                return;
            }
            $this->polje[$i][$j] = -1;
            //if( $this->boje[$i][$j] === 1 || $this->boje[$i][$j] === -2)
              //  $this->broj_pokusaja++; 
            $this->boje[$i][$j] = -1;
        }

        function unesi_broj()
        {
            $red = $_POST['red_za_unjet'];
            $stupac = $_POST['stupac_za_unjet'];
            if( !preg_match( '/^[1-6]$/',$_POST['broj_za_unjet'] ) ){
                $this->postavi_error( true, 'Krivi unos broja! Unesi broj od 1 do 6');
                return;
            }
           elseif( $this->polje[$red][$stupac] === 0 ){
               $this->postavi_error( true, 'Krivi unos, polje je već zauzeto (inicijalno)!');
                return;
           }
           elseif( !in_array( $_POST['broj_za_unjet'], $this->polje_dozvoljenih( $red, $stupac) ) ){    //nije u polju dozvoljenih ofarbat ga crveno i stavit unutra
                $this->polje[$red][$stupac] = $_POST['broj_za_unjet'];
                $this->boje[$red][$stupac] = -2;
                $this->broj_pokusaja++;
           }
           elseif( in_array( $_POST['broj_za_unjet'], $this->polje_dozvoljenih( $red, $stupac) ) ){     //je u polju, ofarbat ga plavo is tavit unutra
                $this->polje[$red][$stupac] = $_POST['broj_za_unjet'];
                $this->boje[$red][$stupac] = 1;
                $this->broj_pokusaja++; 
           }
           else
               $this->postavi_error(true, 'Uneseni broj nije prosao!');
        }

        function polje_dozvoljenih( $red, $stupac) //prime koordinare tamo gdje zelimo ubacit
        {//vraca polje dozvoljennih unosa za te koordinate
            $dozvoljeni = array( 1, 2, 3, 4, 5, 6);
            
            //prolazimo po retku
            for( $j = 0; $j < 6 ; ++$j )
                if( $j !== $stupac )
                    if( in_array( $this->polje[$red][$j], $dozvoljeni) )
                        $dozvoljeni = remove_element( $dozvoljeni, $this->polje[$red][$j]);
            //prolazimo po stupcu
            for( $i = 0; $i < 6 ; ++$i )
                if( $i !== $red )
                    if( in_array( $this->polje[$i][$stupac], $dozvoljeni) )
                        $dozvoljeni = remove_element( $dozvoljeni, $this->polje[$i][$stupac]);
            //box
            if( -1 < $red && $red < 2 && -1 < $stupac && $stupac < 3 )
                $dozvoljeni = $this->box( 2, -1, -1, 3, $red, $stupac, $dozvoljeni);
            if( -1 < $red && $red < 2 && 2 < $stupac && $stupac <  6)
                $dozvoljeni = $this->box( 2, -1, 2, 6, $red, $stupac, $dozvoljeni);
            if( 1 < $red && $red < 4 && -1 < $stupac && $stupac < 3 )
                $dozvoljeni = $this->box( 4, 1, -1, 3, $red, $stupac, $dozvoljeni);
            if( 1 < $red && $red < 4 && 2 < $stupac && $stupac < 6 )
                $dozvoljeni = $this->box( 4, 1, 2, 6, $red, $stupac, $dozvoljeni);
            if( 3 < $red && $red < 6 && -1 < $stupac && $stupac < 3 )
                $dozvoljeni = $this->box( 6, 3, -1, 3, $red, $stupac, $dozvoljeni);
            if( 3 < $red && $red < 6 && 2 < $stupac && $stupac < 6 )
                $dozvoljeni = $this->box( 6, 3, 2, 6, $red, $stupac, $dozvoljeni);
            
            return $dozvoljeni;
        }

        function box( $dole, $gore, $lijevo, $desno, $i, $j, $dozvoljeni)
        { //izbacuje iz dozvoljenih sbe koji nisu dozvoljeni zbog kvadrata
            
            if( $i + 1 < $dole && $j + 1 < $desno )
                if( in_array( $this->polje[($i+1)][($j+1)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i+1)][($j+1)]);
            if( $i + 1 < $dole && $j + 2 < $desno )
                if( in_array( $this->polje[($i+1)][($j+2)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i+1)][($j+2)]);
            if( $i + 1 < $dole && $lijevo < $j - 1)
                if( in_array( $this->polje[($i+1)][($j-1)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i+1)][($j-1)]);
            if( $i + 1 < $dole && $lijevo < $j - 2)
                if( in_array( $this->polje[($i+1)][($j-2)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i+1)][($j-2)]);
            if( $gore < $i - 1 && $lijevo < $j - 1)
                if( in_array( $this->polje[($i-1)][($j-1)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i-1)][($j-1)]);
            if( $gore < $i - 1 && $lijevo < $j - 2)
                if( in_array( $this->polje[($i-1)][($j-2)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i-1)][($j-2)]);
            if( $gore < $i - 1 && $j + 1 < $desno )
                if( in_array( $this->polje[($i-1)][($j+1)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i-1)][($j+1)]);
            if( $gore < $i - 1 && $j + 2 < $desno )
                if( in_array( $this->polje[($i-1)][($j+2)], $dozvoljeni) )
                    $dozvoljeni = remove_element( $dozvoljeni, $this->polje[($i-1)][($j+2)]);

            return $dozvoljeni;
        }

        function ime_igraca()   //vraća ime igrača inače false
        {
            if( $this->ime !== false)
                return $this->ime;
            elseif( isset( $_POST['ime'] ) ){   //salje se ime igrača
                if( preg_match ( '/^[a-zA-Z]{1,20}$/', $_POST['ime'] ) ){ //    ime je dobro
                    $this->ime = $_POST['ime'];
                    return $this->ime;
                }
                else{   //  krivo ime uneseno   -   doradit errore
                    $this->postavi_error( true, 'Error: krivo uneseno ime! Try again.');
                    return false;
                }
            }
            else
                return false;
        }

        function ispisi_broj( $i, $j)
        {
            if( $this->polje[$i][$j] === -1)
                return ' ';
            else
                return $this->polje[$i][$j];
        }

        function ispisi_boju( $i, $j )
        {
            if( $this->boje[$i][$j] === 0 )
                return 'black';
            elseif( $this->boje[$i][$j] === 1 )
                return 'blue';
            else    //if( $this->boje[i][j] === -2 )
                return 'red';
        }
        
        function ispisi_igru()
        {   
            $this->ispisi_header();
            $this->ispisi_pravila();

            echo 'Igrač: ' . $this->ime . '<br>';
            echo 'Broj pokusaja: ' . $this->broj_pokusaja . '<br><br>';

            //  ispis stanja igre -- tj polja/tablice
            $this->ispisi_tablicu_iz_filea();
            //  ispis errora ako ih ima
            $this->ispisi_error();

            //forma za unos akcije
            echo '<form action="' . $_SERVER['PHP_SELF'] .'" method="post">
            <input type="radio" name="akcija" id="unesi_broj" value="unesi_broj">
                <label for="unesi_broj">
                    Unesi broj: 
                    <input type="text" name="broj_za_unjet"> 
                    u redak 
                    <select name="red_za_unjet" >';
                        for( $i = 0; $i < 6; ++$i) 
                            echo '<option value="' . $i . '">' . ($i+1) . '</option>';
                    echo '</select>
                    i stupac
                    <select name="stupac_za_unjet">';
                        for( $i = 0; $i < 6; ++$i) 
                            echo '<option value="' . $i . '">' . ($i+1) . '</option>';
                    echo '</select>                    
                </label><br><br>
            <input type="radio" name="akcija" id="obrisi" value="obrisi">
                <label for="obrisi">
                    Obrisi broj iz retka
                    <select name="red_za_obrisat">';
                    for( $i = 0; $i < 6; ++$i) 
                        echo '<option value="' . $i . '">' . ($i+1) . '</option>';
                    echo '</select>
                    i stupca
                    <select name="stupac_za_obrisat">';
                    for( $i = 0; $i < 6; ++$i) 
                        echo '<option value="' . $i . '">' . ($i+1) . '</option>';
                    echo '</select>
                </label><br><br>
            <input type="radio" name="akcija" id="reset" value="reset">
                <label for="reset">Želim sve ispočetka!</label><br><br>
            <button type="submit" onclick="return confirm(&quot;Želite li izvršiti akciju?&quot;);">Izvrši akciju</button>';
            echo '</form>';
            $this->ispisi_footer();
        }

        function ispisi_pravila()
        {   
            echo '<h3>Pravila</h3>';
            echo '<p> Rokudoku se odvija na tablici od 6 x 6 brojeva, koja je podijeljena na blokove koji se sastoje od po 2 retka i 3 stupca. U svaku ćeliju je potrebno upisati neki prirodni broj između 1 i 6 i to tako da se niti jedan broj ne pojavljuje više puta u istom retku niti u istom stupcu niti u istom bloku. Neki brojevi su na početku igre već upisani u tablicu. </p>';
            echo '<p>Brojevi koje igrač tijekom igre unese, a koji ne krše pravilo igre trebaju biti ispisani plavom bojom, a oni koji krše pravilo igre trebaju biti crveni.</p>';
            echo '<p>Ne smiju se brisati niti mijenjati brojevi koji su zadani na početku igre. </p><br>';
        }

        function ispisi_header()
        {
            echo ' 
            <!DOCTYPE html>
            <html lang="en">
            
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" type="text/css" href="zadaca.css">
                <title>Rokudoku!</title>
            </head>
            <body>
            <h1>Rokudoku!</h1>';
        }

        function ispisi_footer()
        {
            echo'</body></html>';
        }

        function load_table( $ime_filea = null ) 
        {
            if( $ime_filea === null)
                return;
            
            //lodanje iz filea
            $dat = fopen( $ime_filea, 'r');

            for( $i = 0; feof($dat) === false ; ++$i ){
                $line = fgets( $dat );
                $this->polje[$i] = array_map( 'intval', explode( ' ', $line ) );;
            }

            fclose( $dat );

            //postavljanje boja
            for( $red = 0; $red < 6; ++$red)
                for( $stupac = 0; $stupac < 6; ++$stupac)
                {
                    if( $this->polje[$red][$stupac] !== -1 )
                        $this->boje[$red][$stupac] = 0;
                    else
                        $this->boje[$red][$stupac] = -1;
                }
        }

        function ispisi_tablicu_iz_filea( $ime_filea = null)
        {
            //  lodanje tablice
            $this->load_table( $ime_filea);

            //  printanje tablice           width="200" height ="200"
            echo '<table id="grid" style="border-colapse: colapse;" border="1" ><tbody>';
                for( $red = 0; $red < 6; ++$red ){
                    echo '<tr>';
                        for( $stupac = 0; $stupac < 6; ++$stupac)
                        {
                            echo '<td align="center">';
                            if( $this->boje[$red][$stupac] === 0 )
                                {echo '<b>';}
                            echo '<font color="' . $this->ispisi_boju( $red, $stupac ) . '">'. $this->ispisi_broj( $red, $stupac ) .'</font>';
                            if( $this->boje[$red][$stupac] === 0 )
                                {echo '</b>';}
                            echo '</td>';
                        }
                    echo '</tr>';
                }
            echo '</tbody></table><br>';
            
        }

        function postavi_error( $flag = true, $poruka = '')
        {
            $this->errorFlag = $flag;
            $this->errorMsg = $poruka;
        }
        function ispisi_error()
        {
            if( $this->errorFlag ){
                echo '<p>' . $this->errorMsg_() . '</p><br>';
                $this->errorFlag = false;
                $this->errorMsg = '';
            }
        }

        function pocetna_forma()
        {
            $this->ispisi_header();
            $this->ispisi_error();
            
            echo '
                <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                    Unesi svoje ime: 
                    <input type="text" name="ime"><br>
                    <input type="radio" name="br_tablice" id="tablica1" value="tablica1.txt">
                        <label for="tablica1">';
                        $this->ispisi_tablicu_iz_filea( 'tablica1.txt');
                    echo '</label>
                    <input type="radio" name="br_tablice" id="tablica2" value="tablica2.txt">
                        <label for="tablica2">';
                        $this->ispisi_tablicu_iz_filea( 'tablica2.txt');
                    echo '</label>
                    <button type="submit">Započni igru!</button>
                </form>';
            $this->ispisi_footer();            
        }

        function is_over(){ return $this->game_over; }
        function errorMsg_(){ return $this->errorMsg; }

    };
    //  -----------------------------------------

    session_start();

    if( !isset($_SESSION['igra'] ) )
    {
        $igra = new Rokudoku();
        $_SESSION['igra'] = $igra;
    }
    else{
        $igra = $_SESSION['igra'];
    }

    $igra->run();

    $_SESSION['igra'] = $igra;

    //  ----debug
    //echo'<hr>Javit bugove sa debug_info:<br>';
    //debug();
    //  ----------

    //  --------------------------------------------

    function debug()
    {
        echo '<pre>$_POST=';
        print_r( $_POST );
        echo '$_SESSION=';
        print_r( $_SESSION );
        echo '</pre>';
    }
    function remove_element($array,$value) 
    {
        return array_values(array_diff($array, (is_array($value) ? $value : array($value))));
    }



?>