<?php

namespace App\Http\Controllers;

use App\Models\UtilityOption;
use App\Services\PdsPdfExporter;
use App\Support\UtilityOptionRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PdsController extends Controller
{
    public function index(): View
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'User is not linked to an employee record.');
        }

        $employee->load([
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation',
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks',
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId',
            'pdsSectionReviews',
        ]);

        return view('pds.index', compact('employee'));
    }

    public function download(Request $request, PdsPdfExporter $exporter): Response|RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(404);
        }

        try {
            return $exporter->download($employee, $request->boolean('print') ? 'inline' : 'attachment');
        } catch (\Throwable $e) {
            return redirect()
                ->route('pds.index')
                ->with('error', 'Could not generate PDF. Run: composer require barryvdh/laravel-dompdf - '.$e->getMessage());
        }
    }

    public function print(Request $request): View|RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(404);
        }

        $employee->load([
            'user',
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation',
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks',
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId',
        ]);

        return view('pds.print-2025', compact('employee'));
    }

    public function printClean(Request $request): View|RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(404);
        }

        $employee->load([
            'user',
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation',
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks',
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId',
        ]);

        return view('pds.print-clean-2025', compact('employee'));
    }

    public function edit(): View
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(404, 'Employee record not found for this user.');
        }

        $employee->load([
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation',
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks',
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId',
            'pdsSectionReviews',
        ]);

        UtilityOptionRegistry::ensureDefaults();

        $utilityOptionSets = [
            'name_extensions' => UtilityOption::listFor('name_extensions', null, $employee->pdsPersonal?->name_extension),
            'civil_statuses' => UtilityOption::listFor('civil_statuses', null, $employee->pdsPersonal?->civil_status),
            'blood_types' => UtilityOption::listFor('blood_types', null, $employee->pdsPersonal?->blood_type),
            'citizenship_types' => UtilityOption::listFor('citizenship_types', null, $employee->pdsPersonal?->citizenship_type),
            'countries' => UtilityOption::listFor('countries', null, $employee->pdsPersonal?->citizenship_country),
            'education_levels' => UtilityOption::listFor('education_levels'),
            'highest_levels' => UtilityOption::listFor('highest_levels'),
            'eligibility_titles' => UtilityOption::listFor('eligibility_titles'),
            'salary_grades' => UtilityOption::listFor('salary_grades'),
            'step_increments' => UtilityOption::listFor('step_increments'),
            'appointment_statuses' => UtilityOption::listFor('appointment_statuses'),
            'government_service_options' => UtilityOption::listFor('government_service_options'),
            'government_id_types' => UtilityOption::listFor('government_id_types', null, $employee->pdsGovId?->id_type),
        ];

        $locationOptionSets = [
            'countries' => UtilityOption::listFor('countries', null, $employee->pdsPersonal?->citizenship_country)->values(),
            'ph_regions' => UtilityOption::query()->active()->forGroup('ph_regions')->orderBy('sort_order')->orderBy('label')->get(['label', 'value', 'parent_value']),
            'ph_provinces' => UtilityOption::query()->active()->forGroup('ph_provinces')->orderBy('sort_order')->orderBy('label')->get(['label', 'value', 'parent_value']),
            'ph_cities' => UtilityOption::query()->active()->forGroup('ph_cities')->orderBy('sort_order')->orderBy('label')->get(['label', 'value', 'parent_value']),
            'ph_barangays' => UtilityOption::query()->active()->forGroup('ph_barangays')->orderBy('sort_order')->orderBy('label')->get(['label', 'value', 'parent_value']),
        ];

        $cityZipCodes = $this->cityZipCodes();

        return view('pds.edit', compact('employee', 'utilityOptionSets', 'locationOptionSets', 'cityZipCodes'));
    }

    public function update(Request $request): RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(404);
        }

        $personal = $request->input('personal', []);
        if (! empty($personal['mobile_no'])) {
            $digits = substr(preg_replace('/\D/', '', (string) $personal['mobile_no']), 0, 11);
            $personal['mobile_no'] = strlen($digits) === 11
                ? substr($digits, 0, 4).'-'.substr($digits, 4, 3).'-'.substr($digits, 7, 4)
                : $personal['mobile_no'];
            $request->merge(['personal' => $personal]);
        }

        $request->validate([
            'personal.surname' => 'nullable|string',
            'personal.firstname' => 'nullable|string',
            'personal.middlename' => 'nullable|string',
            'personal.name_extension' => 'nullable|string',
            'personal.date_of_birth' => 'nullable|date',
            'personal.place_of_birth' => 'nullable|string',
            'personal.sex' => 'nullable|string',
            'personal.civil_status' => 'nullable|string',
            'personal.height_m' => 'nullable|string',
            'personal.weight_kg' => 'nullable|string',
            'personal.blood_type' => 'nullable|string',
            'personal.gsis_id_no' => 'nullable|string',
            'personal.pagibig_id_no' => 'nullable|string',
            'personal.philhealth_no' => 'nullable|string',
            'personal.sss_no' => 'nullable|string',
            'personal.tin_no' => 'nullable|string',
            'personal.agency_employee_no' => 'nullable|string',
            'personal.umid_no' => 'nullable|string',
            'personal.philsys_no' => 'nullable|string',
            'personal.citizenship' => 'nullable|string',
            'personal.citizenship_type' => 'nullable|string',
            'personal.citizenship_country' => 'nullable|string',
            'personal.res_house_no' => 'nullable|string',
            'personal.res_street' => 'nullable|string',
            'personal.res_subdivision' => 'nullable|string',
            'personal.res_region' => 'nullable|string',
            'personal.res_barangay' => 'nullable|string',
            'personal.res_city' => 'nullable|string',
            'personal.res_province' => 'nullable|string',
            'personal.res_zip_code' => 'nullable|string',
            'personal.perm_house_no' => 'nullable|string',
            'personal.perm_street' => 'nullable|string',
            'personal.perm_subdivision' => 'nullable|string',
            'personal.perm_region' => 'nullable|string',
            'personal.perm_barangay' => 'nullable|string',
            'personal.perm_city' => 'nullable|string',
            'personal.perm_province' => 'nullable|string',
            'personal.perm_zip_code' => 'nullable|string',
            'personal.telephone_no' => 'nullable|string',
            'personal.mobile_no' => ['nullable', 'regex:/^09\d{2}-\d{3}-\d{4}$/'],
            'personal.email_address' => 'nullable|email',
            'family.spouse_surname' => 'nullable|string',
            'family.spouse_firstname' => 'nullable|string',
            'family.spouse_extension' => 'nullable|string',
            'family.father_surname' => 'nullable|string',
            'family.mother_maiden_surname' => 'nullable|string',
            'children.*.fullname' => 'nullable|string',
            'children.*.date_of_birth' => 'nullable|date',
            'education.*.level' => 'nullable|string',
            'education.*.school_name' => 'nullable|string',
            'eligibility.*.title' => 'nullable|string',
            'work_experience.*.position_title' => 'nullable|string',
            'work_experience.*.company' => 'nullable|string',
            'training.*.title' => 'nullable|string',
            'training.*.attachment' => 'nullable|file|mimes:pdf|max:5120',
            'others.*.type' => 'nullable|in:Skill,Distinction,Membership',
            'others.*.description' => 'nullable|string',
            'references.*.name' => 'nullable|string',
            'references.*.telephone_no' => 'nullable|string',
            'questionnaire' => 'nullable|array',
            'gov_id.id_type' => 'nullable|string',
            'gov_id.id_no' => 'nullable|string',
            'gov_id.date_place_issuance' => 'nullable|string',
            'pds_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pds_signature' => 'nullable|image|mimes:png|max:2048',
        ]);

        DB::transaction(function () use ($employee, $request) {
            $employee->pdsPersonal()->updateOrCreate(['employee_id' => $employee->id], $request->input('personal', []));
            $employee->pdsFamily()->updateOrCreate(['employee_id' => $employee->id], $request->input('family', []));
            $employee->pdsQuestionnaire()->updateOrCreate(['employee_id' => $employee->id], $request->input('questionnaire', []));

            if ($request->hasFile('pds_photo')) {
                if ($employee->profile_picture) {
                    Storage::disk('public_uploads')->delete($employee->profile_picture);
                    Storage::disk('public')->delete($employee->profile_picture);
                }

                $employee->update([
                    'profile_picture' => $request->file('pds_photo')->store('profile_pictures', 'public_uploads'),
                ]);
            }

            $govIdData = $request->input('gov_id', []);
            if ($request->hasFile('pds_signature')) {
                $existingSignature = $employee->pdsGovId?->signature_path;
                if ($existingSignature && $existingSignature !== $employee->e_signature_path) {
                    Storage::disk('public')->delete($existingSignature);
                }
                if ($employee->e_signature_path) {
                    Storage::disk('public')->delete($employee->e_signature_path);
                }

                $signaturePath = $request->file('pds_signature')->store('employee-signatures', 'public');
                $govIdData['signature_path'] = $signaturePath;
                $employee->update(['e_signature_path' => $signaturePath]);
            }

            $employee->pdsGovId()->updateOrCreate(['employee_id' => $employee->id], $govIdData);

            $employee->pdsChildren()->delete();
            foreach ($request->input('children', []) as $child) {
                if (! empty($child['fullname'])) {
                    $employee->pdsChildren()->create($child);
                }
            }

            $employee->pdsEducation()->delete();
            foreach ($request->input('education', []) as $edu) {
                if (! empty($edu['school_name'])) {
                    $employee->pdsEducation()->create($edu);
                }
            }

            $employee->pdsEligibilities()->delete();
            foreach ($request->input('eligibility', []) as $eli) {
                if (! empty($eli['title'])) {
                    $employee->pdsEligibilities()->create($eli);
                }
            }

            $employee->pdsWorkExperiences()->delete();
            foreach ($request->input('work_experience', []) as $work) {
                if (! empty($work['position_title'])) {
                    $employee->pdsWorkExperiences()->create($work);
                }
            }

            $employee->pdsTrainings()->delete();
            foreach ($request->input('training', []) as $index => $train) {
                if (! empty($train['title'])) {
                    if ($request->hasFile("training.$index.attachment")) {
                        if (! empty($train['existing_attachment_path'])) {
                            Storage::disk('public')->delete($train['existing_attachment_path']);
                        }

                        $train['attachment_path'] = $request->file("training.$index.attachment")->store('pds-training-attachments', 'public');
                    } elseif (! empty($train['existing_attachment_path'])) {
                        $train['attachment_path'] = $train['existing_attachment_path'];
                    }

                    unset($train['attachment'], $train['existing_attachment_path']);

                    $employee->pdsTrainings()->create($train);
                }
            }

            $employee->pdsVoluntaryWorks()->delete();
            foreach ($request->input('voluntary', []) as $vol) {
                if (! empty($vol['organization_name'])) {
                    $employee->pdsVoluntaryWorks()->create($vol);
                }
            }

            $employee->pdsOthers()->delete();
            foreach ($request->input('others', []) as $other) {
                if (! empty($other['description'])) {
                    $employee->pdsOthers()->create($other);
                }
            }

            $employee->pdsReferences()->delete();
            foreach ($request->input('references', []) as $ref) {
                if (! empty($ref['name'])) {
                    $employee->pdsReferences()->create($ref);
                }
            }
        });

        return redirect()->route('pds.edit')->with('success', 'Personal Data Sheet successfully updated.');
    }

    private function cityZipCodes(): array
    {
        return [
            'Davao De Oro|Compostela' => '6003',
            'Davao De Oro|Mabini' => '4202',
            'Davao De Oro|Maco' => '8114',
            'Davao De Oro|Mawab' => '8108',
            'Davao De Oro|Monkayo' => '8111',
            'Davao De Oro|Montevista' => '8107',
            'Davao De Oro|Nabunturan' => '8800',
            'Davao De Oro|New Bataan' => '8110',
            'Davao De Oro|Pantukan' => '8117',
            'Davao Del Norte|Asuncion' => '8102',
            'Davao Del Norte|Carmen' => '8101',
            'Davao Del Norte|City of Panabo' => '8105',
            'Davao Del Norte|City of Tagum' => '8100',
            'Davao Del Norte|Island Garden City of Samal' => '8119',
            'Davao Del Norte|Kapalong' => '8113',
            'Davao Del Norte|New Corella' => '8104',
            'Davao Del Norte|San Isidro' => '2809',
            'Davao Del Norte|Santo Tomas' => '8112',
            'Davao Del Sur|Bansalan' => '8005',
            'Davao Del Sur|City of Davao' => '8000',
            'Davao Del Sur|City of Digos' => '8002',
            'Davao Del Sur|Hagonoy' => '8006',
            'Davao Del Sur|Kiblawan' => '8008',
            'Davao Del Sur|Magsaysay' => '8004',
            'Davao Del Sur|Malalag' => '8010',
            'Davao Del Sur|Matanao' => '8003',
            'Davao Del Sur|Padada' => '8007',
            'Davao Del Sur|Santa Cruz' => '8001',
            'Davao Del Sur|Sulop' => '8009',
            'Davao Occidental|Don Marcelino' => '8013',
            'Davao Occidental|Jose Abad Santos' => '8014',
            'Davao Occidental|Malita' => '8012',
            'Davao Occidental|Santa Maria' => '3022',
            'Davao Occidental|Sarangani' => '8015',
            'Davao Oriental|Baganga' => '8204',
            'Davao Oriental|Banaybanay' => '8208',
            'Davao Oriental|Boston' => '8206',
            'Davao Oriental|Caraga' => '8203',
            'Davao Oriental|Cateel' => '8205',
            'Davao Oriental|City of Mati' => '8200',
            'Davao Oriental|Lupon' => '8207',
            'Davao Oriental|Manay' => '8202',
            'Davao Oriental|San Isidro' => '8209',
            'Davao Oriental|Tarragona' => '8201',
        ];
    }
}
