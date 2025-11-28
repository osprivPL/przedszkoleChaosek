function userPanel(y){ 
    x = document.getElementById("user_pop_up" + y).classList.contains('visible') ? 1 : 0;
    if(x===0){
        document.getElementById("user_pop_up" + y).classList.add('visible');
    }else{
        document.getElementById("user_pop_up" + y).classList.remove('visible');
    }
}