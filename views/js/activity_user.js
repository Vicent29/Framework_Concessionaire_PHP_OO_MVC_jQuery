function protecturl() {

    var token = localStorage.getItem('token');
    ajaxPromise('module/login/ctrl/ctrl_login.php?op=controluser', 'POST', 'JSON', { 'token': token })
        .then(function(data) {
            console.log(data);

            if (data == "Correct_User") {
                console.log("CORRECTO-->El usario coincide con la session");
            } else if (data == "Wrong_User") {
                console.log("INCORRCTO--> Estan intentando acceder a una cuenta");
                logout_auto();
            }
        })
        .catch(function() { console.log("ANONYMOUS_user") });
}

function control_activity() {
    var token = localStorage.getItem('token');
    if (token) {
        ajaxPromise('module/login/ctrl/ctrl_login.php?op=actividad', 'POST', 'JSON')
            .then(function(response) {
                if (response == "inactivo") {
                    console.log("usuario INACTIVO");
                    logout_auto();
                } else {
                    console.log("usuario ACTIVO")
                }

            });
    } else {
        console.log("No hay usario logeado");
    }

}

function refresh_token() {
    var token = localStorage.getItem('token');
    if (token) {
        ajaxPromise('module/login/ctrl/ctrl_login.php?op=refresh_token', 'POST', 'JSON', { 'token': token })
            .then(function(data_token) {
                console.log("Refresh token correctly");
                localStorage.setItem("token", data_token);
                load_menu();
            });
    }

}

function refresh_cookie() {
    ajaxPromise('module/login/ctrl/ctrl_login.php?op=refresh_cookie', 'POST', 'JSON')
        .then(function(response) {
            console.log("Refresh cookie correctly");
        });
}

function logout_auto() {

    localStorage.removeItem('token');
    toastr.warning("Se ha cerrado la cuenta por seguridad!!");
    setTimeout('window.location.href = "index.php?module=ctrl_login&op=login-register_view";', 2000);


}

$(document).ready(function() {
    setInterval(function() { control_activity() }, 600000); //10min= 600000
    protecturl();
    setInterval(function() { refresh_token() }, 600000);
    setInterval(function() { refresh_cookie() }, 600000);
});