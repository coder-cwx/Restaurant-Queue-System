$(function () {
    if(token != 0 && current_token != 0 && token != -1 && current_token != -1){
        getCurrentToken();
    }

    function getCurrentToken() {
        var data = {
            'department' : $('#dept').text()
        };

        setInterval(function(){
            $.ajax({
                url: baseUrl + '/token/getCurrentToken/',
                type: 'get',
                data: data,
                success: function (data) {
                    var appendData = data;
                    $('#currentToken').html("");
                    $('#currentToken').append(appendData);
                }
            });
        }, 8000);
    }

    $(document).on('click', '#send', function (e) {
        $('.reload').removeClass('hide');
        e.preventDefault();

        if($('#email').val() != '') {
            // Param with value
            var data = {
                'email': $('#email').val(),
                'url' : window.location.href,
                '_token': $('meta[name="csrf-token"]').attr('content'),
            };

            $.ajax({
                url: baseUrl + '/token/sentMail',
                type: 'post',
                data: data,
                success: function (data) {
                    $('#email').val('');
                    $('.reload').addClass('hide');
                    if(data == 'success'){
                        // Success sent email
                        Swal.fire(
                            token_message_success_callback.title,
                            token_message_success_callback.message,
                            'success'
                        );
                    }

                }
            });
        }else{
            // Error when select is empty
            Swal.fire(
                token_message_error_callback.title,
                token_message_error_callback.message,
                'error'
            );
        }
    });
});
