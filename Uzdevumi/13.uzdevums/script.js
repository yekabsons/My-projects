 // Autors: Ervīns Jēkabsons,it25112
    // Izmantots: HTML5 Canvas + Vanilla JavaScript
    // Mana projekta iedvesma ir no šī video - https://www.youtube.com/watch?v=3GqUM4mEYKA

    const canvas = document.getElementById('drawCanvas');
    const ctx = canvas.getContext('2d'); // '2d' norāda, ka veidosim 2D grafiku

    const colorPicker = document.getElementById('colorPicker');
    const brushSize = document.getElementById('brushSize');
    const clearBtn = document.getElementById('clearBtn');

    // Šie mainīgie sekos līdzi zīmēšanas stāvoklim
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Funkcija, kas nostrādā, kad nospiež peles taustiņu (sāk zīmēt)
    function startDrawing(e) {
    isDrawing = true;
    // Piefiksējam sākuma koordinātas attiecībā pret Canvas elementu
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

    // Galvenā zīmēšanas funkcija, kas nostrādā peles kustības laikā
    function draw(e) {
    if (!isDrawing) return; // Ja pele nav nospiesta, pārtraucam funkciju

    // Iestatām līnijas vizuālos parametrus no ievades laukiem
    ctx.strokeStyle = colorPicker.value;
    ctx.lineWidth = brushSize.value;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    // Zīmēšanas process
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();

    // Atjaunojam koordinātas nākamajam solim
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

    // Funkcija, kas nostrādā, kad atlaiž peles taustiņu (beidz zīmēt)
    function stopDrawing() {
    isDrawing = false;
}

    // Funkcija audekla notīrīšanai
    function clearCanvas() {
    // clearRect izdzēš taisnstūra laukumu no koordinātām (0,0) līdz (800,500)
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

    // Pievienojam Canvas elementam "dzirdīgumu" uz peles darbībām
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Pievienojam dzēšanas pogai "klikšķa" notikumu
    clearBtn.addEventListener('click', clearCanvas);