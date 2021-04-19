
$( document ).ready( function()
{
    var gallery = $( 'div.gallery' );

    gallery.each(sakrij_i_pokazi);
    
    $( 'button.galerija' ).on('click', show_gallery );
     
});

function show_gallery()
{
    var div = $( 'div.gallery[title="' +$(this).attr("id") + '"]' );
    div.show();

    $( 'button.galerija' ).prop( 'disabled', true);


    div.css( 'position', 'absolute')
        .css( 'text-align', 'center')
        .css('top', '10%')
        .css('left', '10%')
        .css('height', '80%')
        .css( 'width', '80%' )
        .css( 'background-color', 'gray' );
        
    //  Stvara gubm za izlaz
    var exit = $( '<button>' );
    exit.prop('class', 'exit')
        .html('<b>&times;</b>')
        .css( 'position', 'absolute')
        .css( 'right', '0')
        .css( 'border', 'none')
        .css( 'background-color', 'green')
        .css( 'color', 'white')
        .css( 'text-align', 'center')
        .css( 'padding', '1%')
        .css( 'font-size', '20pt')
        .css( 'top', '0');

    div.append(exit);

    //  Prikaz prve slike
    var img = div.find('img').first();
    
    var strelica_d = $( '<button>');        //  Stvara gubm za desnu sliku
    strelica_d.prop( 'class', 'next' )
            .html( '<b>&#8594;</b>')
            .css( 'position', 'absolute' )
            .css( 'right', '0.5%')
            .css( 'border', 'none')
            .css( 'background-color', 'green')
            .css( 'color', 'white')
            .css( 'text-align', 'center')
            .css( 'padding', '1%')
            .css( 'font-size', '20pt')
            .css( 'top', '45%' );

    div.append(strelica_d);

    var strelica_l = $( '<button>');        //  Stvara gubm za lijevu sliku
    strelica_l.prop( 'class', 'prev' )
            .html( '<b>&#8592;</b>')
            .css( 'position', 'absolute' )
            .css( 'left', '0.5%')
            .css( 'border', 'none')
            .css( 'background-color', 'green')
            .css( 'color', 'white')
            .css( 'text-align', 'center')
            .css( 'padding', '1%')
            .css( 'font-size', '20pt')
            .css( 'top', '45%' );

    div.append(strelica_l);

    var broj = $( '<p>').prop('class', 'broj')
    .css('position', 'absolute')
    .css( 'bottom', '-10%')
    .css( 'left',  '1%') // za absolute
    //.css( 'margin-left', '-100px')
    .css( 'bottom', '0px')
    .css( 'color', 'white');

    div.append(broj);

    var box = $( '<div>').prop( 'class', 'box' )
                        .css( 'position', 'relative')
                        .css( 'display', 'block')
                        .css( 'margin', 'auto')
                        .css( 'width', '85%')
                        .css( 'height', '85%')
                        .css( 'margin-left', 'auto' )
                        .css( 'top', '5%' )
                        //.css( 'object-fit', 'cover')
                        .css( 'margin-bottom', '10%');
    div.append( box );

    //console.log( img );
    prikazi_sliku(img)


    //  Pretplata na sva button-e
    $( 'button.exit' ).on( 'click', hide_gallery );
    $( 'button.next' ).on( 'click', next_slika );
    $( 'button.prev' ).on( 'click', next_slika );



}

function prikazi_sliku( img)
{
 /*   
    img.css( 'position', 'relative')
    .css( 'display', 'block')
    .css( 'margin', 'auto')
    .css( 'max-width', '85%')
    .css( 'max-height', '85%')
    .css( 'margin-left', 'auto' )
    .css( 'top', '5%' )
    .css( 'margin-bottom', '10%')
    //.css( 'left', '10%')
    .show();
*/

    //  Za par
    var par = img.siblings('p[data-target="'+img.attr('src')+'"]');

    par.css('position', 'absolute')
        .css( 'bottom', '-10%')
        .css( 'left',  '50%') // za absolute
        .css( 'margin-left', '-100px')
        .css( 'bottom', '0px')
        .css( 'color', 'white')
        .show();

    
    var t = img.parent().children('img'), i;

    for( i = 0; i < t.length; ++i)
        if( $(t[i]).attr( 'src' ) === img.attr( 'src' ) )
                break;
            

    
    broj = img.siblings('p.broj');
    broj.html( (i+1) +'/'+(img.siblings('img').length+1));
    broj.show();


    //      Id za strelice
    var prevImg = img.prevAll("img").first();
    var nextImg = img.nextAll("img").first();



    if( Number(nextImg.length) > 0)
        $( 'button.next').prop( 'id', nextImg.attr('src') )
                        .show();
    else 
        $( 'button.next').hide();
    if( Number(prevImg.length) > 0)
        $( 'button.prev').prop( 'id', prevImg.attr('src') )
                        .show();
    else
        $( 'button.prev').hide();

    
    var box = $( 'div.box' ), duplicate_4show = img.clone(), koef;


    duplicate_4show
        .css( 'object-fit', 'contain')
        .show();
            
    if( box.height() / duplicate_4show.prop('naturalHeight') < box.width() / duplicate_4show.prop('naturalWidth') )
        koef = box.height() / duplicate_4show.prop('naturalHeight');
    else
        koef = box.width() / duplicate_4show.prop('naturalWidth');
        duplicate_4show
        .css( 'height', duplicate_4show.prop('naturalHeight') * koef )
        .css( 'width', duplicate_4show.prop('naturalWidth') * koef );



    box.append(duplicate_4show);
    //console.log( duplicate_4show.prop('naturalWidth') /  duplicate_4show.prop('naturalHeight'), duplicate_4show.width() / duplicate_4show.height(),box.height(), koef);


}

function next_slika()
{
    // Trenutna slika i sljedeća slika
    var imgNext = $(this).siblings('[src="'+$(this).attr("id")+'"]');
    var imgHide = /*$(this).siblings('img').filter(function(){
        return $(this).css('display')!== 'none';
    });*/
                    $('div.box').children().first();
    var parHide = $(this).siblings('p').filter(function(){
        return $(this).css('display')!== 'none';
    });

    parHide.hide();

    //$(this).append(imgHide);
    //imgHide.hide();
    imgHide.remove();
    prikazi_sliku( imgNext );
}



function sakrij_i_pokazi()
{
    $(this).hide();
    $(this).children().each( function(){
        $(this).hide();
    });
    
    var par = $( '<p>');        // Postavi naslov galerije.
    par.html( $(this).attr('title') );
    $(this).after( par );

    var meni = $( '<button>' );     // Dodaj button za galeriju.
    meni.prop('class', 'galerija')
        .prop('id', $(this).attr('title') )
        .html( 'Prikaži galeriju' );

    $(this).after( meni );

}

function hide_gallery()
{
    $( 'div.box' ).remove();
    $( 'button.galerija' ).prop( 'disabled', false);

    $(this).parent().hide();
    $(this).parent().children().hide();
}

