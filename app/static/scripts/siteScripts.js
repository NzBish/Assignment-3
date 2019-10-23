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

        if (e.originalEvent.getModifierState("CapsLock")) {
            $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
            $("#pPassword").text("Caps Lock is on").show();
            return;
        }

        if (passwd === "") {
            $("#imgPassword").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("").hide();
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