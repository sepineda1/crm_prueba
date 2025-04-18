<div id="page-content" class="page-wrapper clearfix grid-button">
  <div class="card">
    <div class="page-title clearfix notes-page-title">
      <h1><?php echo app_lang("daily_clinic_report"); ?> </h1>
      <div class="title-button-group">
        <?php echo modal_anchor(get_uri("daily_report/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_report'), array("class" => "btn btn-default", "title" => app_lang('add_report'), "aria-label" => "Añadir reporte diario")); ?>
      </div>
    </div>
    <?php echo view('daily_report/daily_report_list', ['reports' => $reports]); ?>
    <?php echo view('daily_report/daily_report_charts', ['clinic_options' => $clinic_options]); // Pasar clinic_options 
    ?>
  </div>
</div>