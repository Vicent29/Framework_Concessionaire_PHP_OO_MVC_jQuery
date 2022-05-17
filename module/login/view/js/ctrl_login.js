function login() {
    if (validate_login() != 0) {
        var data = $('#login__form').serialize();
        ajaxPromise('?module=login&op=login', 'POST', 'JSON', data)
            .then(function (result) {
                console.log(result);
                if (result == "error_user") {
                    document.getElementById('error_username_log').innerHTML = "* El usario no existe,asegurase de que lo a escrito correctamente"
                } else if (result == "error_actiavate") {
                    toastr.warning("El usario esta desacivado, revise su bandeja de entrada");
                    setTimeout(' window.location.href = "?module=home&op=view"; ', 1000);
                }
                else if (result == "error_passwd") {
                    document.getElementById('error_passwd_log').innerHTML = "* La contraseña es incorrecta"
                } else {
                    localStorage.setItem("token", result);
                    toastr.success("Loged succesfully");

                    if (localStorage.getItem('redirect_like')) {
                        setTimeout(' window.location.href = "?module=shop&op=list"; ', 1000);
                    } else {
                        setTimeout(' window.location.href = "?module=home&op=view"; ', 1000);
                    }
                }
            }).catch(function (textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });
    }
}

function click_login() {
    //LOGIN
    $("#login").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            login();
        }
    });

    $('#login').on('click', function (e) {
        e.preventDefault();
        login();
    });
    //MODIFICATE PASSWORD 
    $('#modificate_passwd').on('click', function (e) {
        e.preventDefault();
        load_form_rec_email("modificate");
    });
    //RECOVER PASSWORD 
    $('#recover_passwd').on('click', function (e) {
        e.preventDefault();
        load_form_rec_email("recover");
    });
    //LOGIN GIT HUB
    $('#login_git').on('click', function (e) {
        e.preventDefault();
        social_login("github");

    });
    //LOGIN GOOGLE
    $('#login_google').on('click', function (e) {
        e.preventDefault();
        social_login("google");
    });
}

function validate_login() {
    var error = false;

    if (document.getElementById('username_log').value.length === 0) {
        document.getElementById('error_username_log').innerHTML = "* Tienes que escribir el usuario";
        error = true;
    } else {
        if (document.getElementById('username_log').value.length < 5) {
            document.getElementById('error_username_log').innerHTML = "* El usuario tiene que tener 5 caracteres como minimo";
            error = true;
        } else {
            document.getElementById('error_username_log').innerHTML = "";
        }
    }

    if (document.getElementById('passwd_log').value.length === 0) {
        document.getElementById('error_passwd_log').innerHTML = "* Tienes que escribir la contraseña";
        error = true;
    } else {
        document.getElementById('error_passwd_log').innerHTML = "";
    }

    if (error == true) {
        return 0;
    }
}

// -------------------RECOVER-PASSWORD----------------
function load_form_rec_email(opc) {
    $('.login-wrap').hide();
    $('.modificate-passwd-html').hide();
    $('.recover-email-html').show();

    button_send_email_rec(opc);
}

function button_send_email_rec(opc) {
    $("#send_recover").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            send_email_recover_passwd(opc);
        }
    });

    $('#send_recover').on('click', function (e) {
        e.preventDefault();
        send_email_recover_passwd(opc);
    });
}

function send_email_recover_passwd(opc) {
    if (validate_send_email_rec() != 0) {
        var data = $('#rec_passwd_email').serialize() + '&opc_passswd=' + opc;
        ajaxPromise('?module=login&op=send_recover_email', 'POST', 'JSON', data)
            .then(function (data) {
                console.log(data);
                if (data == "error_email") {
                    $("#error_email_rec").html("* The email doesn't exist");
                } else if (data == "email_social_login") {
                    toastr.error("You can't change the password, the email belongs to another company");
                    setTimeout('window.location.href = "?module=login&op=login_register_view"; ', 2500);
                } else {
                    localStorage.setItem("email_token", data);
                    toastr.warning("Check email sended");
                    setTimeout('window.location.href = "?module=login&op=login_register_view"; ', 1500);
                }
            }).catch(function () {
                console.log("Error send_email_recover_passwd");
            });
    }
}
function validate_send_email_rec() {
    var mail_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var error = false;

    if (document.getElementById('email_rec').value.length === 0) {
        document.getElementById('error_email_rec').innerHTML = "* Tienes que escribir un correo";
        error = true;
    } else {
        if (!mail_exp.test(document.getElementById('email_rec').value)) {
            document.getElementById('error_email_rec').innerHTML = "* Debe de cumplir el formato de email";
            error = true;
        } else {
            document.getElementById('error_email_rec').innerHTML = "";
        }
    }

    if (error == true) {
        return 0;
    }
}

