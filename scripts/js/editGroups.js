function editGroup(g) {
    let divGroupName = document.getElementById("divGroupName" + g);
    let divSupervisor = document.getElementById("divGroupSupervisor" + g);
    let inputGroupName = document.getElementById("inputGroupName" + g);
    let inputGroupSupervisor = document.getElementById("inputGroupSupervisor" + g);
    let supervisor = divSupervisor.innerHTML.substring(divSupervisor.innerHTML.indexOf(':') +2);

    divGroupName.style.display = "none";
    divSupervisor.style.display = "none";

    document.getElementById('frmUpdateGroup'+g).style.display = "block";

    inputGroupName.value = divGroupName.innerHTML.substring(divGroupName.innerHTML.indexOf(':')+2) ;
    inputGroupSupervisor.value = divSupervisor.innerText;
    console.log(supervisor);
    
    for (let i = 0; i < inputGroupSupervisor.options.length; i++) {
        if (inputGroupSupervisor.options[i].text === supervisor) {
            inputGroupSupervisor.options[i].selected = 'selected';
            break;
        }
    }
}