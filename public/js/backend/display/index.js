var getId = $('#department').val();

$(document).ready(function() {
    getUrlTextButton();

    $(document).on('change', '#department', function () {
        getId = $(this).val();
        getUrlTextButton();
    })
});

function getUrlTextButton(){
    var urlDisplay = '<a href="' + baseUrl + '/display/' + getId + '" target="_blank"> <button class="btn btn-primary">' + baseUrl + '/display/' + getId + '</button></a>';
    $('.url').html('');
    $('.url').html(urlDisplay);
}
