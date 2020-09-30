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

    //PERSONAL INFO FORM
    $("#PersonalForm").submit(function() {

        let valid = true;

        let isEmptyFn  = ($("#ifn").val() == "") ? true : false;
        let isEmptyLn  = ($("#iln").val() == "") ? true : false;
        let isValidPh  = ($("#iph").val() != "" && $.isNumeric($("#iph").val())) ? true : false;
        let isValidE   = ($("#ie").val() != "" && emailIsValid($("#ie").val())) ? true : false;
        let isEmptyCou = (!$('#icou').val()) ? true : false;
        let isEmptyStr = ($("#istr").val() == "") ? true : false;
        let isEmptyCty = ($("#icty").val() == "") ? true : false;
        let isEmptyPrv = ($("#iprv").val() == "") ? true : false;
        let isEmptyZip = ($("#izip").val() == "") ? true : false;

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

        if(!isValidE)
        {
            $("#le").css("color", error);
            $("#se").css("color", error);

            $("#le").css("border-bottom", "1px solid " + error);
            $("#le").css("--bottomBorder", "3px solid " + error);

            $("#errorEmail").html("Invalid email address");
        }
        else
        {
            $("#le").css("color", success);
            $("#se").css("color", success);

            $("#le").css("border-bottom", "1px solid " + normal);
            $("#le").css("--bottomBorder", "3px solid " + success);

            $("#errorEmail").html("");
        
        }

        if(isEmptyCou)
        {
            $("#lcou").css("color", error);
            $("#scou").css("color", error);

            $("#lcou").css("border-bottom", "1px solid " + error);
            $("#lcou").css("--bottomBorder", "3px solid " + error);

            $("#errorCountry").html("Country field is empty");
        }
        else
        {
            $("#lcou").css("color", success);
            $("#scou").css("color", success);

            $("#lcou").css("border-bottom", "1px solid " + normal);
            $("#lcou").css("--bottomBorder", "3px solid " + success);

            $("#errorCountry").html("");
        }

        if(isEmptyStr)
        {
            $("#lstr").css("color", error);
            $("#sstr").css("color", error);

            $("#lstr").css("border-bottom", "1px solid " + error);
            $("#lstr").css("--bottomBorder", "3px solid " + error);

            $("#errorStreet").html("Street field is empty");
        }
        else
        {
            $("#lstr").css("color", success);
            $("#sstr").css("color", success);

            $("#lstr").css("border-bottom", "1px solid " + normal);
            $("#lstr").css("--bottomBorder", "3px solid " + success);

            $("#errorStreet").html("");
        }

        if(isEmptyCty)
        {
            $("#lcty").css("color", error);
            $("#scty").css("color", error);

            $("#lcty").css("border-bottom", "1px solid " + error);
            $("#lcty").css("--bottomBorder", "3px solid " + error);

            $("#errorCity").html("City field is empty");
        }
        else
        {
            $("#lcty").css("color", success);
            $("#scty").css("color", success);

            $("#lcty").css("border-bottom", "1px solid " + normal);
            $("#lcty").css("--bottomBorder", "3px solid " + success);

            $("#errorCity").html("");
        }

        if(isEmptyPrv)
        {
            $("#lprv").css("color", error);
            $("#sprv").css("color", error);

            $("#lprv").css("border-bottom", "1px solid " + error);
            $("#lprv").css("--bottomBorder", "3px solid " + error);

            $("#errorProvince").html("State/Province field is empty");
        }
        else
        {
            $("#lprv").css("color", success);
            $("#sprv").css("color", success);

            $("#lprv").css("border-bottom", "1px solid " + normal);
            $("#lprv").css("--bottomBorder", "3px solid " + success);

            $("#errorProvince").html("");
        }

        if(isEmptyZip)
        {
            $("#lzip").css("color", error);
            $("#szip").css("color", error);

            $("#lzip").css("border-bottom", "1px solid " + error);
            $("#lzip").css("--bottomBorder", "3px solid " + error);

            $("#errorZipCode").html("Zip code field is empty");
        }
        else
        {
            $("#lzip").css("color", success);
            $("#szip").css("color", success);

            $("#lzip").css("border-bottom", "1px solid " + normal);
            $("#lzip").css("--bottomBorder", "3px solid " + success);

            $("#errorZipCode").html("");
        }


        if(isEmptyFn || isEmptyLn || isEmptyCou || isEmptyStr || isEmptyCty || isEmptyPrv || isEmptyZip || !isValidPh || !isValidE )
        {
            valid = false
        }

        return valid;

    });


    $("#changePassForm").submit(function() {
        let valid = true;
        
        let isEmptyP1  = ($("#ipold").val()  == "") ? true : false;
        let isValidP2  = ($("#ipnew").val()  != "" &&  $("#ipnew").val().length > 4) ? true : false;

        if(isEmptyP1)
        {
            $("#lpold").css("color", error);
            $("#spold").css("color", error);

            $("#lpold").css("border-bottom", "1px solid " + error);
            $("#lpold").css("--bottomBorder", "3px solid " + error);

            $("#errorOldPass").html("Old password is empty");
        }
        else
        {
            $("#lpold").css("color", success);
            $("#spold").css("color", success);

            $("#lpold").css("border-bottom", "1px solid " + normal);
            $("#lpold").css("--bottomBorder", "3px solid " + success);

            $("#errorOldPass").html("");
        }

        if(!isValidP2)
        {
            $("#lpnew").css("color", error);
            $("#spnew").css("color", error);

            $("#lpnew").css("border-bottom", "1px solid " + error);
            $("#lpnew").css("--bottomBorder", "3px solid " + error);

            $("#errorNewPass").html("Password must be at least 5 characters long");
        }
        else
        {
            $("#lpnew").css("color", success);
            $("#spnew").css("color", success);

            $("#lpnew").css("border-bottom", "1px solid " + normal);
            $("#lpnew").css("--bottomBorder", "3px solid " + success);

            $("#errorNewPass").html("");
        
        }

        if(isEmptyP1 || !isValidP2)
        {
            valid = false;
        }

        return valid;
    });


    $("#updateBrands").submit(function() {

        let valid = true;
        
        let isEmptyName  = ($("#ibname").val()  == "") ? true : false;

        if(isEmptyName)
        {
            $("#lbname").css("color", error);
            $("#sbname").css("color", error);

            $("#lbname").css("border-bottom", "1px solid " + error);
            $("#lbname").css("--bottomBorder", "3px solid " + error);

            $("#errorBName").html("brand Name is empty");
        }
        else
        {
            $("#lbname").css("color", success);
            $("#sbname").css("color", success);

            $("#lbname").css("border-bottom", "1px solid " + normal);
            $("#lbname").css("--bottomBorder", "3px solid " + success);

            $("#errorBName").html("");
        }

        if(isEmptyName)
        {
            valid = false;
        }

        return valid;
    });

    $("#updateCategories").submit(function() {

        let valid = true;
        
        let isEmptyName  = ($("#icname").val()  == "") ? true : false;

        if(isEmptyName)
        {
            $("#lcname").css("color", error);
            $("#scname").css("color", error);

            $("#lcname").css("border-bottom", "1px solid " + error);
            $("#lcname").css("--bottomBorder", "3px solid " + error);

            $("#errorCName").html("brand Name is empty");
        }
        else
        {
            $("#lcname").css("color", success);
            $("#scname").css("color", success);

            $("#lcname").css("border-bottom", "1px solid " + normal);
            $("#lcname").css("--bottomBorder", "3px solid " + success);

            $("#errorCName").html("");
        }

        if(isEmptyName)
        {
            valid = false;
        }

        return valid;
    });

    $("#updatespecs").submit(function() {
    
        let valid = true;
        
        let isEmptyName  = ($("#isname").val()  == "") ? true : false;

        if(isEmptyName)
        {
            $("#lsname").css("color", error);
            $("#ssname").css("color", error);

            $("#lsname").css("border-bottom", "1px solid " + error);
            $("#lsname").css("--bottomBorder", "3px solid " + error);

            $("#errorSName").html("Spec is empty");
        }
        else
        {
            $("#lsname").css("color", success);
            $("#ssname").css("color", success);

            $("#lsname").css("border-bottom", "1px solid " + normal);
            $("#lsname").css("--bottomBorder", "3px solid " + success);

            $("#errorSName").html("");
        }

        if(isEmptyName)
        {
            valid = false;
        }

        return valid;
    });
});