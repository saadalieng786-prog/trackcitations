<?php

namespace App\Http\Controllers;

use App\Filters\TicketFilters;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TicketExportController extends Controller
{
    /**
     * Start a new chunked background export process.
     * Keeps only 1 single export file per user in storage (overwriting previous export).
     */
    public function start(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $filters = new TicketFilters($request);
        $ticketsQuery = Ticket::query()
            ->active()
            ->filterByRole($user);

        $ticketsQuery = $filters->apply($ticketsQuery);
        $totalCount = $ticketsQuery->count();

        $exportId = 'exp_user_' . $user->id;
        $directory = storage_path('app/exports');
        File::ensureDirectoryExists($directory);

        // Single file per user: replace previous export file
        $csvRelativePath = "exports/tickets_export_user_{$user->id}.csv";
        $csvFullPath = storage_path("app/{$csvRelativePath}");

        if (File::exists($csvFullPath)) {
            @File::delete($csvFullPath);
        }

        // Create CSV file with header row
        $handle = fopen($csvFullPath, 'w');
        if ($handle) {
            fputcsv($handle, [
                'Ticket ID',
                'Driver Name',
                'Driver Email',
                'Company',
                'Address',
                'City',
                'State',
                'Zipcode',
                'Date Received',
                'Indicator',
                'Class Commercial?',
                'Roadside Inspection?',
                'Vehicle License Number',
                'Violation',
                'Citation Number',
                'DVER / DATAQ',
                'Ticket Type',
                'Beginning Fine Amount',
                'Final Fine Amount',
                'Total DVER Points',
                'Total DVER Points Removed',
                'Original Points Value',
                'Final Points Value',
                'Points Saved',
                'Court Name',
                'Court Date',
                'Court Address',
                'Attorney Name',
                'Attorney Address',
                'Attorney Response',
            ]);
            fclose($handle);
        }

        $meta = [
            'id' => $exportId,
            'user_id' => $user->id,
            'status' => 'processing',
            'total' => $totalCount,
            'processed' => 0,
            'file' => $csvRelativePath,
            'filename' => 'tickets_export_' . date('Y-m-d') . '.csv',
            'request_params' => $request->all(),
            'created_at' => now()->timestamp,
        ];

        Cache::put("export_{$exportId}", $meta, 7200);
        Cache::put("user_active_export_" . $user->id, $exportId, 7200);

        return response()->json([
            'success' => true,
            'export_id' => $exportId,
            'total' => $totalCount,
            'processed' => 0,
            'percentage' => $totalCount === 0 ? 100 : 0,
        ]);
    }

    /**
     * Process a chunk of tickets (e.g. 2,500 at a time).
     */
    public function processChunk(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $exportId = $request->input('export_id');
        $offset = (int) $request->input('offset', 0);
        $limit = (int) $request->input('limit', 2500);

        $meta = Cache::get("export_{$exportId}");
        if (!$meta) {
            return response()->json(['error' => 'Export session expired or invalid.'], 404);
        }

        if (($meta['status'] ?? '') === 'cancelled') {
            $this->cleanupFile($meta['file'] ?? null);
            return response()->json([
                'status' => 'cancelled',
                'message' => 'Export cancelled by user.',
            ]);
        }

        $user = auth()->user();
        if (!$user || $user->id !== ($meta['user_id'] ?? null)) {
            return response()->json(['error' => 'Unauthorized access to export session.'], 403);
        }

        $filterRequest = new Request($meta['request_params'] ?? []);
        $filters = new TicketFilters($filterRequest);

        $tickets = Ticket::query()
            ->select([
                'id', 'name', 'user_email', 'company_id', 'address', 'city', 'state', 'zip',
                'date_issued', 'indicator', 'class_commercial', 'road_side_inspection',
                'vehicle_lic_no', 'violation_id', 'citation_no', 'ticket_type',
                'beginning_fine_amount', 'final_fine_amount', 'total_dver_points__c',
                'total_dver_points_removed__c', 'court_name', 'court_date', 'court_address',
                'attorney_id', 'attorney_response', 'status'
            ])
            ->with([
                'company:id,name',
                'attorney.user:id,name,address',
                'violation:id,violation',
                'attachments:id,ticket_id,filename'
            ])
            ->active()
            ->filterByRole($user);

        $tickets = $filters->apply($tickets)
            ->orderByDesc('id')
            ->skip($offset)
            ->take($limit)
            ->get();

        $csvFullPath = storage_path('app/' . $meta['file']);
        if (!File::exists($csvFullPath)) {
            return response()->json(['error' => 'Export CSV file missing.'], 500);
        }

        $handle = fopen($csvFullPath, 'a');
        if ($handle) {
            foreach ($tickets as $ticket) {
                $isDverDataq = $ticket->isDverDataq();
                $dverLabel = trim(($isDverDataq['DVER'] ? 'DVER ' : '') . ($isDverDataq['DATAQ'] ? 'DATAQ' : ''));

                fputcsv($handle, [
                    $ticket->id,
                    $ticket->name,
                    $ticket->user_email,
                    $ticket->company?->name,
                    $ticket->address,
                    $ticket->city,
                    $ticket->state,
                    $ticket->zip,
                    $ticket->date_issued ? Carbon::parse($ticket->date_issued)->toDateString() : '',
                    $ticket->indicator,
                    $ticket->class_commercial,
                    $ticket->road_side_inspection,
                    $ticket->vehicle_lic_no,
                    $ticket->violation?->violation,
                    $ticket->citation_no,
                    $dverLabel,
                    $ticket->ticket_type,
                    $ticket->beginning_fine_amount,
                    $ticket->final_fine_amount,
                    $ticket->total_dver_points__c,
                    $ticket->total_dver_points_removed__c,
                    $ticket->original_points_value !== null ? number_format($ticket->original_points_value, 2) : '',
                    $ticket->final_points_value !== null ? number_format($ticket->final_points_value, 2) : '',
                    number_format($ticket->points_saved, 2),
                    $ticket->court_name,
                    $ticket->court_date ? Carbon::parse($ticket->court_date)->toDateString() : '',
                    $ticket->court_address,
                    $ticket->attorney?->user?->name,
                    $ticket->attorney?->user?->address,
                    $ticket->attorney_response,
                ]);
            }
            fclose($handle);
        }

        $chunkCount = $tickets->count();
        $newProcessed = min($meta['total'], $offset + $chunkCount);
        $isFinished = ($newProcessed >= $meta['total']) || ($chunkCount < $limit);

        $meta['processed'] = $newProcessed;
        $meta['status'] = $isFinished ? 'completed' : 'processing';
        Cache::put("export_{$exportId}", $meta, 7200);

        $percentage = $meta['total'] > 0 ? round(($newProcessed / $meta['total']) * 100, 1) : 100;
        if ($percentage > 100) $percentage = 100;

        return response()->json([
            'status' => $meta['status'],
            'export_id' => $exportId,
            'processed' => $newProcessed,
            'total' => $meta['total'],
            'percentage' => $percentage,
            'download_url' => route('tickets.export.download', ['exportId' => $exportId]),
        ]);
    }

    /**
     * Get active export for the authenticated user (if any).
     */
    public function active()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['has_active' => false]);
        }

        $activeExportId = Cache::get("user_active_export_" . $user->id);
        if (!$activeExportId) {
            return response()->json(['has_active' => false]);
        }

        $meta = Cache::get("export_{$activeExportId}");
        if (!$meta || ($meta['status'] ?? '') === 'cancelled') {
            Cache::forget("user_active_export_" . $user->id);
            return response()->json(['has_active' => false]);
        }

        $percentage = ($meta['total'] ?? 0) > 0 ? round((($meta['processed'] ?? 0) / $meta['total']) * 100, 1) : 100;
        if ($percentage > 100) $percentage = 100;

        return response()->json([
            'has_active' => true,
            'export_id' => $activeExportId,
            'status' => $meta['status'],
            'processed' => $meta['processed'],
            'total' => $meta['total'],
            'percentage' => $percentage,
            'download_url' => route('tickets.export.download', ['exportId' => $activeExportId]),
        ]);
    }

    /**
     * Check export status.
     */
    public function status(string $exportId)
    {
        $meta = Cache::get("export_{$exportId}");
        if (!$meta) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $percentage = $meta['total'] > 0 ? round(($meta['processed'] / $meta['total']) * 100, 1) : 100;
        if ($percentage > 100) $percentage = 100;

        return response()->json([
            'status' => $meta['status'],
            'export_id' => $exportId,
            'processed' => $meta['processed'],
            'total' => $meta['total'],
            'percentage' => $percentage,
            'download_url' => route('tickets.export.download', ['exportId' => $exportId]),
        ]);
    }

    /**
     * Cancel an active export.
     */
    public function cancel(string $exportId)
    {
        $user = auth()->user();
        if ($user) {
            Cache::forget("user_active_export_" . $user->id);
        }

        $meta = Cache::get("export_{$exportId}");
        if ($meta) {
            $meta['status'] = 'cancelled';
            Cache::put("export_{$exportId}", $meta, 600);
            $this->cleanupFile($meta['file'] ?? null);
        }

        return response()->json([
            'success' => true,
            'message' => 'Export cancelled successfully.',
        ]);
    }

    /**
     * Download the completed export file.
     */
    public function download(string $exportId)
    {
        $meta = Cache::get("export_{$exportId}");
        if (!$meta) {
            return abort(404, 'Export link expired or not found.');
        }

        $user = auth()->user();
        if (!$user || $user->id !== ($meta['user_id'] ?? null)) {
            return abort(403, 'Unauthorized access to export file.');
        }

        $csvFullPath = storage_path('app/' . $meta['file']);
        if (!File::exists($csvFullPath)) {
            return abort(404, 'Export file not found.');
        }

        return response()->download($csvFullPath, $meta['filename'], [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function cleanupFile(?string $relativePath)
    {
        if ($relativePath) {
            $fullPath = storage_path('app/' . $relativePath);
            if (File::exists($fullPath)) {
                @File::delete($fullPath);
            }
        }
    }
}
