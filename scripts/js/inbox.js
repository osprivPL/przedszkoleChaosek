let ileWiadomosci = 0;
let zaznaczone = 0;

function setIleWiadomosci(n){
    ileWiadomosci = n;
}

function selectAllCheckboxes(n){
    const master = document.getElementById('selectAllCheckbox'+n);
    const check = master.checked;
    const boxes = document.querySelectorAll('.messageCheckbox');
    zaznaczone = check ? boxes.length : 0;
    boxes.forEach(cb => cb.checked = check);
}

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('change', (e) => {
        if (e.target.classList && e.target.classList.contains('messageCheckbox')) {
            if (e.target.checked) zaznaczone++; else zaznaczone--;
            const sel = document.getElementById('selectAllCheckbox');
            if (sel) sel.checked = (zaznaczone === ileWiadomosci);
        }
    });
    // prevent checkbox clicks from bubbling to row onclick (which opens the message)
    document.querySelectorAll('.messageCheckbox').forEach(cb => cb.addEventListener('click', e => e.stopPropagation()));
    const master = document.getElementById('selectAllCheckbox');
    if (master) master.addEventListener('change', selectAllCheckboxes);
});

function showContainerInbox(n){
    let containers = ['receivedContainer', 'sentContainer', 'deletedContainer', 'draftsContainer', 'writeContainer'];
    for (let i = 0; i < containers.length; i++){
        if (containers[i] == 'writeContainer'){
            document.getElementById('newMesTresc').value = "";
            document.getElementById('newMesTytul').value = "";
        }
        document.getElementById(containers[i]).style.display = (i === n) ? 'flex' : 'none';
    }

}

function OpenMessage(json_array, typ){

    if (document.getElementById('OpenedMessage').getAnimations) {
        document.getElementById('OpenedMessage').getAnimations().forEach(a => a.cancel());
    }

    // json[0]  -> id
    // json[1]  -> tytul
    // json[2]  -> tresc
    // json[3]  -> dataWyslana
    // json[4]  -> imie nadawca/odbiorca
    // json[5]  -> nazwisko nadawca/odbiorca
    // json[6]  -> imie_user
    // json[7]  -> nazwisko_user
    // json[8]  -> nadawcaID
    // json[9]  -> usunieteNadawca
    // json[10] -> userID

    document.getElementById('OpenedMessage').style.display = "flex";
    document.getElementById('MessageTytle').textContent = json_array[1];
    document.getElementById('MessageTextarea').textContent = json_array[2];
    document.getElementById('MessageData').textContent = json_array[3];
    document.getElementById('MessageOd').textContent = json_array[4] + " " + json_array[5];
    document.getElementById('MessageDo').textContent = json_array[6] + " " + json_array[7];
    //typ = 0 -> wysłane / odebrane
    if (typ != 0 && typ != 1){ //BUTTON SEND
        const form0 = document.createElement('form');
        form0.method = 'POST';
        form0.action = './../scripts/php/moveToSendMessage.php';
        const button0 = document.createElement('button');
        button0.type = 'submit';
        button0.name = 'messageId';
        button0.value = json_array[0];
        button0.classList = 'jsFormButton';
        button0.innerHTML = "<img src='./../assets/send.png'>";
        form0.appendChild(button0);
        document.getElementById('FormButtons').appendChild(form0);
    }
    //typ = 1 -> usunięte
    if (typ = 1 && json_array[8] == json_array[10] && json_array[9] == 1) { //BUTTON drafts
        const form1 = document.createElement('form');
        form1.method = 'POST';
        form1.action = './../scripts/php/moveToDraftsMessage.php';
        const button1 = document.createElement('button');
        button1.type = 'submit';
        button1.name = 'messageId';
        button1.value = json_array[0];
        button1.classList = 'jsFormButton';
        button1.innerHTML = "<img src='./../assets/drafts_button.png'>";
        form1.appendChild(button1);
        document.getElementById('FormButtons').appendChild(form1);
    }
    if (typ != 1) { //BUTTON trash
        const form2 = document.createElement('form');
        form2.method = 'POST';
        form2.action = './../scripts/php/moveToTrashMessage.php';
        const button2 = document.createElement('button');
        button2.type = 'submit';
        button2.name = 'value';
        button2.value = json_array[0] + "|" + json_array[10] + "|" + json_array[8];
        button2.classList = 'jsFormButton';
        button2.innerHTML = "<img src='./../assets/trash.png'>";
        form2.appendChild(button2);
        document.getElementById('FormButtons').appendChild(form2);
    }



    document.getElementById('OpenedMessage').style.transform = 'translateX(0)';
    if (document.getElementById('OpenedMessage').getAnimations) {
        document.getElementById('OpenedMessage').getAnimations().forEach(a => a.cancel());
    }
    const animationOpen = document.getElementById('OpenedMessage').animate(
        [
            { transform: 'translateX(100vw)'},
            { transform: 'translateX(0)'}
        ],
        {
            duration: 200,
            easing: 'ease-out',
            fill: 'forwards'
        }
    );

}
function EditMessage(){

}
function CloseMessage(){
    // document.getElementById('OpenedMessage').style.display = 'none';
    document.getElementById('FormButtons').innerHTML = '';
    if (document.getElementById('OpenedMessage').getAnimations) {
        document.getElementById('OpenedMessage').getAnimations().forEach(a => a.cancel());
    }
    const animationClose = document.getElementById('OpenedMessage').animate(
        [
            { transform: 'translateX(0)'},
            { transform: 'translateX(100vw)'}
        ],
        {
            duration: 200,
            easing: 'ease-out',
            fill: 'forwards'
        }
    );
    animationClose.finished.then(() => {
        document.getElementById('OpenedMessage').style.display = 'none';

    });
}
