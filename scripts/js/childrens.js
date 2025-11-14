function createDiv(arr) {
    let div = document.createElement('div');
    console.log(arr);
    div.id = arr[2];
    div.classList.add('childCard');

    let name = document.createElement('h3');
    name.innerText = arr[0] + " " + arr[1];
    div.appendChild(name);

    let details = document.createElement('p');
    details.innerText = "Data urodzenia: " + arr[2] + "\nGrupa: " + arr[4] + "\nRodzice: " + arr[5] + " " + arr[6] + "\nKontakt: " + arr[7];
    div.appendChild(details);
    div.style.display = "none";

    return div;
}

function showOnAside(json){
    for (let i = 0; i < json.length; i++){
        // console.log(json[i]);
        let ul = document.getElementById('listaDzieci');
        if (!ul){
            return;
        }
        let li = document.createElement('li');
        li.innerHTML = json[i][0] + " " + json[i][1] + ", gr. " + json[i][4];
        let main = document.getElementById('main');
        if (!main) {
            console.error('Element with ID "main" not found');
            return;
        }
        main.appendChild(createDiv(json[i]));
        li.onclick = function(){
            let div = document.getElementById(json[i][2]);
            if (div.style.display === 'none'){
                let allDivs = document.getElementsByClassName('childCard');
                for (let j = 0; j < allDivs.length; j++){
                    allDivs[j].style.display="none";
                }
                div.style.display='block';
            } else {
                div.style.display="none";
            }
        }
        ul.appendChild(li);

    }
}
