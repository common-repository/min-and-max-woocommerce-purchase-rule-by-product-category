jQuery(document).ready(function(){
    //slider setting options by tabbing
    jQuery('.mamwr-inner-block ul.tabs li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('.mamwr-inner-block ul.tabs li').removeClass('current');
        jQuery('.mamwr-inner-block .tab-content').removeClass('current');
        jQuery(this).addClass('current');
        jQuery("#"+tab_id).addClass('current');
    })

})
