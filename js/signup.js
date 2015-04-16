$(document).ready(function(){
    var usernameValid = false;
    var passwordValid = false;
    var confirmPasswordValid = false;
    var siteurl_dash = "http://sapir.psych.wisc.edu/~yan/Psycho-Project/";
    /*
     * Username input change event, 
     * gets called once the username field is changed
     *
     */
    function username_change(){
        var posturl_checkUser = siteurl_dash + 'api/checkUser_api.php';
        
        usernameValid = true;
        var user_name = $('#username').val();
        if (user_name.length < 6 || user_name.length > 20){
          usernameValid = false;
        }
        
        $.ajax({
            type: "POST",
            url: posturl_checkUser,
            cache: false,
            data: { 
                username: user_name
            }
        })
            .done(function( data ) {
                if (data == 'user exists'){
                    usernameValid = false;
                    if ($('.group_username .alert').length == 0){
                        $warning = $('<div class="alert alert-warning user_exist">Username already registered</div>');
                    }
                    $('.group_username').append($warning);
                    $('.group_username').removeClass("has-success");
                    $('.group_username').addClass("has-error"); 
                }else{
                    usernameValid = true;
                    $('.group_password').removeClass("has-error");
                    $('.group_password').addClass("has-success");
                    $('.group_password .alert .user_exist').remove();
                }
            })
            .fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
            });

        if (user_name.indexOf("'") != -1 || user_name.indexOf('"') != -1 || user_name.indexOf('\\') != -1 || user_name.indexOf('/') != -1 || user_name.indexOf('|') != -1){
          usernameValid = false;
        }

        if (usernameValid){
          $('.group_username').removeClass("has-error");
          $('.group_username').addClass("has-success");
          $('.group_username .alert').remove();
        }else{
          if ($('.group_username .alert').length == 0){
            $warning = $('<div class="alert alert-warning">Username should be between 6 to 20 characters except \, , " , \\ , | and /.</div>');
          }
          $('.group_username').append($warning);
          $('.group_username').removeClass("has-success");
          $('.group_username').addClass("has-error"); 
        }
        // enable submit button once all fields are valid
        if (usernameValid && passwordValid && confirmPasswordValid){
          $('.signup-submit').prop('disabled', false);
        }else{
          $('.signup-submit').prop('disabled', true);
        }
    }

    /*
     * Password input change event,
     * gets called once the password field is changed
     *
     */
    function password_change(){
        passwordValid = true;
        var password = $('#password').val();
        if (password.length < 6 || password.length > 20){
          passwordValid = false;
        }

        if (password.indexOf("'") != -1 || password.indexOf('"') != -1 || password.indexOf('\\') != -1 || password.indexOf('/') != -1){
          passwordValid = false;
        }

        if (passwordValid){
          $('.group_password').removeClass("has-error");
          $('.group_password').addClass("has-success");
          $('.group_password .alert').remove();
        }else{
          if ($('.group_password .alert').length == 0){
            $warning = $('<div class="alert alert-warning">Password should be between 6 and 20 characters except \', ", \\ and //.</div>');
          }
          $('.group_password').append($warning);
          $('.group_password').removeClass("has-success");
          $('.group_password').addClass("has-error");
        }
        // enable submit button once all fields are valid
        if (usernameValid && passwordValid && confirmPasswordValid){
          $('.signup-submit').prop('disabled', false);
        }else{
          $('.signup-submit').prop('disabled', true);
        }

    }

    /*
     * Confirm password input change event,
     * gets called once the password field is changed
     *
     */
    function confirm_password_change(){
        confirmPasswordValid = true;
        var password = $('#password').val();
        var cnfirm_password = $('#confirm_password').val();
        if (cnfirm_password.length < 6){
          confirmPasswordValid = false;
        }

        if (cnfirm_password.indexOf("'") != -1 || cnfirm_password.indexOf('"') != -1 || cnfirm_password.indexOf('\\') != -1 || cnfirm_password.indexOf('/') != -1){
          confirmPasswordValid = false;
        }

        if (cnfirm_password != password){
          confirmPasswordValid = false;
        }

        if (confirmPasswordValid){
          $('.group_confirm_password').removeClass("has-error");
          $('.group_confirm_password').addClass("has-success");
          $('.group_confirm_password .alert').remove();
        }else{
          if ($('.group_confirm_password .alert').length == 0){
            $warning = $('<div class="alert alert-warning">Confirm password should be matched with password.</div>');
          }
          $('.group_confirm_password').append($warning);
          $('.group_confirm_password').removeClass("has-success");
          $('.group_confirm_password').addClass("has-error");
        }
        // enable submit button once all fields are valid
        if (usernameValid && passwordValid && confirmPasswordValid){
          $('.signup-submit').prop('disabled', false);
        }else{
          $('.signup-submit').prop('disabled', true);
        }
    }
    
    // register change/keydown events
    $('#username').keyup(username_change);
    $('#password').keyup(password_change);
    $('#confirm_password').keyup(confirm_password_change);
});