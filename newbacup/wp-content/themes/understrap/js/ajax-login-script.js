jQuery(document).ready(function($) {

    // Perform AJAX login on form submit
    $('button#btnLoginSubmit').on('click', function(e){

        e.preventDefault();
        e.stopPropagation();

        var $this =  $(this)

        if($('#username').val()==''){
            $('#username').addClass('invalid');
            $('#username').parents('.field').find('.error').text('Username is required').show();
			$('form#ajaxLogin .login-response-msg').text('').removeClass('invalid').hide();
        } else {
            $('#username').removeClass('invalid');
            $('#username').parents('.field').find('.error').text('').hide();
        }
        if($('#password').val()==''){
            $('#password').addClass('invalid');
            $('#password').parents('.field').find('.error').text('Password is required').show();
			$('form#ajaxLogin .login-response-msg').text('').removeClass('invalid').hide();
        } else {
            $('#password').removeClass('invalid');
            $('#password').parents('.field').find('.error').text('').hide();
        }
        if (($('#username').val()=='' || $('#password').val()=='')){
            return;
        }

        $('form#ajaxLogin .login-response-msg').removeClass('success invalid').show().text(ajax_login_object.loadingmessage);


        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
			beforeSend: function () {
				$this.prop('disabled', true);
			},
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#ajaxLogin #username').val(),
                'password': $('form#ajaxLogin #password').val(),
                'security': $('form#ajaxLogin #security').val() },
            success: function(data){
                if (data.loggedin == true){
                    // document.location.href = ajax_login_object.redirecturl;
                    // $('form#ajaxLogin .login-response-msg').text(data.message).addClass('success').show();
                    setTimeout(function () {
                        window.location.replace(data.redirecturl);
                    },1000);
                } else if (data.loggedin == false){
                    $('form#ajaxLogin .login-response-msg').text(data.message).addClass('invalid').show();
                }
				$this.prop('disabled', false);
            }
        });
        e.preventDefault();

    });



    $(document).on('click', '#btnCartLogin', function (e) {
    	e.preventDefault();
		$('#ajaxRegisterModal').modal('hide');
		$('#ajaxLoginModal').modal('show');
	});



});
