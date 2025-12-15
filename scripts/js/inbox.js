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

function OpenMessage(tytul, tresc, data, nadawca_imie, nadawca_nazwisko, odbiorca_imie, odbiorca_nazwisko, typ){
    alert(tytul);
}
function CloseMessage(){

}
