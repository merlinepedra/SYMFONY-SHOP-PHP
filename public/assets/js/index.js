//import '../styles/index.css'

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