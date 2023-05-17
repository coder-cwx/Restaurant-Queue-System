var display_time = 8000;

$( document ).ready(function() {

    // Call update token
    getUpdateDataForDisplay();

    // Set the main token
    var getDept = $('.textSelect0').find('#dept-list').text();
    var getCounter = $('.textSelect0').find('#counter-list').text();
    var getNumber = $('.textSelect0').find('#number-list').text();

    if(getDept != ''){
        // Set text the info of queue
        $('.padtb-16rem').find('#dept').text(getDept);
        $('.padtb-16rem').find('#counter').text(getCounter);
        $('.padtb-16rem').find('#number').text(getNumber);
    }else{
        setEmptyMainToken();
    }
    var countWords = $('.marquee-text p').text().split(' ');
    var theTimeSecond = 'marquee ' + countWords.length*1.14 +'s linear infinite';

    $('.marquee-text p').css({
        '-webkit-animation': theTimeSecond,
        'animation': theTimeSecond,
    });
});

// Set empty text if there is no data
function setEmptyMainToken(){
    // Set text the info of queue
    $('.padtb-16rem').find('#dept').text('-');
    $('.padtb-16rem').find('#counter').text('-');
    $('.padtb-16rem').find('#number').text('-');
}

// Get data from database update the data
function getUpdateDataForDisplay() {
    setInterval(function(){
        $.ajax({
            url: baseUrl + '/api/display/apiGetDataDisplay/' + id_department,
            type: 'get',
            success: function (dataResponse) {
                var appendData = '';
                var getData = dataResponse;

                if (getData.getNew.letter !== 0) {
                    // Set text the info of queue
                    $('.padtb-16rem').find('#dept').text(getData.getNew.department);
                    $('.padtb-16rem').find('#counter').text(getData.getNew.counter);
                    $('.padtb-16rem').find('#number').text(getData.getNew.token);

                    var lang = webLang;
                    queue_sounds(notification(getData.getNew.token, lang));
                }

                $.each(getData, function (i, getData) {
                    if(i != 'getNew') {
                        appendData += '<div class="font-mar textSelect' + i + '">' +
                            '<span class="blue-color" id="dept-list">' + getData.department.name + '</span><br>' +
                            '<span id="counter-list">' + getData.counter.name + '</span><br>' +
                            '<span class="red-color" id="number-list">' + getData.department.letter + leftPad(getData.number, 4) + '</span>' +
                            '</div>';
                    }
                });

                $('.d-flex').html("");
                $('.d-flex').append(appendData);
            }
        });
    }, display_time);
}

// Function add number after token
function leftPad(value, length) {
    value = String(value);
    length = length - value.length;
    return ('0'.repeat(length) + value)
}

// Sound
function notification(token = "", lang) {
    var sounds = [];
    // start
    sounds.push(new Audio(baseUrl + '/sounds/start.mp3'));
    sounds.push(new Audio(baseUrl + '/sounds/'+ lang +'/token.mp3'));
    var i = 0;
    while (i < token.length) {
        var char = token.charAt(i).toLowerCase();
        sounds.push(new Audio(baseUrl + '/sounds/'+ lang +'/char/' + char + '.mp3'));
        i++;
    }
    return sounds;
}

// Function play token
function play(audio, callback) {
    var AudioContext = window.AudioContext // Default
        || window.webkitAudioContext // Safari and old versions of Chrome
        || false;

    if (AudioContext) {
        var context = new AudioContext;
        if(context.state === 'suspended') {
            context.resume().then(() => {
                // audio.pause();
                audio.play();
            });
        }else{
            audio.play();
        }
    } else {
        // Web Audio API is not supported
        // Alert the user
        alert("Sorry, but the Web Audio API is not supported by your browser. " +
            "Please, consider upgrading to the latest version or downloading Google Chrome or Mozilla Firefox");
    }

    if (callback) {
        audio.onended = callback;
        audio.remove();
    }
}

// Queue the audio
function queue_sounds(track) {
    var index = 0;

    function recursive_play() {
        if (index + 1 === track.length) {
            play(track[index], null);
        } else {
            play(track[index], function() {
                index++;
                recursive_play();
            });
        }
    }
    recursive_play();
}
