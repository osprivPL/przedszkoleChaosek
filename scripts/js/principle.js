function hideInformation() {
    document.getElementById('informationPopUp').style.display = 'none';
    document.getElementById('dark_bg').style.display = 'none';
}

function showInformation(text) {
    document.getElementById('informationPopUp').style.display = 'flex';
    document.getElementById('dark_bg').style.display = 'block';
    document.getElementById('warningText').innerHTML = text;
}

function hideWarning() {
    let warnings = document.getElementsByClassName('warning');
    for (let i = 0; i < warnings.length; i++) {
        warnings[i].style.display = 'none';
    }
    document.getElementById('dark_bg').style.display = 'none';
}

function dateFromPesel(pesel) {
    let rok = pesel.substring(0, 2);
    let miesiac = parseInt(pesel.substring(2, 4), 10);
    let dzien = pesel.substring(4, 6);
    let stulecie = '';
    if (miesiac >= 1 && miesiac <= 12) {
        stulecie = '19';
    } else if (miesiac >= 21 && miesiac <= 32) {
        stulecie = '20';
        miesiac -= 20;
    }
    let pelnyRok = stulecie + rok;
    miesiac = miesiac.toString().padStart(2, '0');
    return `${pelnyRok}-${miesiac}-${dzien}`;
}

function rozpatrzWniosek(rekord) {
    if (rekord === "") {
        document.getElementById('Application').style.display = "none";
        document.getElementById('listOfApplications').style.display = "block";
    } else {
        document.getElementById('wniosekNumber').innerHTML = "Wniosek #" + rekord[0];
        document.getElementById('Application').style.display = "block";
        document.getElementById('listOfApplications').style.display = "none";
        rekordy = [rekord[5], rekord[6], rekord[7], rekord[8], rekord[1], rekord[2], rekord[4], rekord[3], rekord[8]];
        informations = document.getElementsByClassName('information');
        for (i = 0; i < informations.length; i++) {
            informations[i].innerHTML = rekordy[i];
        }
        document.getElementById('dataur').innerHTML = dateFromPesel(rekord[7]);
    }
}

function odrzucWniosek(idRekordu) {
    fetch('./../scripts/php/deleteApplication.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: idRekordu})
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                const element = document.getElementById('Wniosek#' + idRekordu);
                if (element) {
                    element.style.transition = "opacity 0.5s";
                    element.style.opacity = "0";
                    setTimeout(() => element.remove(), 500);
                }
            } else {
                showInformation("Wystąpił błąd, spróbuj ponownie później.");
            }
            hideWarning()
        })
        .catch(error => {
            // console.error('Błąd sieci:', error)
            showInformation("Wystąpił błąd, spróbuj ponownie później")
        });
    hideWarning();
}

function przyjmijWniosek(idRekordu) {
    const selectElement = document.getElementById("wniosek" + idRekordu + "select");
    const selectedGroup = selectElement ? selectElement.value : "BRAK ELEMENTU";
    console.log("Wysyłanie ID:", idRekordu, "Grupa:", selectedGroup);

    fetch('./../scripts/php/confirmChild.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            id: idRekordu,
            group: selectedGroup
        })
    })
        .then(response => response.text())
        .then(data => {
            // console.log("Odpowiedź PHP:", data);

            if (data.trim() === 'OK') {
                const element = document.getElementById('Wniosek#' + idRekordu);
                if (element) {
                    element.style.transition = "opacity 0.5s";
                    element.style.opacity = "0";
                    setTimeout(() => element.remove(), 500);
                }
            } else {
                alert('Serwer zwrócił błąd: ' + data);
            }
        })
        .catch(error => showInformation("Wystąpił błąd, spróbuj ponownie później"));
}


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
                // console.error('Błąd serwera:', data);
                // alert('Wystąpił błąd podczas zapisu.');
                showInformation("Wystąpił błąd, spróbuj ponownie później.");
            }
            hideWarning();
        })
        .catch(error => {
            // console.error('Błąd sieci:', error);
            showInformation("Wystąpił błąd, spróbuj ponownie później.");
        });
    hideWarning();
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
                // console.error('Błąd serwera:', data);
                // alert('Wystąpił błąd podczas zapisu.');
                showInformation("Wystąpił błąd, spróbuj ponownie później.");
            }
            hideWarning();
        })
        .catch(error => {
            // console.error('Błąd sieci:', error);
            showInformation("Wystąpił błąd, spróbuj ponownie później.");
        });
    hideWarning();
}

async function edytujArtykul(id) {
    document.getElementById('main-panel-articles').style.display = "none";
    document.getElementById('editArticle').style.display = "flex";
    document.getElementById('editArticleHeader').value = document.getElementById('articleHeader' + id).innerHTML;
    document.getElementById('editArticleData').value = document.getElementById('articleDate' + id).innerHTML;
    document.getElementById('editArticleContent').innerHTML = document.getElementById('articleContent' + id).innerHTML;
    document.getElementById('articleIdHiddenInput').value = id;
    const fileInput = document.getElementById('editArticleImg');
    const sourceImg = document.getElementById('articleImg' + id);

    if (sourceImg && sourceImg.src) {
        try {
            const response = await fetch(sourceImg.src);
            const blob = await response.blob();

            const file = new File([blob], "aktualne-zdjecie.jpg", {type: blob.type});

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));

        } catch (e) {
            // console.error("Nie udało się pobrać obrazka do edycji", e);
            showInformation("Nie udało się pobrać obrazka do edycji.");
        }
    }
}


