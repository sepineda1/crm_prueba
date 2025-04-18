<div class="pt-2 ps-3">
  <div class="pt-2"><?php echo app_lang("chart_total_income"); ?></div>
  <canvas id="total-income-chart" style="width: 100%; min-height: 60px; margin-left: -10px; display: none;"></canvas>
  <div id="no-data-total-income" style="display: none;" class="py-5 mt-4 text-center border"><?php echo app_lang("no_data_to_chart"); ?></div>
</div>


<script type="text/javascript">
  var totalIncomeChartContent;

  var initTotalIncomeChart = function(dailyIncome, labels) {
    console.log("Datos de Ingresos:", {
      dailyIncome,
      labels
    });

    var totalIncomeChart = document.getElementById("total-income-chart");
    var noDataTotalIncome = document.getElementById("no-data-total-income");

    // Verificación de valores null y arreglo vacío
    if (!dailyIncome || dailyIncome.length === 0 || !labels || labels.length === 0) {
      totalIncomeChart.style.display = 'none';
      noDataTotalIncome.style.display = 'block';
      return;
    } else {
      totalIncomeChart.style.display = 'block';
      noDataTotalIncome.style.display = 'none';
    }

    if (totalIncomeChartContent) {
      totalIncomeChartContent.destroy();
    }

    const data = {
      labels: labels.map(function(value) {
        const parts = value.split('-');
        const year = parts[0];
        const monthIndex = parseInt(parts[1], 10);
        const day = parts[2];
        const monthNames = [
          "<?php echo app_lang('short_january'); ?>",
          "<?php echo app_lang('short_february'); ?>",
          "<?php echo app_lang('short_march'); ?>",
          "<?php echo app_lang('short_april'); ?>",
          "<?php echo app_lang('short_may'); ?>",
          "<?php echo app_lang('short_june'); ?>",
          "<?php echo app_lang('short_july'); ?>",
          "<?php echo app_lang('short_august'); ?>",
          "<?php echo app_lang('short_september'); ?>",
          "<?php echo app_lang('short_october'); ?>",
          "<?php echo app_lang('short_november'); ?>",
          "<?php echo app_lang('short_december'); ?>"
        ];
        const month = monthNames[monthIndex - 1];
        return `${day} ${month} ${year}`;
      }),
      datasets: [{
        label: "<?php echo app_lang('daily_income'); ?>",
        borderColor: '#32A483',
        backgroundColor: 'rgba(50, 164, 131, 0.2)',
        borderWidth: 2,
        fill: true,
        data: dailyIncome,
        pointRadius: 3,
        pointBackgroundColor: '#32A483'
      }]
    };

    const maxValue = Math.max(...dailyIncome || [0]);

    const config = {
      type: 'line',
      data: data,
      options: {
        responsive: true,
        layout: {
          padding: {
            top: 20
          }
        },
        plugins: {
          legend: {
            position: 'top',
            padding: {
              top: 2 // Añadir padding superior
            }
          },
          title: {
            display: true,
            text: '<?php echo app_lang("daily_income"); ?>'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                var label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                label += toCurrency(context.parsed.y);
                return label;
              }
            }
          }
        },
        scales: {
          xAxes: [{
            display: true,
            scaleLabel: {
              display: true,
              labelString: '<?php echo app_lang("date"); ?>'
            },
            ticks: {
              autoSkip: true,
              maxRotation: 0
            }
          }],
          yAxes: [{
            display: true,
            scaleLabel: {
              display: true,
              labelString: '<?php echo app_lang("total_income"); ?>'
            },
            ticks: {
              beginAtZero: true,
              callback: function(value) {
                return Math.floor(value) === value ? toCurrency(value) : null;
              }
            }
          }]
        }
      },
      plugins: [{
        afterDatasetsDraw: function(chart) {
          var ctx = chart.ctx;

          chart.data.datasets.forEach(function(dataset, i) {
            var meta = chart.getDatasetMeta(i);
            if (!meta.hidden) {
              meta.data.forEach(function(element, index) {
                if (dataset.data[index] !== undefined && dataset.data[index] !== null) {
                  ctx.fillStyle = 'rgb(78, 94, 106)';
                  var fontSize = 12;
                  var fontStyle = 'normal';
                  var fontFamily = 'Open Sans';
                  ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                  var dataString = toCurrency(dataset.data[index]);

                  ctx.textAlign = 'center';
                  ctx.textBaseline = 'middle';

                  var position = element.tooltipPosition();
                  ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - 5);
                }
              });
            }
          });
        }
      }]
    };

    totalIncomeChartContent = new Chart(totalIncomeChart, config);
    console.log("Gráfica de ingresos inicializada.");
  };

  var prepareTotalIncomeChart = function() {
    var clinicId = document.getElementById("clinic_select").value;
    $.ajax({
      url: "<?php echo get_uri('daily_report/getTotalIncomeData'); ?>",
      type: 'GET',
      data: {
        clinic_id: clinicId
      },
      dataType: "json",
      success: function(response) {
        console.log("Respuesta de TotalIncomeData:", response);
        initTotalIncomeChart(response.dailyIncome, response.labels);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error en la llamada AJAX (Ingresos):", textStatus, errorThrown);
      }
    });
  };

  $(document).ready(function() {
    prepareTotalIncomeChart();
    document.getElementById("clinic_select").addEventListener("change", prepareTotalIncomeChart);
  });
</script>

<style>
  @media only screen and (max-width: 768px) {
    #total-income-chart {
      min-height: 300px;
      /* Ajusta este valor según sea necesario */
    }
  }
</style>