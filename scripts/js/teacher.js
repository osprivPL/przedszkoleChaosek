function createDiv(arr) {
    let container = document.createElement('div');
    container.classList.add('main-panel');
    container.classList.add('main-panel-child');
    let div = document.createElement('div');


    return container;
}
function createGroups(json1, json2) { // json 1 - info o grupie, json 2 - lista dzieci
    for (let i = 0; i < json1.length; i++) {
        // console.log(json1[i]);
        let nav = document.getElementById('nav');
        let div = document.createElement('div');
        let img = document.createElement('img');
        let span = document.createElement('span');
        let id = json1[i][2];

        div.classList.add('nav_child');
        div.classList.add('nav_child_child');
        div.classList.add('nav_child_grupa');

        img.src = "./../assets/little-kid.png";
        img.alt = "Grupa";

        span.innerText = "gr " + json1[i][0] + " - " + json1[i][1];
        div.appendChild(img);
        div.appendChild(span);

        nav.appendChild(div);
    }
    // div.onclick = function(){
    //     let nDiv = null;
    //     if (document.getElementById(id)){
    //         nDiv = document.getElementById(id).parentElement; // Get the container, not the inner div
    //     }
    //     else{
    //         nDiv = createDiv(json[i]);
    //         document.getElementById('main').appendChild(nDiv);
    //     }
    //     let allDivs = document.getElementsByClassName('main-panel');
    //     for (let j = 0; j < allDivs.length; j++){
    //         allDivs[j].style.display="none";
    //     }
    //     nDiv.style.display='flex';
    //     nDiv.querySelector('.main-style-panel').style.display='block'; // Show inner div
    // }
}

function createParents(json1, json2) { // json 1 - info o grupie, json 2 - lista dzieci
    for (let i = 0; i < json1.length; i++) {
        // console.log(json1[i]);
        let nav = document.getElementById('nav');
        let div = document.createElement('div');
        let img = document.createElement('img');
        let span = document.createElement('span');
        let id = json1[i][2];

        div.classList.add('nav_child');
        div.classList.add('nav_child_child');
        div.classList.add('nav_child_rodzic');

        img.src = "./../assets/little-kid.png";
        img.alt = "Rodzic";

        span.innerText = "gr " + json1[i][0] + " - " + json1[i][1];
        div.appendChild(img);
        div.appendChild(span);

        nav.appendChild(div);
    }
    // div.onclick = function(){
    //     let nDiv = null;
    //     if (document.getElementById(id)){
    //         nDiv = document.getElementById(id).parentElement; // Get the container, not the inner div
    //     }
    //     else{
    //         nDiv = createDiv(json[i]);
    //         document.getElementById('main').appendChild(nDiv);
    //     }
    //     let allDivs = document.getElementsByClassName('main-panel');
    //     for (let j = 0; j < allDivs.length; j++){
    //         allDivs[j].style.display="none";
    //     }
    //     nDiv.style.display='flex';
    //     nDiv.querySelector('.main-style-panel').style.display='block'; // Show inner div
    // }
}