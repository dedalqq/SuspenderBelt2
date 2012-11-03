/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function onAjaxMod() {
    var reg = new RegExp("\\?");
    var reg_on = new RegExp("a_mode_on");
    $('a').each(function(i, el) {
        
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
            
        $(el).find('input[type="submit"]').click(function() {

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

function updateFile(file_id, form_id) {
    $.ajax({
        type: "POST",
        cache: false,
        data: {get_preview: 1, file_id: file_id},
        url: "handler.php"
        }).done(function(data) {
            $("#"+form_id).html(data);
        });
}