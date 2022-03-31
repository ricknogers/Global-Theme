
jQuery(function($) {
    // Add class pda-step0
    $("body.toplevel_page_pda-wthrou-guide").addClass("pda-step0");

    //Click all <a> in class pda-wthrou-step
    $(".pda-wthrou-step a.btn-next").click(function(e) {
        // Hide class parent pda-wthrou-step
        $(this).parent(".pda-wthrou-step").hide();

        // Add data-step in <a>
        // Declare next = .pda-wthrou-step.data('step') with data('step') = data-step in html
        var next = $(this).data('step');

        // Add data-class
        // Declare pda_class = .pda-wthrou-step.parent(".pda-wthrou-step").data('class');
        //                   = .pda-wthrou-step.pda-wthrou-step with data('class') = data-class
        var pda_class = $(this).parent(".pda-wthrou-step").data('class');
        //console.log(pda_class);

        // pda_class++ = .pda-wthrou-step data-class+1
        $("body.toplevel_page_pda-wthrou-guide").removeClass("pda-step" + pda_class).addClass("pda-step" + ++pda_class);
        $("." + next).fadeIn();
        e.preventDefault();

    });


    $(".pda-wthrou-step a.btn-prev").click(function(e) {
        $(this).parent(".pda-wthrou-step").hide();

        var prev = $(this).data('step');
        var pda_class = $(this).parent(".pda-wthrou-step").data('class');
        console.log(pda_class);

        $("body.toplevel_page_pda-wthrou-guide").removeClass("pda-step" + pda_class).addClass("pda-step" + --pda_class);
        $("." + prev).fadeIn();
        e.preventDefault();

    });


});
