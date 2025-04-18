<?php

namespace App\Controllers;

use App\Models\Daily_report_model;

class Daily_report extends Security_Controller
{
  protected $dailyReportModel;

  public function __construct()
  {
    parent::__construct();
    $this->init_permission_checker("client");
    $this->dailyReportModel = new Daily_report_model();
  }

  /**
   * Mostrar la página principal del módulo de reporte diario.
   */
  public function index()
  {
    $this->check_module_availability("module_note");

    if ($this->login_user->user_type == "client" && !get_setting("client_can_access_notes")) {
      app_redirect("forbidden");
    }

    $reports = $this->dailyReportModel->findAllReports();
    $clinics = $this->dailyReportModel->getClinics();
    $clinic_options = $this->getClinicOptions();

    $data = [
      'reports' => $reports,
      'clinics' => $clinics,
      'clinic_options' => $clinic_options,
      'label_column' => "col-md-2",
      'field_column' => "col-md-10"
    ];

    return $this->template->rander("daily_report/index", $data);
  }

  /**
   * Mostrar el formulario modal para añadir o editar un reporte diario.
   * @param int $id
   */
  public function modal_form($id = 0)
  {
    $view_data = [
      'model_info' => $this->dailyReportModel->findReportById($id),
      'clinic_options' => $this->getClinicOptions(),
      'login_user' => $this->login_user,
      'label_column' => "col-md-2",
      'field_column' => "col-md-10"
    ];

    return $this->template->view('daily_report/modal_form', $view_data);
  }

  /**
   * Obtener las opciones de clínicas disponibles.
   */
  protected function getClinicOptions()
  {
    $clinics = $this->dailyReportModel->getClinics();
    $clinic_options = [];

    foreach ($clinics as $clinic) {
      $clinic_options[$clinic->id] = $clinic->name;
    }

    return $clinic_options;
  }

  /**
   * Guardar un reporte diario.
   */
  public function saveReport()
  {
    $validation = \Config\Services::validation();
    $validation->setRules($this->validateInput());

    $input = $this->request->getPost();
    log_message('debug', 'Datos recibidos: ' . print_r($input, true));

    if ($validation->run($input)) {
      $file = $this->request->getFile('report_file');
      $input['report_file'] = $this->handleFileUpload($file);

      $clinic = $this->dailyReportModel->getClinicById($input['clinic_id']);
      if ($clinic) {
        $input['clinic_name'] = $clinic->name;
      }

      $input['submitted_by'] = $this->login_user->first_name . " " . $this->login_user->last_name;
      $input = clean_data($input);
      log_message('debug', 'Datos a guardar: ' . print_r($input, true));

      $save_id = $this->dailyReportModel->saveReport($input);

      if ($save_id) {
        return $this->response->setJSON(["success" => true, "data" => $input, 'id' => $save_id, 'message' => app_lang('record_saved')]);
      } else {
        log_message('error', 'Error al guardar los datos');
        return $this->response->setJSON(["success" => false, 'message' => app_lang('error_occurred')]);
      }
    } else {
      log_message('error', 'Error en la validación de datos: ' . print_r($validation->getErrors(), true));
      return $this->response->setJSON(["success" => false, 'message' => $validation->getErrors()]);
    }
  }

  /**
   * Validar la entrada del formulario.
   */
  protected function validateInput()
  {
    return [
      "clinic_id" => "required|numeric",
      "report_file" => "uploaded[report_file]|max_size[report_file,2048]|ext_in[report_file,png,jpg,jpeg,pdf]",
      "report_date" => "required",
      "sales_cash" => "numeric",
      "sales_card" => "numeric",
      "sales_other" => "numeric",
      "new_patients_total" => "numeric",
      "followup_patients_total" => "numeric",
      "referral_google" => "numeric",
      "referral_referred" => "numeric",
      "referral_mail" => "numeric",
      "referral_walkby" => "numeric",
      "referral_facebook" => "numeric",
      "referral_events" => "numeric",
      "referral_instagram" => "numeric",
      "referral_youtube" => "numeric",
      "referral_tiktok" => "numeric",
      "referral_radio" => "numeric",
      "referral_newspaper" => "numeric",
      "referral_tv" => "numeric",
      "uninsured_patients" => "numeric",
      "insured_patients" => "numeric",
      "boxed_samples" => "numeric",
      "added_to_square_ecw" => "numeric",
    ];
  }

  /**
   * Manejar la carga de archivos.
   */
  protected function handleFileUpload($file)
  {
    if ($file && $file->isValid()) {
      $fileName = $file->getRandomName();
      $file->move(WRITEPATH . 'uploads', $fileName);
      return $fileName;
    } else {
      return null;
    }
  }

