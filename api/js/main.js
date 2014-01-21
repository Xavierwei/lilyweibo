$('document').ready(function(){
    if(jQuery('body').hasClass('page-admin-comments') || jQuery('body').hasClass('node-type-comments')){
        jQuery('.edit-comment').addClass('actived');
    }

    if(jQuery('body').hasClass('page-node-add-comments')){
        jQuery('.add-comment').addClass('actived');
    }

    if(jQuery('body').hasClass('admin-index')||jQuery('body').hasClass('admin')){
        jQuery('.admin-photo').addClass('actived');
    }

    if(jQuery('body').hasClass('admin-user')){
        jQuery('.admin-user').addClass('actived');
    }
});