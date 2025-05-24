<?php

require_once "admin/db.php"; // falls noch nicht vorhanden

// Sprachdatei laden
require_once __DIR__ . '/lang/lang.php';

if (!isset($lang)) {
    die('⚠️ Sprachdatei konnte nicht geladen werden.');
}

// Werte berechnen
$total   = $pdo->query("SELECT COUNT(*) FROM scents")->fetchColumn();
$count_m = $pdo->query("SELECT COUNT(*) FROM scents WHERE gender = 'Herren'")->fetchColumn();
$count_w = $pdo->query("SELECT COUNT(*) FROM scents WHERE gender = 'Damen'")->fetchColumn();
$count_u = $pdo->query("SELECT COUNT(*) FROM scents WHERE gender = 'Unisex'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Düfte</title>

  <!-- CSS files -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/font-awesome.css">
  <link href="assets/css/tabler.min.css" rel="stylesheet" />
  <link href="assets/css/demo.min.css" rel="stylesheet" />
  <link href="assets/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<style>
  @import url('https://rsms.me/inter/inter.css');

  :root {
    --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
  }

  body {
    font-feature-settings: "cv03", "cv04", "cv11";
  }

  table.table {
    table-layout: fixed;
    width: 100%;
  }

  table.table th,
  table.table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
    padding: 1rem;
    border-bottom: 1px solid #ddd;
  }

  table.table td img {
    vertical-align: middle;
  }

  /* Spaltenbreiten manuell festlegen */
  table.table th:nth-child(1),
  table.table td:nth-child(1) {
    width: 5%; /* Code */
    max-width: 10%;
    text-align: left;
  }

  table.table th:nth-child(2),
  table.table td:nth-child(2) {
    width: 50%; /* Inspiriert von */
    max-width: 50%;
  }

  table.table th:nth-child(3),
  table.table td:nth-child(3) {
    width: 25%; /* Geschlecht */
    max-width: 25%;
  }

  @media (max-width: 768px) {
    table.table {
      display: block;
      overflow-x: auto;
    }
  }
</style>
<style>
    /* Dropdown-Hintergrund fixen */
    .ts-dropdown {
        background-color: #fff !important;
        opacity: 1 !important;
        backdrop-filter: none !important;
    }

    /* Einzelne Optionen */
    .ts-dropdown .option {
        opacity: 1 !important;
        background-color: #fff !important;
        color: #000 !important;
    }
    </style>


<!-- amCharts direkt laden -->
  <script src="assets/js/core.js"></script>
  <script src="assets/js/charts.js"></script>
  <script src="assets/js/animated.js"></script>

  <!-- amCharts script -->
  <script>
  am4core.useTheme(am4themes_animated);
  am4core.addLicense("CH089154539");

  window.renderCharts = function(data) {
    am4core.disposeAllCharts();

    const colorMaps = {
      Dufttyp: {
        "Frisch": "#9bbcb7",
        "Aromatisch": "#a8bbec",
        "Süß": "#d1a2ff",
        "Würzig": "#c16692",
        "Holzig": "#d79785",
        "Fruchtig": "#ffa0ab",
        "Pudrig": "#d7d7c7"
      },
      Stil: {
        "Herren": "#8aafee",
        "Damen": "#f1a1f1",
        "Klassisch": "#aaafc8",
        "Modern": "#cbd5ea"
      },
      Jahreszeit: {
        "Sommer": "#ffd667",
        "Winter": "#a2cbff",
        "Frühling": "#badc82",
        "Herbst": "#d9b1be"
      },
      Anlass: {
        "Täglich": "#a7dcf3",
        "Freizeit": "#f6ea86",
        "Ausgehen": "#a096e8",
        "Arbeit": "#c1c1c1",
        "Abend": "#f480ac"
      }
    };

    const createChart = (divId, chartData, type) => {
      let chart = am4core.create(divId, am4charts.PieChart);
      chart.radius = am4core.percent(60);
      chart.data = chartData;

      let series = chart.series.push(new am4charts.PieSeries());
      series.dataFields.value = "value";
      series.dataFields.category = "category";
      series.labels.template.text = "{category} {value.percent.formatNumber('#.')}%";
      series.labels.template.fill = am4core.color("#55636F");
      series.ticks.template.stroke = am4core.color("#55636F");
      series.hiddenState.properties.startAngle = -90;
      series.hiddenState.properties.endAngle = -90;
      series.fontSize = 11;

      // Dynamische Farben je nach Kategorie
      series.slices.template.adapter.add("fill", (fill, target) => {
        let cat = target.dataItem?.category;
        return colorMaps[type]?.[cat] || fill;
      });
    };

    createChart("chartdiv_dufttyp", data.Dufttyp, "Dufttyp");
    createChart("chartdiv_stil", data.Stil, "Stil");
    createChart("chartdiv_jahreszeit", data.Jahreszeit, "Jahreszeit");
    createChart("chartdiv_anlass", data.Anlass, "Anlass");
  };
