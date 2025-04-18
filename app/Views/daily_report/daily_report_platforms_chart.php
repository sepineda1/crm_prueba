<div class="pt-3 ps-3">
  <div class="pt-2"><?php echo app_lang("chart_marketing_report"); ?></div>
  <canvas id="total-platforms-chart" style="width: 100%; min-height: 60px; margin-left: -10px; display: none;"></canvas>
  <div id="no-data-total-platforms" style="display: none;" class="py-5 mt-4 text-center border"><?php echo app_lang("no_data_to_chart"); ?></div>
</div>

<script type="text/javascript">
  var totalPlatformsChartContent;

  var initTotalPlatformsChart = function(platformsData) {
    var totalPlatformsChart = document.getElementById("total-platforms-chart");
    var noDataTotalPlatforms = document.getElementById("no-data-total-platforms");

    // Verificación y manejo de valores null
    if (!platformsData || Object.values(platformsData).every(item => item === 0)) {
      totalPlatformsChart.style.display = 'none';
      noDataTotalPlatforms.style.display = 'block';
      return;
    } else {
      totalPlatformsChart.style.display = 'block';
      noDataTotalPlatforms.style.display = 'none';
    }

    // Destruir cualquier gráfico previo
    if (totalPlatformsChartContent) {
      totalPlatformsChartContent.destroy();
    }

    // Colores para cada categoría
    const bgColors = ['rgba(54, 66, 235, 0.2)', 'rgba(76, 175, 80, 0.2)', '#FFCE5620'];
    const borderColors = ['#36A2EB', '#4CAF50', '#FFCE56'];
    const data = {
      labels: ['Internet', 'Walking', 'Referred'], // Labels for the categories
      datasets: [{
          label: '<?php echo app_lang("marketing_report_followup"); ?>',
          data: [platformsData.internetSum || 0, null, null], // Dato consolidado para Internet, null para otras categorías
          backgroundColor: bgColors[0],
          borderColor: borderColors[0],
          borderWidth: 2,
          borderRadius: 5,
          borderSkipped: false,
        },
        {
          label: '<?php echo app_lang("marketing_report_established_chronic"); ?>',
          data: [null, platformsData.walkingSum || 0, null], // Dato consolidado para Walking, null para otras categorías
          backgroundColor: bgColors[1],
          borderColor: borderColors[1],
          borderWidth: 2,
          borderRadius: 5,
          borderSkipped: false,
        },
        {
          label: '<?php echo app_lang("marketing_report_established_acute"); ?>',
          data: [null, null, platformsData.referredSum || 0], // Dato consolidado para Referred, null para otras categorías
          backgroundColor: bgColors[2],
          borderColor: borderColors[2],
          borderWidth: 2,
          borderRadius: 5,
          borderSkipped: false,
        }
      ]
    };

    const maxValue = Math.max(platformsData.internetSum || 0, platformsData.walkingSum || 0, platformsData.referredSum || 0);
    const config = {
      type: 'bar',
      data: data,
      options: {
        responsive: true,
        layout: {
          padding: {
            top: 20 // Añadir padding superior
          }
        },
        plugins: {
          legend: {
            display: true, // Mostrar la leyenda con los nombres de las categorías
          },
          title: {
            display: true,
            text: '<?php echo app_lang("chart_total_platforms"); ?>'
          }
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: '<?php echo app_lang("platforms"); ?>'
            }
          },
          y: {
            display: true,
            title: {
              display: true,
              text: '<?php echo app_lang("number_of_patients"); ?>'
            },
            ticks: {
              beginAtZero: true,
              max: maxValue + (maxValue * 0.1), // Añadir un 10% extra al valor máximo
              callback: function(value) {
                return Math.floor(value) === value ? value : null;
              }
            }
          }
        }
      },
      plugins: [{
        afterDatasetsDraw: function(chart) {
          var ctx = chart.ctx;

          chart.data.datasets.forEach(function(dataset, i) {
            var meta = chart.getDatasetMeta(i);
            if (!meta.hidden) {
              meta.data.forEach(function(element, index) {
                // Verificar si el dato es definido antes de usar toString()
                if (dataset.data[index] !== undefined && dataset.data[index] !== null) {
                  // Dibujar el texto en negro, con la fuente especificada
                  ctx.fillStyle = 'rgb(78, 94, 106)';
                  var fontSize = 12;
                  var fontStyle = 'normal';
                  var fontFamily = 'Open Sans';
                  ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                  // Convertir a string
                  var dataString = dataset.data[index].toString();

                  // Asegurar que la alineación sea correcta
                  ctx.textAlign = 'center';
                  ctx.textBaseline = 'middle';
                  var position = element.tooltipPosition();
                  ctx.fillText(dataString, position.x, position.y - (fontSize / 2));
                }
              });
            }
          });
        }
      }]
    };

    totalPlatformsChartContent = new Chart(totalPlatformsChart, config);
    console.log("Gráfica de plataformas inicializada.");
  };

  var prepareTotalPlatformsChart = function() {
    var clinicId = document.getElementById("clinic_select").value;
    console.log("Enviando solicitud AJAX para la clínica (Plataformas):", clinicId);
    $.ajax({
      url: "<?php echo get_uri('daily_report/getPlatformsData'); ?>",
      type: 'GET',
      data: {
        clinic_id: clinicId
      },
      dataType: "json",
      success: function(response) {
        console.log("Respuesta de TotalPlatformsData (Plataformas):", response);
        initTotalPlatformsChart(response);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error en la llamada AJAX (Plataformas):", textStatus, errorThrown);
      }
    });
  };

  $(document).ready(function() {
    prepareTotalPlatformsChart();
    document.getElementById("clinic_select").addEventListener("change", prepareTotalPlatformsChart);
  });
</script>