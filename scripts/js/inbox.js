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
    }

    checkEmptyLists();
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

document.addEventListener('DOMContentLoaded', () => {
    checkEmptyLists();

    document.querySelectorAll('.messageCheckbox').forEach(cb => {
        cb.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });
});

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

    document.getElementById('OpenedMessage').style.display = "flex";
    document.getElementById('MessageTytle').textContent = json_array[1];
    document.getElementById('MessageTextarea').textContent = json_array[2];
    document.getElementById('MessageData').textContent = json_array[3];
    document.getElementById('MessageOd').textContent = json_array[4] + " " + json_array[5];
    document.getElementById('MessageDo').textContent = json_array[6] + " " + json_array[7];

    document.getElementById('FormButtons').innerHTML = '';

    if (typ != 0 && typ != 1){
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

    if (typ == 1 && json_array[8] == json_array[10] && json_array[9] == 1) {
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

    if (typ != 1) {
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

function CloseMessage(){
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