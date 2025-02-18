<?php
// App\Http\Controllers\StudentController.php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\Geofencing; // Ensure this class exists in the specified namespace
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getStudentDetails(Request $request)
    {
        // جلب school_name و student_id من الـ query parameters
        $schoolName = $request->input('school_name');
        $studentId = $request->input('student_id');

        // التحقق من وجود المدرسة بالـ school_name
        $school = School::where('school_name', $schoolName)->first();

        // التحقق من وجود المدرسة
        if (!$school) {
            return response()->json(['message' => 'School not found', 'school_name' => $schoolName], 404);
        }

        // جلب الطالب بناءً على الـ student_id و الـ school_id
        $student = Student::where('id', $studentId)->where('school_id', $school->id)->first();

        // التحقق من وجود الطالب
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // إرجاع البيانات
        return response()->json($student, 200);
    }

    public function checkGeofence(Request $request, $studentId)
    {
        $geofence = Geofencing::where('student_id', $studentId)->first();
        if (!$geofence) {
            return response()->json(['message' => 'Geofence not found for the student.'], 404);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ($this->isInsideGeofence($latitude, $longitude, $geofence)) {
            return response()->json(['message' => 'The student is inside the geofence.']);
        } else {
            return response()->json(['message' => 'The student is outside the geofence.']);
        }
    }

    private function isInsideGeofence($latitude, $longitude, $geofence)
    {
        $distance = $this->calculateDistance($latitude, $longitude, $geofence->latitude, $geofence->longitude);
        return $distance <= $geofence->radius;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
