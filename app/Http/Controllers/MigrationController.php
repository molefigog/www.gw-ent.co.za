<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EnvEditor;
use Illuminate\Support\Facades\Artisan;
use Spatie\Flash\Flash;
use Illuminate\Support\Facades\File;

class MigrationController extends Controller
{

    public function showDatabaseSetupForm()
    {
        return view('database-setup');
    }

    public function runMigrations(Request $request)
    {


        // Update database connection based on user input
        config([
            'database.connections.mysql.database' => $request->input('db_database'),
            'database.connections.mysql.username' => $request->input('db_username'),
            'database.connections.mysql.password' => $request->input('db_password'),
        ]);

        try {
            // Run the migrations
            Artisan::call('migrate');

            // Optionally, you can return a response indicating success
            return <<<HTML
            <script>
                alert("Migration executed successfully!");
                window.location.href = "/";
            </script>
            HTML;
        } finally {
        }
    }



    public function runSeeder(Request $request)
    {
        config([
            'database.connections.mysql.database' => $request->input('db_database'),
            'database.connections.mysql.username' => $request->input('db_username'),
            'database.connections.mysql.password' => $request->input('db_password'),
        ]);

        // Run the admin seeder
        Artisan::call('db:seed', ['--class' => 'AdminSeeder']);

        // Optionally, you can return a response indicating success
        return <<<HTML
                  <script>
                      alert("Admin Seeder executed successfully!");
                      window.location.href = "/";
                  </script>
                  HTML;
    }


    public function runOptimize(Request $request)
    {
        // Clear cache
        Artisan::call('optimize');

        // Optionally, you can return a response indicating success
        return <<<HTML
            <script>
                alert("Cache updated successfully!");
                window.location.href = "/";
            </script>
        HTML;
    }
}
