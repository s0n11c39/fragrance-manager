require.config({
  baseUrl: "../assets/js",
  shim: {
    'core': {
      exports: 'am4core',
      init: function () { return window.am4core; }
    },
    'charts': {
      deps: ['core'],
      exports: 'am4charts',
      init: function () { return window.am4charts; }
    },
    'animated': {
      deps: ['core'],
      exports: 'am4themes_animated',
      init: function () { return window.am4themes_animated; }
    }
  }
});

require(['core', 'charts', 'animated'], function(am4core, am4charts, am4themes_animated) {
  am4core.useTheme(am4themes_animated);

  window.renderCharts = function (diagramme) {
    const mappings = {
      Dufttyp: "chartdiv_dufttyp",
      Stil: "chartdiv_stil",
      Jahreszeit: "chartdiv_jahreszeit",
      Anlass: "chartdiv_anlass"
    };

    // Vorherige Charts ggf. löschen
    Object.values(mappings).forEach(id => {
      let el = document.getElementById(id);
      if (el && el.innerHTML !== "") {
        el.innerHTML = ""; // oder: chart.dispose() wenn du Objekte speicherst
      }
    });

    for (const [key, data] of Object.entries(diagramme)) {
      const divId = mappings[key];
      if (!divId || !data || data.length === 0) continue;

      let chart = am4core.create(divId, am4charts.PieChart);
      chart.data = data;
      chart.radius = am4core.percent(60);

      let series = chart.series.push(new am4charts.PieSeries());
      series.dataFields.value = "value";
      series.dataFields.category = "category";

      series.labels.template.text = "{category} {value.percent.formatNumber('#.')}%";
      series.labels.template.fill = am4core.color("#55636F");
      series.ticks.template.stroke = am4core.color("#55636F");
      series.fontSize = 11;

      // Gleiche Farben wie Parfumo (optional anpassen)
      const colors = {
        Dufttyp: ["#9bbcb7","#a8bbec","#d1a2ff","#c16692","#d79785","#ffa0ab","#d7d7c7"],
        Stil:     ["#8aafee","#f1a1f1","#aaafc8","#cbd5ea"],
        Jahreszeit: ["#a2cbff","#d9b1be","#ffd667","#badc82"],
        Anlass:   ["#a7dcf3","#f6ea86","#a096e8","#c1c1c1","#f480ac"]
      };

      if (colors[key]) {
        series.colors.list = colors[key].map(c => am4core.color(c));
      }

      // Animationseffekt
      series.hiddenState.properties.startAngle = -90;
      series.hiddenState.properties.endAngle = -90;
    }
  };
});
