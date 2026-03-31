<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PdsPersonalInformation;
use App\Models\PdsFamilyBackground;
use App\Models\PdsChild;
use App\Models\PdsEducation;
use App\Models\PdsEligibility;
use App\Models\PdsWorkExperience;
use App\Models\PdsVoluntaryWork;
use App\Models\PdsTraining;
use App\Models\PdsOtherInfo;
use App\Models\PdsQuestionnaire;
use App\Models\PdsReference;
use App\Models\PdsGovernmentId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PdsController extends Controller
{
    /**
     * Display current PDS status.
     */
    public function index(): View
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            abort(403, 'User is not linked to an employee record.');
        }

        $employee->load([
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation', 
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks', 
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId'
        ]);

        return view('pds.index', compact('employee'));
    }

    /**
     * Show the multi-step form for editing PDS.
     */
    public function edit(): View
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            abort(404, 'Employee record not found for this user.');
        }

        $employee->load([
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation', 
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks', 
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId'
        ]);

        return view('pds.edit', compact('employee'));
    }

    /**
     * Update/Save the PDS data.
     */
    public function update(Request $request): RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            abort(404);
        }

        // Validation for simple 1:1 pieces
        $validated = $request->validate([
            // Personal Info
            'personal.date_of_birth' => 'nullable|date',
            'personal.place_of_birth' => 'nullable|string',
            'personal.sex' => 'nullable|in:Male,Female',
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
            'personal.citizenship' => 'nullable|string',
            'personal.citizenship_type' => 'nullable|string',
            'personal.country' => 'nullable|string',
            // Addresses ... (omitted some for brevity, but I will put them all)
            'personal.res_house_no' => 'nullable|string',
            'personal.res_street' => 'nullable|string',
            'personal.res_barangay' => 'nullable|string',
            'personal.res_city' => 'nullable|string',
            'personal.perm_house_no' => 'nullable|string',
            'personal.perm_street' => 'nullable|string',
            'personal.perm_barangay' => 'nullable|string',
            'personal.perm_city' => 'nullable|string',
            'personal.mobile_no' => 'nullable|string',
            'personal.email_address' => 'nullable|email',

            // Family
            'family.spouse_surname' => 'nullable|string',
            'family.spouse_firstname' => 'nullable|string',
            'family.father_surname' => 'nullable|string',
            'family.mother_maiden_surname' => 'nullable|string',
            
            // Multi-row handling
            'children.*.fullname' => 'required_with:children.*.date_of_birth|string',
            'children.*.date_of_birth' => 'required_with:children.*.fullname|date',

            'education.*.level' => 'required|string',
            'education.*.school_name' => 'required|string',

            'eligibility.*.title' => 'required|string',

            'work_experience.*.position_title' => 'required|string',
            'work_experience.*.company' => 'required|string',
            
            'training.*.title' => 'required|string',

            'others.*.type' => 'required|in:Skill,Distinction,Membership',
            'others.*.description' => 'required|string',

            'references.*.name' => 'required|string',
            'references.*.telephone_no' => 'required|string',

            // Questionnaire...
            'questionnaire' => 'nullable|array',
            'gov_id.id_type' => 'nullable|string',
            'gov_id.id_no' => 'nullable|string',
        ]);

        DB::transaction(function () use ($employee, $request) {
            // Update Personal
            $employee->pdsPersonal()->updateOrCreate([], $request->input('personal', []));

            // Update Family
            $employee->pdsFamily()->updateOrCreate([], $request->input('family', []));

            // Update Questionnaire
            $employee->pdsQuestionnaire()->updateOrCreate([], $request->input('questionnaire', []));

            // Update Gov ID
            $employee->pdsGovId()->updateOrCreate([], $request->input('gov_id', []));

            // Handle Children (Sync)
            $employee->pdsChildren()->delete();
            if ($request->has('children')) {
                foreach ($request->children as $child) {
                    if (!empty($child['fullname'])) {
                        $employee->pdsChildren()->create($child);
                    }
                }
            }

            // Handle Education (Sync)
            $employee->pdsEducation()->delete();
            if ($request->has('education')) {
                foreach ($request->education as $edu) {
                    if (!empty($edu['school_name'])) {
                        $employee->pdsEducation()->create($edu);
                    }
                }
            }

            // Handle Eligibilies (Sync)
            $employee->pdsEligibilities()->delete();
            if ($request->has('eligibility')) {
                foreach ($request->eligibility as $eli) {
                    if (!empty($eli['title'])) {
                        $employee->pdsEligibilities()->create($eli);
                    }
                }
            }

            // Handle Work Exp (Sync)
            $employee->pdsWorkExperiences()->delete();
            if ($request->has('work_experience')) {
                foreach ($request->work_experience as $work) {
                    if (!empty($work['position_title'])) {
                        $employee->pdsWorkExperiences()->create($work);
                    }
                }
            }

            // Handle Training (Sync)
            $employee->pdsTrainings()->delete();
            if ($request->has('training')) {
                foreach ($request->training as $train) {
                    if (!empty($train['title'])) {
                        $employee->pdsTrainings()->create($train);
                    }
                }
            }

            // Handle Voluntary Work (Sync)
            $employee->pdsVoluntaryWorks()->delete();
            if ($request->has('voluntary')) {
                foreach ($request->voluntary as $vol) {
                    if (!empty($vol['organization_name'])) {
                        $employee->pdsVoluntaryWorks()->create($vol);
                    }
                }
            }

            // Handle Others (Sync)
            $employee->pdsOthers()->delete();
            if ($request->has('others')) {
                foreach ($request->others as $other) {
                    if (!empty($other['description'])) {
                        $employee->pdsOthers()->create($other);
                    }
                }
            }

            // Handle References (Sync)
            $employee->pdsReferences()->delete();
            if ($request->has('references')) {
                foreach ($request->references as $ref) {
                    if (!empty($ref['name'])) {
                        $employee->pdsReferences()->create($ref);
                    }
                }
            }
        });

        return redirect()->route('pds.index')->with('success', 'Personal Data Sheet successfully updated.');
    }
}
