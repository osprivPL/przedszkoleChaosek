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
let x = 0;
function userPanel(y){ 
    if(x===0){
        document.getElementById("user_pop_up" + y).classList.add('visible');
        x=1;
    }else{
        document.getElementById("user_pop_up" + y).classList.remove('visible');
        x=0;
    }
}