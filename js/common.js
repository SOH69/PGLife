window.addEventListener("load", function () {
    let signup = document.getElementById("signup-form");
    signup.addEventListener("submit", function (event) {
        let XHR = new XMLHttpRequest();
        let form = new FormData(signup);

        XHR.addEventListener("load", signup_success);

        XHR.addEventListener("error", on_error);

        XHR.open("POST", "module/signup.php");

        XHR.send(form);

        document.getElementById("loading").style.display = 'block';
        event.preventDefault();
    });

   //add code corresponding to login form as a part of your assignment
});

let signup_success = function(event) {
    document.getElementById("loading").style.display = 'none';

    let response = JSON.parse(event.target.responseText);
    if (response.success) {
        location.reload();
    } else {
        let msg = document.getElementById("err-msg");
        msg.innerHTML = response.message;
        msg.style.display = 'block';
    }
}

let on_error = function(event) {
    document.getElementById("loading").style.display = 'none';

    alert(response.message);
}

let toggleLike = function(event) {
    if (event.hasAttribute("id")) {
        event.setAttribute('class', 'far fa-heart');
        event.removeAttribute('id')
    } else {
        event.setAttribute('class', 'fas fa-heart');
        event.setAttribute('id', 'liked');
    }
}