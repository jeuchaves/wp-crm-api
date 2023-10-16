jQuery(document).ready(function () {

    console.log('script: on');
    console.log(custom_ajax_vars.ajaxurl);

    jQuery('#form__login_register').on('submit', function (event) {

        event.preventDefault();

        console.log('event: on');

        // Dados do contato
        var nome = jQuery('input[name="your-name"]').val();
        var email = jQuery('input[name="your-email"]').val();
        var whatsapp = jQuery('input[name="your-whatsapp"]').val();

        console.log("nome: " + nome);
        console.log("email: " + email);
        console.log("telefone: " + whatsapp);

        // Solicitação AJAX
        jQuery.ajax({
            type: 'POST',
            url: custom_ajax_vars.ajaxurl,
            data: {
                action: 'add_negotiation',
                nome: nome,
                email: email,
                whatsapp: whatsapp
            },
            success: function (response) {
                console.log('success');
                console.log(response);

                console.log(response.sucess);
                console.log(response.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error');
                console.log(jqXHR.status); // Código de status HTTP
                console.log(errorThrown); // Informações sobre o erro
                console.log(textStatus);
            }
        });

    });
});