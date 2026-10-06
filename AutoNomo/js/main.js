document.addEventListener("DOMContentLoaded", () => {
    console.log("Sākam ģenerēt lapu...");
// --- DARK MODE (Tumšais režīms) LOGIKA ---
    const darkModeToggle = document.getElementById("darkModeToggle");
    const htmlElement = document.documentElement; // Atlasa <html> tagu

    // 1. Pārbaudām, vai lietotājs iepriekš ir saglabājis tumšo režīmu
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
        htmlElement.setAttribute("data-bs-theme", savedTheme);
        updateToggleIcon(savedTheme);
    }

    // 2. Klikšķa notikums uz pogas
    if (darkModeToggle) {
        darkModeToggle.addEventListener("click", () => {
            // Noskaidrojam pašreizējo režīmu (ja nav norādīts, pieņemam, ka tas ir light)
            const currentTheme = htmlElement.getAttribute("data-bs-theme") || "light";
            const newTheme = currentTheme === "light" ? "dark" : "light";

            // Nomainām atribūtu un saglabājam atmiņā
            htmlElement.setAttribute("data-bs-theme", newTheme);
            localStorage.setItem("theme", newTheme);

            // Nomainām ikonu
            updateToggleIcon(newTheme);
        });
    }

    // Palīgfunkcija ikonas nomaiņai (Mēness vai Saule)
    function updateToggleIcon(theme) {
        if (darkModeToggle) {
            darkModeToggle.innerHTML = theme === "dark" ? "☀️" : "🌙";

            // Tumšajā režīmā poga kļūst par outline-warning (dzeltenīga saule)
            if (theme === "dark") {
                darkModeToggle.classList.remove("btn-outline-light");
                darkModeToggle.classList.add("btn-outline-warning");
            } else {
                darkModeToggle.classList.remove("btn-outline-warning");
                darkModeToggle.classList.add("btn-outline-light");
            }
        }
    }
    // Pārbaudām, vai dati vispār eksistē
    if (typeof carsData === 'undefined') {
        console.error("KĻŪDA: carsData masīvs nav atrasts! Pārbaudi js/cars.js failu.");
        return;
    }

    // --- 1. SĀKUMLAPAS RENDERĒŠANA ---
    const popularContainer = document.getElementById("popularCarsContainer");
    if (popularContainer) {
        popularContainer.innerHTML = "";

        // Atlasām tikai populārās
        const popularCars = carsData.filter(car => car.popular === true);
        console.log(`Sākumlapai atrastas ${popularCars.length} populāras mašīnas.`);

        if(popularCars.length === 0) {
            popularContainer.innerHTML = `<p class="text-center text-muted">Neviena populārā mašīna nav atrasta.</p>`;
        } else {
            popularCars.forEach(car => {
                const col = document.createElement("div");
                col.className = "col-12 col-md-6 col-lg-4";

                col.innerHTML = `
                    <div class="card car-card h-100 shadow-sm ${car.id === 'bmw' ? 'border-success' : ''}">
                        <div class="overflow-hidden">
                            <img src="${car.img}" class="card-img-top" alt="${car.brand} ${car.model}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">${car.brand} <br><small class="text-muted fw-normal">${car.model}</small></h5>
                                <span class="price-badge">${car.price}</span>
                            </div>
                            <p class="card-text text-muted mb-4">${car.type}</p>
                            <a href="auto-detalas.html?auto=${car.id}" class="btn btn-success w-100 mt-auto rounded-pill fw-bold">Apskatīt auto</a>
                        </div>
                    </div>
                `;
                popularContainer.appendChild(col);
            });
        }
    }

// --- 2. CENU LAPAS RENDERĒŠANA (Ar dinamiskiem filtriem) ---
    const pricesTableBody = document.getElementById("pricesTableBody");
    const filterButtons = document.querySelectorAll("#filterButtons .btn");

    if (pricesTableBody && typeof carsData !== 'undefined') {

        // Atsevišķa funkcija tabulas ģenerēšanai, lai varam to izsaukt atkārtoti
        const renderTable = (dataToRender) => {
            pricesTableBody.innerHTML = ""; // Notīrām vecos datus

            // Ja saraksts ir tukšs (piemēram, nav neviena auto šajā klasē)
            if (dataToRender.length === 0) {
                pricesTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted fs-5">Šajā klasē pagaidām nav pieejamu auto.</td></tr>`;
                return;
            }

            dataToRender.forEach(car => {
                const tr = document.createElement("tr");
                if (car.id === "bmw") tr.className = "table-success"; // Izceļam BMW

                tr.innerHTML = `
                    <td class="px-4"><img src="${car.img}" alt="${car.brand}" class="rounded shadow-sm" style="width: 80px; height: 50px; object-fit: cover;"></td>
                    <td class="px-4"><span class="fw-bold">${car.type}</span><br><small class="text-muted">${car.brand} ${car.model}</small></td>
                    <td class="text-center fw-bold">${car.price}</td>
                    <td class="text-center">${car.day}</td>
                    <td class="text-center">${car.km}</td>
                    <td class="text-center">
                        <a href="auto-detalas.html?auto=${car.id}" class="btn btn-sm btn-outline-success px-3 rounded-pill">Apskatīt</a>
                    </td>
                `;
                pricesTableBody.appendChild(tr);
            });
        };

        // Sākotnēji ielādējam pilnīgi visus auto
        renderTable(carsData);

        // Pievienojam klikšķa notikumu katrai filtra pogai
        if (filterButtons.length > 0) {
            filterButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    // 1. Noņemam "aktīvo" stilu visām pogām
                    filterButtons.forEach(btn => {
                        btn.classList.remove("btn-success", "active");
                        btn.classList.add("btn-outline-success");
                    });

                    // 2. Uzliekam "aktīvo" stilu tikai nospiestajai pogai
                    const clickedBtn = e.target;
                    clickedBtn.classList.remove("btn-outline-success");
                    clickedBtn.classList.add("btn-success", "active");

                    // 3. Iegūstam filtra vērtību no data-filter atribūta
                    const filterValue = clickedBtn.getAttribute("data-filter");

                    // 4. Filtrējam masīvu un renderējam tabulu no jauna
                    if (filterValue === "all") {
                        renderTable(carsData);
                    } else {
                        const filteredCars = carsData.filter(car => car.type === filterValue);
                        renderTable(filteredCars);
                    }
                });
            });
        }
    }
});