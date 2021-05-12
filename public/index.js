
let skillInfos,timeout,from,to,user_id;

let search = $('#search');

let category = $('#category');

let offset = 0;
let limit = 20;

let is_loading = false;

let modal_login = $('#modal-login');



$('#sync').on('click',function (e) {
    e.preventDefault();

    loading(true);

    is_loading = true;

    modal_login.find('.alert').remove();
    modal_login.find('button').prop('disabled',true);

    modal_login.find('.modal-body').prepend('<div class="alert alert-success" >The first synchronisation can take a while</div>');

    $.post('JSON/JSON_SYNC.php',{
        'login' : $('#login').val(),
        'password' : $('#password').val(),

    })
        .then(function (data) {

            modal_login.find('.alert').remove();

            localStorage.setItem('user_id',data.login.user_id);
            localStorage.setItem('learning_language',data.learning_language);
            localStorage.setItem('user_language',data.user_language);

            is_loading = false;
            loadSkills();
            modal_login.modal('hide');
            modal_login.find('button').prop('disabled',false);
        })
        .fail(function (data) {
            loading(false);
            modal_login.find('button').prop('disabled',false);
            modal_login.find('.modal-body').prepend('<div class="alert alert-danger" >'+ data.responseJSON.error +'</div>');
        });

});

$(document).ready(function () {

    loadSkills();

});



function loadSkills() {

    from = localStorage.getItem('learning_language');
    to = localStorage.getItem('user_language');
    user_id = localStorage.getItem('user_id');


    loading(true);
    $.getJSON('JSON/JSON_GET_SKILLS.php')
        .then(function (skills) {

            skillInfos = skills.skills;

            $.each(skills.categories,function (k,v) {
                category.append('<option value="'+ k +'" >'+ v +'</option>');
            });

            loading(false);
            loadData(true);
        })
        .fail(function () {
            loading(false);
            modal_login.modal('show');
        })
}




/**
 *
 * @param wipe
 */
function loadData(wipe) {

    setCookie("learning_language",localStorage.getItem('learning_language'));
    setCookie("user_language",localStorage.getItem('user_language'));

    from = localStorage.getItem('learning_language');
    to = localStorage.getItem('user_language');

    user_id = localStorage.getItem('user_id');

    if(wipe) {
        offset = 0;
        is_loading = false;
        $('#content').html('');
        loading(true);
    }

    if(is_loading) {
        return;
    }

    is_loading = true;

    $.getJSON('JSON/JSON_DATA.php?offset=' + offset + '&limit=' + limit + '&category=' + category.val() + '&search=' + search.val(),function (data) {

        if(data.data.length == 0 ) {

            addEvents();
            loading(false);

            return;
        }

        let $lang =         $('select[name="lang"]');

        data.languages.forEach((l) => {
            console.log(l);
            $lang.append('<option value="'+ l +'" >'+ l +'</option>');
        });
        $lang.val(data.currentLanguage);

        $lang.on('change',() => {

            let v = $lang.val();

            v = v.split('-');
            localStorage.setItem('learning_language',v[0]);
            localStorage.setItem('user_language',v[1]);
            document.location.reload();
        });


        data.data.forEach(function (val) {

            let template = $($('#row').html());

            template.addClass(val.category.slug);
            template.find('.from').html(val.word);
            template.find('.phonetic').html(val.phonetic);
            template.find('.skill_title').attr('data-tooltip',val.course.slug).html(val.course.normal);

            val.translations.forEach(function (v) {
                template.find('.to').append('<li>'+ v +'</li>');
            });
            val.sentences.forEach(function (v) {
                template.find('.sentences').after('<p class="sentence">'+ v.normal +' <strong class="play" data-audio="'+ v.audio +'"  ><i class="fas fa-volume-down" ></i></strong><em><small>'+ v.translation +'</small></em></p>');
            });

            template.find('.audio').data('audio',val.audio);

            $('#content').append(template);

        });

        is_loading = false;

        offset += limit;

        addEvents();
        loading(false);

        //  loadData();

    }).fail(function () {
        loading(false);
        modal_login.modal('show');
    })

}



function addEvents() {
    $('.play').off('click').on('click',function () {
        let file = $(this).data('audio');

        var audio = new Audio(file);
        audio.play();

    });

    $('.on-tooltip').off('click').on('click',function () {

        let val = $(this).data('tooltip');

        $('.the-tooltip').remove();

        let data = skillInfos[val].explanation;

        if(data) {
            $(this).append('<span class="the-tooltip" >' + data + '<i class="fa fa-close"></i></span>');

            $('.the-tooltip').css('width', $(window).width() * 0.4);
        }

    });

    $('body').on('click',function (e) {
        if($(e.target).hasClass('fa-close')) {
            $('.the-tooltip').remove();
            e.preventDefault();
        }
    });
}


search.on('keyup',function () {

    clearTimeout(timeout);
    loading(true);
    timeout = setTimeout(function () {
        loadData(true);
    },500);
});

category.on('change',function () {
    loadData(true);
});


$(document).ready(function() {
    var win = $(window);

    // Each time the user scrolls
    win.scroll(function() {
        if ($('#content').height() -  win.height() < win.scrollTop()) {
            loadData(false);
        }
    });
});


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
