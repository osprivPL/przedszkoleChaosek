function loginOn(){
    document.getElementById("loginWrapper").classList.add('visible');
    document.getElementById('dark_bg').classList.add('visible');
    document.getElementById('body').style.overflowY = "hidden";
}
function loginOff(){
    document.getElementById("loginWrapper").classList.remove('visible');
    document.getElementById('dark_bg').classList.remove('visible');
    document.getElementById('body').style.overflowY = "scroll";
}