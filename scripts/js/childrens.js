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

function createDiv(arr) {
    let container = document.createElement('div');
    container.classList.add('main-panel');
    container.classList.add('main-child');
    container.classList.add('bigContainers');
    let div = document.createElement('div');
    // console.log(arr);
    div.id = arr[2];
    div.classList.add('childCard')
    div.classList.add('styling-panel');

    let name = document.createElement('h3');
    name.innerText = arr[0] + " " + arr[1];
    div.appendChild(name);

    let details = document.createElement('p');
    details.innerHTML = "Data urodzenia: " + dateFromPesel(arr[2]) + "<br>" + "Grupa: " + arr[4] + "<br>";
    div.appendChild(details);
    let img = document.createElement('img');
    img.src = "./../assets/childrenImages/" + arr[5];
    div.appendChild(img);
    div.style.display = "none";
    container.appendChild(div);

    return container;
}

function showDzieci(json){
    for (let i = 0; i < json.length; i++){
        // console.log(json[i]);
        let nav = document.getElementById('nav');
        let div = document.createElement('div');
        let img = document.createElement('img');
        let span = document.createElement('span');
        let id=json[i][2];

        div.classList.add('nav_child');
        div.classList.add('nav_child_child');
        div.classList.add('nav_child_dziecko');

        img.src="./../assets/little-kid.png";
        img.alt="Dziecko";

        span.innerText = json[i][0] + " " + json[i][1];
        div.appendChild(img);
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
            let allDivs = document.getElementsByClassName('main-panel');
            for (let j = 0; j < allDivs.length; j++){
                allDivs[j].style.display="none";
            }
            nDiv.style.display='flex';
            nDiv.querySelector('.styling-panel').style.display='block'; // Show inner div
        }
    }
}


function showPlan(n){
    let plany = document.getElementsByClassName("plan-container")
    console.log(plany);
    for (let i = 0; i < plany.length; i++){
        if (i===n-1){
            plany[i].style.display="block";
            continue;
        }

        plany[i].style.display="none";
    }
}