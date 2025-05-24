;(function($) {
    "use strict";  
    
    //* Form js
    function verificationForm(){
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

    // ───── Neue Funktion: Aktualisiert den grünen Balken ─────
    function updateProgressLine() {
        var $bar    = $("#progressbar");
        var $active = $bar.find("li.active").last();
        // Berechne die x‑Position der Mitte des letzten aktiven Kreises
        var center  = $active.position().left + $active.outerWidth() / 2;
        // Setze die CSS‑Variable in Pixeln, so endet die Linie exakt dort
        $bar.css("--progress", center + "px");
      }
    // ────────────────────────────────────────────────────────────

        var $bar   = $("#progressbar");
        var $first = $bar.find("li.active");

        // Icon bleibt anfangs grau
        $first.removeClass("active");

        // Ziel‑Position der Mitte berechnen
        var center = $first.position().left + $first.outerWidth() / 2;

        // Linie zunächst auf 0 setzen …
        $bar.css("--progress", "0px");

        // … und sofort animieren
        setTimeout(function(){
        $bar.css("--progress", center + "px");
        // Icon färben, sobald die CSS‑Transition (0.8s) wirklich durchgelaufen ist
        setTimeout(function(){
            $first.addClass("active");
        }, 320);
        }, 20);

        $(".next").click(function () {
            if (animating) return false;
            animating = true;
        
            current_fs = $(this).closest("fieldset");
            next_fs    = current_fs.next();
        
            // ───────────────────────────────────────────────────────────────
            // statt sofort .addClass + updateProgressLine
            var idx   = $("fieldset").index(next_fs);
            var $next = $("#progressbar li").eq(idx);
            var center = $next.position().left + $next.outerWidth() / 2;
            $("#progressbar").css("--progress", center + "px");
            setTimeout(function(){
              $next.addClass("active");
            }, 800);
            // ───────────────────────────────────────────────────────────────
        
            // Feldset‑Animation bleibt wie gehabt
            next_fs.show();
            current_fs.animate({ opacity: 0 }, {
                step: function (now, mx) {
                    scale   = 1 - (1 - now) * 0.2;
                    left    = (now * 50) + "%";
                    opacity = 1 - now;
                    current_fs.css({
                        transform: "scale(" + scale + ")",
                        position:  "absolute"
                    });
                    next_fs.css({ left: left, opacity: opacity });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                easing: "easeInOutBack"
            });
        });
          

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).closest('fieldset');
            previous_fs = current_fs.prev();


            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            // ─── Fortschrittslinie animieren ───
            updateProgressLine();

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".submit").click(function () {
            return false;
        })
    }; 
    
    //* Add Phone no select
    function phoneNoselect(){
        if ( $('#msform').length ){   
            $("#phone").intlTelInput(); 
            $("#phone").intlTelInput("setNumber", "+880"); 
        };
    }; 
    //* Select js
    function nice_Select(){
        if ( $('.product_select').length ){ 
            $('select').niceSelect();
        };
    }; 
    /*Function Calls*/  
    verificationForm ();
    phoneNoselect ();
    nice_Select ();
})(jQuery);