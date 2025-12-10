

function openGroupTab(evt, tabId) {
    let contents = document.getElementsByClassName("tab-content");
    for (let i = 0; i < contents.length; i++) {
        contents[i].style.display = "none";
    }
    let btns = document.getElementsByClassName("tab-btn");
    for (let i = 0; i < btns.length; i++) {
        btns[i].className = btns[i].className.replace(" active", "");
    }
    document.getElementById(tabId).style.display = "block";
    evt.currentTarget.className += " active";
}
function ukryjArtykul(idRekordu) {
    fetch('./../scripts/php/deleteArticle.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idRekordu})
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                const element = document.getElementById('article' + idRekordu);
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

async function edytujArtykul(id) {
    document.getElementById('main-panel-articles').style.display = "none";
    document.getElementById('editArticle').style.display = "flex";
    document.getElementById('editArticleHeader').value = document.getElementById('articleHeader' + id).innerHTML;
    document.getElementById('editArticleData').value = document.getElementById('articleDate' + id).innerHTML.substr(6, document.getElementById('articleDate' + id).innerHTML.length);
    document.getElementById('editArticleContent').innerHTML = document.getElementById('articleContent' + id).innerHTML;
    document.getElementById('articleIdHiddenInput').value = id;
    const fileInput = document.getElementById('editArticleImg');
    const sourceImg = document.getElementById('articleImg' + id);

    if (sourceImg && sourceImg.src) {
        try {
            const response = await fetch(sourceImg.src);
            const blob = await response.blob();

            const file = new File([blob], "aktualne-zdjecie.jpg", { type: blob.type });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));

        } catch (e) {
            console.error("Nie udało się pobrać obrazka do edycji", e);
        }
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

function usunDziecko(idRekordu) {
    fetch('./../scripts/php/deleteChild.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idRekordu})
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                const element = document.getElementById('dziecko' + idRekordu);
                if (element) {
                    element.style.transition = "opacity 0.5s";
                    element.style.opacity = "0";
                    setTimeout(() => element.remove(), 500);
                }
            } else {
                alert('Wystąpił błąd podczas zapisu.');
            }
        })
        .catch(error => console.error('Błąd sieci:', error));
}

function edytujDziecko(daneDziecka, idPanel) {
    daneDziecka = daneDziecka.split(';');
    daneDziecka.splice(6,1);
    document.getElementById("group"+idPanel+"Management").style.display="none";
    console.log("Dane dziecka:", daneDziecka);
    document.getElementById('editChild').style.display='flex';
    document.getElementById('editChildName').value=daneDziecka[0];
    document.getElementById('editChildSurname').value=daneDziecka[1];
    document.getElementById('editChildPesel').value=daneDziecka[2];
    document.getElementById('editChildAddress').value=daneDziecka[3];
    let select = document.getElementById('editChildGrupa');
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === daneDziecka[6]) {
            select.options[i].selected = 'selected';
            break;
        }
    }
    document.getElementById('editChildId').value=idPanel;
    document.getElementById('editChildOpinion').value = daneDziecka[7];

}
function saveChildrenChanges(idRekordu){
    fetch('./../scripts/php/editChild.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idRekordu})
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                const element = document.getElementById('dziecko' + idRekordu);
                if (element) {
                    element.style.transition = "opacity 0.5s";
                    element.style.opacity = "0";
                    setTimeout(() => element.remove(), 500);
                }
            } else {
                alert('Wystąpił błąd podczas zapisu.');
            }
        })
        .catch(error => console.error('Błąd sieci:', error));
}
function showGroupPlan(groupId) {
    const containers = document.querySelectorAll('.group-plan-container');
    containers.forEach(div => div.style.display = 'none');
    const target = document.getElementById('group-plan-container-' + groupId);
    if (target) target.style.display = 'block';
    document.querySelectorAll('.plan-group-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('btn-group-' + groupId).classList.add('active');
}