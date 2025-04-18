<?php

namespace App\Models;

use CodeIgniter\Model;

class Daily_report_model extends Model
{
  protected $table = 'daily_reports';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'clinic_id',
    'clinic_name',
    'report_file',
    'report_date',
    'sales_cash',
    'sales_card',
    'sales_other',
    'new_patients_total',
    'followup_patients_total',
    'referral_google',
    'referral_referred',
    'referral_mail',
    'referral_walkby',
    'referral_facebook',
    'referral_events',
    'referral_instagram',
    'referral_youtube',
    'referral_tiktok',
    'referral_radio',
    'referral_newspaper',
    'referral_tv',
    'uninsured_patients',
    'insured_patients',
    'boxed_samples',
    'added_to_square_ecw',
    'submitted_by'
  ];

  /**
   * Guardar un reporte diario.
   * @param array $data
   * @return mixed
   */
  public function saveReport($data)
  {
    if ($this->insert($data)) {
      return $this->insertID();  // Devuelve el ID del nuevo registro insertado
    }
    return false;  // Si no se pudo insertar, retorna false
  }

  /**
   * Obtener todos los reportes.
   * @return array
   */
  public function findAllReports()
  {
    return $this->findAll();
  }

  /**
   * Obtener un reporte por su ID.
   * @param int $id
   * @return array
   */
  public function findReportById($id)
  {
    return $this->find($id);
  }

  /**
   * Obtener todas las clínicas.
   * @return array
   */
  public function getClinics()
  {
    try {
      $clinic_table = $this->db->prefixTable('clinic_directory');
      return $this->db->table($clinic_table)
        ->select('id, name')
        ->get()
        ->getResult();
    } catch (\Exception $e) {
      log_message('error', "Error al obtener las clínicas: " . $e->getMessage());
      throw new \Exception("Error al ejecutar la consulta en la tabla: " . $clinic_table);
    }
  }

  /**
   * Obtener el nombre de una clínica por su ID.
   * @param int $clinic_id
   * @return object
   */
  public function getClinicById($clinic_id)
  {
    return $this->db->table('clinic_directory')->select('name')->where('id', $clinic_id)->get()->getRow();
  }

  /**
   * Obtener reportes por ID de clínica.
   * @param int $clinic_id
   * @return array
   */
  public function findReportsByClinic($clinic_id)
  {
    return $this->where('clinic_id', $clinic_id)
      ->findAll();
  }

  /**
   * Obtener reportes filtrados.
   * @param int $start
   * @param int $length
   * @param string $search
   * @param string $columnName
   * @param string $columnSortOrder
   * @param string|null $startDate
   * @param string|null $endDate
   * @return array
   */
  public function getFilteredReports($start, $length, $search, $columnName, $columnSortOrder, $startDate = null, $endDate = null)
  {
    $builder = $this->table($this->table);

    if ($search) {
      $builder->groupStart()
        ->like('submitted_by', $search)
        ->orLike('clinic_name', $search)
        ->orLike('report_date', $search)
        ->groupEnd();
    }

    if (!empty($startDate) && !empty($endDate)) {
      $builder->where('report_date >=', $startDate)
        ->where('report_date <=', $endDate);
    }

    return $builder->orderBy($columnName, $columnSortOrder)
      ->limit($length, $start)
      ->get()
      ->getResultArray();
  }

  /**
   * Obtener el total de reportes.
   * @return int
   */
  public function getTotalReportsCount()
  {
    return $this->countAll();
  }

  /**
   * Obtener el total de reportes filtrados.
   * @param string $search
   * @param string|null $startDate
   * @param string|null $endDate
   * @return int
   */
  public function getFilteredReportsCount($search, $startDate = null, $endDate = null)
  {
    $builder = $this->table($this->table);

    if ($search) {
      $builder->groupStart()
        ->like('submitted_by', $search)
        ->orLike('clinic_name', $search)
        ->orLike('report_date', $search)
        ->groupEnd();
    }

    if (!empty($startDate) && !empty($endDate)) {
      $builder->where('report_date >=', $startDate)
        ->where('report_date <=', $endDate);
    }

    return $builder->countAllResults();
  }

  /**
   * Obtener datos de pacientes.
   * @param int $clinicId
   * @return array
   */
  public function getPatientsData($clinicId)
  {
    $monthlyPatientsQuery = $this->select('DATE_FORMAT(report_date, "%Y-%m") as month, SUM(new_patients_total + uninsured_patients + insured_patients) as total_patients')
      ->where('clinic_id', $clinicId)
      ->groupBy('DATE_FORMAT(report_date, "%Y-%m")')
      ->get();
    $monthlyPatients = $monthlyPatientsQuery->getResultArray();

    if (empty($monthlyPatients)) {
      return [
        'monthlyPatients' => [0],
        'labels' => []
      ];
    }

    $labels = array_column($monthlyPatients, 'month');

    return [
      'monthlyPatients' => array_column($monthlyPatients, 'total_patients'),
      'labels' => $labels
    ];
  }

  /**
   * Obtener datos de ingresos.
   * @param int $clinicId
   * @return array
   */
  public function getIncomeData($clinicId)
  {
    $dailyIncomeQuery = $this->select('DATE(report_date) as date, SUM(sales_cash + sales_card + sales_other) as total_income')
      ->where('clinic_id', $clinicId)
      ->groupBy('DATE(report_date)')
      ->get();
    $dailyIncome = $dailyIncomeQuery->getResultArray();

    if (empty($dailyIncome)) {
      return [
        'dailyIncome' => [],
        'labels' => []
      ];
    }

    $labels = array_column($dailyIncome, 'date');

    return [
      'dailyIncome' => array_column($dailyIncome, 'total_income'),
      'labels' => $labels
    ];
  }

  /**
   * Obtener datos de plataformas de marketing.
   * @param int $clinicId
   * @return array
   */
  public function getPlatformsData($clinicId)
  {
    $platformsQuery = $this->select([
      'SUM(followup_patients_total) as followup_patients_total',
      'SUM(referral_google) as referral_google',
      'SUM(referral_referred) as referral_referred',
      'SUM(referral_mail) as referral_mail',
      'SUM(referral_walkby) as referral_walkby',
      'SUM(referral_facebook) as referral_facebook',
      'SUM(referral_events) as referral_events',
      'SUM(referral_instagram) as referral_instagram',
      'SUM(referral_youtube) as referral_youtube',
      'SUM(referral_tiktok) as referral_tiktok',
      'SUM(referral_radio) as referral_radio',
      'SUM(referral_newspaper) as referral_newspaper'
    ])
      ->where('clinic_id', $clinicId)
      ->get();

    $platformsData = $platformsQuery->getRowArray();

    if ($platformsData === null) {
      $platformsData = [
        'followup_patients_total' => 0,
        'referral_google' => 0,
        'referral_referred' => 0,
        'referral_mail' => 0,
        'referral_walkby' => 0,
        'referral_facebook' => 0,
        'referral_events' => 0,
        'referral_instagram' => 0,
        'referral_youtube' => 0,
        'referral_tiktok' => 0,
        'referral_radio' => 0,
        'referral_newspaper' => 0
      ];
    } else {
      $platformsData = array_map(function ($value) {
        return $value === null ? 0 : $value;
      }, $platformsData);
    }

    $internetSum = $platformsData['followup_patients_total'] + $platformsData['referral_google'] + $platformsData['referral_mail'] + $platformsData['referral_facebook'] + $platformsData['referral_instagram'] + $platformsData['referral_youtube'] +     $platformsData['referral_radio'] + $platformsData['referral_newspaper'];
    $walkingSum = $platformsData['referral_walkby'];
    $referredSum = $platformsData['referral_events'] + $platformsData['referral_referred'];

    return [
      'internetSum' => $internetSum,
      'walkingSum' => $walkingSum,
      'referredSum' => $referredSum
    ];
  }

  /**
   * Obtener datos de prevalencia de seguros.
   * @param int $clinicId
   * @return array
   */
  public function getInsurancePrevalenceData($clinicId)
  {
    $insuranceQuery = $this->select([
      'SUM(insured_patients) as insured_patients',
      'SUM(uninsured_patients) as uninsured_patients',
      'SUM(referral_tv) as referral_tv'
    ])
      ->where('clinic_id', $clinicId)
      ->get();
    $insuranceData = $insuranceQuery->getRowArray();

    if ($insuranceData === null) {
      $insuranceData = [
        'insured_patients' => 0,
        'uninsured_patients' => 0,
        'referral_tv' => 0
      ];
    } else {
      $insuranceData = array_map(function ($value) {
        return $value === null ? 0 : $value;
      }, $insuranceData);
    }

    $labels = ['Insured Patients', 'Uninsured Patients'];

    return [
      'insuranceData' => array_values($insuranceData),
      'labels' => $labels
    ];
  }
}
