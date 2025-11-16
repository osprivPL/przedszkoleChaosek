document.getElementById("frmRekrtuacja").addEventListener("submit", function(e){
    e.preventDefault();
    const pesel = document.getElementById("frmChildPesel");
    let weight = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
    let sum = 0;
    let controlNumber = parseInt(pesel.substring(10, 11));
    let error = false;

    if (document.getElementById("frmChildImie").length() < 1){
        document.getElementById("frmChildImie").style.borderColor="red";
        error = true;
    }
    if (document.getElementById("frmChildNazwisko").length() < 1){
        document.getElementById("frmChildNazwisko").style.borderColor="red";
        error = true;
    }
    if (document.getElementById("frmChildAdres").length < 1){
        document.getElementById("frmChildAdres").style.borderColor="red";
        error = true;
    }
    if (pesel.value.length !== 11 || isNaN(pesel.value)) {
        pesel.style.borderColor = "red";
        error = true;
    }

    for (let i = 0; i < weight.length; i++) {
        sum += (parseInt(pesel.substring(i, i + 1)) * weight[i]);
    }
    sum = sum % 10;
    if ((10-sum) % 10 !== controlNumber){
        error = true;
    }
    if (error) return;

    this.submit();
});