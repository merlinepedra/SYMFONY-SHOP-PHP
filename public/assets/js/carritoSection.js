var resize_all = function(){
    var parentWidth = $('#carritoSection .index-box').parent('div').width()
    var value = (parentWidth/3) + (parentWidth/10)
    $('#carritoSection .index-box').css('width', value)
    $('#carritoSection .index-box').css('height', value)
    $('#cant_sub div').css('width', (parentWidth - 10)/ 2)

    if(value < 60){
        $('.cantidad_texto').css('font-size', '40%')
        $('#_0000_00_€').css('font-size', '50%')
        $('#_1').css('font-size', '50%')
        $('.Ver_carrito').css('font-size', '70%')
    } 
    else{
        $('.cantidad_texto').css('font-size', '70%')
        $('#_0000_00_€').css('font-size', '80%')
        $('#_1').css('font-size', '80%')
        $('.Ver_carrito').css('font-size', '100%')
    }
  };

$(window).resize(resize_all);
$(window).on('load', resize_all);