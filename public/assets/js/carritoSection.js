var resize_all = function(){
    var parentWidth = $('#carritoSection').width()
    $('#cant_sub div').css('width', (parentWidth - 10)/ 2)

    /*
    var value = (parentWidth/3) + (parentWidth/10)
    if(value < 60){
        $('.cantidad_texto').css('font-size', '40%')
        $('#_0000_00_€').css('font-size', '48%')
        $('#_1').css('font-size', '48%')
    } 
    */
  };

$(window).resize(resize_all);
$(window).on('load', resize_all);