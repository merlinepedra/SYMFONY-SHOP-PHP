var resize_all = function(){
    var parentWidth = $('#carritoSection').width()
    $('#cant_sub div').css('width', (parentWidth - 10)/ 2)
  };

$(window).resize(resize_all);
$(window).on('load', resize_all);