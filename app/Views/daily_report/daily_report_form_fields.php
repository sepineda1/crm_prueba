<p class="mb-4"><?php echo app_lang("scan_documents_instruction"); ?></p>
<div class="d-flex">
  <p class="fw-bold pe-2"><?php echo app_lang("report_sent_by"); ?>
  </p>
  <p class="text-primary fw-semibold"><?php echo $login_user->first_name . " " . $login_user->last_name; ?></p> <!-- Mostrar el nombre del usuario -->
</div>

<p class="fw-bold"><?php echo app_lang("select_clinic_reporting"); ?></p>
<div class="form-group">
  <div class="d-flex row align-items-center">
    <label for="clinic_id" class="<?php echo $label_column; ?>"><?php echo app_lang('clinic_list'); ?></label>
    <div class="<?php echo $field_column; ?>">
      <?php $selected_clinic_id = isset($model_info) && $model_info !== null ? $model_info->clinic_id : '';
      echo form_dropdown(
        "clinic_id",
        $clinic_options,
        $selected_clinic_id,
        'class="select2" id="clinic_id" required aria-required="true" aria-label="' . app_lang('clinic_list') . '"'
      ); ?> </div>
  </div>
</div>
<p class="fw-bold"><?php echo app_lang("scan_daily_ebo_square_report"); ?>
</p>
<div class="form-group">
  <div class="row"> <label for="report_file"><?php echo app_lang('report_file'); ?></label>
    <div>
      <div id="drop-area" class="drop-area" tabindex="0" role="button" aria-label="<?php echo app_lang('drag_and_drop_files_here_or_click_to_select'); ?>">
        <p><?php echo app_lang('drag_and_drop_files_here_or_click_to_select'); ?></p> <input type="file" id="report_file" name="report_file" class="file-input" required hidden /> <span id="file-name" class="file-name"><?php echo app_lang('no_file_chosen'); ?></span>
      </div>
    </div>
  </div>
</div>
<p class="fw-bold"><?php echo app_lang("report_corresponding_date"); ?>
</p>
<div class="form-group">
  <div class="row">
    <label for="report_date"><?php echo app_lang('report_date'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "report_date",
        "name" => "report_date",
        "type" => "date",
        "class" => "form-control",
        "required" => true,
        "aria-required" => "true",
        "aria-label" => app_lang('report_date'),
        "value" => date('Y-m-d')
      ));
      ?>
    </div>
  </div>
</div>

