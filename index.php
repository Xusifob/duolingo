<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap" rel="stylesheet">
    <title>Learn Mandarin - Duolingo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">

    <link rel="stylesheet" href="public/style.css">

</head>
<body>

<div class="form-top">
    <div class="container">
        <form class="form-inline">
            <div class="form-group mx-sm-4 mb-3">
                <input type="search" class="form-control" id="search" placeholder="Search a value">
            </div>
            <div class="form-group mx-sm-4 mb-3">
                <select name="category" id="category" class="form-control">
                    <option value="">Filter by category</option>
                </select>
            </div>
            <div class="form-group mx-sm-4 mb-3">
                <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#modal-login" ><i class="fa fa-sync"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="container" style="margin-top: 100px;">
    <table id="table" class="table table-stripped">
        <thead>
        <tr>
            <th>Chinese</th>
            <th>Phonetic</th>
            <th class="sr-only">Slug</th>
            <th>English</th>
            <th>Category</th>
            <th>Phrases</th>
            <th><i class="fas fa-volume-down"></i></th>
        </tr>
        </thead>
        <tbody id="content">
        </tbody>
    </table>
</div>

<div class="loading-container">
    <div class="loading">
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<div class="modal" tabindex="-1" id="modal-login" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login to Duolingo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><small>Your credentials are only used to fetch your own data from duolingo and are not stored in any way.</small></p>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="login">Your duolingo login</label>
                        <input type="text" id="login" placeholder="Duolingo login" name="login" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Your duolingo password</label>
                        <input type="password" id="password" placeholder="Duolingo password" name="password" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sync">Synchronise</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

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

        loading(true);
        $.getJSON('JSON/JSON_GET_SKILLS.php?from=' + from + '&to=' + to)
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


    function loading(toggle) {
        if(toggle) {
            $('.loading-container').fadeIn('200');
        } else {
            $('.loading-container').fadeOut('200');
        }
    }

    /**
     *
     * @param wipe
     */
    function loadData(wipe) {

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

        $.getJSON('JSON/JSON_DATA.php?user='+ user_id +'&from='+ from +'&to='+ to +'&offset=' + offset + '&limit=' + limit + '&category=' + category.val() + '&search=' + search.val(),function (data) {

            if(data.data.length == 0 ) {

                addEvents();
                loading(false);

                return;
            }

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

</script>
</body>
</html>

<script type="text/template" id="row">
    <tr class="">
        <td><strong class="only-mobile">Chinese : </strong><span class="to-search from"></span></td>
        <td><strong class="only-mobile">Phonetic : </strong><span class="to-search phonetic"></span></td>
        <td class="to-search sr-only slug"></td>
        <td>
            <strong class="only-mobile">English : </strong>
            <ul class="to-search to"></ul>
        </td>
        <td>
            <strong class="only-mobile">Category : </strong>
            <span class="on-tooltip to-search skill_title" data-tooltip=""></span>
        </td>
        <td>
            <strong class="only-mobile sentences">Sentences : </strong>
        </td>
        <td>
            <i data-audio="" class="audio fas fa-volume-down play"></i>
        </td>
    </tr>
</script>