function checkEmptyLists() {
    const map = [
        { container: 'receivedContainer', master: 'selectAllCheckboxReceived' },
        { container: 'sentContainer',     master: 'selectAllCheckboxSent' },
        { container: 'deletedContainer',  master: 'selectAllCheckboxDeleted' },
        { container: 'draftsContainer',   master: 'selectAllCheckboxDrafts' }
    ];

    map.forEach(item => {
        const container = document.getElementById(item.container);
        const master = document.getElementById(item.master);

        if (container && master) {
            const messageCount = container.querySelectorAll('.message').length;

            if (messageCount === 0) {
                master.disabled = true;
                master.checked = false;
                master.indeterminate = false;
                master.style.cursor = 'not-allowed';
            } else {
                master.disabled = false;
                master.style.cursor = 'pointer';
            }
        }
    });
}

function showContainerInbox(n) {
    let containers = ['receivedContainer', 'sentContainer', 'deletedContainer', 'draftsContainer', 'writeContainer'];

    document.querySelectorAll('.messageCheckbox').forEach(cb => cb.checked = false);

    const masters = [
        'selectAllCheckboxReceived',
        'selectAllCheckboxSent',
        'selectAllCheckboxDeleted',
        'selectAllCheckboxDrafts'
    ];
    masters.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.checked = false;
            el.indeterminate = false;
        }
    });

    for (let i = 0; i < containers.length; i++) {
        const el = document.getElementById(containers[i]);
        if (el) el.style.display = (i === n) ? 'flex' : 'none';
        if(containers[i] == 'writeContainer'){
            clearNewMes();
        }
    }

    checkEmptyLists();
    updateDeletingBar();
}

function toggleAll(master, containerId) {
    const container = document.getElementById(containerId);
    const boxes = container.querySelectorAll('.messageCheckbox');

    if (boxes.length === 0) {
        master.checked = false;
        master.indeterminate = false;
        return;
    }

    boxes.forEach(box => {
        box.checked = master.checked;
    });
    master.indeterminate = false;
    updateDeletingBar();
}

function updateMasterState(containerId, masterId) {
    const container = document.getElementById(containerId);
    const master = document.getElementById(masterId);
    const boxes = container.querySelectorAll('.messageCheckbox');

    const total = boxes.length;
    const checkedCount = Array.from(boxes).filter(b => b.checked).length;

    if (total === 0) {
        master.checked = false;
        master.indeterminate = false;
        return;
    }

    if (checkedCount === 0) {
        master.indeterminate = false;
        master.checked = false;
    } else if (checkedCount === total) {
        master.indeterminate = false;
        master.checked = true;
    } else {
        master.checked = false;
        master.indeterminate = true;
    }
    updateDeletingBar();
}

document.addEventListener('change', (e) => {
    if (e.target.classList.contains('messageCheckbox')) {
        const container = e.target.closest('.messagesContainer');
        if (!container) return;

        let masterId = '';
        if (container.id === 'receivedContainer') masterId = 'selectAllCheckboxReceived';
        if (container.id === 'sentContainer') masterId = 'selectAllCheckboxSent';
        if (container.id === 'deletedContainer') masterId = 'selectAllCheckboxDeleted';
        if (container.id === 'draftsContainer') masterId = 'selectAllCheckboxDrafts';

        if (masterId) updateMasterState(container.id, masterId);
    }
});