</script>

</head>
<body>
<div class="container my-4">
<!-- Logo -->
<img src="assets/img/logo.svg" alt="Logo" style="width: 160px; height: auto; display: block; margin: 40px auto 20px;" />

<!-- Abstand zum Logo -->
<div class="my-4"></div>

<!-- Kontakt-Buttons nebeneinander, zentriert und responsive -->
<div class="container my-4">
  <div class="row justify-content-center g-3">
    <!-- WhatsApp Button -->
    <div class="col-12 col-md-auto">
      <a href="https://wa.me/491772160174?text=Hallo%2C%20ich%20habe%20eine%20Frage%20zu%20deinen%20Düften"
         class="btn btn-6"
         style="background-color: #25D366; color: white;"
         target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="24" height="24" viewBox="0 0 24 24"
             fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp me-2">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
          <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
        </svg>
        <?= $lang['index_btn_whatsapp'] ?>
      </a>
    </div>
    <!-- Instagram Button -->
    <div class="col-12 col-md-auto">
      <a href="https://www.instagram.com/lcree.de"
         class="btn btn-6 btn-instagram"
         target="_blank" rel="noopener" aria-label="Chat on Instagram">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="24" height="24" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round"
             class="icon me-2">
          <path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z"></path>
          <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
          <path d="M16.5 7.5v.01"></path>
        </svg>
        <?= $lang['index_btn_instagram'] ?>
      </a>
    </div>
  </div>
