function loginOn(){
    document.getElementById("loginWrapper").style.visibility = "visible";
    document.getElementById('dark_bg').style.visibility = "visible";
    document.getElementById('body').style.overflowY = "hidden";
}
function loginOff(){
    document.getElementById("loginWrapper").style.visibility = "hidden";
    document.getElementById('dark_bg').style.visibility = "hidden"; 
    document.getElementById('body').style.overflowY = "scroll";
}
function userPanelOn(){
    document.getElementById("userWrapper").style.visibility = "visible";
}
function userPanelOff(){
    document.getElementById("userWrapper").style.visibility = "hidden";
}