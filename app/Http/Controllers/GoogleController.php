<?php

namespace App\Http\Controllers;

use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URL'));
        $this->client->addScope(Calendar::CALENDAR_READONLY);
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect()->away($authUrl); // Use a redirect instead of making a request
        //return redirect()->away($this->client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $this->client->authenticate($request->input('code'));
        $accessToken = $this->client->getAccessToken();

         // Save the access token and refresh token to the database
        \DB::table('google_tokens')->updateOrInsert(
            ['id' => 1], // Update the first row or insert a new one if it doesn't exist
            [
                'access_token' => json_encode($accessToken),
                'refresh_token' => $accessToken['refresh_token'] ?? null,
                'expires_at' => now()->addSeconds($accessToken['expires_in']),
                'updated_at' => now(),
            ]
        );

        return redirect('/');
    }

    public function getCalendarEvents()
    {
        // Fetch the stored access token from the database
        $tokenData = \DB::table('google_tokens')->first();

        if ($tokenData) {
            $accessToken = json_decode($tokenData->access_token, true);
            $this->client->setAccessToken($accessToken);

            // Check if the access token is expired and refresh it if needed
            if ($this->client->isAccessTokenExpired()) {
                // Refresh the token
                $refreshToken = $this->client->getRefreshToken();
                $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

                // Update the stored access token in the database
                \DB::table('google_tokens')->update([
                    'access_token' => json_encode($newAccessToken),
                    'expires_at' => now()->addSeconds($newAccessToken['expires_in']),
                    'updated_at' => now(),
                ]);
            }

            // Instantiate the Google Calendar service
            $service = new \Google\Service\Calendar($this->client);
            $calendarId = 'primary';
            $optParams = [
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            ];

            try {
                // Fetch events from Google Calendar
                $results = $service->events->listEvents($calendarId, $optParams);

                // Process and format the events into an array
                $eventsArray = array_map(function ($event) {
                    return [
                        'event_id' => $event->getId(),
                        'summary' => $event->getSummary(),
                        'description' => $event->getDescription(),
                        'start_time' => $event->getStart()->getDate(),
                        'end_time' => $event->getEnd()->getDate(),
                        'location' => $event->getLocation()
                    ];
                }, $results->getItems());

                // Save events to the database
                foreach ($eventsArray as $eventData) {
                    \App\Models\CalendarEvent::updateOrCreate(
                        ['event_id' => $eventData['event_id']], // Unique identifier
                        $eventData
                    );
                }

                // Return the array of events
                return response()->json($eventsArray, 200);

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        // If no access token, redirect to Google OAuth flow
        return redirect()->route('google.redirect');
    }
}