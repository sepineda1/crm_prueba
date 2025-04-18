<div class="pt-3 ps-3">
  <div class="pt-2"><?php echo app_lang("chart_insurance_prevalence"); ?></div>
  <canvas id="insurance-prevalence-chart" style="width: 100%; min-height: 60px; margin-left: -10px; display: none;"></canvas>
  <div id="no-data-insurance-prevalence" style="display: none;" class="py-5 mt-4 text-center border"><?php echo app_lang("no_data_to_chart"); ?></div>
</div>

<script type="text/javascript">
  var insurancePrevalenceChartContent;

  var initInsurancePrevalenceChart = function(insuranceData, labels) {
    var insurancePrevalenceChart = document.getElementById("insurance-prevalence-chart");
    var noDataInsurancePrevalence = document.getElementById("no-data-insurance-prevalence");

    // Verificación de valores null y arreglo vacío
    if (!insuranceData || insuranceData.length === 0 || !labels || labels.length === 0 || insuranceData.every(item => item === 0)) {
      insurancePrevalenceChart.style.display = 'none';
      noDataInsurancePrevalence.style.display = 'block';
      return;
    } else {
      insurancePrevalenceChart.style.display = 'block';
      noDataInsurancePrevalence.style.display = 'none';
    }

    if (insurancePrevalenceChartContent) {
      insurancePrevalenceChartContent.destroy();
    }

    const data = {
      labels: labels, // The types of patients
      datasets: [{
        label: "<?php echo app_lang('insured_patients'); ?>",
        data: insuranceData, // Inicializar con 0 si es null
        borderColor: '#4CAF50',
        backgroundColor: 'rgba(76, 175, 80, 0.2)',
        borderWidth: 2,
        borderRadius: 5,
        borderSkipped: false,
      }]
    };

    const maxValue = Math.max(...insuranceData); // Manejar maxValue con datos inicializados a 0 si es necesario

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
            position: 'top',
          },
          title: {
            display: true,
            text: '<?php echo app_lang("chart_insurance_prevalence"); ?>'
          }
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: '<?php echo app_lang("types_of_patients"); ?>'
            }
          },
          y: {
            beginAtZero: true,
            min: 0,
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

    insurancePrevalenceChartContent = new Chart(insurancePrevalenceChart, config);
    console.log("Gráfica de prevalencia de seguro médico inicializada.");
  };

  var prepareInsurancePrevalenceChart = function() {
    var clinicId = document.getElementById("clinic_select").value;
    console.log("Enviando solicitud AJAX para la clínica (Prevalencia de Seguro):", clinicId);
    $.ajax({
      url: "<?php echo get_uri('daily_report/getInsurancePrevalenceData'); ?>",
      type: 'GET',
      data: {
        clinic_id: clinicId
      },
      dataType: "json",
      success: function(response) {
        console.log("Respuesta de InsurancePrevalenceData:", response);
        initInsurancePrevalenceChart(response.insuranceData, response.labels);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error en la llamada AJAX (Prevalencia de Seguro):", textStatus, errorThrown);
      }
    });
  };

  $(document).ready(function() {
    prepareInsurancePrevalenceChart();
    document.getElementById("clinic_select").addEventListener("change", prepareInsurancePrevalenceChart);
  });
</script>