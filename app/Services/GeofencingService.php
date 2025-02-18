<?php

namespace App\Services;

use App\Models\Bus;
use App\Models\Student;
use App\Models\ParentNotification; // Ensure this class exists in the specified namespace
use App\Models\GeofenceLog;

class GeofencingService
{
    public function checkProximity(Bus $bus)
    {
        $currentLocation = $bus->currentLocation;
        if (!$currentLocation) return;

        $students = $bus->students()->with('parentNotifications')->get();

        foreach ($students as $student) {
            $distance = $this->calculateDistance(
                $currentLocation->latitude,
                $currentLocation->longitude,
                $student->pickup_latitude,
                $student->pickup_longitude
            );

            $radius = $bus->geofenceSetting->notification_radius;

            if ($distance <= $radius) {
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
        // Implement FCM notification logic here
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
