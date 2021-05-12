let category,phonetic,translation,word,main_game,i,lang;

let words = [];

let url = new URL(document.location.href);
category = url.searchParams.get("category");
lang = url.searchParams.get("lang");

$(document).ready(function () {

    phonetic = $('.phonetic');
    translation = $('.translation');
    main_game = $('.main-game');



    user_id = localStorage.getItem('user_id');

    launch();


    if(hasPhonetic()) {
        phonetic.find('.secondary-game').on('click', function () {
            phonetic.find('.secondary-game').removeClass('error').removeClass('active');

            $(this).addClass('active');

            handleValidateButton();

        });
    } else {
        phonetic.hide();
    }
    translation.find('.secondary-game').on('click',function () {
        translation.find('.secondary-game').removeClass('error').removeClass('active');

        $(this).addClass('active');

        handleValidateButton();

    });

    main_game.on('click',function () {
        let file = word.audio;

        var audio = new Audio(file);
        audio.play();
    });

    $('.validate-game').on('click',function () {

        let t = translation.find('.active').attr('word');
        let p = phonetic.find('.active').attr('word');
        let m = main_game.attr('word');

        let isvalid = hasPhonetic() ? (t == m) && (p == m) : (t == m);

        if(t != m ) {
            translation.find('.active').removeClass('active').addClass('error');
        }
        if(hasPhonetic()) {
            if (p != m) {
                phonetic.find('.active').removeClass('active').addClass('error');
            }
        }

        if(isvalid) {
            $('.success').fadeIn(100);
            i++;

            loading(true);

            setTimeout(function () {
                startGame();
            },1000)
        } else {
            $('.-alert.error').fadeIn(100);
            setTimeout(function () {
                $('.error').fadeOut(100);
            },1000)
        }

    });

    $('.game-try').on('click',function () {
        $('.done').fadeOut(200);
        launch();
    })

});


/**
 *
 */
function startGame() {

    word = words[i];

    $('.secondary-game').removeClass('active').removeClass('error').show();

    $('.success').fadeOut();
    $('.error').hide();

    handleValidateButton();


    if(!word) {
        loading(false);
        $('.done').fadeIn(200);
        return;
    }


    main_game.html(word.word);
    main_game.attr('word',word.word);

    $('.category').html(word.category.normal);

    let a = shuffle([0,1,2,3]);

    translation.find('.secondary-game').each(function (k, e) {
        let w = word.words[a[k]];
        $(e).html(w.translations.join('<br>'));
        $(e).attr('word',w.word);
    });

    a = shuffle([0,1,2,3]);

    if(hasPhonetic()) {
        phonetic.find('.secondary-game').each(function (k, e) {
            let w = word.words[a[k]];
            $(e).html(w.phonetic);
            $(e).attr('word', w.word);
        });
    }

    setTimeout(function () {
        loading(false);
    },100);

}


function launch() {

    $.getJSON('JSON/JSON_GET_GAME.php?category=' + category)
        .then(function (data) {

            words = data;
            i = 0;

            startGame();
        });
}


/**
 *
 */
function handleValidateButton() {

    let valid;

    if(hasPhonetic()) {
        valid = translation.find('.active').length === 1 && phonetic.find('.active').length === 1;
    } else {
        valid = translation.find('.active').length === 1;
    }

    $('.validate-game').toggleClass('disabled',!valid).prop('disabled', !valid);

}


/**
 *
 * @return {boolean}
 */
hasPhonetic = () => {

    return ['zs-en'].indexOf(lang) !== -1;

}
