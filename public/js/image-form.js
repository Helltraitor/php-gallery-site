function changeColor(element, setRed) {
    if (setRed) {
        if (!element.classList.contains('is-invalid'))
            element.classList.add('is-invalid');
    } else {
        if (element.classList.contains('is-invalid'))
            element.classList.remove('is-invalid');
    }
}

function checkFile() {
    const fileFile = document.getElementById('fileFile');
    const file = fileFile.files[0];
    return file
            && (file.name.endsWith('.jpg') || file.name.endsWith('.jpeg'))
            && file.size <= 3 * 1024 * 1024;
}

function fileFileTrigger() {
    let fileFile = document.getElementById('fileFile');
    changeColor(fileFile, fileFile.files.length > 0 && !checkFile());
}

function fileTrigger() {
    let fileConfirm = document.getElementById('fileConfirm');
    if (checkFile()) {
        if (fileConfirm.classList.contains('disabled'))
            fileConfirm.classList.remove('disabled');
    } else {
        if (!fileConfirm.classList.contains('disabled'))
            fileConfirm.classList.add('disabled');
    }
}