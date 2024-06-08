<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientInformation;
use App\Models\Soap;
use App\Models\LabRequest;
use App\Models\DietHistory;
use App\Models\Pcwm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FormController extends Controller
{
    // VIEW FUNCTION IN CASE IN THE FUTURE
    public function viewMedicalRecord($patient_pin, $patient_number)
    {
        // Fetch information from each table based on the patient_number
        $patientInformation = PatientInformation::where('patient_number', $patient_number)->first();
        $soap = Soap::where('patient_number', $patient_number)->first();
        $labRequest = LabRequest::where('patient_number', $patient_number)->first();
        $dietHistory = DietHistory::where('patient_number', $patient_number)->first();
        $pcwm = Pcwm::where('patient_number', $patient_number)->first();

        // Pass the data to the view
        return view('view_medical_record', compact('patientInformation', 'soap', 'labRequest', 'dietHistory', 'pcwm'));
    }
    
    // ARCHIVE FUNCTION
    public function archive(Request $request, $patient_number)
    {
        $patientInformation = PatientInformation::where('patient_number', $patient_number)->first();
        
        if ($patientInformation) {
            $patientInformation->update(['is_archived' => true]);
    
            // Handle archiving related records
            $patientInformation->soap()->update(['is_archived' => true]);
            $patientInformation->labRequest()->update(['is_archived' => true]);
            $patientInformation->dietHistory()->update(['is_archived' => true]);
            $patientInformation->pcwm()->update(['is_archived' => true]);
    
            return redirect()->back()->with('success', 'Medical Record archived successfully.');
        }
    
        return redirect()->back()->with('error', 'Medical Record not found.');
    }

    // UNARCHIVE FUNCTION
    public function unarchive(Request $request, $patient_number)
    {
        $patientInformation = PatientInformation::where('patient_number', $patient_number)->first();

        if ($patientInformation) {
            $patientInformation->update(['is_archived' => false]);

            // Handle unarchiving related records if needed
            $patientInformation->soap()->update(['is_archived' => false]);
            $patientInformation->labRequest()->update(['is_archived' => false]);
            $patientInformation->dietHistory()->update(['is_archived' => false]);
            $patientInformation->pcwm()->update(['is_archived' => false]);

            return redirect()->back()->with('success', 'Medical Record unarchived successfully.');
        }

        return redirect()->back()->with('error', 'Medical Record not found.');
    }

    // SHOW TABLE FUNCTION
    public function index()
    {
        $nutritionEvents = PatientInformation::select(
            'patient_number',
            'first_name',
            'age',
            'home_address',
            'contact_number',
        )->get();

        return view('includes/nutritionforms', compact('nutritionEvents'));
    }
}