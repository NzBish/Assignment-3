$(document).ready(function() {
    /*$("#inputUName").keyup(function() {

    });*/

    $("#inputPassword").keyup(function() {
        var regExpr = new RegExp("/^(?=\w*[A-Z]\w*)(\w{7,14})$/g");
        var passwd = $("#inputPassword").val();

        if (passwd === "") {
            $("#imgPassword").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("").hide();
            return;
        }

        if ((passwd.toUpperCase() === passwd) && (passwd.toLowerCase() !== passwd) && (!this.shiftKey)) {
            $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
            $("#pPassword").text("Caps Lock is on").show();
            return;
        }

        if (regExpr.test(passwd)) {
            $("#imgPassword").attr({"src": "/static/yes.png", "alt": "Password OK"}).show();
            $("#pPassword").text("").hide();
        } else {
            $("#imgPassword").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
            $("#pPassword").text("Password must be between 7 - 14 alphanumeric characters and contain at least one uppercase letter").show();
        }
    });

    $("#loginPassword").keyup(function () {
        var passwd = $("#loginPassword").val();

        if ((passwd.toUpperCase() === passwd) && (passwd.toLowerCase() !== passwd) && (!this.shiftKey)) {
            $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
            $("#pPassword").text("Caps Lock is on").show();
        } else {
            $("#imgPassword").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("").hide();
        }
    });
});