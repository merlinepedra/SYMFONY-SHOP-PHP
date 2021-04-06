//import '../styles/index.css'

var resize_boxes = function(){
    var parentWidth = $('#todosLosProductos').width()
    var value = (parentWidth/5) - (parentWidth/20) - (parentWidth/300)
    console.log('Value vale: ', value)
    $('#todosLosProductos .other-box-index').css('width', value)
    $('#todosLosProductos .other-box-index').css('height', value)
}

$(window).resize(resize_boxes);
$(window).on('load', resize_boxes);

$('.categoriaCarteles').each( function(index, element ){
    if($(this).attr('id') == 'novedades_section')
    {
        $( this ).text('')
        var $span1 = $(`<span style="color: white;">n</span>`)
        var $span2 = $(`<span style="color: black;">ovedades</span>`)

        $(this).append($span1, [])
        $(this).append($span2, [])
        //$(this).parent('div').addClass('bg-warning')
        $(this).parent('div').addClass('novedades_fondo')
    }
    if($(this).attr('id') == 'populares_section')
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
    if($(this).attr('id') == 'valorados_section')
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