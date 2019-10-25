$(document).ready(function() {
    /**
     * Username validity check
     *
     * Uses Regex and AJAX to confirm if the username the user is entering:
     * - Contains only alphanumeric characters (Regex)
     * - Is not already in use (AJAX)
     * Changes the contents of #imgUName and #pUName depending on the result of these tests
     * Also enables or disables #createButton depending on the results
     *
     * @uses $("#inputUName").val() The text content of the #inputUName input field
     */
    $("#inputUName").keyup(function() {
        var regExpr = new RegExp(/^([a-zA-Z0-9]+)$/);
        var uname = $("#inputUName").val();

        if (uname !== "") {
            if (!regExpr.test(uname)) {
                $("#imgUName").attr({"src": "/static/no.png", "alt": "Username not OK"}).show();
                $("#pUName").text("Username must only contain alphanumeric characters").show();
                $("#createButton").prop("disabled", true);
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
                        $("#createButton").prop("disabled", false);
                    } else {
                        $("#imgUName").attr({"src": "/static/no.png", "alt": "Username not OK"}).show();
                        $("#pUName").text("Username is not unique").show();
                        $("#createButton").prop("disabled", true);
                    }
                }
            });
        } else {
            $("#imgUName").attr({"src": "", "alt": ""}).hide();
            $("#pUName").text("").hide();
            $("#createButton").prop("disabled", true);
        }
    });

    /**
     * Password validity check 1
     *
     * Checks if CapsLock is on and alerts the user if so
     * Uses Regex to confirm if the password the user is entering meets requirements
     * If so, checks if the user has entered anything in the second password field, and either:
     * - Calls #("#inputPassword2").keyup
     * or:
     * - Alerts the user that they need to re-enter their password in this field
     * Changes the contents of #imgPassword, #imgPassword2, and #pPassword depending on the result of these tests
     * Also enables or disables #createButton depending on the results
     *
     * @uses $("#inputPassword").val() The text content of the #inputPassword input field
     * @uses $("#inputPassword2").val() The text content of the #inputPassword2 input field
     */
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
            $("#createButton").prop("disabled", true);
            return;
        }

        if (regExpr.test(passwd)) {
            if (passwd2 === "") {
                $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Password not OK"}).show();
                $("#imgPassword2").attr({"src": "/static/warning.png", "alt": "Password not OK"}).show();
                $("#pPassword").text("Please re-enter your password").show();
                $("#createButton").prop("disabled", true);
            } else {
                $("#inputPassword2").keyup();
            }
        } else {
            $("#imgPassword").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
            $("#imgPassword2").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("Password must be 7 - 14 alphanumeric characters with 1+ uppercase letters").show();
            $("#createButton").prop("disabled", true);
        }
    });

    /**
     * Password validity check 2
     *
     * Checks if CapsLock is on and alerts the user if so
     * If the contents of #inputPassword2 are not empty:
     * - Checks if #pPassword contains a specific error and does nothing if so
     * - Checks if the contents of #inputPassword and #inputPassword2 match
     * Changes the contents of #imgUName and #pUName depending on the result of these tests
     * Also enables or disables #createButton depending on the results
     *
     * @uses $("#inputPassword").val() The text content of the #inputPassword input field
     * @uses $("#inputPassword2").val() The text content of the #inputPassword2 input field
     */
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
            if ($("#pPassword").text() === "Password must be 7 - 14 alphanumeric characters with 1+ uppercase letters") {
                return;
            }
            if (passwd === passwd2) {
                $("#imgPassword").attr({"src": "/static/yes.png", "alt": "Password OK"}).show();
                $("#imgPassword2").attr({"src": "/static/yes.png", "alt": "Password OK"}).show();
                $("#pPassword").text("").hide();
                $("#createButton").prop("disabled", false);
            } else {
                $("#imgPassword").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
                $("#imgPassword2").attr({"src": "/static/no.png", "alt": "Password not OK"}).show();
                $("#pPassword").text("Passwords do not match").show();
                $("#createButton").prop("disabled", true);
            }
        } else {
            $("#imgPassword2").attr({"src": "", "alt": ""}).hide();
            $("#inputPassword").keyup();
            $("#createButton").prop("disabled", true);
        }

        $("#pPassword2").text("").hide();
    });

    /**
     * Login password CapsLock check
     *
     * Checks if CapsLock is on and alerts the user if so
     * Changes the contents of #imgPassword and #pPassword depending on the result of this test
     */
    $("#loginPassword").keyup(function (e) {
        if (e.originalEvent.getModifierState("CapsLock")) {
            $("#imgPassword").attr({"src": "/static/warning.png", "alt": "Warning"}).show();
            $("#pPassword").text("Caps Lock is on").show();
        } else {
            $("#imgPassword").attr({"src": "", "alt": ""}).hide();
            $("#pPassword").text("").hide();
        }
    });

    /**
     * Search results asynchronous loading
     *
     * Uses AJAX to load in a search result table of products based on what the user has entered so far
     * Changes the content of #loadingWheel to either be hidden or contain the loading wheel .gif image depending
     * on the status of the current AJAX request
     * Changes the content of #results to contain either this table, an error message, or nothing depending on the
     * user's input
     *
     * @uses $(this).val() The text content of the #searchProducts input field
     */
    $("#searchProducts").keyup(function() {
        var txt = $(this).val();

        if (txt !== "") {
            $("#loadingWheel").attr({"src": "/static/loading.gif", "alt": "Loading..."}).show();

            $.ajax({
                url:"/search/retrieve/",
                method:"post",
                data:{search: txt},
                dataType:"text",
                success:function (data) {
                    $("#results").html(data);
                    $("#loadingWheel").attr({"src": "", "alt": ""}).hide();
                }
            });
        } else {
            $("#results").html('');
            $("#loadingWheel").attr({"src": "", "alt": ""}).hide();
        }
    });
});