function load_form_new_passwd() {
    if (localStorage.getItem('email_token')) {
        toastr.success("Verified email to change password");
        var email_token = localStorage.getItem('email_token');
        localStorage.removeItem('email_token');
        ajaxPromise('?module=login&op=verify_email_token', 'POST', 'JSON', { 'email_token': email_token })
            .then(function (data) {
                if (data == "correctly_email") {
                    click_change_password(email_token);
                } else {
                    console.log("error email token");
                }
            }).catch(function () {
                console.log('Error cheked email_token');
            });
        //Mejora para cuando dan para que el link de recover passwd del email solo se puieda usar una vez para que sea mass seguro
    } else {
        toastr.warning("The time to change the password has expired");
        setTimeout(' window.location.href = "?module=login&op=login_register_view";', 1500);
    }
}

function click_change_password(email_token) {
    // MODIFICATE PASSWD
    $("#modificate_passwd_bt").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            send_new_passwd(email_token, "modificate");
        }
    });
    $('#modificate_passwd_bt').on('click', function (e) {
        e.preventDefault();
        send_new_passwd(email_token, "modificate");
    });
    // RECOVER PASSWD
    $("#recover_passwd_bt").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            send_new_passwd(email_token, "recover");
        }
    });
    $('#recover_passwd_bt').on('click', function (e) {
        e.preventDefault();
        send_new_passwd(email_token, "recover");
    });
}

function send_new_passwd(email_token, opc_passwd) {
    if (opc_passwd == "modificate") {
        if (validate_modificate_password() != 0) {
            var data = $('#modificate-passwd').serialize() + '&email_token=' + email_token;
            console.log(data);
            ajaxPromise('?module=login&op=send_new_passwd_modificate', 'POST', 'JSON', data)
                .then(function (data) {
                    if (data == "error_old_passwd") {
                        $("#error_old_passwd").html("* The passsword is incorrect");
                    } else if (data == "correctly_update") {
                        $("#error_old_passwd").html("");
                        toastr.success("Password changed successfully");
                        setTimeout('window.location.href = "?module=login&op=login_register_view&load_all_view"; ', 1500);
                    }
                // }).catch(function () {
                //     console.log("Error send_email_modificate_passwd");
                });
        }
    } else if (opc_passwd == "recover") {
        if (validate_recover_password() != 0) {
            var data = $('#recover-passwd').serialize() + '&email_token=' + email_token;
            ajaxPromise('?module=login&op=send_new_passwd_recover', 'POST', 'JSON', data)
                .then(function (data) {
                    if (data == "correctly_update") {
                        toastr.success("Password changed successfully");
                        setTimeout('window.location.href = "?module=login&op=login_register_view&load_all_view"; ', 1500);
                    }
                }).catch(function () {
                    console.log("Error send_email_recover_passwd");
                });
        }
    }
}

function validate_modificate_password() {
    var pssswd_exp = /^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/;
    var error = false;

    if (document.getElementById('old_passwd').value.length === 0) {
        document.getElementById('error_old_passwd').innerHTML = "* Tienes que escribir tu antigua contraseña";
        error = true;
    } else {
        if (document.getElementById('old_passwd').value.length < 8) {
            document.getElementById('error_old_passwd').innerHTML = "* La password tiene que tener 8 caracteres como minimo";
            error = true;
        } else {
            if (!pssswd_exp.test(document.getElementById('old_passwd').value)) {
                document.getElementById('error_old_passwd').innerHTML = "* Debe de contener mayusculas, minusculas y simbolos especiales";
                error = true;
            } else {
                document.getElementById('error_old_passwd').innerHTML = "";
            }
        }
    }

    if (document.getElementById('new_passwd').value.length === 0) {
        document.getElementById('error_new_passwd').innerHTML = "* Debe de introducir la nueva contraseña";
        error = true;
    } else if (!pssswd_exp.test(document.getElementById('new_passwd').value)) {
        document.getElementById('error_new_passwd').innerHTML = "* Debe de contener mayusculas, minusculas y simbolos especiales";
        error = true;
    } else {
        if (document.getElementById('new_passwd').value.length < 8) {
            document.getElementById('error_new_passwd').innerHTML = "La password tiene que tener 8 caracteres como minimo";
            error = true;
        } else {
            if (document.getElementById('new_passwd').value === document.getElementById('old_passwd').value) {
                document.getElementById('error_new_passwd').innerHTML = "* Introduzca una pasword diferente a la anterior";
            } else {
                document.getElementById('error_new_passwd').innerHTML = "";
            }
        }
    }
    if (error == true) {
        return 0;
    }
}