// updates the deleting bar visibility and the selected count
function updateDeletingBar() {
    const deletingBar = document.querySelector('.deleting');
    const countSpan = document.getElementById('howMuchCheckboxes');
    if (!deletingBar || !countSpan) return;
    // count all checked messageCheckboxes except those with class 'example'
    const checked = document.querySelectorAll('.messageCheckbox:not(.example):checked').length;
    countSpan.textContent = checked;
    if(checked > 0){
        deletingBar.classList.add('visible');
    }
    else{
        deletingBar.classList.remove('visible');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    checkEmptyLists();

    document.querySelectorAll('.messageCheckbox').forEach(cb => {
        cb.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
    updateDeletingBar();
});

function PopUpDraft(){
    const mesTopic = document.getElementById("newMesTytul");
    const mesContent = document.getElementById("newMesTresc");

    if (mesTopic.value.trim() !== '' && mesContent.value.trim() !== ''){
        document.getElementById('writeContainer').style.display = 'none';
        document.getElementById('warningPopUp').style.display = 'flex';
    }
    else{
        if (mesTopic.value.trim() == '' && mesContent.value.trim() == ''){
            showContainerInbox(0);
        }else{
            if (mesTopic.value.trim() == ''){
                mesTopic.classList.add('error');
            }else {
                mesTopic.classList.remove('error');
            }
            if (mesContent.value.trim() == ''){
                mesContent.classList.add('error');
            }else {
                mesContent.classList.remove('error');
            }
        }

    }
}
function clearNewMes(){
    const a = document.getElementById("newMesTytul");
    const b = document.getElementById("newMesTresc");

    a.value = '';
    b.value = '';

    if (a.classList.contains('error') || b.classList.contains('error')) {
        a.classList.remove('error');
        b.classList.remove('error');
    }
}
function SaveAsDraft(){
    document.getElementById('buttonDraftId').click();
    document.getElementById('warningPopUp').style.display = 'none';
    //showContainerInbox(0);
}
function DontSaveDraft(){
    document.getElementById('warningPopUp').style.display = 'none';
    clearNewMes();
    showContainerInbox(0);
}

function OpenMessage(json_array, typ) {
    const anyChecked = document.querySelectorAll('.messageCheckbox:checked').length > 0;

    if (anyChecked) {
        const messageId = json_array[0];
        const checkbox = document.querySelector('input[name="message' + messageId + '"]');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        }
        return;
    }

    if (document.getElementById('OpenedMessage').getAnimations) {
        document.getElementById('OpenedMessage').getAnimations().forEach(a => a.cancel());
    }

    // json[0]  -> id wiadomości
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
    // json[11] -> odbiorcaID
    const spanDo = document.getElementById('MessageDoSpan');
    const selectDo = document.getElementById('MessageDoSelect')
    const tytle = document.getElementById('MessageTytle');
    const textArea = document.getElementById('MessageTextarea');
    if (spanDo.style.display == 'none'){
        spanDo.style.display = 'block';
        selectDo.style.display = 'none';
        tytle.disabled = true;
        textArea.disabled = true;
    }

    document.getElementById('OpenedMessage').style.display = "flex";
    tytle.value = json_array[1];
    textArea.textContent = json_array[2];
    document.getElementById('MessageData').textContent = json_array[3];
    if (typ != 2){
        document.getElementById('MessageOd').textContent = json_array[4] + " " + json_array[5];

    }else {
        spanDo.style.display = 'none';
        selectDo.style.display = 'block';
        tytle.disabled = false;
        textArea.disabled = false;
    }

    document.getElementById('MessageDo').textContent = json_array[6] + " " + json_array[7];

    document.getElementById('FormButtons').innerHTML = '';
    if (json_array[10] == json_array[11]){
        const buttonOdp = document.createElement('button');
        buttonOdp.classList = 'submitButton';
        buttonOdp.innerHTML = "<img src='./../assets/send.png'>";
        buttonOdp.onclick =  () => EditMessage(json_array);
        buttonOdp.innerHTML="Odpisz";
        document.getElementById('FormButtons').appendChild(buttonOdp);
    }

    if (typ == 2) {
        spanDo.style.display = 'none';
        const selectDo = document.createElement('select')
        document.getElementById('MessageOd')
        const buttonEdit = document.createElement('button');
        buttonEdit.type = 'submit';
        buttonEdit.name = 'value';
        buttonEdit.value = json_array[0] + "|" + json_array[1] + "|" + json_array[2] + "|" + json_array[11] + "|" + "save" ;
        buttonEdit.classList = 'submitButton';
        buttonEdit.innerHTML = 'Zapisz';
        document.getElementById('FormButtons').appendChild(buttonEdit);

        const button0 = document.createElement('button');
        button0.type = 'submit';
        button0.name = 'value';
        button0.value = json_array[0] + "|" + json_array[1] + "|" + json_array[2] + "|" + json_array[11] + "|" + "send" ;
        button0.classList = 'submitButton';
        button0.innerHTML = 'Wyślij';
        document.getElementById('FormButtons').appendChild(button0);

    }
    if (typ == 1 && json_array[8] == json_array[10] && json_array[9] == 1) {
        const form1 = document.createElement('form');
        form1.method = 'POST';
        form1.action = './../scripts/php/moveToDraftsMessage.php';
        const button1 = document.createElement('button');
        button1.type = 'submit';
        button1.name = 'messageId';
        button1.value = json_array[0];
        button1.classList = 'submitButton';
        button1.innerHTML = 'Zapisz Kopie roboczą';
        form1.appendChild(button1);
        document.getElementById('FormButtons').appendChild(form1);
    }

    if (typ != 1) {
        const form2 = document.createElement('form');
        form2.method = 'POST';
        form2.action = './../scripts/php/moveToTrashMessage.php';
        const button2 = document.createElement('button');
        button2.type = 'submit';
        button2.name = 'value';
        button2.value = json_array[0] + "|" + json_array[10] + "|" + json_array[8];
        button2.classList = 'submitButton';
        button2.innerHTML = 'Usuń wiadomość';
        form2.appendChild(button2);
        document.getElementById('FormButtons').appendChild(form2);
    }
    if (typ != 0 && typ != 1 && typ != 2){
        const form0 = document.createElement('form');
        form0.method = 'POST';
        form0.action = './../scripts/php/moveToSendMessage.php';
        const button0 = document.createElement('button');
        button0.type = 'submit';
        button0.name = 'messageId';
        button0.value = json_array[0];
        button0.classList = 'submitButton';
        button0.innerHTML = 'Wyślij';
        form0.appendChild(button0);
        document.getElementById('FormButtons').appendChild(form0);
    }

    document.getElementById('OpenedMessage').style.transform = 'translateX(0)';
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
function EditMessage(json_array){
    document.getElementById('OpenedMessage').style.display = 'none';
    //new Message w tytułem taki sam jak tam ale z dopiskiem Re:

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