function showContainer(n){
    let containers = ['main-main', 'main-child1' , 'main-cafeteria', 'main-news'];
    for (let i = 0; i < containers.length; i++){
        document.getElementById(containers[i]).style.opacity = (i === n) ? '1' : '0';
    }
}

function showMore(){
    document.getElementsByClassName("nav_child_child")
}