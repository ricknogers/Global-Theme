var $ = jQuery.noConflict();
$(document).ready(function($){
    if ($('#pda-search-no-access-page').val()) {
        $('#pda-clear-search').show();
    } else {
        $('#pda-clear-search').hide();
    }
    $('#pda-search-no-access-page').keyup(function() {
        if ($(this).val()) {
            $('#pda-clear-search').show();
        } else {
            $('#pda-clear-search').hide();
        }

        $('#pda-result-existing-page-post').val('');
        $("#pda-search-result").empty();
        $('#pda-error-nap-existing-page-post').hide();
        var search = $("#pda-search-no-access-page").val();
        var data = {
            'action': 'wp-link-ajax',
            'search': search,
            'page':	1,
            '_ajax_linking_nonce': $("#_ajax_linking_nonce").val(),
        };
        console.log(data);
        if (search.length > 2) {
            var searchTimer;
            $('#pda-clear-search').hide();
            $('#pda-search-loading').show();
            window.clearTimeout( searchTimer );
            window.setTimeout( function() {
                $.ajax({
                        type: "POST",
                        url: server_data.ajax_url,
                        data: data,
                        dataType: "json",
                        success: function (res) {
                            $("#pda-search-result").empty();
                            for (var i = 0; i < res.length; i++) {
                                if( res[i].title === '' ) {
                                    $("#pda-search-result").append('<li id="' + res[i].permalink + '">(no title)</li>');
                                } else {
                                    $("#pda-search-result").append('<li id="' + res[i].permalink + '">' + res[i].title + '</li>');
                                }
                                $('#pda-clear-search').show();
                                $('#pda-search-loading').hide();
                                $('.pda-wrap-search-research').show();
                            }
                            if ( ! res ) {
                                $('#pda-clear-search').show();
                                $('#pda-search-loading').hide();
                            }
                        }
                    });
            }, 500 );
        } else {
            $("#pda-search-result").empty();
            $('#pda-search-loading').hide();
        }
    });

    $('#pda-search-result').click(function(evt) {
        evt.preventDefault();
        var title = evt.target.innerText;
        var link = evt.target.id;
        $("#pda-result-existing-page-post").val(link+';'+title);
        $(this).empty();
        $('#pda-error-nap-existing-page-post').hide();
        $('#pda-search-no-access-page').val(title);
    });

    $('.pda-wrap-search-research').mouseleave(function() {
        $(".pda-wrap-search-research").hide();
    });

    $('#pda-search-no-access-page').mouseenter(function() {
        $('.pda-wrap-search-research').show();
    });

});
