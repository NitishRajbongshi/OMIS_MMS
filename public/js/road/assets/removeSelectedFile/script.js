function removeFile(inputId) {
    var fileInput = document.getElementById(inputId);
    if (fileInput) {
        // Check if the element exists
        fileInput.value = "";
        hideRemoveBtn(inputId);
    }
}

function showRemoveBtn(inputId) {
    var removeButton = document.getElementById("removeBtn_" + inputId);
    if (removeButton) {
        // Check if the element exists
        removeButton.style.display = "inline-block";
    }
}

function hideRemoveBtn(inputId) {
    var removeButton = document.getElementById("removeBtn_" + inputId);
    if (removeButton) {
        // Check if the element exists
        removeButton.style.display = "none";
    }
}
