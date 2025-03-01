<?php

namespace App\Services;

use App\Models\Bus;
use App\Models\Student;
use App\Models\ParentNotification;
use App\Models\Geofencing;

class GeofencingService
{
    public function checkProximity(Bus $bus, $latitude, $longitude)
    {
        $students = Student::where('bus_id', $bus->id)->with('parentNotifications')->get();

        foreach ($students as $student) {
            $geofence = Geofencing::where('student_id', $student->id)->first();
            if (!$geofence) {
                continue;
            }

            $distance = $this->calculateDistance(
                $latitude,
                $longitude,
                $geofence->latitude,
                $geofence->longitude
            );

            if ($distance <= $geofence->geofence_radius) {
                $this->notifyParents($student);
            }
        }
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

    public function isWithinRadius($latitude1, $longitude1, $latitude2, $longitude2, $radius)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latFrom = deg2rad($latitude1);
        $lonFrom = deg2rad($longitude1);
        $latTo = deg2rad($latitude2);
        $lonTo = deg2rad($longitude2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $distance = $earthRadius * $angle;

        return $distance <= $radius;
    }

    private function notifyParents(Student $student)
    {
        foreach ($student->parentNotifications as $notification) {
            if ($notification->notify_on_approach && $notification->fcm_token) {
                $this->sendFCMNotification($notification);
            }
        }
    }

    private function sendFCMNotification(ParentNotification $notification)
    {
        $fcmService = new FCMService();

        return $fcmService->send(
            $notification->fcm_token,
            'School Bus Approaching',
            'The school bus is approaching your child\'s pickup location.',
            [
                'type' => 'bus_approaching',
                'student_id' => $notification->student_id
            ]
        );
    }
}
