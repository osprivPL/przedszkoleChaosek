function showContainer(n){
    for (let i = 0; i <  document.getElementsByClassName('bigContainers').length; i++){
        document.getElementsByClassName('bigContainers')[i].style.display = (i === n) ? 'flex' : 'none';
    }
    let children = document.getElementsByClassName('main-panel-child');
    for (let i = 0; i < children.length; i++){
        children[i].style.display = 'none';
    }
}

function showChildren(n){
    // n - what children to show
    let children = ['.nav_child_dziecko', '.nav_child_oSzkole', '.nav_child_article','.nav_child_annoucement', '.nav_child_group'];
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
function edytujKomunikat(id){
    document.getElementById('annoucementManager').style.display = "none";
    document.getElementById('editAnnoucements').style.display = "flex";
    document.getElementById('editKomunikatHeader').value = document.getElementById('annoucementHeader'+id).innerHTML;
    document.getElementById('editKomunikatContent').value=document.getElementById('annoucementContent'+id).innerHTML;
    document.getElementById('editKomunikatIdHiddenInput').value = id;
    let select = document.getElementById('editKomunikatGrupa');
    let widocznosc = document.getElementById('annoucementVisibility'+id).innerHTML;
    for (let i = 0; i < select.options.length; i++) {
        console.log(select.options[i].text);
        if (select.options[i].text ===widocznosc) {
            select.options[i].selected = true;
            break;
        }
    }
}


function showGroups(n){
    let children = ['.nav_child_grupa'];
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
function ukryjKomunikat(idRekordu) {
    fetch('./../scripts/php/deleteAnnoucement.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idRekordu})
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                const element = document.getElementById('komunikat' + idRekordu);
                if (element) {
                    element.style.transition = "opacity 0.5s";
                    element.style.opacity = "0";

                    setTimeout(() => element.remove(), 500);
                }
            } else {
                console.error('Błąd serwera:', data);
                alert('Wystąpił błąd podczas zapisu.');
            }
        })
        .catch(error => {
            console.error('Błąd sieci:', error);
        });
}