  /**
   * Obtener datos filtrados de los reportes diarios.
   */
  public function list_data()
  {
    $request = \Config\Services::request();
    $start = $request->getPost('start');
    $length = $request->getPost('length');
    $search = $request->getPost('search')['value'] ?? '';
    $order = $request->getPost('order') ?? [];
    $columnIndex = $order[0]['column'] ?? 0;
    $columns = $request->getPost('columns') ?? [];
    $columnName = $columns[$columnIndex]['data'] ?? 'id';
    $columnSortOrder = $order[0]['dir'] ?? 'asc';

    $startDate = $request->getPost('startDate') ?? null;
    $endDate = $request->getPost('endDate') ?? null;

    $reports = $this->dailyReportModel->getFilteredReports($start, $length, $search, $columnName, $columnSortOrder, $startDate, $endDate);
    $totalRecords = $this->dailyReportModel->getTotalReportsCount();
    $filteredRecords = $this->dailyReportModel->getFilteredReportsCount($search, $startDate, $endDate);

    $data = [];
    foreach ($reports as $report) {
      $data[] = [
        'submitted_by' => $report['submitted_by'],
        'id' => $report['id'],
        'clinic_id' => $report['clinic_id'],
        'clinic_name' => $report['clinic_name'],
        'report_date' => $report['report_date'],
        'report_file' => base_url('writable/uploads/' . esc($report['report_file'])),
        'sales_cash' => $report['sales_cash'],
        'sales_card' => $report['sales_card'],
        'sales_other' => $report['sales_other'],
        'new_patients_total' => $report['new_patients_total'],
        'followup_patients_total' => $report['followup_patients_total'],
        'referral_google' => $report['referral_google'],
        'referral_referred' => $report['referral_referred'],
        'referral_mail' => $report['referral_mail'],
        'referral_walkby' => $report['referral_walkby'],
        'referral_facebook' => $report['referral_facebook'],
        'referral_events' => $report['referral_events'],
        'referral_instagram' => $report['referral_instagram'],
        'referral_youtube' => $report['referral_youtube'],
        'referral_tiktok' => $report['referral_tiktok'],
        'referral_radio' => $report['referral_radio'],
        'referral_newspaper' => $report['referral_newspaper'],
        'referral_tv' => $report['referral_tv'],
        'uninsured_patients' => $report['uninsured_patients'],
        'insured_patients' => $report['insured_patients']
      ];
    }

    $headers = [
      app_lang("reported_by"),
      app_lang("report_id"),
      app_lang("clinic_id"),
      app_lang("clinic_name"),
      app_lang("report_date"),
      app_lang("file"),
      app_lang("cash_sales"),
      app_lang("card_sales"),
      app_lang("other_sales"),
      app_lang("new_patients"),
      app_lang("followup_patients"),
      app_lang("referral_google"),
      app_lang("referral_referred"),
      app_lang("referral_mail"),
      app_lang("referral_walkby"),
      app_lang("referral_facebook"),
      app_lang("referral_events"),
      app_lang("referral_instagram"),
      app_lang("referral_youtube"),
      app_lang("referral_tiktok"),
      app_lang("referral_radio"),
      app_lang("referral_newspaper"),
      app_lang("referral_tv"),
      app_lang("uninsured_patients"),
      app_lang("insured_patients")
    ];

    return $this->response->setJSON([
      'draw' => intval($request->getPost('draw')),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $filteredRecords,
      'data' => $data,
      'headers' => $headers
    ]);
  }

  /**
   * Obtener datos de pacientes.
   */
  public function getTotalPatientsData()
  {
    $clinicId = $this->request->getGet('clinic_id');

    $data = $this->dailyReportModel->getPatientsData($clinicId);
    return $this->response->setJSON($data);
  }

  /**
   * Obtener datos de ingresos.
   */
  public function getTotalIncomeData()
  {
    $clinicId = $this->request->getGet('clinic_id');
    $data = $this->dailyReportModel->getIncomeData($clinicId);
    return $this->response->setJSON($data);
  }

  /**
   * Obtener datos de plataformas.
   */
  public function getPlatformsData()
  {
    $clinicId = $this->request->getGet('clinic_id');
    $data = $this->dailyReportModel->getPlatformsData($clinicId);
    return $this->response->setJSON($data);
  }

  /**
   * Obtener datos de prevalencia de seguros.
   */
  public function getInsurancePrevalenceData()
  {
    $clinicId = $this->request->getGet('clinic_id');
    $data = $this->dailyReportModel->getInsurancePrevalenceData($clinicId);
    return $this->response->setJSON($data);
  }
}
