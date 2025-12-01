function createDiv(arr) {
    let container = document.createElement('div');
    container.classList.add('main-panel');
    container.classList.add('main-panel-child');
    let div = document.createElement('div');
    console.log(arr);
    div.id = arr[2];
    div.classList.add('childCard')
    div.classList.add('main-style-panel');

    let name = document.createElement('h3');
    name.innerText = arr[0] + " " + arr[1];
    div.appendChild(name);

    let details = document.createElement('p');
    details.innerText = "Data urodzenia: " + arr[2] + "\nGrupa: " + arr[4] + "\nRodzice: " + arr[5] + " " + arr[6] + "\nKontakt: " + arr[7];
    div.appendChild(details);
    div.style.display = "none";
    container.appendChild(div);

    return container;
}

function showDzieci(json){
    for (let i = 0; i < json.length; i++){
        // console.log(json[i]);
        let nav = document.getElementById('nav');
        let div = document.createElement('div');
        let id=json[i][2];
        div.classList.add('nav_child');
        div.classList.add('nav_child_child');
        div.classList.add('nav_child_dziecko');
        let img = document.createElement('img');
        img.src="./../assets/little-kid.png";
        img.alt="Dziecko";
        div.appendChild(img);
        let span = document.createElement('span');
        span.innerText = json[i][0] + " " + json[i][1];
        div.appendChild(span);
        nav.appendChild(div);

        div.onclick = function(){
            let nDiv = null;
            if (document.getElementById(id)){
                nDiv = document.getElementById(id).parentElement; // Get the container, not the inner div
            }
            else{
                nDiv = createDiv(json[i]);
                document.getElementById('main').appendChild(nDiv);
            }

            // Hide all panels
            let allDivs = document.getElementsByClassName('main-panel');
            for (let j = 0; j < allDivs.length; j++){
                allDivs[j].style.display="none";
            }

            // Show the clicked child panel
            nDiv.style.display='block';
            nDiv.querySelector('.main-style-panel').style.display='block'; // Show inner div
        }


    }
}