<p class="fw-bold text-center fs-5"><?php echo app_lang("sales_report"); ?>
</p>
<div class="form-group">
  <div class="row"> <label for="sales_cash"><?php echo app_lang('sales_cash'); ?></label>
    <div> <?php echo form_input(array("id" => "sales_cash", "name" => "sales_cash", "type" => "number", "class" => "form-control", "placeholder" => "e.g., 1500.00", "required" => false, "aria-label" => app_lang('sales_cash'))); ?> </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="sales_card"><?php echo app_lang('sales_card'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "sales_card",
        "name" => "sales_card",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 2000.00",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="sales_other"><?php echo app_lang('sales_other'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "sales_other",
        "name" => "sales_other",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 500.00",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<p class="fw-bold text-center fs-5"><?php echo app_lang("marketing_report"); ?>
</p>
<div class="form-group">
  <div class="row">
    <label for="new_patients_total"><?php echo app_lang('new_patients_total'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "new_patients_total",
        "name" => "new_patients_total",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 5",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="followup_patients_total"><?php echo app_lang('followup_patients_total'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "followup_patients_total",
        "name" => "followup_patients_total",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 8",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_google"><?php echo app_lang('referral_google'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_google",
        "name" => "referral_google",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 2",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_referred"><?php echo app_lang('referral_referred'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_referred",
        "name" => "referral_referred",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 3",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_mail"><?php echo app_lang('referral_mail'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_mail",
        "name" => "referral_mail",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_walkby"><?php echo app_lang('referral_walkby'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_walkby",
        "name" => "referral_walkby",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_facebook"><?php echo app_lang('referral_facebook'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_facebook",
        "name" => "referral_facebook",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_events"><?php echo app_lang('referral_events'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_events",
        "name" => "referral_events",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_instagram"><?php echo app_lang('referral_instagram'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_instagram",
        "name" => "referral_instagram",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_youtube"><?php echo app_lang('referral_youtube'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_youtube",
        "name" => "referral_youtube",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_tiktok"><?php echo app_lang('referral_tiktok'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_tiktok",
        "name" => "referral_tiktok",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_radio"><?php echo app_lang('referral_radio'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_radio",
        "name" => "referral_radio",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_newspaper"><?php echo app_lang('referral_newspaper'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_newspaper",
        "name" => "referral_newspaper",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="referral_tv"><?php echo app_lang('referral_tv'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "referral_tv",
        "name" => "referral_tv",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<p class="fw-bold text-center fs-5"><?php echo app_lang("insurance_prevalence_report"); ?>
</p>
<div class="form-group">
  <div class="row">
    <label for="uninsured_patients"><?php echo app_lang('uninsured_patients'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "uninsured_patients",
        "name" => "uninsured_patients",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 1",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <label for="insured_patients"><?php echo app_lang('insured_patients'); ?></label>
    <div>
      <?php
      echo form_input(array(
        "id" => "insured_patients",
        "name" => "insured_patients",
        "type" => "number",
        "class" => "form-control",
        "placeholder" => "e.g., 4",
        "required" => false
      ));
      ?>
    </div>
  </div>
</div>
<p class="fw-bold text-center fs-5"><?php echo app_lang("daily_closure_protocols"); ?>
</p>
<p class="fw-bold pb-4"><?php echo app_lang("select_completed_protocols"); ?>
</p>
<div class="form-group ps-3">
  <div class="row align-items-center">
    <div class="form-check">
      <?php
      echo form_checkbox(array(
        "id" => "boxed_samples",
        "name" => "boxed_samples",
        "value" => "1",
        "class" => "form-check-input"
      ));
      ?>
      <label for="boxed_samples" class="form-check-label"><?php echo app_lang('boxed_samples'); ?></label>
    </div>
  </div>
</div>

<div class="form-group ps-3">
  <div class="row align-items-center">
    <div class="form-check">
      <?php
      echo form_checkbox(array(
        "id" => "added_to_square_ecw",
        "name" => "added_to_square_ecw",
        "value" => "1",
        "class" => "form-check-input"
      ));
      ?>
      <label for="added_to_square_ecw" class="form-check-label"><?php echo app_lang('added_to_square_ecw'); ?></label>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
    $('#clinic_id').select2({})
    const dropArea = document.getElementById("drop-area");
    const fileInput = document.getElementById("report_file");
    const fileNameDisplay = document.getElementById("file-name");
    dropArea.addEventListener("click", () => fileInput.click());
    dropArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropArea.classList.add("dragging");
    });
    dropArea.addEventListener("dragleave", () => dropArea.classList.remove("dragging"));
    dropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      dropArea.classList.remove("dragging");
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        fileInput.files = files;
        updateFileNameDisplay(files[0]);
      }
    });
    fileInput.addEventListener("change", () => {
      const files = fileInput.files;
      if (files.length > 0) {
        updateFileNameDisplay(files[0]);
      }
    });

    function updateFileNameDisplay(file) {
      fileNameDisplay.textContent = file.name;
    }
  });
</script>
<style>
  .drop-area {
    border: 2px dashed #ccc;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .drop-area:hover,
  .drop-area:focus {
    background-color: #f8f9fa;
  }

  .drop-area.dragging {
    background-color: #e9ecef;
  }

  .drop-area p {
    margin: 0;
    font-size: 16px;
    color: #666;
  }

  .file-name {
    display: block;
    margin-top: 10px;
    font-size: 14px;
    color: #333;
  }
</style>