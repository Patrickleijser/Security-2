$(document).ready(function() {

    $('body').on('click', 'button', function(e) {
        e.preventDefault();
        var _self = $(this);

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: _self.closest('form').serialize(),
            success: function(res) {
                console.log(res);
                if(res.type == 'encrypt') {
                    _self.next('.message').text(res.msg);
                } else {
                    _self.next('.message').text(res.msg);
                   $('#message2').val(res.decryptedMessage);
                }
            }
        });
    });


});