/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

  var socket = io.connect('http://42qq.ru:8080/');
  
  socket.on('text', function (data) {
    $('#event').append('<div>'+data['text']+'</div>');
  });

function onAjaxMod() {
    var reg = new RegExp("\\?");
    var reg_on = new RegExp("a_mode_on");
    $('.ax').each(function(i, el) {
        
            if (!reg_on.test($(el).attr('class'))) {
            
            $(el).click(function() {
                
                var url = $(el).attr('href');
                var advanced_parametr = (reg.test(url) ? '&' : '?')+'print_only_content=1';
                $.ajax({
                type: "GET",
                cache: false,
                url: url+advanced_parametr,
                dataType: 'json'
                }).done(function(data) {
                    loadContent(data);
                });

                return false;
            });
            $(el).attr('class', $(el).attr('class') + ' a_mode_on');
            }
    });
    
    $('form').each(function(i, el) {
        var action = $(el).attr('action');
        
        if (!reg_on.test($(el).attr('class'))) {
            
        $(el).find('input[type=submit]:not(.a_mode_on)').click(function() {

            $.ajax({
                type: "POST",
                cache: false,
                dataType: 'json',
                url: action+'&print_only_content=1',
                data: $(el).serialize()
            }).done(function(data) {
                loadContent(data);
            });

            return false;
        });
        }
        $(el).attr('class', $(el).attr('class') + ' a_mode_on');
    });
    
    //$('#content').html();
    //alert($('#login_form').attr('class'));
    return true;
}

function loadContent(data) {
$('#dialog_window').animate({
        top: -400
    }, 500);
var control = function(i, data) {
        $('#'+i).fadeOut("slow", function() {
            $('#'+i).html(data[i]);
            onAjaxMod();
            $('#'+i).fadeIn("slow");
        });
}
var open_dialog = function(i, data) {
    $('#'+i).html(data[i]);
    onAjaxMod();
    $('#dialog_window').animate({
        top: 105
    }, 500);
}
    for(var i in data) {
        if (i == 'dialog') {
            open_dialog(i, data);
        }
        else {
            control(i, data);
        }
    }
}

function initUploadFile() {
    $('.file_input').change(function() {
            $(this).parent().submit();
        });
}

function updateFile(file_id) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {get_preview: 1, file_id: file_id},
        url: ""
        }).done(function(data) {
            $("#frame").html(data);
        });
}

function initTagControl() {
    $('#tag_input').keypress(function() {
        $.ajax({
            type: "POST",
            cache: false,
            url: "/tags/",
            data: { tag: $(this).val()}
                }).done(function( msg ) {
                    if (msg != '') {
                        $('#select_list').css('display', 'block');
                    }
                    else {
                        $('#select_list').css('display', 'none');
                    }
                  $('#select_list').html(msg);
                });
    });
}

/**
 * При удалении фокуса добавлять тег
 * в списке ввсегда выводить по умолчанию выделленый тег что уже введен
 * при енторе добавлять тег
 * отслеживать ентор и добавлять тег при нажатии
 */

function add_tag(name) {
    var html = '<div class="added_tag"><input type="hidden" name="tag[]" value="'+name+'" /><span>'+name+'</span> <img src="/tpl/main/img/x.png"></div>';
    $('#tag_list').append(html);
}

/**
 * @todo переименовать, что бы было ясно что это к коментам
 */
function sand() {
    
    $.ajax({
        type: "POST",
        cache: false,
        dataType: 'json',
        url: '',
        data: $("#coment_form").serialize()
    }).done(function(data) {
        //alert(1);
        $('#comments_list').append(data.comment);
        $("#comment_info").remove();
    });
            
   return false;
}

/**
* @todo реализовать функцию вставки событий
 */
function showEvent(q_text) {
    socket.emit('text', { text: q_text });
}