<?php

namespace App\Http\Controllers;

use App\Services\PdsPdfExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PdsController extends Controller
{
    /**
     * Display current PDS status.
     */
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

    /**
     * Download PDS as PDF (CS Form No. 212 Revised 2017 format).
     */
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
                ->with('error', 'Could not generate PDF. Run: composer require barryvdh/laravel-dompdf â€” '.$e->getMessage());
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

    /**
     * Show the multi-step form for editing PDS.
     */
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

        return view('pds.edit', compact('employee'));
    }

    /**
     * Update/Save the PDS data.
     */
    public function update(Request $request): RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(404);
        }

        // Validation for simple 1:1 pieces
        $validated = $request->validate([
            // Personal Info
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
            // Addresses
            'personal.res_house_no' => 'nullable|string',
            'personal.res_street' => 'nullable|string',
            'personal.res_subdivision' => 'nullable|string',
            'personal.res_barangay' => 'nullable|string',
            'personal.res_city' => 'nullable|string',
            'personal.res_province' => 'nullable|string',
            'personal.res_zip_code' => 'nullable|string',

            'personal.perm_house_no' => 'nullable|string',
            'personal.perm_street' => 'nullable|string',
            'personal.perm_subdivision' => 'nullable|string',
            'personal.perm_barangay' => 'nullable|string',
            'personal.perm_city' => 'nullable|string',
            'personal.perm_province' => 'nullable|string',
            'personal.perm_zip_code' => 'nullable|string',

            'personal.telephone_no' => 'nullable|string',
            'personal.mobile_no' => 'nullable|string',
            'personal.email_address' => 'nullable|email',

            // Family
            'family.spouse_surname' => 'nullable|string',
            'family.spouse_firstname' => 'nullable|string',
            'family.father_surname' => 'nullable|string',
            'family.mother_maiden_surname' => 'nullable|string',

            // Multi-row handling (All made nullable to allow partial saves)
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

            // Questionnaire...
            'questionnaire' => 'nullable|array',
            'gov_id.id_type' => 'nullable|string',
            'gov_id.id_no' => 'nullable|string',
            'gov_id.date_place_issuance' => 'nullable|string',
            'pds_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pds_signature' => 'nullable|image|mimes:png|max:2048',
        ]);

        DB::transaction(function () use ($employee, $request) {
            // Update Personal
            $employee->pdsPersonal()->updateOrCreate(
                ['employee_id' => $employee->id],
                $request->input('personal', [])
            );

            // Update Family
            $employee->pdsFamily()->updateOrCreate(
                ['employee_id' => $employee->id],
                $request->input('family', [])
            );

            // Update Questionnaire
            $employee->pdsQuestionnaire()->updateOrCreate(
                ['employee_id' => $employee->id],
                $request->input('questionnaire', [])
            );

            // Update official PDS photo without changing the printed photo-box dimensions.
            if ($request->hasFile('pds_photo')) {
                if ($employee->profile_picture) {
                    Storage::disk('public_uploads')->delete($employee->profile_picture);
                    Storage::disk('public')->delete($employee->profile_picture);
                }

                $employee->update([
                    'profile_picture' => $request->file('pds_photo')->store('profile_pictures', 'public_uploads'),
                ]);
            }

            // Update Gov ID and signature asset used by the printable CS Form 212 page 4.
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

            $employee->pdsGovId()->updateOrCreate(
                ['employee_id' => $employee->id],
                $govIdData
            );

            // Handle Children (Sync)
            $employee->pdsChildren()->delete();
            if ($request->has('children')) {
                foreach ($request->children as $child) {
                    if (! empty($child['fullname'])) {
                        $employee->pdsChildren()->create($child);
                    }
                }
            }

            // Handle Education (Sync)
            $employee->pdsEducation()->delete();
            if ($request->has('education')) {
                foreach ($request->education as $edu) {
                    if (! empty($edu['school_name'])) {
                        $employee->pdsEducation()->create($edu);
                    }
                }
            }

            // Handle Eligibilies (Sync)
            $employee->pdsEligibilities()->delete();
            if ($request->has('eligibility')) {
                foreach ($request->eligibility as $eli) {
                    if (! empty($eli['title'])) {
                        $employee->pdsEligibilities()->create($eli);
                    }
                }
            }

            // Handle Work Exp (Sync)
            $employee->pdsWorkExperiences()->delete();
            if ($request->has('work_experience')) {
                foreach ($request->work_experience as $work) {
                    if (! empty($work['position_title'])) {
                        $employee->pdsWorkExperiences()->create($work);
                    }
                }
            }

            // Handle Training (Sync)
            $employee->pdsTrainings()->delete();
            if ($request->has('training')) {
                foreach ($request->training as $index => $train) {
                    if (! empty($train['title'])) {
                        // Change: training "Hours" was replaced by an attachment upload in the PDS form.
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
            }

            // Handle Voluntary Work (Sync)
            $employee->pdsVoluntaryWorks()->delete();
            if ($request->has('voluntary')) {
                foreach ($request->voluntary as $vol) {
                    if (! empty($vol['organization_name'])) {
                        $employee->pdsVoluntaryWorks()->create($vol);
                    }
                }
            }

            // Handle Others (Sync)
            $employee->pdsOthers()->delete();
            if ($request->has('others')) {
                foreach ($request->others as $other) {
                    if (! empty($other['description'])) {
                        $employee->pdsOthers()->create($other);
                    }
                }
            }

            // Handle References (Sync)
            $employee->pdsReferences()->delete();
            if ($request->has('references')) {
                foreach ($request->references as $ref) {
                    if (! empty($ref['name'])) {
                        $employee->pdsReferences()->create($ref);
                    }
                }
            }
        });

        return redirect()->route('pds.index')->with('success', 'Personal Data Sheet successfully updated.');
    }
}

