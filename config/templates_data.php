<?php
/**
 * Shabab Deira Operations CRM - 18 Ready-Made UAE Manpower & Operations Templates Catalog
 * Provides standardized templates for UAE labour supply, timesheets, payroll, and invoicing.
 */

function get_system_templates() {
    $today = date('d/m/Y');
    $due = date('d/m/Y', strtotime('+30 days'));

    return [
        // 1. Manpower Supply Invoice
        'manpower_supply_invoice' => [
            'key' => 'manpower_supply_invoice',
            'title' => 'Manpower Supply Invoice',
            'category' => 'Invoicing & Tax',
            'docType' => 'TAX INVOICE / فاتورة ضريبية',
            'docCodePrefix' => 'SD-MSI-',
            'badge' => 'Manpower Supply',
            'badgeColor' => '#0F3763',
            'description' => 'Standard monthly/weekly billing for outsourced labour, tradesmen, and helpers with regular hours, overtime rates, and 5% UAE VAT.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#0F3763',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'corporate'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Manpower Supply & Technical Services Division',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Abu Hail Road, Hor Al Anz East, Deira, Dubai, UAE',
                'phone' => '+971 56 135 9305 / +971 52 809 0786',
                'email' => 'info@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Invoice No', 'value' => 'SD-MSI-2026-041', 'key' => 'doc_no'],
                ['label' => 'Invoice Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Due Date', 'value' => $due, 'key' => 'due_date'],
                ['label' => 'Payment Terms', 'value' => '30 Days Net', 'key' => 'terms'],
                ['label' => 'Client P.O. No', 'value' => 'PO-DPW-8821', 'key' => 'po_no'],
                ['label' => 'Site Location', 'value' => 'JAFZA South Hub, Berth 7', 'key' => 'site_name']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Eng. Rashid Al Nuaimi (Site Director)',
                'address' => 'Gate 4, Jebel Ali Free Zone South, Dubai, UAE',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'procurement@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr #', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Worker Category / Trade Description', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'workers', 'title' => 'Headcount', 'width' => '75px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'hours', 'title' => 'Normal Hrs', 'width' => '85px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'rate', 'title' => 'Rate (AED)', 'width' => '90px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'ot_hours', 'title' => 'OT Hrs', 'width' => '75px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'ot_rate', 'title' => 'OT Rate', 'width' => '85px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'amount', 'title' => 'Gross (AED)', 'width' => '110px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Certified Warehouse Helpers & Cargo Handlers', 'workers' => 12, 'hours' => 2400, 'rate' => 22.00, 'ot_hours' => 180, 'ot_rate' => 28.00, 'amount' => 57840.00],
                ['sr' => 2, 'desc' => 'Heavy Cargo Riggers & Pallet Stacking Crew', 'workers' => 4, 'hours' => 800, 'rate' => 28.00, 'ot_hours' => 64, 'ot_rate' => 35.00, 'amount' => 24640.00],
                ['sr' => 3, 'desc' => 'Certified Forklift Operators (3.5T - 7T)', 'workers' => 2, 'hours' => 400, 'rate' => 34.00, 'ot_hours' => 30, 'ot_rate' => 42.00, 'amount' => 14860.00],
                ['sr' => 4, 'desc' => 'On-Site Safety Coordinator & Attendance Lead', 'workers' => 1, 'hours' => 200, 'rate' => 40.00, 'ot_hours' => 15, 'ot_rate' => 50.00, 'amount' => 8750.00]
            ],
            'notes' => "1. All deployed personnel hold 100% compliant Shabab Deira residency visas with active Wages Protection System (WPS) payroll enrollment.\n2. Overtime calculated in compliance with UAE Labour Law (1.25x regular / 1.50x Friday & Public Holidays).\n3. Bank Transfer: Emirates NBD, Deira Main Branch | Account: 0192837465 | IBAN: AE44026000000192837465 | Swift: EBILAEADXXX",
            'signatures' => [
                ['title' => 'Prepared By', 'name' => 'Zubair Khan', 'role' => 'Billing Specialist'],
                ['title' => 'Verified By Operations', 'name' => 'Farhan Siddiqui', 'role' => 'Site Operations Lead'],
                ['title' => 'Client Acceptance & Stamp', 'name' => 'Authorized Signatory', 'role' => 'DP World Logistics']
            ]
        ],

        // 2. Daily Cash Salary Register
        'daily_cash_register' => [
            'key' => 'daily_cash_register',
            'title' => 'Daily Cash Salary Register',
            'category' => 'Payroll & Cash',
            'docType' => 'DAILY CASH PAYMENT REGISTER / سجل الدفع النقدي اليومي',
            'docCodePrefix' => 'DCR-',
            'badge' => 'Daily Wage Payout',
            'badgeColor' => '#6366F1',
            'description' => 'Log daily outsourced worker payouts with ID/Passport verification, signature confirmation, and supervisor authorization.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#0F3763',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 24,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'register'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Outsourced Manpower Daily Disbursal Desk',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Abu Hail Road, Hor Al Anz East, Dubai',
                'phone' => '+971 56 135 9305 / +971 52 809 0786',
                'email' => 'info@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Register No', 'value' => 'DCR-20261014-001', 'key' => 'doc_no'],
                ['label' => 'Payment Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Site Location', 'value' => 'Jebel Ali Logistics Depot - Berth 4', 'key' => 'site_name'],
                ['label' => 'Group / Team', 'value' => 'Team Alpha - Cargo Unloading Shift', 'key' => 'group_team'],
                ['label' => 'Purpose', 'value' => 'Daily Salary Payment to Outsourced Workers', 'key' => 'purpose'],
                ['label' => 'Authorized By', 'value' => 'Rashid Al Nuaimi (Manager)', 'key' => 'auth_by']
            ],
            'client' => [
                'name' => 'Internal Operations Disbursal',
                'attention' => 'Site Cashier & Shift Supervisor',
                'address' => 'Dubai Industrial City / JAFZA Site Office',
                'trn' => 'N/A (Cash Wages)',
                'phone' => '+971 56 135 9305',
                'email' => 'cashier@shababdeira.com'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr #', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'worker_name', 'title' => 'Worker Full Name', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'id_no', 'title' => 'Passport / Emirates ID No', 'width' => '170px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'contact', 'title' => 'Contact Number', 'width' => '130px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'amount', 'title' => 'Cash Paid (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'total'],
                ['key' => 'signature', 'title' => 'Worker Signature / Stamp', 'width' => '150px', 'align' => 'center', 'type' => 'text']
            ],
            'items' => [
                ['sr' => 1, 'worker_name' => 'Muhammad Imran Ali', 'id_no' => '784-1991-3829104-1', 'contact' => '052-1928374', 'amount' => 140.00, 'signature' => 'Received & Signed'],
                ['sr' => 2, 'worker_name' => 'Tariq Mehmood Khan', 'id_no' => '784-1988-5928174-3', 'contact' => '056-8291042', 'amount' => 140.00, 'signature' => 'Received & Signed'],
                ['sr' => 3, 'worker_name' => 'Abdul Rehman Siddiqui', 'id_no' => '784-1994-1029384-5', 'contact' => '050-7182930', 'amount' => 150.00, 'signature' => 'Received & Signed'],
                ['sr' => 4, 'worker_name' => 'Bilal Ahmed Shaikh', 'id_no' => '784-1992-8472910-2', 'contact' => '054-9281726', 'amount' => 140.00, 'signature' => 'Received & Signed'],
                ['sr' => 5, 'worker_name' => 'Noor Alam Hossain', 'id_no' => '784-1989-4920184-7', 'contact' => '055-6182934', 'amount' => 160.00, 'signature' => 'Received & Signed'],
                ['sr' => 6, 'worker_name' => 'Ziaur Rahman Mollah', 'id_no' => '784-1993-6182930-4', 'contact' => '052-8392019', 'amount' => 140.00, 'signature' => 'Received & Signed'],
            ],
            'notes' => "Declaration: I hereby confirm that all workers listed above have received their full daily cash wages for the completed shift without any deductions. Each recipient has verified their identity and signed in my presence.",
            'signatures' => [
                ['title' => 'Site Supervisor Signature', 'name' => 'Muhammad Tariq', 'role' => 'Operations Field Supervisor'],
                ['title' => 'Cash Disbursal Officer', 'name' => 'Farhan Siddiqui', 'role' => 'Accounts Custodian'],
                ['title' => 'Management Sign-Off', 'name' => 'Rashid Al Nuaimi', 'role' => 'Operations Director']
            ]
        ],

        // 3. Timesheet Invoice
        'timesheet_invoice' => [
            'key' => 'timesheet_invoice',
            'title' => 'Timesheet & Labour Hours Invoice',
            'category' => 'Timesheets & Labour',
            'docType' => 'LABOUR TIMESHEET INVOICE / فاتورة ساعات العمل',
            'docCodePrefix' => 'SD-TSI-',
            'badge' => 'Timesheet Billing',
            'badgeColor' => '#0062D2',
            'description' => 'Detailed worker-by-worker timesheet invoice recording regular days, daily hours, Friday overtime, and total billing.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#0056B3',
                'accent' => '#E0472C',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 26,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'timesheet'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Workforce Attendance & Billing Center',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Abu Hail Road, Hor Al Anz East, Dubai',
                'phone' => '+971 56 135 9305 / +971 52 809 0786',
                'email' => 'info@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Timesheet No', 'value' => 'SD-TSI-0101', 'key' => 'doc_no'],
                ['label' => 'Period Covered', 'value' => '01/10/2026 - 14/10/2026', 'key' => 'doc_date'],
                ['label' => 'Client Project', 'value' => 'Naif Deira Depot - Palletizing', 'key' => 'site_name'],
                ['label' => 'Contract Ref', 'value' => 'SD-LBR-2026-99', 'key' => 'po_no'],
                ['label' => 'Total Hours', 'value' => '152:00 Hrs', 'key' => 'total_hours'],
                ['label' => 'Status', 'value' => 'Issued', 'key' => 'status']
            ],
            'client' => [
                'name' => 'Al Futtaim Freight & Cargo Services',
                'attention' => 'Tariq Mehmood (Operations Manager)',
                'address' => 'Cargo Village, Terminal 2, Dubai, UAE',
                'trn' => '100293847500003',
                'phone' => '+971 4 299 4444',
                'email' => 'accounts.cargo@alfuttaim.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr #', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'name', 'title' => 'Worker Name', 'width' => '160px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'trade', 'title' => 'Skill / Trade', 'width' => '120px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'days', 'title' => 'Days', 'width' => '55px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'regular_hrs', 'title' => 'Reg Hrs', 'width' => '70px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'ot_hrs', 'title' => 'OT Hrs', 'width' => '70px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'total_hrs', 'title' => 'Total Hrs', 'width' => '75px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'rate', 'title' => 'Rate (AED)', 'width' => '80px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'amount', 'title' => 'Total (AED)', 'width' => '105px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'name' => 'Muhammad Bilal', 'trade' => 'Helper', 'days' => 14, 'regular_hrs' => 112, 'ot_hrs' => 24, 'total_hrs' => 136, 'rate' => 20.00, 'amount' => 2720.00],
                ['sr' => 2, 'name' => 'Rashid Mehmood', 'trade' => 'Packer', 'days' => 14, 'regular_hrs' => 112, 'ot_hrs' => 18, 'total_hrs' => 130, 'rate' => 22.00, 'amount' => 2860.00],
                ['sr' => 3, 'name' => 'Kamran Akmal', 'trade' => 'Loader', 'days' => 13, 'regular_hrs' => 104, 'ot_hrs' => 20, 'total_hrs' => 124, 'rate' => 20.00, 'amount' => 2480.00],
                ['sr' => 4, 'name' => 'Sohail Tanvir', 'trade' => 'Forklift Driver', 'days' => 14, 'regular_hrs' => 112, 'ot_hrs' => 28, 'total_hrs' => 140, 'rate' => 32.00, 'amount' => 4480.00],
                ['sr' => 5, 'name' => 'Shahid Afridi', 'trade' => 'Site Coordinator', 'days' => 14, 'regular_hrs' => 112, 'ot_hrs' => 14, 'total_hrs' => 126, 'rate' => 38.00, 'amount' => 4788.00]
            ],
            'notes' => "Timesheets certified against biometric gate records. Payment due within agreed 30-day corporate credit term.",
            'signatures' => [
                ['title' => 'Prepared By', 'name' => 'Zubair Khan', 'role' => 'Billing Administrator'],
                ['title' => 'Verified By Client In-Charge', 'name' => 'Tariq Mehmood', 'role' => 'Al Futtaim Cargo'],
                ['title' => 'Authorized Finance Stamp', 'name' => 'Shabab Deira Accounts', 'role' => 'Head Office']
            ]
        ],

        // 4. Labour Attendance Sheet
        'labour_attendance' => [
            'key' => 'labour_attendance',
            'title' => 'Labour Daily Attendance Sheet',
            'category' => 'Timesheets & Labour',
            'docType' => 'DAILY WORKFORCE ATTENDANCE / كشف الحضور والانصراف اليومي للعمالة',
            'docCodePrefix' => 'SD-ATT-',
            'badge' => 'Daily Attendance',
            'badgeColor' => '#10B981',
            'description' => 'Site attendance tracking sheet with badge numbers, clock-in/out times, normal and overtime hours, and shift supervisor initial.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#065F46',
                'accent' => '#D97706',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 24,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'attendance'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'On-Site Workforce Management Desk',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Dubai - Deira - Hor Al Anz East',
                'phone' => '+971 56 135 9305',
                'email' => 'attendance@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Attendance Sheet #', 'value' => 'SD-ATT-20261014', 'key' => 'doc_no'],
                ['label' => 'Shift Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Work Shift', 'value' => 'Morning (07:00 - 16:00)', 'key' => 'shift'],
                ['label' => 'Site / Depot', 'value' => 'JAFZA South Terminal Berth 5', 'key' => 'site_name'],
                ['label' => 'Client', 'value' => 'DP World Logistics', 'key' => 'client_name'],
                ['label' => 'Supervisor', 'value' => 'Muhammad Tariq', 'key' => 'supervisor']
            ],
            'client' => [
                'name' => 'DP World Logistics Site Office',
                'attention' => 'Gate Security & HSE In-Charge',
                'address' => 'Jebel Ali Free Zone, Dubai',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'safety@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '38px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'badge', 'title' => 'Badge #', 'width' => '80px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'worker_name', 'title' => 'Worker Name', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'trade', 'title' => 'Trade', 'width' => '110px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'in_time', 'title' => 'Time In', 'width' => '75px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'out_time', 'title' => 'Time Out', 'width' => '75px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'normal_hrs', 'title' => 'Reg Hrs', 'width' => '65px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'ot_hrs', 'title' => 'OT Hrs', 'width' => '65px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'status', 'title' => 'Status', 'width' => '70px', 'align' => 'center', 'type' => 'badge'],
                ['key' => 'initial', 'title' => 'Sign', 'width' => '65px', 'align' => 'center', 'type' => 'text']
            ],
            'items' => [
                ['sr' => 1, 'badge' => 'SD-101', 'worker_name' => 'Muhammad Imran', 'trade' => 'Helper', 'in_time' => '06:55', 'out_time' => '17:00', 'normal_hrs' => 8, 'ot_hrs' => 2, 'status' => 'Present', 'initial' => 'MI'],
                ['sr' => 2, 'badge' => 'SD-102', 'worker_name' => 'Tariq Mehmood', 'trade' => 'Loader', 'in_time' => '06:58', 'out_time' => '17:00', 'normal_hrs' => 8, 'ot_hrs' => 2, 'status' => 'Present', 'initial' => 'TM'],
                ['sr' => 3, 'badge' => 'SD-103', 'worker_name' => 'Abdul Rehman', 'trade' => 'Rigger', 'in_time' => '06:50', 'out_time' => '16:00', 'normal_hrs' => 8, 'ot_hrs' => 0, 'status' => 'Present', 'initial' => 'AR'],
                ['sr' => 4, 'badge' => 'SD-104', 'worker_name' => 'Bilal Ahmed', 'trade' => 'Helper', 'in_time' => '07:05', 'out_time' => '17:00', 'normal_hrs' => 8, 'ot_hrs' => 2, 'status' => 'Present', 'initial' => 'BA'],
                ['sr' => 5, 'badge' => 'SD-105', 'worker_name' => 'Noor Alam', 'trade' => 'Forklift Driver', 'in_time' => '06:45', 'out_time' => '18:00', 'normal_hrs' => 8, 'ot_hrs' => 3, 'status' => 'Present', 'initial' => 'NA'],
                ['sr' => 6, 'badge' => 'SD-106', 'worker_name' => 'Suresh Kumar', 'trade' => 'Helper', 'in_time' => '07:00', 'out_time' => '16:00', 'normal_hrs' => 8, 'ot_hrs' => 0, 'status' => 'Present', 'initial' => 'SK']
            ],
            'notes' => "Summary: 6 Workers Deployed | 6 Present | 0 Absent | Total Normal Hours: 48 | Total OT Hours: 9 | Gate pass verified.",
            'signatures' => [
                ['title' => 'Shift Supervisor', 'name' => 'Muhammad Tariq', 'role' => 'Shabab Deira Site Lead'],
                ['title' => 'Client Site In-Charge', 'name' => 'Eng. Khalid Mansoor', 'role' => 'DP World Logistics'],
                ['title' => 'HSE & Security Clearance', 'name' => 'Gate Security Desk', 'role' => 'Terminal 2 Entrance']
            ]
        ],

        // 5. Overtime Record Sheet
        'overtime_records' => [
            'key' => 'overtime_records',
            'title' => 'Workforce Overtime Record Sheet',
            'category' => 'Timesheets & Labour',
            'docType' => 'AUTHORIZATION OF OVERTIME HOURS / اعتماد ساعات العمل الإضافي',
            'docCodePrefix' => 'SD-OTR-',
            'badge' => 'Overtime Authorization',
            'badgeColor' => '#EA580C',
            'description' => 'Formal overtime authorization sheet breaking down regular OT (1.25x) and holiday/weekend OT (1.50x) under UAE Labour Law.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#9A3412',
                'accent' => '#FBBF24',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 25,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'overtime'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Workforce Compliance & Payroll Department',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Deira, Dubai',
                'phone' => '+971 56 135 9305',
                'email' => 'payroll@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'OT Record No', 'value' => 'SD-OTR-2026-10', 'key' => 'doc_no'],
                ['label' => 'Period', 'value' => '01/10/2026 - 15/10/2026', 'key' => 'doc_date'],
                ['label' => 'Project / Site', 'value' => 'JAFZA South Container Stacking', 'key' => 'site_name'],
                ['label' => 'Client', 'value' => 'DP World Logistics', 'key' => 'client_name'],
                ['label' => 'OT Multiplier', 'value' => '1.25x Normal / 1.50x Weekend', 'key' => 'multiplier'],
                ['label' => 'Approved By', 'value' => 'Eng. Rashid Al Nuaimi', 'key' => 'approver']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Operations Payroll Desk',
                'address' => 'Jebel Ali, Dubai, UAE',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'procurement@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '40px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'worker_name', 'title' => 'Worker Name', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'trade', 'title' => 'Trade / Designation', 'width' => '120px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'ot_normal', 'title' => 'Normal OT (1.25x)', 'width' => '120px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'ot_holiday', 'title' => 'Weekend OT (1.50x)', 'width' => '130px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'total_ot', 'title' => 'Total OT Hrs', 'width' => '100px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'rate_ot', 'title' => 'OT Rate (AED)', 'width' => '100px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'gross_ot', 'title' => 'Gross OT (AED)', 'width' => '115px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'worker_name' => 'Muhammad Imran Ali', 'trade' => 'Helper', 'ot_normal' => 24, 'ot_holiday' => 8, 'total_ot' => 32, 'rate_ot' => 25.00, 'gross_ot' => 800.00],
                ['sr' => 2, 'worker_name' => 'Tariq Mehmood Khan', 'trade' => 'Loader', 'ot_normal' => 20, 'ot_holiday' => 8, 'total_ot' => 28, 'rate_ot' => 25.00, 'gross_ot' => 700.00],
                ['sr' => 3, 'worker_name' => 'Abdul Rehman Siddiqui', 'trade' => 'Rigger', 'ot_normal' => 18, 'ot_holiday' => 6, 'total_ot' => 24, 'rate_ot' => 35.00, 'gross_ot' => 840.00],
                ['sr' => 4, 'worker_name' => 'Bilal Ahmed Shaikh', 'trade' => 'Helper', 'ot_normal' => 22, 'ot_holiday' => 8, 'total_ot' => 30, 'rate_ot' => 25.00, 'gross_ot' => 750.00],
                ['sr' => 5, 'worker_name' => 'Noor Alam Hossain', 'trade' => 'Forklift Driver', 'ot_normal' => 26, 'ot_holiday' => 10, 'total_ot' => 36, 'rate_ot' => 40.00, 'gross_ot' => 1440.00]
            ],
            'notes' => "Compliance Note: All overtime hours listed were pre-approved by client site management and comply with MOHRE Article 19 regarding maximum work hours.",
            'signatures' => [
                ['title' => 'Site Coordinator', 'name' => 'Farhan Siddiqui', 'role' => 'Shabab Deira Lead'],
                ['title' => 'Client Project Engineer', 'name' => 'Eng. Rashid Al Nuaimi', 'role' => 'DP World Logistics'],
                ['title' => 'Payroll Verification', 'name' => 'Accounts Department', 'role' => 'Shabab Deira LLC']
            ]
        ],

        // 6. Worker Deployment Sheet
        'worker_deployment' => [
            'key' => 'worker_deployment',
            'title' => 'Worker Mobilization & Deployment Sheet',
            'category' => 'Operations & Logistics',
            'docType' => 'WORKFORCE MOBILIZATION ORDER / أمر توزيع وتعيين العمالة',
            'docCodePrefix' => 'SD-DEP-',
            'badge' => 'Site Deployment',
            'badgeColor' => '#0284C7',
            'description' => 'Mobilization order documenting worker allocation to client sites, gate pass status, PPE compliance, and emergency contacts.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#0369A1',
                'accent' => '#0D9488',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 25,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'deployment'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Workforce Logistics & Mobilization Wing',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Deira, Dubai, UAE',
                'phone' => '+971 56 135 9305',
                'email' => 'operations@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Deployment Order #', 'value' => 'SD-DEP-2026-088', 'key' => 'doc_no'],
                ['label' => 'Mobilization Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Client Name', 'value' => 'DP World Logistics FZE', 'key' => 'client_name'],
                ['label' => 'Destination Site', 'value' => 'Jebel Ali South Berth 7 Depot', 'key' => 'site_name'],
                ['label' => 'Duration', 'value' => '30 Days Renewable', 'key' => 'duration'],
                ['label' => 'Crew Coordinator', 'value' => 'Muhammad Tariq (+971 52 809 0786)', 'key' => 'lead']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Eng. Khalid Mansoor (HSE Officer)',
                'address' => 'Gate 4, JAFZA, Dubai',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'ops@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '40px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'worker_name', 'title' => 'Worker Name', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'eid', 'title' => 'Emirates ID No', 'width' => '160px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'trade', 'title' => 'Trade', 'width' => '120px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'gate_pass', 'title' => 'Gate Pass', 'width' => '90px', 'align' => 'center', 'type' => 'badge'],
                ['key' => 'ppe_status', 'title' => 'PPE Issued', 'width' => '100px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'induction', 'title' => 'Safety Induction', 'width' => '110px', 'align' => 'center', 'type' => 'text']
            ],
            'items' => [
                ['sr' => 1, 'worker_name' => 'Muhammad Imran Ali', 'eid' => '784-1991-3829104-1', 'trade' => 'Certified Helper', 'gate_pass' => 'Active', 'ppe_status' => 'Full Kit Issued', 'induction' => 'Completed'],
                ['sr' => 2, 'worker_name' => 'Tariq Mehmood Khan', 'eid' => '784-1988-5928174-3', 'trade' => 'Pallet Loader', 'gate_pass' => 'Active', 'ppe_status' => 'Full Kit Issued', 'induction' => 'Completed'],
                ['sr' => 3, 'worker_name' => 'Abdul Rehman Siddiqui', 'eid' => '784-1994-1029384-5', 'trade' => 'Heavy Rigger', 'gate_pass' => 'Active', 'ppe_status' => 'Full Kit Issued', 'induction' => 'Completed'],
                ['sr' => 4, 'worker_name' => 'Bilal Ahmed Shaikh', 'eid' => '784-1992-8472910-2', 'trade' => 'Certified Helper', 'gate_pass' => 'Active', 'ppe_status' => 'Full Kit Issued', 'induction' => 'Completed'],
                ['sr' => 5, 'worker_name' => 'Noor Alam Hossain', 'eid' => '784-1989-4920184-7', 'trade' => 'Forklift Driver (RTA)', 'gate_pass' => 'Active', 'ppe_status' => 'Full Kit Issued', 'induction' => 'Completed']
            ],
            'notes' => "All personnel have undergone medical fitness screening and possess valid UAE workmen's compensation insurance coverage. Uniform, safety boots, high-vis vest, and hard hats provided.",
            'signatures' => [
                ['title' => 'Dispatched By (SDSC)', 'name' => 'Farhan Siddiqui', 'role' => 'Logistics Operations Lead'],
                ['title' => 'Site Coordinator', 'name' => 'Muhammad Tariq', 'role' => 'Crew Supervisor'],
                ['title' => 'Received & Accepted By Client', 'name' => 'Eng. Khalid Mansoor', 'role' => 'DP World HSE Director']
            ]
        ],

        // 7. Monthly Payroll Register
        'monthly_payroll' => [
            'key' => 'monthly_payroll',
            'title' => 'Monthly Workforce Payroll Register (WPS)',
            'category' => 'Payroll & Cash',
            'docType' => 'MONTHLY WAGES PROTECTION (WPS) PAYROLL REGISTER / كشف الرواتب الشهري',
            'docCodePrefix' => 'SD-WPS-',
            'badge' => 'WPS Payroll',
            'badgeColor' => '#7C3AED',
            'description' => 'Comprehensive monthly payroll sheet matching MOHRE Wages Protection System specifications with Basic, Allowances, Overtime, and SIF file reference.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#5B21B6',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 24,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'payroll'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'MOHRE Employer Establishment No: 829104',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Hor Al Anz East, Deira, Dubai',
                'phone' => '+971 56 135 9305',
                'email' => 'wps@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Payroll Sheet #', 'value' => 'SD-WPS-2026-09', 'key' => 'doc_no'],
                ['label' => 'Salary Month', 'value' => 'September 2026', 'key' => 'doc_date'],
                ['label' => 'WPS SIF File', 'value' => '829104_202609_001.SIF', 'key' => 'sif_no'],
                ['label' => 'Bank / Exchange', 'value' => 'Al Ansari Exchange / ENBD', 'key' => 'bank_name'],
                ['label' => 'Total Workers', 'value' => '5 Active Personnel', 'key' => 'worker_count'],
                ['label' => 'Payment Mode', 'value' => 'Electronic WPS Transfer', 'key' => 'pay_mode']
            ],
            'client' => [
                'name' => 'Ministry of Human Resources & Emiratisation',
                'attention' => 'WPS Compliance & Audit Section',
                'address' => 'Dubai Labour Office, UAE',
                'trn' => 'MOHRE-EST-829104',
                'phone' => '600 590000',
                'email' => 'wps@mohre.gov.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '35px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'emp_id', 'title' => 'Emp #', 'width' => '65px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'name', 'title' => 'Employee Name', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'basic', 'title' => 'Basic (AED)', 'width' => '85px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'allow', 'title' => 'Allowances', 'width' => '85px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'ot', 'title' => 'OT Pay', 'width' => '75px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'gross', 'title' => 'Gross', 'width' => '85px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'deduct', 'title' => 'Deductions', 'width' => '75px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'net_pay', 'title' => 'Net Salary (AED)', 'width' => '105px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'emp_id' => 'SD-01', 'name' => 'Muhammad Imran Ali', 'basic' => 1200.00, 'allow' => 600.00, 'ot' => 450.00, 'gross' => 2250.00, 'deduct' => 0.00, 'net_pay' => 2250.00],
                ['sr' => 2, 'emp_id' => 'SD-02', 'name' => 'Tariq Mehmood Khan', 'basic' => 1200.00, 'allow' => 600.00, 'ot' => 380.00, 'gross' => 2180.00, 'deduct' => 0.00, 'net_pay' => 2180.00],
                ['sr' => 3, 'emp_id' => 'SD-03', 'name' => 'Abdul Rehman Siddiqui', 'basic' => 1500.00, 'allow' => 700.00, 'ot' => 520.00, 'gross' => 2720.00, 'deduct' => 0.00, 'net_pay' => 2720.00],
                ['sr' => 4, 'emp_id' => 'SD-04', 'name' => 'Bilal Ahmed Shaikh', 'basic' => 1200.00, 'allow' => 600.00, 'ot' => 410.00, 'gross' => 2210.00, 'deduct' => 0.00, 'net_pay' => 2210.00],
                ['sr' => 5, 'emp_id' => 'SD-05', 'name' => 'Noor Alam Hossain', 'basic' => 1800.00, 'allow' => 800.00, 'ot' => 650.00, 'gross' => 3250.00, 'deduct' => 0.00, 'net_pay' => 3250.00]
            ],
            'notes' => "SIF Generation Note: 100% compliant salary disbursement executed through Central Bank of the UAE WPS routing network. Zero unauthorized deductions.",
            'signatures' => [
                ['title' => 'Prepared By HR', 'name' => 'Zubair Khan', 'role' => 'HR Operations Desk'],
                ['title' => 'Finance Director', 'name' => 'Rashid Al Nuaimi', 'role' => 'Authorized Signatory'],
                ['title' => 'WPS Corporate Bank Stamp', 'name' => 'Corporate Bank / Exchange', 'role' => 'Salary Transfer Certified']
            ]
        ],

        // 8. Salary Payment Receipt
        'salary_receipt' => [
            'key' => 'salary_receipt',
            'title' => 'Individual Salary Payment Receipt',
            'category' => 'Payroll & Cash',
            'docType' => 'SALARY PAYMENT VOUCHER / إيصال استلام الراتب',
            'docCodePrefix' => 'SD-SPR-',
            'badge' => 'Worker Salary Voucher',
            'badgeColor' => '#059669',
            'description' => 'Official bilingual salary voucher for an individual worker detailing basic pay, overtime, allowances, and signature / thumbprint.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#064E3B',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'voucher'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Workforce Disbursal & Accounts Department',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Abu Hail, Deira, Dubai, UAE',
                'phone' => '+971 56 135 9305',
                'email' => 'accounts@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Voucher No', 'value' => 'SD-SPR-2026-0901', 'key' => 'doc_no'],
                ['label' => 'Payment Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Salary Month', 'value' => 'September 2026', 'key' => 'month'],
                ['label' => 'Employee Name', 'value' => 'Muhammad Imran Ali', 'key' => 'emp_name'],
                ['label' => 'Passport / EID', 'value' => '784-1991-3829104-1', 'key' => 'eid'],
                ['label' => 'Designation', 'value' => 'Warehouse Helper / Logistics Crew', 'key' => 'trade']
            ],
            'client' => [
                'name' => 'Employee Details',
                'attention' => 'Muhammad Imran Ali (Badge SD-101)',
                'address' => 'Hor Al Anz Staff Accommodation, Dubai',
                'trn' => 'WPS Card: 82910401',
                'phone' => '+971 52 192 8374',
                'email' => 'worker@shababdeira.com'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'component', 'title' => 'Salary Component / Description', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'basis', 'title' => 'Calculation Basis', 'width' => '150px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'earnings', 'title' => 'Earnings (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'deductions', 'title' => 'Deductions (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'net', 'title' => 'Net (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'component' => 'Basic Salary for September 2026', 'basis' => 'Full Month 30 Days', 'earnings' => 1200.00, 'deductions' => 0.00, 'net' => 1200.00],
                ['sr' => 2, 'component' => 'Accommodation & Transportation Allowance', 'basis' => 'Contractual Allowance', 'earnings' => 600.00, 'deductions' => 0.00, 'net' => 600.00],
                ['sr' => 3, 'component' => 'Overtime Working Hours Payout', 'basis' => '32 OT Hours Verified', 'earnings' => 450.00, 'deductions' => 0.00, 'net' => 450.00],
                ['sr' => 4, 'component' => 'Attendance Bonus / Site Commendation', 'basis' => '100% Punctuality', 'earnings' => 100.00, 'deductions' => 0.00, 'net' => 100.00]
            ],
            'notes' => "Worker Acknowledgment: I hereby acknowledge receipt of my full salary and entitlements amounting to AED 2,350.00 (Two Thousand Three Hundred Fifty Dirhams Only) for September 2026 without any claim remaining.",
            'signatures' => [
                ['title' => 'Disbursed By (Cashier)', 'name' => 'Farhan Siddiqui', 'role' => 'Shabab Deira Accounts'],
                ['title' => 'Authorized Manager', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director'],
                ['title' => 'Worker Signature / Thumbprint', 'name' => 'Muhammad Imran Ali', 'role' => 'Recipient']
            ]
        ],

        // 9. Employee Advance Voucher
        'employee_advance' => [
            'key' => 'employee_advance',
            'title' => 'Employee Salary Advance Voucher',
            'category' => 'Payroll & Cash',
            'docType' => 'SALARY ADVANCE REQUEST & VOUCHER / سند صرف سلفة راتب',
            'docCodePrefix' => 'SD-ADV-',
            'badge' => 'Salary Advance',
            'badgeColor' => '#D97706',
            'description' => 'Formal salary advance request voucher with repayment deduction schedule, reason for advance, and management approvals.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#B45309',
                'accent' => '#1E293B',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'advance'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'HR Welfare & Corporate Accounts Desk',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Hor Al Anz East, Deira, Dubai',
                'phone' => '+971 56 135 9305',
                'email' => 'hr@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Advance Voucher #', 'value' => 'SD-ADV-2026-044', 'key' => 'doc_no'],
                ['label' => 'Disbursal Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Applicant Name', 'value' => 'Tariq Mehmood Khan', 'key' => 'emp_name'],
                ['label' => 'Employee Code', 'value' => 'SD-102', 'key' => 'emp_code'],
                ['label' => 'Advance Amount', 'value' => 'AED 600.00', 'key' => 'adv_amount'],
                ['label' => 'Repayment Term', 'value' => 'Deducted from Oct 2026 Payroll', 'key' => 'repay_terms']
            ],
            'client' => [
                'name' => 'Internal Welfare Disbursal',
                'attention' => 'HR & Payroll Operations',
                'address' => 'Head Office, Deira',
                'trn' => 'N/A',
                'phone' => '+971 56 135 9305',
                'email' => 'accounts@shababdeira.com'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Advance Justification / Deduction Schedule', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'month_deduct', 'title' => 'Payroll Month', 'width' => '150px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'pct', 'title' => '% of Wage', 'width' => '100px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'amount', 'title' => 'Deduction (AED)', 'width' => '130px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Emergency Family Medical Assistance (Requested by Worker)', 'month_deduct' => 'October 2026', 'pct' => '25% Max', 'amount' => 300.00],
                ['sr' => 2, 'desc' => 'Second Installment Payroll Deduction', 'month_deduct' => 'November 2026', 'pct' => '25% Max', 'amount' => 300.00]
            ],
            'notes' => "Legal Consent: I hereby agree that the sum of AED 600.00 received today in cash will be deducted in two equal installments from my upcoming salaries pursuant to UAE Labour Law regulations.",
            'signatures' => [
                ['title' => 'Employee Signature', 'name' => 'Tariq Mehmood Khan', 'role' => 'Applicant'],
                ['title' => 'HR Approval', 'name' => 'Zubair Khan', 'role' => 'HR Manager'],
                ['title' => 'Managing Director Approval', 'name' => 'Rashid Al Nuaimi', 'role' => 'Disbursal Authorized']
            ]
        ],

        // 10. Petty Cash Voucher
        'petty_cash' => [
            'key' => 'petty_cash',
            'title' => 'Operations Petty Cash Voucher',
            'category' => 'Payroll & Cash',
            'docType' => 'PETTY CASH DISBURSAL VOUCHER / سند صرف نثريات الموقع',
            'docCodePrefix' => 'SD-PCV-',
            'badge' => 'Petty Cash',
            'badgeColor' => '#0F766E',
            'description' => 'Log site operational expenses, worker transportation, Salik toll fees, safety consumables, and immediate site requirements.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#115E59',
                'accent' => '#F59E0B',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'voucher'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Site Logistics & Field Expense Desk',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Dubai - Deira Main Office',
                'phone' => '+971 56 135 9305',
                'email' => 'cashier@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Petty Cash #', 'value' => 'SD-PCV-2026-112', 'key' => 'doc_no'],
                ['label' => 'Expense Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Paid To', 'value' => 'Muhammad Tariq (Site Coordinator)', 'key' => 'paid_to'],
                ['label' => 'Project Site', 'value' => 'JAFZA Logistics Depot - Gate 4', 'key' => 'site_name'],
                ['label' => 'Cost Center', 'value' => 'Field Crew Logistics & Safety', 'key' => 'cost_center'],
                ['label' => 'Total AED', 'value' => 'AED 472.50 (Inc VAT)', 'key' => 'total_display']
            ],
            'client' => [
                'name' => 'Field Operations Expense',
                'attention' => 'Muhammad Tariq (Crew Lead)',
                'address' => 'Jebel Ali Site Depot',
                'trn' => 'Internal Expense',
                'phone' => '+971 52 809 0786',
                'email' => 'site.ops@shababdeira.com'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Expense Description / Receipt Ref', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'category', 'title' => 'Category', 'width' => '130px', 'align' => 'left', 'type' => 'text'],
                ['key' => 'qty', 'title' => 'Qty / Trips', 'width' => '85px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'unit_price', 'title' => 'Rate (AED)', 'width' => '100px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'amount', 'title' => 'Amount (AED)', 'width' => '115px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Worker Van Fuel & RTA Salik Toll Passes (Deira to JAFZA South)', 'category' => 'Transport', 'qty' => 1, 'unit_price' => 180.00, 'amount' => 180.00],
                ['sr' => 2, 'desc' => 'Emergency Heavy-Duty Safety Work Gloves & Dust Masks (12 Workers)', 'category' => 'Safety PPE', 'qty' => 12, 'unit_price' => 12.50, 'amount' => 150.00],
                ['sr' => 3, 'desc' => 'Industrial Pallet Shrink Wrap & Lashing Straps for Emergency Container', 'category' => 'Packaging', 'qty' => 4, 'unit_price' => 30.00, 'amount' => 120.00]
            ],
            'notes' => "Original tax invoices and fuel receipts attached. Disbursed from Petty Cash Float No. 2.",
            'signatures' => [
                ['title' => 'Requested By', 'name' => 'Muhammad Tariq', 'role' => 'Site Supervisor'],
                ['title' => 'Disbursed By Cashier', 'name' => 'Farhan Siddiqui', 'role' => 'Accounts Custodian'],
                ['title' => 'Verified By Director', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director']
            ]
        ],

        // 11. Job Completion Report (JCR)
        'job_completion' => [
            'key' => 'job_completion',
            'title' => 'Job Completion Report (JCR)',
            'category' => 'Operations & Logistics',
            'docType' => 'JOB COMPLETION CERTIFICATE / شهادة إنجاز وتسليم الأعمال',
            'docCodePrefix' => 'SD-JCR-',
            'badge' => 'Service Completion',
            'badgeColor' => '#2563EB',
            'description' => 'Formal project completion sign-off certifying manpower supply, container stuffing, or rigging services executed satisfactorily.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#1E40AF',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'completion'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Technical Rigging & Industrial Logistics',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Abu Hail Road, Hor Al Anz East, Dubai',
                'phone' => '+971 56 135 9305',
                'email' => 'ops@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'JCR Number', 'value' => 'SD-JCR-2026-039', 'key' => 'doc_no'],
                ['label' => 'Completion Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Client Company', 'value' => 'Al Futtaim Freight & Logistics', 'key' => 'client_name'],
                ['label' => 'P.O. Reference', 'value' => 'PO-AF-99281', 'key' => 'po_no'],
                ['label' => 'Site Location', 'value' => 'Cargo Village Warehouse 14, Dubai', 'key' => 'site_name'],
                ['label' => 'Inspection Status', 'value' => '100% Passed & Accepted', 'key' => 'status']
            ],
            'client' => [
                'name' => 'Al Futtaim Freight & Logistics',
                'attention' => 'Tariq Mehmood (Logistics Director)',
                'address' => 'Cargo Village, Terminal 2, Dubai',
                'trn' => '100293847500003',
                'phone' => '+971 4 299 4444',
                'email' => 'tariq.mehmood@alfuttaim.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'scope', 'title' => 'Scope of Manpower & Technical Work Executed', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'crew_size', 'title' => 'Crew Size', 'width' => '90px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'hours', 'title' => 'Hours Logged', 'width' => '100px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'quality', 'title' => 'Quality Status', 'width' => '120px', 'align' => 'center', 'type' => 'badge'],
                ['key' => 'acceptance', 'title' => 'Acceptance Note', 'width' => '140px', 'align' => 'left', 'type' => 'text']
            ],
            'items' => [
                ['sr' => 1, 'scope' => 'Unloading & Palletizing of 8x 40ft High-Cube Shipping Containers', 'crew_size' => '16 Workers', 'hours' => '128 Hrs', 'quality' => 'Approved', 'acceptance' => 'Zero cargo damage'],
                ['sr' => 2, 'scope' => 'Heavy Machinery Rigging, Industrial Skid Lashing & Positioning', 'crew_size' => '4 Riggers', 'hours' => '32 Hrs', 'quality' => 'Approved', 'acceptance' => 'Certified secure'],
                ['sr' => 3, 'scope' => 'Barcode Scanning, Inventory Sorting & Warehouse Bin Placement', 'crew_size' => '6 Workers', 'hours' => '48 Hrs', 'quality' => 'Approved', 'acceptance' => '100% reconciled']
            ],
            'notes' => "Client Certification: We hereby verify that the outsourced workforce and technical services provided by Shabab Deira Shipping & Cargo Services LLC have been fully inspected, completed on schedule, and delivered in compliance with all agreed quality and safety standards.",
            'signatures' => [
                ['title' => 'Shabab Deira Site Supervisor', 'name' => 'Farhan Siddiqui', 'role' => 'Operations Lead'],
                ['title' => 'Client Project Manager', 'name' => 'Tariq Mehmood', 'role' => 'Al Futtaim Logistics'],
                ['title' => 'Client Acceptance Stamp', 'name' => 'Official Seal & Signature', 'role' => 'Dubai Operations']
            ]
        ],

        // 12. Service Invoice
        'service_invoice' => [
            'key' => 'service_invoice',
            'title' => 'Commercial Cargo & Labour Service Invoice',
            'category' => 'Invoicing & Tax',
            'docType' => 'COMMERCIAL TAX INVOICE / فاتورة خدمات تجارية',
            'docCodePrefix' => 'SD-SRV-',
            'badge' => 'Service Billing',
            'badgeColor' => '#076835',
            'description' => 'Standard commercial invoice for cargo packaging, container stuffing, warehousing, and turnkey manpower operations with 5% VAT.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#076835',
                'accent' => '#D97706',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'corporate'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Cargo Handling, Packaging & Workforce Solutions',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Abu Hail Road, Dubai',
                'phone' => '+971 56 135 9305 / +971 52 809 0786',
                'email' => 'info@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Invoice No', 'value' => 'SD-SRV-2026-104', 'key' => 'doc_no'],
                ['label' => 'Invoice Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Due Date', 'value' => $due, 'key' => 'due_date'],
                ['label' => 'Client P.O. No', 'value' => 'PO-EGI-4019', 'key' => 'po_no'],
                ['label' => 'Payment Terms', 'value' => '30 Days Net', 'key' => 'terms'],
                ['label' => 'Service Depot', 'value' => 'Naif Deira Warehouse Facility', 'key' => 'site_name']
            ],
            'client' => [
                'name' => 'Emirates Global Industrial Group',
                'attention' => 'Sultan Ahmed (Procurement Head)',
                'address' => 'Al Quoz Industrial Area 3, Dubai, UAE',
                'trn' => '100492817200003',
                'phone' => '+971 4 340 8888',
                'email' => 'procurement@emiratesglobal.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Service Description', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'unit', 'title' => 'Unit', 'width' => '90px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'qty', 'title' => 'Quantity', 'width' => '85px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'rate', 'title' => 'Unit Rate (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'amount', 'title' => 'Net Amount (AED)', 'width' => '130px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Industrial Cargo Palletizing, Shrink Wrapping & Strapping Service', 'unit' => 'Pallets', 'qty' => 140, 'rate' => 45.00, 'amount' => 6300.00],
                ['sr' => 2, 'desc' => 'Container Lashing, Chocking & Wooden Dunnage Installation (40ft HC)', 'unit' => 'Containers', 'qty' => 4, 'rate' => 1250.00, 'amount' => 5000.00],
                ['sr' => 3, 'desc' => 'Turnkey Warehouse Helpers Supply (Night Shift Loading Crew)', 'unit' => 'Man-Days', 'qty' => 30, 'rate' => 180.00, 'amount' => 5400.00],
                ['sr' => 4, 'desc' => 'Certified Forklift Driver Supply (5-Ton Electric)', 'unit' => 'Days', 'qty' => 6, 'rate' => 300.00, 'amount' => 1800.00]
            ],
            'notes' => "Payment Terms: 30 days from invoice date. Remit via direct wire to Emirates NBD account. All rates subject to 5% UAE Federal Value Added Tax.",
            'signatures' => [
                ['title' => 'Prepared By', 'name' => 'Zubair Khan', 'role' => 'Billing Officer'],
                ['title' => 'Operations Manager', 'name' => 'Rashid Al Nuaimi', 'role' => 'Shabab Deira LLC'],
                ['title' => 'Client Acceptance', 'name' => 'Sultan Ahmed', 'role' => 'Emirates Global Group']
            ]
        ],

        // 13. Commercial Quotation
        'quotation' => [
            'key' => 'quotation',
            'title' => 'Commercial Manpower & Logistics Quotation',
            'category' => 'Invoicing & Tax',
            'docType' => 'COMMERCIAL RATE PROPOSAL / عرض أسعار توريد عمالة',
            'docCodePrefix' => 'SD-QTN-',
            'badge' => 'Rate Quotation',
            'badgeColor' => '#4F46E5',
            'description' => 'Formal rate proposal for manpower supply with hourly and monthly packages, overtime multiplier, mobilization timeline, and terms.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#3730A3',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'corporate'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Workforce Outsourcing & Corporate Business Desk',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Hor Al Anz East, Dubai',
                'phone' => '+971 56 135 9305 / +971 52 809 0786',
                'email' => 'info@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Quotation #', 'value' => 'SD-QTN-2026-078', 'key' => 'doc_no'],
                ['label' => 'Quotation Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Validity', 'value' => '30 Days From Issue', 'key' => 'validity'],
                ['label' => 'Payment Terms', 'value' => '30 Days Credit', 'key' => 'terms'],
                ['label' => 'Mobilization Lead Time', 'value' => '24 to 48 Hours', 'key' => 'lead_time'],
                ['label' => 'Inquiry Ref', 'value' => 'REQ-DPW-2026-901', 'key' => 'inquiry_no']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Procurement & Vendor Management Committee',
                'address' => 'Jebel Ali Free Zone, Dubai, UAE',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'procurement@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'trade', 'title' => 'Workforce Trade / Skill Description', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'hourly_rate', 'title' => 'Hourly Rate (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'ot_rate', 'title' => 'OT Rate (AED)', 'width' => '110px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'monthly_rate', 'title' => 'Monthly (26 Days)', 'width' => '130px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'status', 'title' => 'Availability', 'width' => '110px', 'align' => 'center', 'type' => 'badge']
            ],
            'items' => [
                ['sr' => 1, 'trade' => 'General Warehouse Helper & Container Unloading Crew', 'hourly_rate' => 22.00, 'ot_rate' => 28.00, 'monthly_rate' => 4500.00, 'status' => 'Immediate (50+)'],
                ['sr' => 2, 'trade' => 'Heavy Cargo Rigger / Slinger (Third-Party Certified)', 'hourly_rate' => 28.00, 'ot_rate' => 35.00, 'monthly_rate' => 5800.00, 'status' => 'Available (15)'],
                ['sr' => 3, 'trade' => 'Certified Forklift Operator (RTA Valid License)', 'hourly_rate' => 34.00, 'ot_rate' => 42.00, 'monthly_rate' => 7000.00, 'status' => 'Available (10)'],
                ['sr' => 4, 'trade' => 'On-Site Bilingual Operations Supervisor', 'hourly_rate' => 40.00, 'ot_rate' => 50.00, 'monthly_rate' => 8500.00, 'status' => 'Available (4)']
            ],
            'notes' => "Terms & Conditions:\n1. Rate includes worker visa, medical insurance, accommodation, and PPE.\n2. Overtime calculated at 1.25x for standard days and 1.50x for Fridays/Public Holidays.\n3. Mobilization within 48 hours following receipt of official signed Purchase Order.",
            'signatures' => [
                ['title' => 'Prepared By Commercial Desk', 'name' => 'Zubair Khan', 'role' => 'Business Development'],
                ['title' => 'Approved By Management', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director'],
                ['title' => 'Client Acceptance Confirmation', 'name' => 'Authorized Signature & Stamp', 'role' => 'DP World Logistics']
            ]
        ],

        // 14. Purchase Order (PO)
        'purchase_order' => [
            'key' => 'purchase_order',
            'title' => 'Outsourced Manpower Purchase Order (PO)',
            'category' => 'Operations & Logistics',
            'docType' => 'OFFICIAL PURCHASE ORDER / أمر شراء خدمات توريد عمالة',
            'docCodePrefix' => 'SD-PO-',
            'badge' => 'Purchase Order',
            'badgeColor' => '#B45309',
            'description' => 'Formal corporate purchase order for procuring manpower services, safety equipment, or specialized tooling.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#78350F',
                'accent' => '#D97706',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'corporate'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Procurement & Logistics Department',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Deira, Dubai, UAE',
                'phone' => '+971 56 135 9305',
                'email' => 'procurement@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Purchase Order #', 'value' => 'SD-PO-2026-219', 'key' => 'doc_no'],
                ['label' => 'P.O. Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Delivery Due Date', 'value' => date('d/m/Y', strtotime('+5 days')), 'key' => 'delivery_date'],
                ['label' => 'Deployment Site', 'value' => 'JAFZA Logistics Depot - Berth 7', 'key' => 'site_name'],
                ['label' => 'Payment Terms', 'value' => '30 Days Net on Invoice', 'key' => 'terms'],
                ['label' => 'Requisition Ref', 'value' => 'REQ-WH-1049', 'key' => 'req_no']
            ],
            'client' => [
                'name' => 'Gulf Safety & Industrial Supplies LLC',
                'attention' => 'Sales & Delivery Desk',
                'address' => 'Ras Al Khor Industrial Area 2, Dubai, UAE',
                'trn' => '100382910400003',
                'phone' => '+971 4 333 9922',
                'email' => 'sales@gulfsafety.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'item_desc', 'title' => 'Item Specification / Safety Gear', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'unit', 'title' => 'Unit', 'width' => '85px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'qty', 'title' => 'Quantity', 'width' => '80px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'unit_price', 'title' => 'Unit Price (AED)', 'width' => '120px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'amount', 'title' => 'Total AED (Ex VAT)', 'width' => '130px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'item_desc' => 'Steel-Toe Industrial Safety Boots (EN ISO 20345 Certified)', 'unit' => 'Pairs', 'qty' => 50, 'unit_price' => 75.00, 'amount' => 3750.00],
                ['sr' => 2, 'item_desc' => 'High-Visibility Safety Vests with Reflective Strips & SDSC Logo', 'unit' => 'Pcs', 'qty' => 100, 'unit_price' => 14.00, 'amount' => 1400.00],
                ['sr' => 3, 'item_desc' => 'Industrial Heavy Rigging Helmets with Chin Straps', 'unit' => 'Pcs', 'qty' => 50, 'unit_price' => 28.00, 'amount' => 1400.00],
                ['sr' => 4, 'item_desc' => 'Reinforced Grip Cargo Handling Work Gloves (Dozen Pack)', 'unit' => 'Packs', 'qty' => 20, 'unit_price' => 45.00, 'amount' => 900.00]
            ],
            'notes' => "Deliver strictly to Shabab Deira Central Warehouse, Hor Al Anz East, Dubai. Invoice must indicate this P.O. Number and delivery note receipt.",
            'signatures' => [
                ['title' => 'Procurement Officer', 'name' => 'Farhan Siddiqui', 'role' => 'Purchasing Lead'],
                ['title' => 'Finance Approval', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director'],
                ['title' => 'Vendor Acknowledgment', 'name' => 'Gulf Safety Supplies', 'role' => 'Supplier Sign & Stamp']
            ]
        ],

        // 15. Delivery Note / Manpower Handover
        'delivery_note' => [
            'key' => 'delivery_note',
            'title' => 'Delivery Note & Manpower Handover',
            'category' => 'Operations & Logistics',
            'docType' => 'SITE DELIVERY NOTE & CREW HANDOVER / إذن تسليم الموقع واستلام العمالة',
            'docCodePrefix' => 'SD-DN-',
            'badge' => 'Delivery Note',
            'badgeColor' => '#0891B2',
            'description' => 'Delivery note certifying physical handover of deployed workforce at client site, document verification, and safety induction clearance.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#0E7490',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'deployment'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Site Logistics & Manpower Operations',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Hor Al Anz East, Deira, Dubai',
                'phone' => '+971 56 135 9305',
                'email' => 'ops@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Delivery Note #', 'value' => 'SD-DN-2026-061', 'key' => 'doc_no'],
                ['label' => 'Arrival Date & Time', 'value' => $today . ' 07:00 AM', 'key' => 'doc_date'],
                ['label' => 'Client Company', 'value' => 'DP World Logistics FZE', 'key' => 'client_name'],
                ['label' => 'Contract / P.O. Ref', 'value' => 'PO-DPW-8821', 'key' => 'po_no'],
                ['label' => 'Dispatched By', 'value' => 'Muhammad Tariq (+971 52 809 0786)', 'key' => 'driver'],
                ['label' => 'Site Location', 'value' => 'Berth 7, JAFZA South Container Terminal', 'key' => 'site_name']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Eng. Khalid Mansoor (Site In-Charge)',
                'address' => 'Gate 4, JAFZA South, Dubai',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'ops@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Deployed Crew Description & Documentation', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'qty', 'title' => 'Personnel Count', 'width' => '115px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'passport_check', 'title' => 'Passport / Visa', 'width' => '120px', 'align' => 'center', 'type' => 'badge'],
                ['key' => 'ppe_check', 'title' => 'Safety PPE', 'width' => '110px', 'align' => 'center', 'type' => 'badge'],
                ['key' => 'condition', 'title' => 'Site Arrival Status', 'width' => '130px', 'align' => 'center', 'type' => 'text']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Certified Warehouse Helpers & Cargo Loading Crew', 'qty' => 12, 'passport_check' => 'Verified', 'ppe_check' => 'Full Kit', 'condition' => 'Fit for Duty'],
                ['sr' => 2, 'desc' => 'Heavy Riggers & Lashing Specialists', 'qty' => 4, 'passport_check' => 'Verified', 'ppe_check' => 'Full Kit', 'condition' => 'Fit for Duty'],
                ['sr' => 3, 'desc' => 'Certified Forklift Driver with UAE RTA Card', 'qty' => 2, 'passport_check' => 'Verified', 'ppe_check' => 'Full Kit', 'condition' => 'Fit for Duty'],
                ['sr' => 4, 'desc' => 'On-Site Bilingual Coordinator & Safety Lead', 'qty' => 1, 'passport_check' => 'Verified', 'ppe_check' => 'Full Kit', 'condition' => 'Fit for Duty']
            ],
            'notes' => "Acknowledgment of Handover: Client site authority confirms receipt of the total 19 deployed personnel in good health, equipped with mandatory PPE, and cleared through site security.",
            'signatures' => [
                ['title' => 'Delivered By (SDSC Lead)', 'name' => 'Muhammad Tariq', 'role' => 'Crew Coordinator'],
                ['title' => 'Received & Inspected By', 'name' => 'Eng. Khalid Mansoor', 'role' => 'DP World Site Engineer'],
                ['title' => 'Client Company Stamp', 'name' => 'Official Seal & Signature', 'role' => 'DP World Logistics FZE']
            ]
        ],

        // 16. VAT Tax Invoice (FTA Compliant)
        'vat_tax_invoice' => [
            'key' => 'vat_tax_invoice',
            'title' => 'UAE Federal Tax Authority (FTA) Tax Invoice',
            'category' => 'Invoicing & Tax',
            'docType' => 'TAX INVOICE / فاتورة ضريبية',
            'docCodePrefix' => 'SD-TAX-',
            'badge' => 'FTA Standard Tax',
            'badgeColor' => '#D97706',
            'description' => 'Official UAE VAT Tax Invoice meeting all FTA Federal Decree-Law No. 8 specifications with bilingual English/Arabic headers, TRN, and line-item VAT.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#0F3763',
                'accent' => '#D97706',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'tax'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Licensed Logistics, Manpower Supply & Cargo Handling Services',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Abu Hail Road, Hor Al Anz East, Deira, Dubai, UAE',
                'phone' => '+971 56 135 9305 / +971 52 809 0786',
                'email' => 'info@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Tax Invoice No', 'value' => 'SD-TAX-2026-004', 'key' => 'doc_no'],
                ['label' => 'Date of Issue', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Date of Supply', 'value' => $today, 'key' => 'supply_date'],
                ['label' => 'Client P.O. No', 'value' => 'PO-EGI-4019', 'key' => 'po_no'],
                ['label' => 'Payment Due Date', 'value' => $due, 'key' => 'due_date'],
                ['label' => 'Payment Terms', 'value' => '30 Days Net', 'key' => 'terms']
            ],
            'client' => [
                'name' => 'Emirates Global Industrial Group LLC',
                'attention' => 'Finance & Accounts Department',
                'address' => 'Al Quoz Industrial Area 3, Dubai, UAE',
                'trn' => '100492817200003',
                'phone' => '+971 4 340 8888',
                'email' => 'finance@emiratesglobal.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '40px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Description of Goods or Services / بيان الخدمات', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'qty', 'title' => 'Qty / Hrs', 'width' => '75px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'unit_price', 'title' => 'Unit Price', 'width' => '90px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'taxable', 'title' => 'Taxable Amt', 'width' => '100px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'vat_rate', 'title' => 'VAT %', 'width' => '65px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'vat_amt', 'title' => 'VAT (AED)', 'width' => '85px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'gross', 'title' => 'Total (AED)', 'width' => '105px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Outsourced General Construction & Logistics Crew Supply (45 Days Deployment)', 'qty' => 45, 'unit_price' => 400.00, 'taxable' => 18000.00, 'vat_rate' => '5%', 'vat_amt' => 900.00, 'gross' => 18900.00],
                ['sr' => 2, 'desc' => 'Certified Industrial Forklift Operator Deployment Service', 'qty' => 10, 'unit_price' => 350.00, 'taxable' => 3500.00, 'vat_rate' => '5%', 'vat_amt' => 175.00, 'gross' => 3675.00],
                ['sr' => 3, 'desc' => 'Pallet Wrapping, Heavy Dunnage & Container Securing Charges', 'qty' => 1, 'unit_price' => 1200.00, 'taxable' => 1200.00, 'vat_rate' => '5%', 'vat_amt' => 60.00, 'gross' => 1260.00]
            ],
            'notes' => "Declaration: This document is a valid Tax Invoice issued in accordance with UAE Federal Decree-Law No. (8) of 2017 on Value Added Tax.\nBank Wire: Emirates NBD | IBAN: AE44026000000192837465 | Swift: EBILAEADXXX",
            'signatures' => [
                ['title' => 'Prepared By (Billing)', 'name' => 'Zubair Khan', 'role' => 'Certified Tax Accountant'],
                ['title' => 'Authorized Finance Lead', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director'],
                ['title' => 'Official Corporate Stamp', 'name' => 'Shabab Deira LLC', 'role' => 'Tax Registered Entity']
            ]
        ],

        // 17. Tax Credit Note
        'credit_note' => [
            'key' => 'credit_note',
            'title' => 'Tax Credit Note (VAT Adjustment)',
            'category' => 'Invoicing & Tax',
            'docType' => 'TAX CREDIT NOTE / إشعار دائن ضريبي',
            'docCodePrefix' => 'SD-CRN-',
            'badge' => 'Credit Note',
            'badgeColor' => '#DC2626',
            'description' => 'Official FTA Tax Credit Note for billed hours adjustments, discounts, or billing reconciliations referencing original Tax Invoice.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#991B1B',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 5,
                'showVat' => true,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'corporate'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Accounts & Billing Adjustment Department',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Hor Al Anz East, Deira, Dubai, UAE',
                'phone' => '+971 56 135 9305',
                'email' => 'accounts@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Credit Note #', 'value' => 'SD-CRN-2026-009', 'key' => 'doc_no'],
                ['label' => 'Credit Note Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Original Invoice #', 'value' => 'SD-MSI-2026-041', 'key' => 'orig_inv'],
                ['label' => 'Original Invoice Date', 'value' => '01/10/2026', 'key' => 'orig_date'],
                ['label' => 'Reason for Credit', 'value' => 'Adjustment for 30 hours unutilized shift', 'key' => 'reason'],
                ['label' => 'Settlement Mode', 'value' => 'Offset against Next Statement', 'key' => 'settlement']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Accounts Payable & Reconciliation',
                'address' => 'Jebel Ali Free Zone, Dubai, UAE',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'ap@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Adjustment Particulars / Description', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'hours', 'title' => 'Hours Credited', 'width' => '120px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'rate', 'title' => 'Agreed Rate (AED)', 'width' => '130px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'taxable', 'title' => 'Taxable Credit', 'width' => '120px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'vat', 'title' => 'VAT 5%', 'width' => '90px', 'align' => 'right', 'type' => 'currency'],
                ['key' => 'total', 'title' => 'Net Credit (AED)', 'width' => '130px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Reconciliation of General Helper Shift Hours (Rain stoppage at Berth 7 on 05/10/2026)', 'hours' => 30, 'rate' => 22.00, 'taxable' => 660.00, 'vat' => 33.00, 'total' => 693.00],
                ['sr' => 2, 'desc' => 'Commercial Billing Discount approved for Volume Exceeding 2,500 Hours', 'hours' => 1, 'rate' => 500.00, 'taxable' => 500.00, 'vat' => 25.00, 'total' => 525.00]
            ],
            'notes' => "Tax Adjustment Clause: This credit note reduces the taxable consideration under UAE Federal Decree-Law No. (8) on Value Added Tax. The client is advised to adjust their input tax deduction accordingly.",
            'signatures' => [
                ['title' => 'Prepared By Accounts', 'name' => 'Zubair Khan', 'role' => 'Billing Administrator'],
                ['title' => 'Approved By Management', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director'],
                ['title' => 'Client Acknowledgment', 'name' => 'Authorized Finance Stamp', 'role' => 'DP World Logistics']
            ]
        ],

        // 18. Official Payment Receipt
        'payment_receipt' => [
            'key' => 'payment_receipt',
            'title' => 'Official Corporate Payment Receipt',
            'category' => 'Invoicing & Tax',
            'docType' => 'OFFICIAL PAYMENT RECEIPT / سند قبض رسمي',
            'docCodePrefix' => 'SD-REC-',
            'badge' => 'Payment Receipt',
            'badgeColor' => '#15803D',
            'description' => 'Formal company receipt voucher acknowledging client funds received via Bank Wire, Cheque, or Cash with stamp box.',
            'bilingual' => true,
            'theme' => [
                'primary' => '#166534',
                'accent' => '#C8AD7E',
                'fontFamily' => "'Source Sans 3', sans-serif",
                'rowHeight' => 28,
                'bordered' => true,
                'striped' => true,
                'currency' => 'AED',
                'vatRate' => 0,
                'showVat' => false,
                'showStamp' => true,
                'showSignature' => true,
                'headerLayout' => 'voucher'
            ],
            'company' => [
                'nameEn' => 'SHABAB DEIRA SHIPPING & CARGO SERVICES LLC',
                'nameAr' => 'شباب ديرة لخدمات الملاحة والشحن ش.ذ.م.م',
                'subEn' => 'Official Treasury & Finance Operations',
                'trn' => '100592837400003',
                'license' => 'CN-829104',
                'address' => 'Office M33, Alphamed Bldg, Abu Hail, Hor Al Anz East, Dubai',
                'phone' => '+971 56 135 9305',
                'email' => 'treasury@shababdeira.com',
                'web' => 'https://shababdeira.com'
            ],
            'metaFields' => [
                ['label' => 'Receipt No', 'value' => 'SD-REC-2026-118', 'key' => 'doc_no'],
                ['label' => 'Receipt Date', 'value' => $today, 'key' => 'doc_date'],
                ['label' => 'Received From', 'value' => 'DP World Logistics FZE', 'key' => 'received_from'],
                ['label' => 'Payment Mode', 'value' => 'Direct Bank Wire (ENBD Transfer)', 'key' => 'payment_mode'],
                ['label' => 'Bank Reference #', 'value' => 'ENBD-FT-829104829', 'key' => 'ref_no'],
                ['label' => 'Against Invoice #', 'value' => 'SD-MSI-2026-041', 'key' => 'inv_ref']
            ],
            'client' => [
                'name' => 'DP World Logistics FZE',
                'attention' => 'Accounts Payable Department',
                'address' => 'Jebel Ali Free Zone, Dubai, UAE',
                'trn' => '100349281000003',
                'phone' => '+971 4 881 2222',
                'email' => 'accounts@dpworldlogistics.ae'
            ],
            'columns' => [
                ['key' => 'sr', 'title' => 'Sr', 'width' => '45px', 'align' => 'center', 'type' => 'number'],
                ['key' => 'desc', 'title' => 'Payment Settlement Particulars', 'width' => 'auto', 'align' => 'left', 'type' => 'text'],
                ['key' => 'inv_no', 'title' => 'Settled Invoice', 'width' => '150px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'inv_date', 'title' => 'Invoice Date', 'width' => '120px', 'align' => 'center', 'type' => 'text'],
                ['key' => 'amount', 'title' => 'Amount Paid (AED)', 'width' => '150px', 'align' => 'right', 'type' => 'total']
            ],
            'items' => [
                ['sr' => 1, 'desc' => 'Full Payment for Manpower Supply Services for JAFZA South Logistics Hub', 'inv_no' => 'SD-MSI-2026-041', 'inv_date' => '01/10/2026', 'amount' => 106050.00]
            ],
            'notes' => "Amount Received in Words: One Hundred Six Thousand and Fifty UAE Dirhams Only.\nReceipt is valid subject to realization of funds in Shabab Deira corporate account.",
            'signatures' => [
                ['title' => 'Received By (Accounts)', 'name' => 'Farhan Siddiqui', 'role' => 'Treasury Officer'],
                ['title' => 'Finance Director', 'name' => 'Rashid Al Nuaimi', 'role' => 'Managing Director'],
                ['title' => 'Official Seal & Stamp', 'name' => 'Shabab Deira LLC', 'role' => 'Dubai Head Office']
            ]
        ]
    ];
}
