document.addEventListener('DOMContentLoaded', function () {
    inicijalizujStavke();
    inicijalizujValidaciju();
});

function inicijalizujStavke() {
    var kontejner = document.getElementById('stavke-lista');
    if (!kontejner) return;

    var dugmeDodaj = document.getElementById('dodaj-stavku');
    dugmeDodaj.addEventListener('click', function () {
        dodajRedStavke(kontejner);
    });

    kontejner.addEventListener('click', function (e) {
        if (e.target.classList.contains('ukloni-stavku')) {
            var redovi = kontejner.querySelectorAll('.stavka-red');
            if (redovi.length <= 1) {
                alert('Prijava mora imati bar jednu stavku (kompaniju/poziciju).');
                return;
            }
            e.target.closest('.stavka-red').remove();
            preimenujIndekse(kontejner);
        }
    });
}

function dodajRedStavke(kontejner, vrednosti) {
    vrednosti = vrednosti || {};
    var indeks = kontejner.querySelectorAll('.stavka-red').length;

    var div = document.createElement('div');
    div.className = 'stavka-red';
    div.innerHTML =
        '<table><tr>' +
        '<td><label>Kompanija</label><br><input type="text" name="stavke[' + indeks + '][kompanija]" maxlength="100" required value="' + escHtml(vrednosti.kompanija) + '"></td>' +
        '<td><label>Pozicija</label><br><input type="text" name="stavke[' + indeks + '][pozicija]" maxlength="100" required value="' + escHtml(vrednosti.pozicija) + '"></td>' +
        '<td><label>Trajanje prakse</label><br><input type="text" name="stavke[' + indeks + '][trajanje]" maxlength="50" required placeholder="npr. 3 meseca" value="' + escHtml(vrednosti.trajanje) + '"></td>' +
        '<td><label>Napomena</label><br><input type="text" name="stavke[' + indeks + '][napomena]" maxlength="255" value="' + escHtml(vrednosti.napomena) + '"></td>' +
        '<td style="vertical-align:bottom;"><button type="button" class="dugme dugme-opasnost ukloni-stavku">&times;</button></td>' +
        '</tr></table>';
    kontejner.appendChild(div);
}

function preimenujIndekse(kontejner) {
    kontejner.querySelectorAll('.stavka-red').forEach(function (red, indeks) {
        red.querySelectorAll('input').forEach(function (polje) {
            polje.name = polje.name.replace(/stavke\[\d+\]/, 'stavke[' + indeks + ']');
        });
    });
}

function escHtml(vrednost) {
    if (!vrednost) return '';
    var div = document.createElement('div');
    div.textContent = vrednost;
    return div.innerHTML.replace(/"/g, '&quot;');
}

function inicijalizujValidaciju() {
    document.querySelectorAll('form[data-validiraj]').forEach(function (forma) {
        forma.addEventListener('submit', function (e) {
            var greske = [];
            forma.querySelectorAll('[required]').forEach(function (polje) {
                if (!polje.value || polje.value.trim() === '') {
                    polje.style.borderColor = '#b3261e';
                    greske.push(polje);
                } else {
                    polje.style.borderColor = '';
                }
            });
            if (greske.length > 0) {
                e.preventDefault();
                alert('Molimo popunite sva obavezna polja.');
                greske[0].focus();
            }
        });
    });
}
