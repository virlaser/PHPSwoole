$(function () {
    $('#discuss-box').keydown(function (event) {
        if(event.keyCode == 13) {
            var text = $(this).val();
            var url = "http://127.0.0.1:8811/?s=index/Chart/index";
            var data = {'content':text, 'game_id':1};

            $.post(url, data, function (result) {
                // todo
            }, 'json');
        }
    })
});

