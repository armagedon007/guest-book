$(function(){
    $('.del-post').click(function(){
        var self = $(this);
        if(confirm('Вы уверены, что хотите удалить статью?')){
            $.post('/?route=admin/deletepost', {id:self.data('id')}, function(result){
                if(result.status == 'success'){
                    location = '/?route=admin/index';
                }
            });
        }
    });
});