function validate_recover_password() {
    var pssswd_exp = /^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/;
    var error = false;
    if (document.getElementById('new_passwd1').value.length === 0) {
        document.getElementById('error_new_passwd1').innerHTML = "* Debe de introducir la nueva contraseña";
        error = true;
    }else if (document.getElementById('new_passwd1').value.length < 8) {
        document.getElementById('error_new_passwd1').innerHTML = "* La password tiene que tener 8 caracteres como minimo";
        error = true;
    } else {
        if (!pssswd_exp.test(document.getElementById('new_passwd1').value)) {
            document.getElementById('error_new_passwd1').innerHTML = "* Debe de contener mayusculas, minusculas y simbolos especiales";
            error = true;
        } else {
            document.getElementById('error_new_passwd1').innerHTML = "";
        }
    }
    if (document.getElementById('new_passwd2').value.length === 0) {
        document.getElementById('error_new_passwd2').innerHTML = "* Debe de repetir la nueva contraseña";
        error = true;
    }else if (document.getElementById('new_passwd2').value.length < 8) {
        document.getElementById('error_new_passwd2').innerHTML = "* La password tiene que tener 8 caracteres como minimo";
        error = true;
    } else {
        if (!pssswd_exp.test(document.getElementById('new_passwd2').value)) {
            document.getElementById('error_new_passwd2').innerHTML = "* Debe de contener mayusculas, minusculas y simbolos especiales";
            error = true;
        } else {
            if (document.getElementById('new_passwd1').value !== document.getElementById('new_passwd2').value) {
                document.getElementById('error_new_passwd2').innerHTML = "* La contarseña se debe repetir";
                error = true;
            }else{
                document.getElementById('error_new_passwd2').innerHTML = "";
            }
        }
    }
    if (error == true) {
        return 0;
    }
}

// -------------------SOCIAL-LOGIN----------------
function social_login(param) {
    authService = firebase_config();
    authService.signInWithPopup(provider_config(param))
        .then(function (result) {
            var data_user = { id: result.user.uid, username: result.user.displayName, email: result.user.email, avatar: result.user.photoURL, provider: result.credential.provider };
            if (result) {
                ajaxPromise("?module=login&op=social_login", 'POST', 'JSON', data_user)
                    .then(function (data) {
                        if (data == "error_insert") {
                            toastr.error('Error logging in, you may already have an account.');
                        } else {
                            localStorage.setItem("token", data);
                            toastr.success("Loged succesfully");

                            if (localStorage.getItem('redirect_like')) {
                                setTimeout(' window.location.href = "?module=shop&op=list"; ', 1000);
                            } else {
                                setTimeout(' window.location.href = "?module=home&op=view"; ', 1000);
                            }
                        }
                    });
            }
        }).catch(function (error) {
            console.log('Error social login', error);
        });
}

function firebase_config() {
    var config = {
        apiKey: "AIzaSyAzGh7-z5tVoeu04I4HMqlz4bE4xSgbLSg",
        authDomain: "web-concesionario.firebaseapp.com",
        databaseURL: "https://web-concesionario.firebaseio.com",
        projectId: "web-concesionario",
        storageBucket: "web-concesionario.appspot.com",
        messagingSenderId: "369700061150"  //613764177727    
    };
    if (!firebase.apps.length) {
        firebase.initializeApp(config);
    } else {
        firebase.app();
    }
    return authService = firebase.auth();
}

function provider_config(param) {
    if (param === 'google') {
        var provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        return provider;
    } else if (param === 'github') {
        return provider = new firebase.auth.GithubAuthProvider();
    }
}



// -------------------LOAD-CONTENT----------------
function load_content() {
    let path = window.location.search.split('&');
    if (path[2] === 'verify') {
        ajaxPromise('?module=login&op=verify_email', 'POST', 'JSON', { token_email: path[3] })
            .then(function (data) {
                toastr.success("Verify email succesfully");
                setTimeout(' window.location.href = "?module=home&op=view&load_all_view";', 1500);
            }).catch(function () {
                console.log('Error: verify email error');
            });

    } else if (path[2] === 'recover') {
        window.location.href = "?module=login&op=login_register_view&load_recover";
    } else if (path[2] === 'modificate') {
        window.location.href = "?module=login&op=login_register_view&load_modificate";
    }
    if (path[2] === 'load_recover') {
        $('.recover-passwd-html').show();
        $('.login-wrap').hide();
        load_form_new_passwd();
    } else if (path[2] === 'load_modificate') {
        $('.modificate-passwd-html').show();
        $('.login-wrap').hide();
        load_form_new_passwd();
    } else if (path[2] === 'load_all_view') {
        $('.recover-email-html').hide();
        $('.recover-passwd-html').hide();
        $('.login-wrap').show();
    }
}

function forms_hide() {
    $('.recover-email-html').hide();
    $('.recover-passwd-html').hide();
    $('.modificate-passwd-html').hide();
}


$(document).ready(function () {
    forms_hide();
    click_login();
    load_content();
    firebase_config();
});