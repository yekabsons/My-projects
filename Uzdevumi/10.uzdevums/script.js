    function aprekinat(darbiba) {
    let sk1 = parseFloat(document.getElementById('sk1').value);
    let sk2 = parseFloat(document.getElementById('sk2').value);
    let rez = 0;

    if (isNaN(sk1) || isNaN(sk2)) {
    alert("Lūdzu ievadiet abus skaitļus!");
    return;
}

    switch(darbiba) {
    case '+': rez = sk1 + sk2; break;
    case '-': rez = sk1 - sk2; break;
    case '*': rez = sk1 * sk2; break;
    case '/': rez = sk1 / sk2; break;
}
    document.getElementById('rezultats').value = rez;
}

    function aprekinatIzteiksmi() {
    let izteiksme = document.getElementById('izteiksme').value;
    try {
    let rez = eval(izteiksme);
    document.getElementById('izteiksmes_rez').value = rez;
} catch (e) {
    alert("Kļūda izteiksmē!");
}
}

    function notirit() {
    document.getElementById('sk1').value = '';
    document.getElementById('sk2').value = '';
    document.getElementById('rezultats').value = '';
    document.getElementById('izteiksme').value = '';
    document.getElementById('izteiksmes_rez').value = '';
}

    function generetReizrekinu() {
    let saturs = "";
    for (let i = 1; i <= 10; i++) {
    saturs += "<div class='multiplication-box'>";
    for (let j = 1; j <= 10; j++) {
    saturs += i + " * " + j + " = " + (i * j) + "<br>";
}
    saturs += "</div>";
}
    document.getElementById('modal-body-content').innerHTML = saturs;
}