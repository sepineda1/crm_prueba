<?php echo form_open(get_uri("daily_report/saveReport"), array("id" => "daily_report-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
  <div class="container-fluid">
    <!-- Contenido del Modal -->
    <div class="modal-body clearfix">
      <div class="container-fluid">
        <!-- Incluir campos del formulario -->
        <?php echo view("daily_report/daily_report_form_fields"); ?>
      </div>
    </div>
    <!-- Pie del Modal con botones de cerrar y guardar -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?>
      </button>
      <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?>
      </button>
    </div>
  </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
  $(document).ready(function() {
    // Inicializar formulario con appForm
    $("#daily_report-form").appForm({
      onSuccess: function(result) {
        if (result.success) {
          // Mostrar mensaje de éxito y recargar página
          appAlert.success(result.message, {
            duration: 10000
          });
          setTimeout(function() {
            location.reload();
          }, 500);
        } else {
          // Actualizar tabla de reportes
          $("#daily-report-table").appTable({
            newData: result.data,
            dataId: result.id
          });
          // Recargar vista kanban si es visible
          $("#reload-kanban-button:visible").trigger("click");
        }
      },
      onError: function(result) {
        // Mostrar mensaje de error
        appAlert.error(result.message);
      }
    });
  });
</script>