//import '../styles/index.css'

$('.categoriaCarteles').each( function(index, element ){
    if(index == 0)
    {
        $( this ).text('')
        var $span1 = $(`<span style="color: white;">n</span>`)
        var $span2 = $(`<span style="color: black;">ovedades</span>`)

        $(this).append($span1, [])
        $(this).append($span2, [])
        //$(this).parent('div').addClass('bg-warning')
        $(this).parent('div').addClass('novedades_fondo')
    }
    if(index == 1)
    {
        $( this ).text('')
        var $span1 = $(`<span style="color: white;">lo </span>`)
        var $span2 = $(`<span style="color: orangered;">+ </span>`)
        var $span3 = $(`<span style="color: white;">popular</span>`)
        $(this).append($span1, [])
        $(this).append($span2, [])
        $(this).append($span3, [])
        //$(this).parent('div').addClass('bg-dark')
        $(this).parent('div').addClass('lo_mas_popular_fondo')
    }
    if(index == 2)
    {
        $( this ).text('')
        var $span1 = $(`<span style="color: black;">mejor </span>`)
        var $span2 = $(`<span style="color: orangered;">v</span>`)
        var $span3 = $(`<span style="color: black;">alorados</span>`)
        $(this).append($span1, [])
        $(this).append($span2, [])
        $(this).append($span3, [])
        //$(this).parent('div').addClass('bg-secondary')
        $(this).parent('div').addClass('mejor_valorados_fondo')
    }
});

$('.other-box-index').each(function(index, element){
    var color = $(this).data('color')
    $(this).css('background-color', color)
});


$('.pname').each(function(index, element){
    var imageWidth = $(this).closest('.other-box-index').width
    $(this).css('max-width', imageWidth-5)
});


/*
$('tbody tr:nth-child(1) .precio').each(function(index, element){
    var obw = $('.other-box-index').height()
    var labels = $('tbody tr:nth-child(1) .precio').height()
    console.log(obw)
    console.log(Math.max(labels))
    $(this).css('position', 'absolute')
    $(this).css('top', obw + Math.max(labels) + 100)
})
*/

/*
$('tbody').each(function(index, element)
{
    var productos = $(this).data('productos')
    console.log(productos)
    for (let i = 0; i < 2; i++) 
    {
        var $newFila = $(`<tr></tr>`)
        for (let j = 0; j < 4; j++) 
        {
            var p = productos[4*i + j]
            var $newColumna = $(`<td><div class="container">
                <picture>
                    <div class="other-box-index" style="background-color: 'red';"></div>
                    <figcaption>${p.nombre}</figcaption>
                </picture>
            </div></td>`)
            $newFila.append($newColumna, [])
        }
        $(this).append($newFila)
    }
});
*/