</div>

                        <!-- Cards Duefte -->
                        <div class="col-12">
                            <div class="row row-cards">
                                <!-- Gesamt Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-primary text-white avatar">
                                                        <i class="fa-solid fa-boxes-stacked"
                                                            style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $total ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['index_total'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Herren Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-cyan text-white avatar">
                                                        <i class="fa-solid fa-mars" style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $count_m ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['index_men'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Damen Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-pink text-white avatar">
                                                        <i class="fa-solid fa-venus" style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $count_w ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['index_women'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Unisex Card -->
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-teal text-white avatar">
                                                        <i class="fa-solid fa-venus-mars fa-2x"
                                                            style="font-size: 27px;"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?= $count_u ?>
                                                    </div>
                                                    <div class="text-secondary">
                                                    <?= $lang['index_unisex'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<!-- Abstand zum Filter -->
<div class="my-4"></div>

<!-- Suchleiste + Filter in einer Zeile -->
<div class="row g-3 align-items-center mb-4" id="filterRow">

  <!-- Suchleiste nimmt 100% auf Mobile, 4 Spalten auf Desktop -->
  <div class="col-12 col-md-4">
    <div class="input-icon w-100">
      <input type="text" id="searchInput" class="form-control" placeholder="<?= $lang['index_search_placeholder'] ?>">
      <span class="input-icon-addon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
          <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
          <path d="M21 21l-6 -6"></path>
        </svg>
      </span>
    </div>
  </div>

  <!-- Filter: Geschlecht -->
  <div class="col-6 col-md-2">
    <select id="genderFilter" class="form-select">
      <option value=""><?= $lang['index_filter_gender'] ?></option>
      <option value="Herren"><?= $lang['index_gender_men'] ?></option>
      <option value="Damen"><?= $lang['index_gender_women'] ?></option>
      <option value="Unisex"><?= $lang['index_gender_unisex'] ?></option>
    </select>
  </div>

  <!-- Filter: Jahreszeit -->
  <div class="col-6 col-md-2">
    <select id="seasonFilter" class="form-select">
      <option value=""><?= $lang['index_filter_season'] ?></option>
      <option value="Frühling"><?= $lang['index_season_spring'] ?></option>
      <option value="Sommer"><?= $lang['index_season_summer'] ?></option>
      <option value="Herbst"><?= $lang['index_season_autumn'] ?></option>
      <option value="Winter"><?= $lang['index_season_winter'] ?></option>
    </select>
  </div>

  <!-- Filter: Anlass -->
  <div class="col-6 col-md-2">
    <select id="occasionFilter" class="form-select">
      <option value=""><?= $lang['index_filter_occasion'] ?></option>
      <option value="Ausgehen"><?= $lang['index_occasion_out'] ?></option>
      <option value="Abend"><?= $lang['index_occasion_evening'] ?></option>
      <option value="Freizeit"><?= $lang['index_occasion_free'] ?></option>
      <option value="Täglich"><?= $lang['index_occasion_daily'] ?></option>
      <option value="Arbeit"><?= $lang['index_occasion_work'] ?></option>
    </select>
  </div>

  <!-- Zurücksetzen-Button -->
  <div class="col-6 col-md-2">
    <button id="resetFilters" class="btn btn-outline-secondary w-100">
    <?= $lang['index_btn_reset'] ?>
    </button>
  </div>
</div>


<!-- Tabelle -->
<table id="duftTabelle" class="table table-hover">
  <thead>
    <tr><th><?= $lang['index_table_code'] ?></th><th><?= $lang['index_table_inspired_by'] ?></th><th><?= $lang['index_table_gender'] ?></th></tr>
  </thead>
  <tbody id="duftBody">
    <tr><td colspan="3"><?= $lang['index_table_loading'] ?></td></tr>
  </tbody>
</table>

<!-- Modalbox -->
<div id="duftModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('duftModal').style.display='none'">&times;</span>
    <div id="duftHeader">
  <h2 id="duftTitle">Titel</h2>
  <i id="genderIcon" class="fa fa-question"></i>
  </div>
    <p id="duftDesc"></p>
    <div id="duftPunkte"></div>

    <div id="pyramide" class="notes_list"></div>

    <div class="charts-grid">
      <div><span class="black bold"><?= $lang['index_modal_chart_type'] ?></span><div id="chartdiv_dufttyp" class="chart_placeholder"></div></div>
      <div><span class="black bold"><?= $lang['index_modal_chart_style'] ?></span><div id="chartdiv_stil" class="chart_placeholder"></div></div>
      <div><span class="black bold"><?= $lang['index_modal_chart_season'] ?></span><div id="chartdiv_jahreszeit" class="chart_placeholder"></div></div>
      <div><span class="black bold"><?= $lang['index_modal_chart_occasion'] ?></span><div id="chartdiv_anlass" class="chart_placeholder"></div></div>
    </div>
  </div>
</div>

<!-- Footer -->
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a href="defekt.php" target="_self" class="link-secondary" rel="noopener"><?= $lang['index_footer_problem'] ?></a></li>
                  <li class="list-inline-item">
                    <a href="https://github.com/sponsors/codecalm" target="_blank" class="link-secondary" rel="noopener">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-pink icon-inline icon-4">
                        <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                      </svg>
                      <?= $lang['index_footer_sponsor'] ?>
                    </a>
                  </li>
                  <li class="list-inline-item"><a href="#" target="_blank" class="link-secondary" rel="noopener"><?= $lang['index_footer_imprint'] ?></a></li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                  Copyright &copy; <?php echo date("Y"); ?> <a href="." class="link-secondary">L'CREE</a>. <?= $lang['index_footer_rights'] ?>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>



</div> <!-- Ende Container -->
<script src="assets/js/tom-select.complete.js" defer></script>

<script>
// Globale Variable für die Originaldaten
let originalData = [];

// Funktion zum Rendern der Tabelle anhand der aktuellen Filter
function renderTable(data) {
  const query = document.getElementById("searchInput")?.value.toLowerCase() || "";
  const genderFilter = document.getElementById("genderFilter")?.value || "";
  const seasonFilter = document.getElementById("seasonFilter")?.value || "";
  const occasionFilter = document.getElementById("occasionFilter")?.value || "";

  const body = document.getElementById("duftBody");
  body.innerHTML = "";

  const filtered = data.filter(item => {
    const matchesQuery = item.code.toLowerCase().includes(query)
      || item.inspired_by.toLowerCase().includes(query)
      || item.gender.toLowerCase().includes(query)
      || (item.description ? item.description.toLowerCase().includes(query) : false);

    const matchesGender = !genderFilter || item.gender === genderFilter;

    const seasons = [...(item.diagramme?.Jahreszeit || [])].sort((a, b) => b.value - a.value);
    const topSeasons = seasons.slice(0, 2).map(e => e.category);
    if (seasons[2] && seasons[2].value > 10) topSeasons.push(seasons[2].category);
    const matchesSeason = !seasonFilter || topSeasons.includes(seasonFilter);

    const occasions = [...(item.diagramme?.Anlass || [])].sort((a, b) => b.value - a.value);
    const topOccasions = occasions.slice(0, 2).map(e => e.category);
    if (occasions[2] && occasions[2].value > 10) topOccasions.push(occasions[2].category);
    const matchesOccasion = !occasionFilter || topOccasions.includes(occasionFilter);

    return matchesQuery && matchesGender && matchesSeason && matchesOccasion;
  });

  if (filtered.length === 0) {
    const emptyRow = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = 3;
    td.className = "text-center py-5 text-muted fw-bold";
    td.textContent = "Keine passenden Düfte gefunden.";
    emptyRow.appendChild(td);
    body.appendChild(emptyRow);
    return;
  }

  filtered.forEach(item => {
    const row = document.createElement("tr");
    row.style.cursor = "pointer";
    // Wenn auf den Duftcode in der Tabelle geklickt wird, öffne die Modalbox für diesen Duft und aktualisiere die URL
    row.onclick = () => {
      showModal(item);
      window.history.pushState({}, "", `?code=${item.code}`);  // URL mit dem Duftcode aktualisieren
    };

    const tdCode = document.createElement("td");
    tdCode.textContent = item.code;

    const tdInspired = document.createElement("td");
    tdInspired.textContent = item.inspired_by;

    const tdGender = document.createElement("td");
    let iconClass = "fa fa-question";
    let iconColor = "#888";
    if (item.gender === "Herren") {
      iconClass = "fa fa-mars"; iconColor = "#70a2d4";
    } else if (item.gender === "Damen") {
      iconClass = "fa fa-venus"; iconColor = "#ff7eb8";
    } else if (item.gender === "Unisex") {
      iconClass = "fa fa-venus-mars"; iconColor = "#56cda7";
    }
    tdGender.innerHTML = `<i class="${iconClass}" style="color:${iconColor}; font-size: 1.25rem;"></i>`;

    row.appendChild(tdCode);
    row.appendChild(tdInspired);
    row.appendChild(tdGender);
    body.appendChild(row);
  });
}

// Funktion zum Befüllen der dynamischen Filter
function populateDynamicFilters(data) {
  const genderFilter = document.getElementById("genderFilter");
  const seasonFilter = document.getElementById("seasonFilter");
  const occasionFilter = document.getElementById("occasionFilter");

  const genderEmojis = { Herren: "♂️", Damen: "♀️", Unisex: "⚧️" };
  const seasonEmojis = { Frühling: "🌸", Sommer: "🌞", Herbst: "🍂", Winter: "❄️" };
  const occasionEmojis = { Ausgehen: "🍷", Abend: "🌙", Freizeit: "🚶‍♂️", Täglich: "📅", Arbeit: "💼" };

  const sets = {
    gender: Object.keys(genderEmojis),
    season: [...new Set(data.flatMap(item => (item.diagramme?.Jahreszeit || []).map(e => e.category)))],
    occasion: [...new Set(data.flatMap(item => (item.diagramme?.Anlass || []).map(e => e.category)))],
  };

  const initSelect = (el, list, icons, placeholder) => {
    el.innerHTML = `<option value="">${placeholder}</option>`;
    list.sort().forEach(entry => {
      el.innerHTML += `<option value="${entry}" data-custom-properties="${icons[entry] || "❔"}">${entry}</option>`;
    });
    new TomSelect(el, {
      allowEmptyOption: true,
      create: false,
      searchField: [],
      controlInput: null,
      render: {
        option: data =>
          `<div class="d-flex align-items-center">${data.customProperties || ""} ${data.text}</div>`,
        item: data =>
          `<div class="d-flex align-items-center">${data.customProperties || ""} ${data.text}</div>`
      }
    });
  };

  initSelect(genderFilter, sets.gender, genderEmojis, "<?= $lang['index_filter_gender'] ?>");
  initSelect(seasonFilter, sets.season, seasonEmojis, "<?= $lang['index_filter_season'] ?>");
  initSelect(occasionFilter, sets.occasion, occasionEmojis, "<?= $lang['index_filter_occasion'] ?>");
}

// Daten laden & initiale Anzeige
fetch("load_scents.php")
  .then(res => res.json())
  .then(data => {
    originalData = data;
    populateDynamicFilters(originalData);
    renderTable(originalData);

    // Überprüfen, ob ein Duftcode in der URL übergeben wurde
    const urlParams = new URLSearchParams(window.location.search);
    const code = urlParams.get('code');
    
    // Wenn ein Code in der URL vorhanden ist, prüfen wir, ob der Duft existiert
    if (code) {
      const scent = originalData.find(item => item.code === code);
      if (scent) {
        showModal(scent); // Duft in der Modalbox anzeigen
      }
      // Wenn kein Duft gefunden wird, wird keine Modalbox geöffnet und es wird die Tabelle angezeigt.
    }
  })
  .catch(err => {
    document.getElementById("duftBody").innerHTML = "<tr><td colspan='3'>Fehler beim Laden der Daten</td></tr>";
  });

// Event Listener für die Filterfelder
["searchInput", "genderFilter", "seasonFilter", "occasionFilter"].forEach(id => {
  document.getElementById(id)?.addEventListener("input", () => renderTable(originalData));
});

// Event Listener zum Zurücksetzen der Filter
document.getElementById("resetFilters")?.addEventListener("click", () => {
  ["searchInput", "genderFilter", "seasonFilter", "occasionFilter"].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = "";
    if (el?.tomselect) {
      el.tomselect.clear();
      el.tomselect.setValue("");
    }
  });
  renderTable(originalData);
});

</script>



<script>
// Funktion zum Anzeigen der Modalbox und Befüllen der Inhalte
function showModal(item) {
  // Öffne die Modalbox und setze Titel
  document.getElementById("duftModal").style.display = "block";
  document.getElementById("duftTitle").innerText = `${item.code} – ${item.inspired_by}`;

  // Setze das Geschlechts-Icon
  const icon = document.getElementById("genderIcon");
  if (item.gender === "Herren") {
    icon.className = "fa fa-mars"; icon.style.color = "#70a2d4";
  } else if (item.gender === "Damen") {
    icon.className = "fa fa-venus"; icon.style.color = "#ff7eb8";
  } else {
    icon.className = "fa fa-venus-mars"; icon.style.color = "#56cda7";
  }

  // Abschnitt: Duftrichtung
  const punkteContainer = document.getElementById("duftPunkte");
  punkteContainer.innerHTML = `
    <h3 class="mb-2 mt-3"><?= $lang['index_modal_type'] ?></h3>
  `;

  const direction = item.description.split(",").map(x => x.trim());
  const dufttypData = item.diagramme?.Dufttyp || [];
  const max = Math.max(...dufttypData.map(e => e.value));
  const farben = ["#9bbcb7", "#a8bbec", "#d1a2ff", "#c16692", "#d79785", "#ffa0ab", "#d7d7c7"];
  const wrapper = document.createElement("div");
  wrapper.className = "duftdesc-punkte";

  direction.forEach(label => {
    const match = dufttypData.find(e => e.category.toLowerCase() === label.toLowerCase());
    if (!match) return;
    const percent = (match.value / max) * 100;
    let size = "punkt-s";
    if (percent >= 90) size = "punkt-l";
    else if (percent >= 50) size = "punkt-m";
    const color = farben[dufttypData.indexOf(match) % farben.length];
    const el = document.createElement("div");
    el.className = "duftdesc-punkt";
    el.innerHTML = `<span class="punkt ${size}" style="background:${color}"></span>${label}`;
    wrapper.appendChild(el);
  });
  punkteContainer.appendChild(wrapper);

  // Abschnitt: Duftpyramide oder Duftnoten
  const pyramidContainer = document.getElementById("pyramide");
  let pyramidHTML = `
    <h3 class="mb-2 mt-4">${item.use_pyramid == 0 ? "<?= $lang['index_modal_notes_title'] ?>" : "<?= $lang['index_modal_pyramid_title'] ?>"}</h3>
  `;

  const block = (icon, title, notes, noteClass) => `
    <div class="pyramid_block w-100 mt-2">
      <div class="left">
        ${icon ? `<img src="${icon}" alt="${title}" class="pyramid-icon">` : ""}
        ${title}
      </div>
      <div class="right notes_list">
        ${notes.map(n => `
          <span class="clickable_note_img ${noteClass}">
            <span class="nowrap pointer">
              ${n.img ? `<img src="${n.img}" alt="${n.name}" class="np np2" loading="lazy">` : ""}
              ${n.name}
            </span>
          </span>
        `).join('')}
      </div>
    </div>`;

  if (item.use_pyramid == 0) {
    const allNotes = item.pyramid.All || [];
    pyramidHTML += `
    <div class="notes_list d-flex flex-wrap gap-2 mt-2">
      ${allNotes.map(n => `
        <span class="clickable_note_img notefont-general">
          <span class="nowrap pointer">
            ${n.img ? `<img src="${n.img}" alt="${n.name}" class="np np2" loading="lazy">` : ""}
            ${n.name}
          </span>
        </span>
      `).join('')}
    </div>`;
  } else {
    pyramidHTML +=
      block("assets/img/pyr_top.png", "<?= $lang['index_modal_pyramid_top'] ?>", item.pyramid.Kopf, "notefont2") +
      block("assets/img/pyr_middle.png", "<?= $lang['index_modal_pyramid_heart'] ?>", item.pyramid.Herz, "notefont3") +
      block("assets/img/pyr_base.png", "<?= $lang['index_modal_pyramid_base'] ?>", item.pyramid.Basis, "notefont2");
  }

  pyramidContainer.innerHTML = pyramidHTML;

  // Abschnitt: Diagramme (Titel vor bestehenden Charts)
  const chartsGrid = document.querySelector(".charts-grid");
  if (!document.getElementById("diagrammeHeader")) {
    chartsGrid.insertAdjacentHTML("beforebegin", `<h3 id="diagrammeHeader" class="mb-2 mt-4"><?= $lang['index_modal_charts_title'] ?></h3>`);
  }

  // Falls vorhanden: Rendering von Diagrammen (falls eine entsprechende Funktion definiert ist)
  if (window.renderCharts && item.diagramme) {
    window.renderCharts(item.diagramme);
  }
}

// Wenn die Seite geladen wird, prüfe, ob ein Duftcode in der URL ist
window.onload = function() {
  const urlParams = new URLSearchParams(window.location.search);
  const duftCode = urlParams.get('code'); // Hier den Duftcode aus der URL holen
  if (duftCode) {
    // Wenn ein Duftcode in der URL vorhanden ist, den Duft aus originalData finden
    const duft = originalData.find(item => item.code === duftCode);
    if (duft) {
      showModal(duft);  // Modal mit den Duftdetails öffnen
    }
    // Falls kein passender Duft gefunden wird, Modalbox nicht öffnen
    // Keine Fehlermeldung und keine Modalbox, die Tabelle bleibt sichtbar.
  }
};
</script>




</body>
</html>
