function handleRadio(radio) {
    var row = radio.closest('tr');
    var selectInput = row.querySelector('select');

    if (radio.value === 'valid') {

        selectInput.style.display = 'block';
        var f = radio.closest('div');
        f.style.display = 'none';
    } else if (radio.value === 'invalid') {

        row.remove();
    }
}
