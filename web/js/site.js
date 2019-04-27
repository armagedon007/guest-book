var success;
$(function() {
    $('#add-post-form').submit(function(e){
        e.preventDefault();

        var self = $(this), data = new FormData(self[0]), config = {
            url: self.prop('action'),
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: success
        };
        
        $.ajax(config);
    });
    $('#save-post').click(function(){
        var form = $('#add-post-form');
        form.trigger('submit');
    });
    $('#add-post').on('show.bs.modal', function (e) {
        $('#add-post-form')[0].reset();
    });
});