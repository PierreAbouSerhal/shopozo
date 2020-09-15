let normal  = "#9E9E9E";
let success = "#6EBE47";
let error   = "#ff0a0a";

let mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

$(document).ready(function() {

    function emailIsValid(email)
    {
        if(mailformat.test(email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //REGISTER FORM
    $("#registerForm").submit(function() { 

        let valid = true;
        
        let isEmptyFn = ($("#ifn").val() == "") ? true : false;
        let isEmptyLn = ($("#iln").val() == "") ? true : false;
        let isValidE  = ($("#ie" ).val()  != "" && emailIsValid($("#ie").val())) ? true : false;
        let isValidP1 = ($("#ip1").val() != "" && $("#ip1").val().length > 4) ? true : false;
        let isValidP2 = ($("#ip2").val() != "" && $("#ip2").val() == $("#ip1").val()) ? true : false;

        if(isEmptyFn)
        {
            $("#lfn").css("color", error);
            $("#sfn").css("color", error);

            $("#lfn").css("border-bottom", "1px solid " + error);
            $("#lfn").css("--bottomBorder", "3px solid " + error);

            $("#errorFN").html("First name is empty");
        }
        else
        {
            $("#lfn").css("color", success);
            $("#sfn").css("color", success);

            $("#lfn").css("border-bottom", "1px solid " + normal);
            $("#lfn").css("--bottomBorder", "3px solid " + success);

            $("#errorFN").html("");
        }

        if(isEmptyLn)
        {
            $("#lln").css("color", error);
            $("#sln").css("color", error);

            $("#lln").css("border-bottom", "1px solid " + error);
            $("#lln").css("--bottomBorder", "3px solid " + error);

            $("#errorLN").html("Last name is empty");
        }
        else
        {
            $("#lln").css("color", success);
            $("#sln").css("color", success);

            $("#lln").css("border-bottom", "1px solid " + normal);
            $("#lln").css("--bottomBorder", "3px solid " + success);

            $("#errorLN").html("");
        }

        if(!isValidE)
        {
            $("#le").css("color", error);
            $("#se").css("color", error);

            $("#le").css("border-bottom", "1px solid " + error);
            $("#le").css("--bottomBorder", "3px solid " + error);

            $("#errorEmail").html("Email address is not valid");
        }
        else
        {
            $("#le").css("color", success);
            $("#se").css("color", success);

            $("#le").css("border-bottom", "1px solid " + normal);
            $("#le").css("--bottomBorder", "3px solid " + success);

            $("#errorEmail").html("");
        }

        if(!isValidP1)
        {
            $("#lp1").css("color", error);
            $("#sp1").css("color", error);

            $("#lp1").css("border-bottom", "1px solid " + error);
            $("#lp1").css("--bottomBorder", "3px solid " + error);

            $("#errorPass1").html("Password must be at least 5 characters long");
        }
        else
        {
            $("#lp1").css("color", success);
            $("#sp1").css("color", success);

            $("#lp1").css("border-bottom", "1px solid " + normal);
            $("#lp1").css("--bottomBorder", "3px solid " + success);

            $("#errorPass1").html("");

            if(!isValidP2)
            {
                $("#ip2").val("");
                $("#lp2").css("color", error);
                $("#sp2").css("color", error);

                $("#lp2").css("border-bottom", "1px solid " + error);
                $("#lp2").css("--bottomBorder", "3px solid " + error);

                $("#errorPass2").html("Passwords didn't match");
            }
            else
            {
                $("#lp2").css("color", success);
                $("#sp2").css("color", success);

                $("#lp2").css("border-bottom", "1px solid " + normal);
                $("#lp2").css("--bottomBorder", "3px solid " + success);

                $("#errorPass2").html("");
            }
        }

        if(isEmptyFn || isEmptyLn || !isValidE || !isValidP1 || !isValidP2)
        {
            valid = false
        }

    return valid;
    });

    //SIGNIN FORM
    $("#signinForm").submit(function() {

        let valid = true;

        let isValidE  = ($("#ie").val() != "" && emailIsValid($("#ie").val())) ? true : false;
        let isEmptyP  = ($("#ip").val() == "") ? true : false;

        if(!isValidE)
        {
            $("#le").css("color", error);
            $("#se").css("color", error);

            $("#le").css("border-bottom", "1px solid " + error);
            $("#le").css("--bottomBorder", "3px solid " + error);

            $("#errorEmail").html("Email address is not valid");
        }
        else
        {
            $("#le").css("color", success);
            $("#se").css("color", success);

            $("#le").css("border-bottom", "1px solid " + normal);
            $("#le").css("--bottomBorder", "3px solid " + success);

            $("#errorEmail").html("");
        }

        if(isEmptyP)
        {
            $("#lp").css("color", error);
            $("#sp").css("color", error);

            $("#lp").css("border-bottom", "1px solid " + error);
            $("#lp").css("--bottomBorder", "3px solid " + error);

            $("#errorPass").html("Password is empty");
        }
        else
        {
            $("#lp").css("color", success);
            $("#sp").css("color", success);

            $("#lp").css("border-bottom", "1px solid " + normal);
            $("#lp").css("--bottomBorder", "3px solid " + success);

            $("#errorPass").html("");
        }

        if(!isValidE || isEmptyP)
        {
            valid = false
        }

        return valid;

    });

    //FORGOT PASS FORM
    $("#forgotPassForm").submit(function() {

        let valid = true;

        let isValidE, isValidPin, isValidP1, isValidP2 = true;

        if($("#ie").length != 0)
        {
            isValidE = ($("#ie").val() != "" && emailIsValid($("#ie").val())) ? true : false;

            if(!isValidE)
            {
                $("#le").css("color", error);
                $("#se").css("color", error);

                $("#le").css("border-bottom", "1px solid " + error);
                $("#le").css("--bottomBorder", "3px solid " + error);

                $("#errorEmail").html("Email address is not valid");
            }
            else
            {
                $("#le").css("color", success);
                $("#se").css("color", success);

                $("#le").css("border-bottom", "1px solid " + normal);
                $("#le").css("--bottomBorder", "3px solid " + success);

                $("#errorEmail").html("");
            }

            if(!isValidE)
            {
                valid = false;
            }
        }
        else
        {
            isValidPin = ($("#ipin").val() != "" && $.isNumeric($("#ipin").val()) && $("#ipin").val().length == 4) ? true : false;
            isValidP1  = ($("#ip1").val()  != "" && $("#ip1").val().length > 4) ? true : false;
            isValidP2  = ($("#ip2").val()  != "" && $("#ip2").val() == $("#ip1").val()) ? true : false;

            if(!isValidPin)
            {
                $("#lpin").css("color", error);
                $("#spin").css("color", error);

                $("#lpin").css("border-bottom", "1px solid " + error);
                $("#lpin").css("--bottomBorder", "3px solid " + error);

                $("#errorPin").html("Pin must be 4 digits long");
            }
            else
            {
                $("#lpin").css("color", success);
                $("#spin").css("color", success);

                $("#lpin").css("border-bottom", "1px solid " + normal);
                $("#lpin").css("--bottomBorder", "3px solid " + success);

                $("#errorPin").html("");
            }

            if(!isValidP1)
            {
                $("#lp1").css("color", error);
                $("#sp1").css("color", error);

                $("#lp1").css("border-bottom", "1px solid " + error);
                $("#lp1").css("--bottomBorder", "3px solid " + error);

                $("#errorPass1").html("Password must be at least 5 characters long");
            }
            else
            {
                $("#lp1").css("color", success);
                $("#sp1").css("color", success);

                $("#lp1").css("border-bottom", "1px solid " + normal);
                $("#lp1").css("--bottomBorder", "3px solid " + success);

                $("#errorPass1").html("");

                if(!isValidP2)
                {
                    $("#ip2").val("");
                    $("#lp2").css("color", error);
                    $("#sp2").css("color", error);

                    $("#lp2").css("border-bottom", "1px solid " + error);
                    $("#lp2").css("--bottomBorder", "3px solid " + error);

                    $("#errorPass2").html("Passwords didn't match");
                }
                else
                {
                    $("#lp2").css("color", success);
                    $("#sp2").css("color", success);

                    $("#lp2").css("border-bottom", "1px solid " + normal);
                    $("#lp2").css("--bottomBorder", "3px solid " + success);

                    $("#errorPass2").html("");
                }
            }

            if(!isValidPin || !isValidP1 || !isValidP2)
            {
                valid = false;
            }
        }

        return valid;

    });

    //FORGOT PASS FORM
    $("#PersonalForm").submit(function() {

        let valid = true;

        let isEmptyFn = ($("#ifn").val() == "") ? true : false;
        let isEmptyLn = ($("#iln").val() == "") ? true : false;
        let isValidPh = ($("#iph").val() != "" && $.isNumeric($("#iph").val())) ? true : false;
        let isValidP1 = ($("#ip1").val() != "") ? true : false;
        let isValidP2 = ($("#ip2").val() != "" && $("#ip2").val().length > 4) ? true : false;

        if(isEmptyFn)
        {
            $("#lfn").css("color", error);
            $("#sfn").css("color", error);

            $("#lfn").css("border-bottom", "1px solid " + error);
            $("#lfn").css("--bottomBorder", "3px solid " + error);

            $("#errorFN").html("First name is empty");
        }
        else
        {
            $("#lfn").css("color", success);
            $("#sfn").css("color", success);

            $("#lfn").css("border-bottom", "1px solid " + normal);
            $("#lfn").css("--bottomBorder", "3px solid " + success);

            $("#errorFN").html("");
        }

        if(isEmptyLn)
        {
            $("#lln").css("color", error);
            $("#sln").css("color", error);

            $("#lln").css("border-bottom", "1px solid " + error);
            $("#lln").css("--bottomBorder", "3px solid " + error);

            $("#errorLN").html("Last name is empty");
        }
        else
        {
            $("#lln").css("color", success);
            $("#sln").css("color", success);

            $("#lln").css("border-bottom", "1px solid " + normal);
            $("#lln").css("--bottomBorder", "3px solid " + success);

            $("#errorLN").html("");
        }

        if(!isValidPh)
        {
            $("#lph").css("color", error);
            $("#sph").css("color", error);

            $("#lph").css("border-bottom", "1px solid " + error);
            $("#lph").css("--bottomBorder", "3px solid " + error);

            $("#errorPhone").html("Invalid phone number");
        }
        else
        {
            $("#lph").css("color", success);
            $("#sph").css("color", success);

            $("#lph").css("border-bottom", "1px solid " + normal);
            $("#lph").css("--bottomBorder", "3px solid " + success);

            $("#errorPhone").html("");
        }

        if($("#ip2").val() != "")
        {
            if(!isValidP2)
            {
                $("#lp2").css("color", error);
                $("#sp2").css("color", error);

                $("#lp2").css("border-bottom", "1px solid " + error);
                $("#lp2").css("--bottomBorder", "3px solid " + error);

                $("#errorPass2").html("Password must be at least 5 characters long");
            }
            else
            {
                $("#lp2").css("color", success);
                $("#sp2").css("color", success);

                $("#lp2").css("border-bottom", "1px solid " + normal);
                $("#lp2").css("--bottomBorder", "3px solid " + success);

                $("#errorPass2").html("");
            }

            if(!isValidP1)
            {
                $("#lp1").css("color", error);
                $("#sp1").css("color", error);

                $("#lp1").css("border-bottom", "1px solid " + error);
                $("#lp1").css("--bottomBorder", "3px solid " + error);

                $("#errorPass1").html("Old password is empty");
            }
            else
            {
                $("#lp1").css("color", success);
                $("#sp1").css("color", success);

                $("#lp1").css("border-bottom", "1px solid " + normal);
                $("#lp1").css("--bottomBorder", "3px solid " + success);

                $("#errorPass1").html("");
            }
        }

        if(isEmptyFn || isEmptyLn || !isValidPh)
        {
            valid = false
        }

        if($("#ip2").val() != "")
        {
            if(!isValidP2 || !isValidP1)
            {
                valid = false;
            }
        }

        return valid;

    });
});