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
        span.innerText = json[i][0] + " " + json[i][1] + ", gr. " + json[i][4];
        div.appendChild(span);
        nav.appendChild(div);

        div.onclick = function(){
            let nDiv = null;
            if (document.getElementById(id)){
                nDiv = document.getElementById(id);
            }
            else{
                nDiv = createDiv(json[i]);
            }

            document.getElementById('main').appendChild(nDiv);
            if (nDiv.style.display === 'none'){
                let allDivs = document.getElementsByClassName('main-panel');
                for (let j = 0; j < allDivs.length; j++){
                    allDivs[j].style.display="none";
                }
                allDivs = document.getElementsByClassName('childCard');
                for (let j = 0; j < allDivs.length; j++){
                    allDivs[j].style.display="none";
                }

                nDiv.style.display='block';
            } else {
                nDiv.style.display="none";
            }
        }

    }
}
