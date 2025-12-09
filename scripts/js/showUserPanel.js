function showSomething(y){ 
    x = document.getElementById("somethingBeingShown" + y).classList.contains('visible') ? 1 : 0;
    if(x===0){
        document.getElementById("somethingBeingShown" + y).classList.add('visible');
    }else{
        document.getElementById("somethingBeingShown" + y).classList.remove('visible');
    }
}