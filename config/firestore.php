<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Cloud Project ID
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Google Cloud project ID. This is used to
    | connect to the correct Firestore database.
    |
    */

    'project_id' => env('FIRESTORE_PROJECT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Service Account Credentials
    |--------------------------------------------------------------------------
    |
    | The path to the service account credentials JSON file. This file is
    | required for authentication with Google Cloud. You can download it
    | from the Google Cloud Console.
    |
    */

    'credentials' => storage_path(env('FIRESTORE_CREDENTIALS', 'app/firestore/google_credentials.json')),
];
