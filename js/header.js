$(document).ready(function(){
    var SidebarMenuEffects = (function() {

        function initSidebarEffects() {

            var container = $( '#st-container' );
            var resetMenu = function() { container.removeClass('st-menu-open'); };

            var the_button = $('#menu-btn');
            var effect = the_button.attr('data-effect');

            the_button.click(function( ev ) {
                ev.stopPropagation();
                ev.preventDefault();
                container.className = 'st-container'; // clear
                container.addClass( effect );
                setTimeout( function() {
                    container.addClass( 'st-menu-open' );
                }, 25 );
                $('body').click(function(event){
                    event.stopPropagation();
                    resetMenu();
                    setTimeout(function(e){
                        if(!$('#st-container').hasClass('st-menu-open')){
                            the_button.css("opacity", 1);
                        }
                    }, 500);
                });
                $('.st-menu').click(
                    function(event){
                        event.stopPropagation();
                    }
                );
            });

        }

        initSidebarEffects();

    })();
    
    
});