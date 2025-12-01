function showContainer(n){
    let containers = ['main-main', 'main-child1', 'main-teachers', 'main-cafeteria', 'main-news']
    for (let i = 0; i < containers.length; i++){
        document.getElementById(containers[i]).style.display = (i === n) ? 'flex' : 'none';
    }
    let children = document.getElementsByClassName('main-panel-child');
    for (let i = 0; i < children.length; i++){
        children[i].style.display = 'none';
    }
}

function showChildren(n){
    // n - what children to show
    let children = ['.nav_child_dziecko', '.nav_child_oSzkole'];
    let isVisible = document.querySelectorAll(children[n])[0].classList.contains('visible');
    if(isVisible){
        document.querySelectorAll(children[n]).forEach(item => {
            item.classList.remove('visible');
        })
    }else{
        document.querySelectorAll(children[n]).forEach(item => {
            item.classList.add('visible');
        })
    }
}