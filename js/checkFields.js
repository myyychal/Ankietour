function checkProductTypeFields(msg) {
    if (msg == 'create') {
        var nameString = document.getElementById("newName").value;
        if (!nameString) {
            document.getElementById("errMsg").innerHTML = "You must specify new product type's name.";
            return false;
        } else {
            return true;
        }
    } else if (msg == 'edit') {
        var nameString = document.getElementById("editName").value;
        if (!nameString) {
            document.getElementById("errMsg2").innerHTML = "You must specify product type's name.";
            return false;
        } else {
            return true;
        }
    }
}

function checkUserFields() {
    var nameString = document.getElementById("newUsername").value;
    var passwdString = document.getElementById("newPassword").value;

    if (!nameString && !passwdString) {
        document.getElementById("errMsg").innerHTML = "You must specify new user's login and password.";
        return false;
    } else {
        if (!passwdString) {
            document.getElementById("errMsg").innerHTML = "You must specify new user's password.";
            return false;
        } else if (!nameString) {
            document.getElementById("errMsg").innerHTML = "You must specify new user's login.";
            return false;
        } else {
            return true;
        }
    }
}

function checkQuestionnaireFields(){
    var selectedUserId = document.getElementById("newPersonId").value;
    var selectedTypeId = document.getElementById("newTypeId").value;
    var chosenEmail = document.getElementById("newEmail").value;
    if (selectedUserId == 0 && selectedTypeId == 0 && !chosenEmail) {
        document.getElementById("errMsg").innerHTML = "You must specify pollster and product type.";
        return false;
    } else {
        if (selectedUserId == 0 && !chosenEmail) {
            document.getElementById("errPersonId").innerHTML = "You must specify pollster.";
            return false;
        } else if (selectedTypeId == 0) {
            document.getElementById("errTypeId").innerHTML = "You must specify product type.";
            return false;
        } else {
            return true;
        }
    }
}