$(document).ready(function() {
    $("#loginForm").submit(function (e) {
        e.preventDefault();

        // let customer_email = $("input[name='customer_email']").val().trim();
        // let customer_pass = $("input[name='customer_pass']").val().trim();

        let customer_email = $("#customer_email").val().trim();
        let customer_pass = $("#customer_pass").val().trim();

        //regrex for email validation
        let emailRegrex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        let errors = [];
        if(!emailRegrex.test(customer_email)) {
            errors.push("Please enter a valid email address");
        }

        if(customer_pass.length < 8) {
            errors.push("Password must be at least 8 characters long");
        }

        if(errors.length > 0) {
            $("#msg").html(
                `<div class="alert alert-danger">${errors.join("<br>")}</div>`
            );
            return;
        }

        $.ajax({
            url: "../actions/login_customer.php",
            type: "POST",
            data: {customer_email:customer_email, customer_pass:customer_pass},
            datatype: "json",
            success: function (response){
                if(response.status === "success"){
                    $("#msg").html(
                        `<div class="alert alert-success">${response.message}</div>`

                    );

                    setTimeout(() => {
                        window.location.href ="../index.php";
                    }, 1500)
                } else {
                    $("#msg").html(
                        `<div class="alert alert-danger">${response.message}</div>`
                    );
                }
            },
            error: function () {
                $("#msg").html(
                    `<div class="alert alert-danger">Error connecting to server</div>`
                );
            },
        });
        
    });
});