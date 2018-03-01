$( document ).ready(function() {
    // $('.deleteNews').click(function(){
    //     console.log()
    // $('.confirmation').css('display', 'block');
    // $('.cancel').click(function(){
    //     $('.confirmation').css('display', 'none');
    // });
    $('.deleteNews').on('click', function () {
        var currentId = $(this).attr('id');
        var path = "{{ path('delete', {'newsId': "+currentId+"})";
        $('.confirmation').css('display', 'block');
        $('.confirmation > .delete').attr('href', path);
        $('.cancel').click(function () {
            $('.confirmation').css('display', 'none');
        });
    });
    $('.addNews').on('click', function () {
        $('.add-div').toggleClass('hide');
    });
    $('#editAlbum').on('click', function () {
        $('.edit-div').toggleClass('hide');
    });
    $('.cancelNews').on('click', function () {
        $('.add-div').addClass('hide');
        $(this).closest('form').find("input[type=text], textarea").val("");
    });

});
