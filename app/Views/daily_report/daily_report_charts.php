<div id="page-content" class="page-wrapper clearfix">
  <div class="card bg-white">
    <div class="card-header clearfix">
      <i data-feather="pie-chart" class="icon-16"></i> &nbsp;<?php echo app_lang("graphs"); ?>
    </div>
    <div class="card-body rounded-bottom">
      <div class="form-group">
        <div class="d-flex row align-items-center">
          <label for="clinic_select" class="col-md-4"><?php echo app_lang("select_clinic_report"); ?></label>
          <div class="col-md-8">
            <?php
            $selected_clinic_id = isset($model_info) && $model_info !== null ? $model_info->clinic_id : '';
            echo form_dropdown(
              "clinic_select",
              $clinic_options,
              $selected_clinic_id,
              'class="select_graph w-100" id="clinic_select" required aria-required="true" aria-label="' . app_lang('clinic_list') . '"'
            ); ?>
          </div>
        </div>
      </div>
    </div>
    <div id="patients-data-container"></div>
    <div class="row">
      <div class="col-12 col-md-6">
        <?php echo view("daily_report/daily_report_patients_chart"); ?>
      </div>
      <div class="col-12 col-md-6">
        <?php echo view("daily_report/daily_report_income_chart"); ?>
      </div>
      <div class="col-12 col-md-6">
        <?php echo view("daily_report/daily_report_platforms_chart"); ?>
      </div>
      <div class="col-12 col-md-6">
        <?php echo view("daily_report/daily_report_insurance_prevalence_chart"); ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
    // Inicializar select2 en el select con el ID clinic_select
    $(".select2").select2();

    // Verificar si el select está inicializado correctamente 
    var clinicSelect = document.getElementById("clinic_select");

    // Mostrar mensaje inicial
    var selectClinicMessage = document.getElementById("select-clinic-message");
    if (selectClinicMessage) {
      selectClinicMessage.style.display = 'block';
    }
  });
</script>

<style>
  .select_graph {
    padding: 0.5rem;
    border: none;
    background-color: #F6F8F9;
  }
</style>