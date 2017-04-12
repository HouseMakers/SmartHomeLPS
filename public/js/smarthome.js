(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.AlertManager = {
        showAlertSuccess: function(title, message) {
            new PNotify({
                title: title,
                text: message,
                type: 'success',
                hide: true,
                styling: 'bootstrap3'
            });
        },
        
        showAlertDanger: function(title, message) {
            new PNotify({
                title: title,
                text: message,
                type: 'error',
                hide: true,
                styling: 'bootstrap3'
            });
        }
    };
    
})(jQuery, $.AdminLTE);