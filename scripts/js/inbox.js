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
    document.getElementById('messagesContainer').addEventListener('change', (e) => {
        if (e.target.classList.contains('messageCheckbox')) {
            if (e.target.checked) zaznaczone++; else zaznaczone--;
            document.getElementById('selectAllCheckbox').checked = (zaznaczone === ileWiadomosci);
        }
    });
    const master = document.getElementById('selectAllCheckbox');
    master.addEventListener('change', selectAllCheckboxes);
});

function showContainerInbox(n){
    let containers = ['receivedContainer', 'sentContainer', 'deletedContainer', 'draftsContainer', 'writeContainer'];
    for (let i = 0; i < containers.length; i++){
        document.getElementById(containers[i]).style.display = (i === n) ? 'flex' : 'none';
    }
}

function OpenMessage(json_array, typ){

    if (document.getElementById('OpenedMessage').getAnimations) {
        document.getElementById('OpenedMessage').getAnimations().forEach(a => a.cancel());
    }

    document.getElementById('OpenedMessage').style.display = "flex";
    document.getElementById('MessageTytle').textContent = json_array[0];
    document.getElementById('MessageTextarea').textContent = json_array[1];
    document.getElementById('MessageData').textContent = json_array[2];
    document.getElementById('MessageOd').textContent = json_array[3] + " " + json_array[4];
    document.getElementById('MessageDo').textContent = json_array[5] + " " + json_array[6];


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
function CloseMessage(){
    // document.getElementById('OpenedMessage').style.display = 'none';
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
