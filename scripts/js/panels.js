function showContainer(n){
    let containers = ['main-main', 'main-child1','main-teachers', 'main-cafeteria', 'main-news'];
    for (let i = 0; i < containers.length; i++){
        document.getElementById(containers[i]).style.display = (i === n) ? 'flex' : 'none';
    }
}

function showChildren(n){
    // n - what children to show
    let children = ['.nav_child_dziecko', '.nav_child_oSzkole'];
    document.querySelectorAll(children[n]).forEach(item => {
        item.style.display = "flex";
    })
}