// async function edytujArtykul(id) {
//     document.getElementById('main-panel-articles').style.display = "none";
//     document.getElementById('editArticle').style.display = "flex";
//     document.getElementById('editArticleHeader').value = document.getElementById('articleHeader' + id).innerHTML;
//     document.getElementById('editArticleData').value = document.getElementById('articleDate' + id).innerHTML;
//     document.getElementById('editArticleContent').innerHTML = document.getElementById('articleContent' + id).innerHTML;
//     document.getElementById('articleIdHiddenInput').value = id;
//
//     const fileInput = document.getElementById('editArticleImg');
//     const sourceImg = document.getElementById('articleImg' + id);
//
//     if (sourceImg && sourceImg.src) {
//         try {
//             const response = await fetch(sourceImg.src);
//             const blob = await response.blob();
//
//             const imgUrl = new URL(sourceImg.src);
//             const fullFileName = decodeURIComponent(imgUrl.pathname.split('/').pop());
//             const fileName = fullFileName.replace(/_.{13}(?=\.[^.]+$)/, '');
//
//             const file = new File([blob], fileName, {type: blob.type});
//
//             const dataTransfer = new DataTransfer();
//             dataTransfer.items.add(file);
//             fileInput.files = dataTransfer.files;
//             fileInput.dispatchEvent(new Event('change'));
//
//         } catch (e) {
//             console.error(e);
//              showInformation("Nie udało się pobrać obrazka do edycji.");
//         }
//     }
// }

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

            const file = new File([blob], "teacher_photo.jpg", {type: blob.type});

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            fileInput.dispatchEvent(new Event('change'));

        } catch (e) {
            // console.error("Nie udało się pobrać obrazka do pliku", e);
            showInformation("Nie udało się pobrać obrazka do pliku.");
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
            } else {
                // alert('Wystąpił błąd podczas zapisu.' + data.trim());
                showInformation("Wystąpił błąd, spróbuj ponownie później.");
            }
            hideWarning();
        })
        .catch(error => showInformation("Wystąpił błąd, spróbuj ponownie później."));
    hideWarning();
}

function edytujDziecko(daneDziecka, idPanel) {
    daneDziecka = daneDziecka.split(';');
    document.getElementById("group" + idPanel + "Management").style.display = "none";
    document.getElementById('senderDiv').value = idPanel;
    console.log("Dane dziecka:", daneDziecka);
    document.getElementById('editChild').style.display = 'flex';
    document.getElementById('editChildName').value = daneDziecka[0];
    document.getElementById('editChildSurname').value = daneDziecka[1];
    document.getElementById('editChildPesel').value = daneDziecka[2];
    document.getElementById('editChildAddress').value = daneDziecka[3];
    let select = document.getElementById('editChildGrupa');
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === daneDziecka[7]) {
            select.options[i].selected = 'selected';
            break;
        }
    }
    document.getElementById('editChildId').value = daneDziecka[6];
    document.getElementById('editChildOpinion').value = daneDziecka[8];

}

function cancelEditingChild(g) {
    document.getElementById('editChild').style.display = 'none';
    document.getElementById('group' + senderDiv.value + "Management").style.display = 'flex';
}

function saveChildrenChanges(idRekordu) {
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
                // alert('Wystąpił błąd podczas zapisu.');
                showInformation("Wystąpił błąd, spróbuj ponownie później.");
            }
        })
        .catch(error => showInformation('Wystąpił błąd, spróbuj ponownie później.'));
}

function deleteGroup(g) {
    fetch('./../scripts/php/deleteGroup.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: g})
    }).then(response => response.text()).then(data => {
        if (data.trim() === 'OK') {
            let element = document.getElementById('group' + g + 'Management');
            if (element) {
                element.style.transition = "opacity 0.5s";
                element.style.opacity = "0";
                setTimeout(() => element.remove(), 500);
            }
            element = document.getElementById('navGroup' + g);
            if (element) {
                element.style.transition = "opacity 0.5s";
                element.style.opacity = "0";
                setTimeout(() => element.remove(), 500);
            }
            hideWarning();
            document.getElementById('witajPanel').style.display = "flex";
        }
        else if (data.trim() === 'dzieciGrupa'){
            hideWarning();
            showInformation("Nie można usunąć grupy, w której są dzieci");
        }
        else {
            // alert('Wystąpił błąd podczas zapisu.' + data.trim());
            hideWarning();
            showInformation("Wystąpił błąd, spróbuj ponownie później.");
        }
    })
}

function showWarningRekrutacja(id) {
    document.getElementById('dark_bg').style.display = 'block';
    document.getElementById('rekrutacjaWarning').style.display = 'flex';
    document.getElementById('btnWarningAcceptRekrutacja').onclick = function () {
        odrzucWniosek(id)
    };
}

function showWarningArtykul(id) {
    document.getElementById('dark_bg').style.display = 'block';
    document.getElementById('artykulWarning').style.display = 'flex';
    document.getElementById('btnWarningAcceptArticle').onclick = function () {
        ukryjArtykul(id)
    };
}

function showWarningTeacher(id) {
    document.getElementById('dark_bg').style.display = 'block';
    document.getElementById('teacherWarning').style.display = 'flex';
    document.getElementById('btnWarningAcceptTeacher').onclick = function () {
        usunNauczyciela(id)
    };
}

function showWarningGroup(id) {
    document.getElementById('dark_bg').style.display = 'block';
    document.getElementById('group' + id + 'Warning').style.display = 'flex';
    document.getElementById('btnWarningAcceptGroup' + id).onclick = function () {
        deleteGroup(id)
    };
}

function showWarningChild(groupId, id) {
    document.getElementById('dark_bg').style.display = 'block';
    document.getElementById('childWarning' + groupId).style.display = 'flex';
    document.getElementById('btnWarningAcceptChild' + groupId).onclick = function () {
        usunDziecko(id)
    };
}

