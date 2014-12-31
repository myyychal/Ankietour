function showAndHideDiv(divElem1, divElem2, divElem3) {
    document.getElementById(divElem2).style.display = 'none';
    document.getElementById(divElem3).style.display = 'none';
    if (document.getElementById(divElem1).style.display != 'none') {
        document.getElementById(divElem1).style.display = 'none';
    } else {
        document.getElementById(divElem1).style.display = 'block'
    }
}

function reloadShowAndHideDiv(divElem1, divElem2, divElem3) {
    document.getElementById(divElem1).style.display = 'block';
    document.getElementById(divElem2).style.display = 'none';
    document.getElementById(divElem3).style.display = 'none';
}

function hideAll(divElem1, divElem2, divElem3) {
    document.getElementById(divElem1).style.display = 'none';
    document.getElementById(divElem2).style.display = 'none';
    document.getElementById(divElem3).style.display = 'none';
}

function _add_more() {
    var txt = document.createElement('input');
    txt.type = "file";
    txt.name = "item_file[]";
    var br = document.createElement('br');
    document.getElementById("files").appendChild(txt);
    document.getElementById("files").appendChild(br);
}

function getSelectedPerson() {
    var e = document.getElementById("selectPerson");
    var strUser = e.options[e.selectedIndex].value;
    return strUser;
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('imagePreview').src=e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function getSliderValue(){
    var rangeValue = 1000;
    //rangeValue = document.getElementById("fader").value;
    return rangeValue;
}

function showPopup(id, name) {
    var randomnumber = Math.floor((Math.random()*100)+1);
    window.open( "productInfo.php?id=" + id, name,"status = 1, height = 500, width = 300, resizable = 0" )
}