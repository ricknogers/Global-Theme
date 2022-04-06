(function($, window){
    $(document).ready(function(){
        $('.form-toggle').click(function(){
            $('.sidebar-contact').toggleClass('active')
            $('.form-toggle').toggleClass('active')
        })
    });
})(jQuery, window);