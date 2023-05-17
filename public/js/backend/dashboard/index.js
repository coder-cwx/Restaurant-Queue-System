"use strict";
$(function () {

    setInterval(function(){
        $.ajax({
            url: baseUrl + '/dashboard/checkUserOfflineOnline',
            type: 'get',
            dataType: 'json',
            success: function (getData) {
                var appendData = '';
                var getStatus;
                var getBranch;

                $('.badge.badge-danger').text(getData.length + ' Members');

                $.each(getData, function (i, getData) {
                    var image = baseUrl + '/uploads/' + getData.image;

                    if(getData.is_online == 0){
                        getStatus = 'Offline';
                    }else{
                        getStatus = 'Online';
                    }

                    if(getData.id_branch !== null && getData.id_branch !== undefined){
                        getBranch = getData.branch.name;
                    }else{
                        getBranch = '-';
                    }

                    appendData +=
                        '<li>' +
                        '<img src="'+image+'" alt="'+getData.name+'" width="80">' +
                        '<a class="users-list-name" href="#">'+getData.name+'</a>' +
                        '<span class="users-list-name"><i class="fa fa-building" aria-hidden="true"></i> ' + getBranch + '</span>' +
                        '<span class="users-'+getStatus+'">'+getStatus+'</span>' +
                        '</li>';
                });

                $('.users-list').html("");
                $('.users-list').append(appendData);
            }
        });
    }, 4000);
});
