

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

function usunNauczyciela(idRekordu) {
    fetch('./../scripts/php/deleteTeacher.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idRekordu})
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                const element = document.getElementById('teacherCard' + idRekordu);
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

async function editTeacher(id) {
    document.getElementById("main-teachers").style.display = 'none';
    document.getElementById("edit-teacher").style.display = 'flex';

    document.getElementById('editTeacherId').value = id;
    document.getElementById('editTeacherFirstName').value = document.getElementById('teacherName' + id).innerHTML;
    document.getElementById('editTeacherImie').innerHTML = document.getElementById('teacherName' + id).innerHTML;

    document.getElementById('editTeacherLastName').value = document.getElementById('teacherLastName' + id).innerHTML;
    document.getElementById('editTeacherNazwisko').innerHTML = document.getElementById('teacherLastName' + id).innerHTML;

    document.getElementById('editTeacherEmail').value = document.getElementById('teacherEmail' + id).innerHTML;
    document.getElementById('editTeacherEmail2').innerHTML = document.getElementById('teacherEmail' + id).innerHTML;

    if (document.getElementById('teacherType' + id).innerHTML.trim() === 'Nauczyciel') {
        document.getElementById('editTeacherRole').options[1].selected = 'selected';
        document.getElementById('editTeacherType').innerHTML = 'Nauczyciel';
    } else {
        document.getElementById('editTeacherRole').options[0].selected = 'selected';
        document.getElementById('editTeacherType').innerHTML = 'Dyrektor';
    }

    document.getElementById('editTeacherPhone').value = document.getElementById('teacherPhoneNumber' + id).innerHTML;
    document.getElementById('editTeacherDesc').value = document.getElementById('teacherDesc' + id).innerHTML;

    const fileInput = document.getElementById('editTeacherImg');
    const sourceElement = document.getElementById('teacherImg' + id);

    const computedStyle = window.getComputedStyle(sourceElement);
    const bgImage = computedStyle.backgroundImage;
    document.getElementById('editTeacherPhoto').style.backgroundImage = bgImage;

    const url = bgImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');

    if (url && url !== 'none') {
        try {
            const response = await fetch(url);
            const blob = await response.blob();

            const file = new File([blob], "teacher_photo.jpg", { type: blob.type });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            fileInput.dispatchEvent(new Event('change'));

        } catch (e) {
            console.error("Nie udało się pobrać tła do inputa", e);
        }
    }
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
            }
            else {
                alert('Wystąpił błąd podczas zapisu.'+ data.trim());
            }
        })
        .catch(error => console.error('Błąd sieci:', error));
}

function edytujDziecko(daneDziecka, idPanel) {
    daneDziecka = daneDziecka.split(';');
    document.getElementById("group"+idPanel+"Management").style.display="none";
    console.log("Dane dziecka:", daneDziecka);
    document.getElementById('editChild').style.display='flex';
    document.getElementById('editChildName').value=daneDziecka[0];
    document.getElementById('editChildSurname').value=daneDziecka[1];
    document.getElementById('editChildPesel').value=daneDziecka[2];
    document.getElementById('editChildAddress').value=daneDziecka[3];
    let select = document.getElementById('editChildGrupa');
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === daneDziecka[7]) {
            select.options[i].selected = 'selected';
            break;
        }
    }
    document.getElementById('editChildId').value=daneDziecka[6];
    document.getElementById('editChildOpinion').value = daneDziecka[8];

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

function deleteGroup(g){
    fetch('./../scripts/php/deleteGroup.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: g})
    }).then (response => response.text()).then(data => {
        if (data.trim() === 'OK') {
            let element= document.getElementById('group'+g+'Management');
            if (element) {
                element.style.transition = "opacity 0.5s";
                element.style.opacity = "0";
                setTimeout(() => element.remove(), 500);
            }
            element= document.getElementById('navGroup' + g);
            if (element) {
                element.style.transition = "opacity 0.5s";
                element.style.opacity = "0";
                setTimeout(() => element.remove(), 500);
            }
            document.getElementById('witajPanel').style.display = "flex";
        }
        else{
            alert('Wystąpił błąd podczas zapisu.'+ data.trim());
        }
    })
}