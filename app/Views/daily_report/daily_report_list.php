<div id="page-content" class="page-wrapper clearfix">
  <div class="card">
    <div class="d-flex row pb-4 align-items-end gap-2">
      <p class="fw-bold pb-2"><?php echo app_lang("filter_by_date"); ?></p>
      <div class="col-md-2">
        <label for="start_date"><?php echo app_lang("start_date"); ?></label>
        <input type="date" id="start_date" class="form-control">
      </div>
      <div class="col-md-2">
        <label for="end_date"><?php echo app_lang("end_date"); ?></label>
        <input type="date" id="end_date" class="form-control">
      </div>
      <div class="col-md-3">
        <button id="filter_dates" class="btn btn-primary"><?php echo app_lang("filter"); ?></button>
      </div>
    </div>

    <div id="daily-report-list" class="table-responsive">
      <table id="daily-report-table" class="table table-striped">
        <thead>
          <tr>
            <th><?php echo app_lang("reported_by"); ?></th>
            <th><?php echo app_lang("report_id"); ?></th>
            <th><?php echo app_lang("clinic_id"); ?></th>
            <th><?php echo app_lang("clinic_name"); ?></th>
            <th><?php echo app_lang("report_date"); ?></th>
            <th><?php echo app_lang("file"); ?></th>
            <th><?php echo app_lang("cash_sales"); ?></th>
            <th><?php echo app_lang("card_sales"); ?></th>
            <th><?php echo app_lang("other_sales"); ?></th>
            <th><?php echo app_lang("new_patients"); ?></th>
            <th><?php echo app_lang("followup_patients"); ?></th>
            <th><?php echo app_lang("referral_google"); ?></th>
            <th><?php echo app_lang("referral_referred"); ?></th>
            <th><?php echo app_lang("referral_mail"); ?></th>
            <th><?php echo app_lang("referral_walkby"); ?></th>
            <th><?php echo app_lang("referral_facebook"); ?></th>
            <th><?php echo app_lang("referral_events"); ?></th>
            <th><?php echo app_lang("referral_instagram"); ?></th>
            <th><?php echo app_lang("referral_youtube"); ?></th>
            <th><?php echo app_lang("referral_tiktok"); ?></th>
            <th><?php echo app_lang("referral_radio"); ?></th>
            <th><?php echo app_lang("referral_newspaper"); ?></th>
            <th><?php echo app_lang("referral_tv"); ?></th>
            <th><?php echo app_lang("uninsured_patients"); ?></th>
            <th><?php echo app_lang("insured_patients"); ?></th>
          </tr>
        </thead>
        <tbody>
          <!-- Los datos se llenarán a través de DataTables -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    var isMobile = window.matchMedia("only screen and (max-width: 768px)").matches;

    var table = $("#daily-report-table").DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo_uri('daily_report/list_data') ?>",
        "type": "POST",
        "data": function(d) {
          d.startDate = $('#start_date').val();
          d.endDate = $('#end_date').val();
        }
      },
      "paging": true,
      "lengthMenu": [5, 10, 20, 50, 100],
      "pageLength": isMobile ? 10 : 5,
      "scrollX": true,
      "scrollY": isMobile ? "400px" : "",
      "columns": [{
          "data": "submitted_by"
        },
        {
          "data": "id"
        },
        {
          "data": "clinic_id"
        },
        {
          "data": "clinic_name"
        },
        {
          "data": "report_date"
        },
        {
          "data": "report_file",
          "render": function(data, type, row) {
            return '<a href="' + data + '" target="_blank"><?php echo app_lang("view_file"); ?></a>';
          }
        },
        {
          "data": "sales_cash"
        },
        {
          "data": "sales_card"
        },
        {
          "data": "sales_other"
        },
        {
          "data": "new_patients_total"
        },
        {
          "data": "followup_patients_total"
        },
        {
          "data": "referral_google"
        },
        {
          "data": "referral_referred"
        },
        {
          "data": "referral_mail"
        },
        {
          "data": "referral_walkby"
        },
        {
          "data": "referral_facebook"
        },
        {
          "data": "referral_events"
        },
        {
          "data": "referral_instagram"
        },
        {
          "data": "referral_youtube"
        },
        {
          "data": "referral_tiktok"
        },
        {
          "data": "referral_radio"
        },
        {
          "data": "referral_newspaper"
        },
        {
          "data": "referral_tv"
        },
        {
          "data": "uninsured_patients"
        },
        {
          "data": "insured_patients"
        }
      ],
      "order": [
        [4, "desc"]
      ]
    });

    $('#filter_dates').click(function() {
      table.draw();
    });

  });
</script>

<style>
  @media only screen and (max-width: 768px) {

    .dataTables_length,
    .dataTables_filter {
      display: inline-block;
      width: auto;
      vertical-align: middle;
      margin-right: 10px;
      /* Espaciado entre elementos */
    }

    .dataTables_wrapper .dataTables_filter input {
      width: auto;
      display: inline-block;
    }

    .dataTables_wrapper .dataTables_filter {
      float: left;
    }

    .dataTables_wrapper .dataTables_length {
      float: left;
      margin-bottom: 10px;
    }
  }
</style>