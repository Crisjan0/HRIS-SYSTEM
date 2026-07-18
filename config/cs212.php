<?php

/**
 * CS Form 212 (Revised 2017) overlay positions — points, origin top-left.
 * Calibrated from public/images/pds/pds-page-*.png (1275×2100 px → 612×1008 pt).
 *
 * Layout: left data panel ≈ 72–298 pt, right data panel ≈ 305–585 pt.
 */
return [
    'page_size' => [612, 1008],
    'font_size' => 6.5,
    'row_height' => 18,

    'page1' => [
        // Field 2 — name (left panel, three columns)
        'surname' => ['x' => 124, 'y' => 122, 'w' => 462],
        'first_name' => ['x' => 124, 'y' => 141, 'w' => 375],
        'name_extension' => ['x' => 503, 'y' => 141, 'w' => 83],
        'middle_name' => ['x' => 124, 'y' => 160, 'w' => 462],

        // Field 3–4
        'date_of_birth' => ['x' => 124, 'y' => 184, 'w' => 105],
        'place_of_birth' => ['x' => 124, 'y' => 251, 'w' => 105],

        // Field 5–6 (sex / civil status — text beside checkboxes)
        'sex' => ['x' => 124, 'y' => 290, 'w' => 105],
        'civil_status' => ['x' => 124, 'y' => 310, 'w' => 105],

        // Field 7–9
        'height' => ['x' => 124, 'y' => 348, 'w' => 105],
        'weight' => ['x' => 124, 'y' => 369, 'w' => 105],
        'blood_type' => ['x' => 124, 'y' => 390, 'w' => 105],

        // Field 10–15 (government IDs — left panel, 3 columns × 2 rows)
        'gsis' => ['x' => 124, 'y' => 407, 'w' => 105],
        'pagibig' => ['x' => 124, 'y' => 424, 'w' => 105],
        'philhealth' => ['x' => 124, 'y' => 441, 'w' => 105],
        'sss' => ['x' => 124, 'y' => 458, 'w' => 105],
        'tin' => ['x' => 124, 'y' => 473, 'w' => 105],
        'agency_employee' => ['x' => 124, 'y' => 491, 'w' => 105],

        // Field 16 — citizenship (right panel)
        'citizenship' => ['x' => 368, 'y' => 184, 'w' => 205],

        // Field 17 — residential address (right panel, 4 columns × 2 rows)
        'res_house' => ['x' => 330, 'y' => 259, 'w' => 125],
        'res_street' => ['x' => 459, 'y' => 259, 'w' => 125],
        'res_subd' => ['x' => 330, 'y' => 280, 'w' => 125],
        'res_brgy' => ['x' => 459, 'y' => 280, 'w' => 125],
        'res_city' => ['x' => 330, 'y' => 301, 'w' => 125],
        'res_province' => ['x' => 459, 'y' => 301, 'w' => 125],
        'res_zip' => ['x' => 264, 'y' => 301, 'w' => 50],

        // Field 18 — permanent address
        'perm_house' => ['x' => 330, 'y' => 344, 'w' => 125],
        'perm_street' => ['x' => 459, 'y' => 344, 'w' => 125],
        'perm_subd' => ['x' => 330, 'y' => 365, 'w' => 125],
        'perm_brgy' => ['x' => 459, 'y' => 365, 'w' => 125],
        'perm_city' => ['x' => 330, 'y' => 386, 'w' => 125],
        'perm_province' => ['x' => 459, 'y' => 386, 'w' => 125],
        'perm_zip' => ['x' => 264, 'y' => 386, 'w' => 50],

        // Field 19–21
        'telephone' => ['x' => 330, 'y' => 410, 'w' => 254],
        'mobile' => ['x' => 330, 'y' => 426, 'w' => 254],
        'email' => ['x' => 330, 'y' => 443, 'w' => 254],

        // Section II — family (left panel)
        'spouse_surname' => ['x' => 124, 'y' => 523, 'w' => 200],
        'spouse_first' => ['x' => 124, 'y' => 541, 'w' => 105],
        'spouse_middle' => ['x' => 124, 'y' => 559, 'w' => 200],
        'spouse_occupation' => ['x' => 124, 'y' => 576, 'w' => 200],
        'spouse_employer' => ['x' => 124, 'y' => 593, 'w' => 200],
        'spouse_tel' => ['x' => 124, 'y' => 628, 'w' => 200],

        'father_surname' => ['x' => 124, 'y' => 646, 'w' => 105],
        'father_first' => ['x' => 124, 'y' => 663, 'w' => 105],
        'father_middle' => ['x' => 124, 'y' => 681, 'w' => 200],
        'mother_surname' => ['x' => 124, 'y' => 716, 'w' => 200],
        'mother_first' => ['x' => 124, 'y' => 734, 'w' => 200],
        'mother_middle' => ['x' => 124, 'y' => 747, 'w' => 200],

        // Children table (right panel)
        'children' => ['x' => 330, 'y' => 523, 'w' => 254, 'row_h' => 18, 'max' => 8],

        // Section III — education (do not write level labels; pre-printed on form)
        'education' => [
            'y' => 850,
            'row_h' => 18,
            'max' => 5,
            'cols' => [
                'school' => 122,
                'course' => 236,
                'from' => 368,
                'to' => 422,
                'units' => 462,
                'year' => 503,
                'honors' => 550,
            ],
        ],
    ],

    'page2' => [
        'eligibility' => [
            'y' => 88,
            'row_h' => 19,
            'max' => 7,
            'cols' => [
                'title' => 18,
                'rating' => 108,
                'date' => 142,
                'place' => 178,
                'number' => 258,
                'validity' => 285,
            ],
        ],
        'work' => [
            'y' => 275,
            'row_h' => 19,
            'max' => 28,
            'cols' => [
                'from' => 18,
                'to' => 40,
                'position' => 64,
                'company' => 142,
                'salary' => 221,
                'grade' => 237,
                'status' => 258,
                'gov' => 285,
            ],
        ],
    ],

    'page3' => [
        'voluntary' => [
            'y' => 88,
            'row_h' => 19,
            'max' => 7,
            'cols' => [
                'org' => 32,
                'from' => 121,
                'to' => 148,
                'hours' => 174,
                'position' => 201,
            ],
        ],
        'training' => [
            'y' => 275,
            'row_h' => 19,
            'max' => 21,
            'cols' => [
                'title' => 32,
                'from' => 121,
                'to' => 148,
                'hours' => 174,
                'type' => 201,
                'by' => 231,
            ],
        ],
        'other_skills' => ['x' => 32, 'y' => 674, 'w' => 190, 'h' => 120],
        'other_distinctions' => ['x' => 232, 'y' => 674, 'w' => 190, 'h' => 120],
        'other_membership' => ['x' => 432, 'y' => 674, 'w' => 140, 'h' => 120],
    ],

    'page4' => [
        'questions' => ['x' => 363, 'y' => 168, 'row_h' => 22, 'max' => 12],
        'references' => [
            'y' => 548,
            'row_h' => 19,
            'max' => 3,
            'cols' => [
                'name' => 20,
                'address' => 133,
                'tel' => 241,
            ],
        ],
        'gov_id_type' => ['x' => 20, 'y' => 728, 'w' => 200],
        'gov_id_no' => ['x' => 20, 'y' => 748, 'w' => 200],
        'gov_id_issuance' => ['x' => 20, 'y' => 768, 'w' => 200],
        'date_accomplished' => ['x' => 20, 'y' => 800, 'w' => 120],
        'photo' => ['x' => 363, 'y' => 468, 'w' => 95, 'h' => 115],
        'thumbmark' => ['x' => 363, 'y' => 588, 'w' => 95, 'h' => 55],
    ],
];
