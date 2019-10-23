$(document).ready(function() {
    $("#inputUName").keyup(function() {
        var regExpr = new RegExp(/^([a-zA-Z0-9]+)$/);
        var uname = $("#inputUName").val();

        if (uname !== "") {
            if (!regExpr.test(uname)) {
                $("#imgUName").attr({"src": "/static/no.png", "alt": "Username not OK"}).show();
                $("#pUName").text("Username must only contain alphanumeric characters").show();
                return;
            }

            $("#imgUName").attr({"src": "/static/loading.gif", "alt": "Loading..."}).show();

            $.ajax({
                url: "/users/check/",
                method: "post",
                data: {checkName: uname},
                dataType: "text",
                success: function(unique) {
                    if (unique === "unique") {
                        $("#imgUName").attr({"src": "/static/yes.png", "alt": "Username OK"}).show();
                        $("#pUName").text("").hide();
                    } else {
                        $("#imgUName").attr({"src": "/static/no.png", "alt": "Username not OK"}).show();
                        $("#pUName").text("Username is not unique").show();
                    }
                }
            });
        } else {
            $("#imgUName").attr({"src": "", "alt": ""}).hide();
            $("#pUName").text("").hide();
        }
    });

    $("#inputPassword").keyup(function(e) {
        var regExpr = new RegExp(/^(?=[a-zA-Z0-9]*[A-Z][a-zA-Z0-9]*)([a-zA-Z0-9]{7,14})$/);
        var passwd = $("#inputPassword").val();
        var passwd2 = $("#inputPassword2").val();

        if (e.originalEvent !== undefined) {
            if (e.originalEvent.getModifierState("CapsLock")) {
                $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
                $("#pPassword").text("Caps Lock is on").show();
                return;
            }
        }

        if (passwd === "") {
            $("#imgPassword").attr({"src": "", "alt": ""}).hide();
            $("#imgPassword2").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("").hide();
            return;
        }

        if (regExpr.test(passwd)) {
            if (passwd2 === "") {
                $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Password not OK"}).show();
                $("#imgPassword2").attr({"src": "/static/warning.png", "alt": "Password not OK"}).show();
                $("#pPassword").text("Please re-enter your password").show();
            } else {
                $("#inputPassword2").keyup();
            }
        } else {
            $("#imgPassword").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
            $("#imgPassword2").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("Password must be between 7 - 14 alphanumeric characters and contain at least one uppercase letter").show();
        }
    });

    $("#inputPassword2").keyup(function (e) {
        var passwd = $("#inputPassword").val();
        var passwd2 = $("#inputPassword2").val();

        if (e.originalEvent !== undefined) {
            if (e.originalEvent.getModifierState("CapsLock")) {
                $("#imgPassword2").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
                $("#pPassword2").text("Caps Lock is on").show();
                return;
            }
        }

        if (passwd2 !== "") {
            if (passwd === passwd2) {
                $("#imgPassword").attr({"src": "/static/yes.png", "alt": "Password OK"}).show();
                $("#imgPassword2").attr({"src": "/static/yes.png", "alt": "Password OK"}).show();
                $("#pPassword").text("").hide();
            } else {
                $("#imgPassword").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
                $("#imgPassword2").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
                $("#pPassword").text("Passwords do not match").show();
            }
        } else {
            $("#imgPassword2").attr({"src": "", "alt": ""}).hide();
            $("#inputPassword").keyup();
        }

        $("#pPassword2").text("").hide();
    });

    $("#loginPassword").keyup(function (e) {
        if (e.originalEvent.getModifierState("CapsLock")) {
            $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
            $("#pPassword").text("Caps Lock is on").show();
        } else {
            $("#imgPassword").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("").hide();
        }
    });

    $("#searchProducts").keyup(function(){
        var txt = $(this).val();
        if(txt!=='')
        {
            $.ajax({
                url:"/retrieve",
                method:"post",
                data:{search:txt},
                dataType:"text",
                success:function (data)
                {
                    $("#results").html(data);
                }
            });

        }
        else
        {
            $("tbody").html('');

        }
    });
});