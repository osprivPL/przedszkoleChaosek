document.getElementById("loginPanel").addEventListener("submit", function(e){
    e.preventDefault();

    let email = document.getElementById("tbxEmail");
    let password = document.getElementById("tbxHaslo");
    let error = false;

    if (email.value==="" || !email.value.includes("@") || !email.value.includes(".")){
        email.style.borderColor="red";
        document.getElementById("emailError").innerHTML="Wprowadź poprawny email";
        error = true;
    }
    else{
        email.style.borderColor="initial";
        document.getElementById("emailError").innerHTML="";
    }
    if (password.value===""){
        password.style.borderColor="red";
        document.getElementById("passwordError").innerHTML="Wprowadź hasło";
        error=true;
    }
    else{
        password.style.borderColor="initial";
        document.getElementById("passwordError").innerHTML="";
    }
    if (error) return;

    this.submit();
});