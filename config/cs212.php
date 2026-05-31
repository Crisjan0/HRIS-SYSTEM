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
        'surname' => ['x' => 72, 'y' => 104, 'w' => 72],
        'first_name' => ['x' => 147, 'y' => 104, 'w' => 72],
        'name_extension' => ['x' => 222, 'y' => 104, 'w' => 72],
        'middle_name' => ['x' => 72, 'y' => 122, 'w' => 222],

        // Field 3–4
        'date_of_birth' => ['x' => 72, 'y' => 158, 'w' => 95],
        'place_of_birth' => ['x' => 172, 'y' => 158, 'w' => 122],

        // Field 5–6 (sex / civil status — text beside checkboxes)
        'sex' => ['x' => 72, 'y' => 198, 'w' => 55],
        'civil_status' => ['x' => 132, 'y' => 198, 'w' => 160],

        // Field 7–9
        'height' => ['x' => 72, 'y' => 258, 'w' => 48],
        'weight' => ['x' => 125, 'y' => 258, 'w' => 48],
        'blood_type' => ['x' => 178, 'y' => 258, 'w' => 48],

        // Field 10–15 (government IDs — left panel, 3 columns × 2 rows)
        'gsis' => ['x' => 72, 'y' => 288, 'w' => 70],
        'pagibig' => ['x' => 147, 'y' => 288, 'w' => 70],
        'philhealth' => ['x' => 222, 'y' => 288, 'w' => 70],
        'sss' => ['x' => 72, 'y' => 306, 'w' => 70],
        'tin' => ['x' => 147, 'y' => 306, 'w' => 70],
        'agency_employee' => ['x' => 222, 'y' => 306, 'w' => 70],

        // Field 16 — citizenship (right panel)
        'citizenship' => ['x' => 308, 'y' => 288, 'w' => 272],

        // Field 17 — residential address (right panel, 4 columns × 2 rows)
        'res_house' => ['x' => 308, 'y' => 328, 'w' => 65],
        'res_street' => ['x' => 376, 'y' => 328, 'w' => 65],
        'res_subd' => ['x' => 444, 'y' => 328, 'w' => 65],
        'res_brgy' => ['x' => 512, 'y' => 328, 'w' => 68],
        'res_city' => ['x' => 308, 'y' => 346, 'w' => 65],
        'res_province' => ['x' => 376, 'y' => 346, 'w' => 65],
        'res_zip' => ['x' => 512, 'y' => 346, 'w' => 68],

        // Field 18 — permanent address
        'perm_house' => ['x' => 308, 'y' => 386, 'w' => 65],
        'perm_street' => ['x' => 376, 'y' => 386, 'w' => 65],
        'perm_subd' => ['x' => 444, 'y' => 386, 'w' => 65],
        'perm_brgy' => ['x' => 512, 'y' => 386, 'w' => 68],
        'perm_city' => ['x' => 308, 'y' => 404, 'w' => 65],
        'perm_province' => ['x' => 376, 'y' => 404, 'w' => 65],
        'perm_zip' => ['x' => 512, 'y' => 404, 'w' => 68],

        // Field 19–21
        'telephone' => ['x' => 72, 'y' => 444, 'w' => 70],
        'mobile' => ['x' => 147, 'y' => 444, 'w' => 70],
        'email' => ['x' => 308, 'y' => 444, 'w' => 272],

        // Section II — family (left panel)
        'spouse_surname' => ['x' => 72, 'y' => 472, 'w' => 222],
        'spouse_first' => ['x' => 72, 'y' => 490, 'w' => 105],
        'spouse_middle' => ['x' => 182, 'y' => 490, 'w' => 112],
        'spouse_occupation' => ['x' => 72, 'y' => 508, 'w' => 105],
        'spouse_employer' => ['x' => 182, 'y' => 508, 'w' => 112],
        'spouse_tel' => ['x' => 72, 'y' => 526, 'w' => 222],

        'father_surname' => ['x' => 72, 'y' => 554, 'w' => 70],
        'father_first' => ['x' => 147, 'y' => 554, 'w' => 70],
        'father_middle' => ['x' => 222, 'y' => 554, 'w' => 70],
        'mother_surname' => ['x' => 72, 'y' => 572, 'w' => 70],
        'mother_first' => ['x' => 147, 'y' => 572, 'w' => 70],
        'mother_middle' => ['x' => 222, 'y' => 572, 'w' => 70],

        // Children table (right panel)
        'children' => ['x' => 308, 'y' => 472, 'w' => 272, 'row_h' => 18, 'max' => 8],

        // Section III — education (do not write level labels; pre-printed on form)
        'education' => [
            'y' => 738,
            'row_h' => 19,
            'max' => 5,
            'cols' => [
                'school' => 125,
                'course' => 190,
                'from' => 218,
                'to' => 238,
                'units' => 259,
                'year' => 281,
                'honors' => 308,
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
