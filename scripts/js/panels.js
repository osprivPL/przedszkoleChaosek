function showContainer(n){
    let containers = ['main', 'jadlospisContainer'];
    for (let i = 0; i < containers.length; i++){
        document.getElementById(containers[i]).style.display = (i === n) ? 'flex' : 'none';
    }
}