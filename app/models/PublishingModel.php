<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PublishingModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function generateUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function fetchReportData($userId, $isAdmin, $month, $year)
    {
        $stmt = $this->db->prepare("SELECT * FROM publishing_reports WHERE report_month = :m AND report_year = :y");
        $stmt->execute(['m' => $month, 'y' => $year]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$report) {
            return ['report' => null, 'sections' => [], 'rows' => [], 'cells' => []];
        }
        
        $reportId = $report['id'];

        // Fetch sections
        $stmt = $this->db->prepare("SELECT * FROM publishing_sections WHERE report_id = :report_id");
        $stmt->execute(['report_id' => $reportId]);
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sectionIds = array_column($sections, 'id');
        if (empty($sectionIds)) {
            return ['report' => $report, 'sections' => [], 'rows' => [], 'cells' => []];
        }

        // Fetch rows
        $placeholders = implode(',', array_fill(0, count($sectionIds), '?'));
        $stmt = $this->db->prepare("SELECT * FROM publishing_rows WHERE section_id IN ($placeholders) ORDER BY sort_order ASC");
        $stmt->execute($sectionIds);
        $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rows = [];
        $rowIds = [];
        $validSectionIds = [];
        foreach ($allRows as $row) {
            $assignedUsers = json_decode($row['assigned_users_json'], true) ?: [];
            
            if ($isAdmin || in_array($userId, $assignedUsers)) {
                $rows[] = $row;
                $rowIds[] = $row['id'];
                $validSectionIds[] = $row['section_id'];
            }
        }

        if (!$isAdmin) {
            $validSectionIds = array_unique($validSectionIds);
            $sections = array_filter($sections, function($sec) use ($validSectionIds) {
                return in_array($sec['id'], $validSectionIds);
            });
            $sections = array_values($sections);
        }

        if (empty($rowIds)) {
            return ['report' => $report, 'sections' => $sections, 'rows' => [], 'cells' => []];
        }

        // Fetch cells
        $placeholders = implode(',', array_fill(0, count($rowIds), '?'));
        $stmt = $this->db->prepare("SELECT * FROM publishing_cells WHERE row_id IN ($placeholders)");
        $stmt->execute($rowIds);
        $cells = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'report' => $report,
            'sections' => $sections,
            'rows' => $rows,
            'cells' => $cells
        ];
    }

    public function createMonthReport($month, $year)
    {
        // 1. Check if already exists
        $stmt = $this->db->prepare("SELECT id FROM publishing_reports WHERE report_month = :m AND report_year = :y");
        $stmt->execute(['m' => $month, 'y' => $year]);
        if ($stmt->fetch()) {
            throw new \Exception("Report for this month and year already exists.");
        }

        // 2. Find latest report to clone structure
        $stmt = $this->db->query("SELECT id FROM publishing_reports ORDER BY created_at DESC LIMIT 1");
        $latestReport = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->db->beginTransaction();
        try {
            // Calculate total days
            $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Create new report
            $reportId = $this->generateUuid();
            $stmt = $this->db->prepare("INSERT INTO publishing_reports (id, title, report_month, report_year, total_days, created_by) VALUES (:id, :title, :report_month, :report_year, :total_days, :created_by)");
            $stmt->execute([
                'id' => $reportId,
                'title' => 'Publishing Report',
                'report_month' => $month,
                'report_year' => $year,
                'total_days' => $totalDays,
                'created_by' => $_SESSION['user_id'] ?? 'e3e3e3e3-e3e3-4e3e-a3e3-e3e3e3e3e3e3'
            ]);

            if ($latestReport) {
                // Clone sections
                $stmt = $this->db->prepare("SELECT * FROM publishing_sections WHERE report_id = :report_id");
                $stmt->execute(['report_id' => $latestReport['id']]);
                $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmtInsSection = $this->db->prepare("INSERT INTO publishing_sections (id, report_id, section_key, section_name) VALUES (:id, :report_id, :section_key, :section_name)");
                $stmtRows = $this->db->prepare("SELECT * FROM publishing_rows WHERE section_id = :section_id");
                $stmtInsRow = $this->db->prepare("INSERT INTO publishing_rows (id, section_id, title, assigned_users_json, sort_order) VALUES (:id, :section_id, :title, :assigned_users_json, :sort_order)");

                foreach ($sections as $section) {
                    $newSectionId = $this->generateUuid();
                    $stmtInsSection->execute([
                        'id' => $newSectionId,
                        'report_id' => $reportId,
                        'section_key' => $section['section_key'],
                        'section_name' => $section['section_name']
                    ]);

                    // Clone rows for this section
                    $stmtRows->execute(['section_id' => $section['id']]);
                    $rows = $stmtRows->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($rows as $row) {
                        $stmtInsRow->execute([
                            'id' => $this->generateUuid(),
                            'section_id' => $newSectionId,
                            'title' => $row['title'],
                            'assigned_users_json' => $row['assigned_users_json'],
                            'sort_order' => $row['sort_order']
                        ]);
                    }
                }
            } else {
                // Create default sections if no previous report
                $sectionsToCreate = [
                    ['key' => 'facebook_ads', 'name' => 'Facebook Ads'],
                    ['key' => 'posts', 'name' => 'Posts'],
                    ['key' => 'reels', 'name' => 'Reels']
                ];
                
                foreach ($sectionsToCreate as $sec) {
                    $stmtIns = $this->db->prepare("INSERT INTO publishing_sections (id, report_id, section_key, section_name) VALUES (:id, :report_id, :section_key, :section_name)");
                    $stmtIns->execute([
                        'id' => $this->generateUuid(),
                        'report_id' => $reportId,
                        'section_key' => $sec['key'],
                        'section_name' => $sec['name']
                    ]);
                }
            }

            $this->db->commit();
            return $reportId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function saveReportData($data, $userId, $isAdmin)
    {
        $this->db->beginTransaction();
        try {
            $rowIdMap = [];
            
            if ($isAdmin) {
                // Admin can update report title
                if (isset($data['report']['title']) && isset($data['report']['id'])) {
                    $stmt = $this->db->prepare("UPDATE publishing_reports SET title = :title, total_days = :total_days WHERE id = :id");
                    $stmt->execute([
                        'title' => $data['report']['title'],
                        'total_days' => $data['report']['total_days'] ?? 15,
                        'id' => $data['report']['id']
                    ]);
                }

                // Admin can save rows
                if (isset($data['rows']) && is_array($data['rows'])) {
                    foreach ($data['rows'] as $row) {
                        $isTemp = (strpos($row['id'], 'temp-') === 0);
                        if (empty($row['id']) || $isTemp) {
                            $tempId = $row['id'];
                            $row['id'] = $this->generateUuid();
                            if ($isTemp) {
                                $rowIdMap[$tempId] = $row['id'];
                            }
                            $stmt = $this->db->prepare("INSERT INTO publishing_rows (id, section_id, title, assigned_users_json, sort_order) VALUES (:id, :section_id, :title, :assigned_users_json, :sort_order)");
                            $stmt->execute([
                                'id' => $row['id'],
                                'section_id' => $row['section_id'],
                                'title' => $row['title'],
                                'assigned_users_json' => $row['assigned_users_json'] ?? '[]',
                                'sort_order' => $row['sort_order'] ?? 0
                            ]);
                        } else {
                            $stmt = $this->db->prepare("UPDATE publishing_rows SET title = :title, assigned_users_json = :assigned_users_json, sort_order = :sort_order WHERE id = :id");
                            $stmt->execute([
                                'id' => $row['id'],
                                'title' => $row['title'],
                                'assigned_users_json' => $row['assigned_users_json'] ?? '[]',
                                'sort_order' => $row['sort_order'] ?? 0
                            ]);
                        }
                    }
                }
            }

            // Both Admin and Staff can save cells (with restrictions for staff)
            if (isset($data['cells']) && is_array($data['cells'])) {
                foreach ($data['cells'] as $cell) {
                    // Map row_id if it's a temp ID
                    if (isset($rowIdMap[$cell['row_id']])) {
                        $cell['row_id'] = $rowIdMap[$cell['row_id']];
                    }

                    // If staff, verify permission for this row
                    if (!$isAdmin) {
                        $stmt = $this->db->prepare("SELECT assigned_users_json FROM publishing_rows WHERE id = :id");
                        $stmt->execute(['id' => $cell['row_id']]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if (!$row) {
                            throw new \Exception("Row not found.");
                        }
                        
                        $assignedUsers = json_decode($row['assigned_users_json'], true) ?: [];
                        if (!in_array($userId, $assignedUsers)) {
                            throw new \Exception("You are not assigned to this row.");
                        }
                    }

                    // Save cell (upsert)
                    $stmt = $this->db->prepare("
                        INSERT INTO publishing_cells (id, row_id, day_number, cell_value, status_color) 
                        VALUES (:id, :row_id, :day_number, :cell_value, :status_color)
                        ON DUPLICATE KEY UPDATE cell_value = VALUES(cell_value), status_color = VALUES(status_color)
                    ");
                    $stmt->execute([
                        'id' => $this->generateUuid(),
                        'row_id' => $cell['row_id'],
                        'day_number' => $cell['day_number'],
                        'cell_value' => $cell['cell_value'] ?? '',
                        'status_color' => $cell['status_color'] ?? 'empty'
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function deleteRow($rowId, $isAdmin)
    {
        if (!$isAdmin) {
            throw new \Exception("Only admins can delete rows.");
        }

        $stmt = $this->db->prepare("DELETE FROM publishing_rows WHERE id = :id");
        return $stmt->execute(['id' => $rowId]);
    }
}
