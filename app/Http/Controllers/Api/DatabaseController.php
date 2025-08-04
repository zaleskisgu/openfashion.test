<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    /**
     * Reset and seed the database.
     */
    public function reset(): JsonResponse
    {
        try {
            // Выполняем миграции с нуля
            Artisan::call('migrate:fresh');
            
            // Заполняем базу тестовыми данными
            Artisan::call('db:seed');
            
            return response()->json([
                'message' => 'Database reset and seeded successfully',
                'data' => [
                    'migrations' => 'All tables recreated',
                    'seeding' => 'Test data inserted'
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to reset database',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
