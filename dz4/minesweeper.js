
var /*nRows= 9,nCols= 9, nMines = 10, */strKvadrata = 30; 

$( document ).ready( function()
{

    $( 'button.reset' ).on( 'click', reset );

});

function print_table()
{
    var ctx = $( '#cnv' ).get(0).getContext( '2d' ), cnv = $( '#cnv' ).get(0);
    
    $( '#cnv' ).show();
    $( 'button.reset' ).show();
    
    //cnv.width = localStorage.nCols * strKvadrata;
    //cnv.height = localStorage.nRows * strKvadrata;
    ctx.canvas.width = localStorage.nCols * strKvadrata;
    ctx.canvas.height = localStorage.nRows * strKvadrata;


    for ( var j = 0; j < localStorage.nRows; ++j )
        for( var i = 0; i < localStorage.nCols; ++i )
        {
            ctx.fillStyle = 'gray';
            ctx.lineWidth = "0.5";
            ctx.strokeStyle = "black";
            ctx.fillRect( i*strKvadrata, j*strKvadrata, strKvadrata, strKvadrata );
            ctx.strokeRect( i*strKvadrata, j*strKvadrata, strKvadrata, strKvadrata );
        }
    
    inicijaliziraj();
    
    $( '#cnv' ).on( 'click', otkrijPolje );
    $( '#cnv' ).on( 'contextmenu', function() {return false} );
    $( '#cnv' ).on( 'contextmenu', upitnik );
}

function upitnik( event )
{
    var ctx = $( '#cnv' ).get(0).getContext( '2d' ), cnv = $( '#cnv' ).get(0);
    var box = cnv.getBoundingClientRect();
    var x = parseInt((event.clientX - box.left)/strKvadrata), y = parseInt((event.clientY - box.top)/strKvadrata);

    ctx.fillStyle = 'gray';
    ctx.fillRect( x*strKvadrata, y*strKvadrata, strKvadrata, strKvadrata );
    ctx.fillStyle = "black";
    ctx.font = "10pt sans-serif";
    ctx.lineWidth = "0.5";
    ctx.strokeStyle = "black";
    ctx.fillText('?', x*strKvadrata + strKvadrata/3, y*strKvadrata + strKvadrata/3*2);
    ctx.strokeRect(  x*strKvadrata, y*strKvadrata, strKvadrata, strKvadrata );
}

function otkrijPolje( event )
{
    var x = event.clientX, y = event.clinetY;
    var ctx = $( '#cnv' ).get(0).getContext( '2d' ), cnv = $( '#cnv' ).get(0);
    var box = cnv.getBoundingClientRect();
    var x = event.clientX - box.left, y = event.clientY - box.top;

    $.ajax(
        {
            url: 'https://rp2.studenti.math.hr/~zbujanov/dz4/cell.php',
            method: 'get',
            data:
            {
                id: localStorage.id,
                row: parseInt(y / strKvadrata),
                col: parseInt(x / strKvadrata)
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'error' ) ){
                    alert( data.error );
                    reset();
                }
                else if( data.hasOwnProperty( 'boom' ) ){
                    //console.log(data);
                    if( data.boom )
                        izgubio(parseInt(x / strKvadrata), parseInt(y / strKvadrata));
                    data.cells.forEach(element => {
                        ispisi_polje( element.col, element.row, element.mines );
                    });
                }
                else{
                    alert( 'ERROR: Ajax - Undefined' );
                    reset();
                }
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
            }
        }
    );
}

function ispisi_polje( x, y, brmina )
{
    var ctx = $( '#cnv' ).get(0).getContext( '2d' ), cnv = $( '#cnv' ).get(0);
    var color=['blue', 'red', 'green', 'yellow', 'pink', 'orange'];

    ctx.fillStyle = 'white';
    ctx.fillRect( x*strKvadrata, y*strKvadrata, strKvadrata, strKvadrata );
    ctx.fillStyle = color[brmina];
    ctx.font = "10pt sans-serif";
    ctx.lineWidth = "0.5";
    ctx.strokeStyle = "black";
    if(brmina){
        ctx.fillText(brmina, x*strKvadrata + strKvadrata/3, y*strKvadrata + strKvadrata/3*2);
    }
    ctx.strokeRect(  x*strKvadrata, y*strKvadrata, strKvadrata, strKvadrata );

    localStorage.preostaloPoljaZaOtvorit -= 1;
    if( localStorage.preostaloPoljaZaOtvorit == 0 )
        pobjedio();
}

function pobjedio()
{
    var p = $( '<p> ');

    p.attr( 'class', 'izgubio')
        .html('Pobjedili ste!');
    $('form').before( p );
    $( '#cnv' ).off( 'click', otkrijPolje );
    $( '#cnv' ).off( 'contextmenu', upitnik );
}

function izgubio(x, y)
{
    var p = $( '<p> ');

    p.attr( 'class', 'izgubio')
        .html('Izgubili ste, stali ste na minu!');
    $('form').before( p );
    $( '#cnv' ).off( 'click', otkrijPolje );
    $( '#cnv' ).off( 'contextmenu', upitnik );

    var ctx = $( '#cnv' ).get(0).getContext( '2d' ), cnv = $( '#cnv' ).get(0);

    ctx.fillStyle = 'red';
    ctx.fillRect( x*strKvadrata, y*strKvadrata, strKvadrata, strKvadrata );
    ctx.lineWidth = "0.5";
    ctx.strokeStyle = "black";
    ctx.strokeRect( x*strKvadrata, y*strKvadrata, strKvadrata, strKvadrata );
}

function inicijaliziraj()
{
    $.ajax(
        {
            url: 'https://rp2.studenti.math.hr/~zbujanov/dz4/id.php',
            method: 'get',
            data:
            {
                nRows: localStorage.nRows,
                nCols: localStorage.nCols,
                nMines: localStorage.nMines
            },
            success: function( data )
            {
                if( data.hasOwnProperty( 'error' ) ){
                    alert( 'Inicijalizacija'+ data.error );
                    reset();
                }
                else if( data.hasOwnProperty( 'id' ) ){
                    localStorage.id = data.id;
                }
                else{
                    alert( 'ERROR: Ajax - Undefined' );
                    reset();
                }
            },
            error: function()
            {
                console.log( 'Greška u Ajax pozivu...');
                alert('Greška u Ajax pozivu...');
            }
        }
    );
}

function reset()
{
    localStorage.removeItem('id');
    localStorage.removeItem('nRows');
    localStorage.removeItem('nCols');
    localStorage.removeItem('nMines');
    
    $( 'p.izgubio' ).remove();
    $( '#cnv' ).hide();
    $( 'button.reset' ).hide();
    $( 'form[name="forma"]' ).show();

    console.log('reset');
}

function validateForm(event){
    var row = document.forms["forma"]["nRows"].value,
        col = document.forms["forma"]["nCols"].value,
        mines = document.forms["forma"]["nMines"].value;
        
    if ( ! (1 <= row && row <= 20 && 1 <= col && col <= 20) ) {
      alert("Prvi uvijet nije zadovoljen!");
      return false;
    }
    else if ( ! (0 <= mines && mines <= row*col) ) {
        alert("Drugi uvijet nije zadovoljen!");
        return false;
    }
    else{// svi uvjeti su zadovoljeni
        event.preventDefault();
        localStorage.nRows = row;
        localStorage.nCols = col;
        localStorage.nMines = mines;
        localStorage.preostaloPoljaZaOtvorit = row*col-mines;

        $( 'form[name="forma"]' ).hide();
        print_table();
    }
}