function selectAllCheckboxes(){
    let cb = document.getElementById('selectAllCheckbox').checked;
    if(cb){
        let checkboxes = document.getElementsByClassName('checkbox');
        for(let i=0; i<checkboxes.length; i++){
            checkboxes[i].checked = true;
        }
    }
    else{
        let checkboxes = document.getElementsByClassName('checkbox');
        for(let i=0; i<checkboxes.length; i++){
            checkboxes[i].checked = true;
        }
    }
}