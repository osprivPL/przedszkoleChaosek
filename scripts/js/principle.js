function openEditor(date, type, element) {
    // Pobierz tekst, usuń białe znaki i sprawdź czy to placeholder "+ Dodaj"
    let currentText = element.innerText.trim();
    if(currentText.includes('+ Dodaj') || currentText === '+ Dodaj') {
        currentText = '';
    }

    // Ustaw wartości w formularzu
    document.getElementById('inputDate').value = date;
    document.getElementById('inputType').value = type;
    document.getElementById('inputDesc').value = currentText;

    // Pokaż okienko
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('inputDesc').focus();
}

function closeEditor() {
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
}