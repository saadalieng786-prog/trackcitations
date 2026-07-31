<?php

namespace App\Http\Controllers;

use App\Models\MessageAttachment;
use App\Models\TicketAttachment;
use App\Support\EnvironmentWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Support\AttachmentStorage;

class StorageSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'filesystem_disk' => env('FILESYSTEM_DISK', config('filesystems.default')),
            'attachments_disk' => env('ATTACHMENTS_DISK', config('filesystems.ticket_attachments_disk')),
            'message_attachments_disk' => env('MESSAGE_ATTACHMENTS_DISK', config('filesystems.message_attachments_disk')),
            'aws_access_key_id' => env('AWS_ACCESS_KEY_ID', ''),
            'aws_secret_access_key' => env('AWS_SECRET_ACCESS_KEY', ''),
            'aws_default_region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'aws_bucket' => env('AWS_BUCKET', ''),
            'aws_url' => env('AWS_URL', ''),
            'aws_endpoint' => env('AWS_ENDPOINT', ''),
            'aws_use_path_style_endpoint' => filter_var(env('AWS_USE_PATH_STYLE_ENDPOINT', false), FILTER_VALIDATE_BOOL),
        ];

        $status = [
            'default_disk' => config('filesystems.default'),
            'attachments_disk' => config('filesystems.ticket_attachments_disk'),
            'message_attachments_disk' => config('filesystems.message_attachments_disk'),
            's3_ready' => filled($settings['aws_access_key_id']) && filled($settings['aws_secret_access_key']) && filled($settings['aws_bucket']),
        ];

        $diagnostics = [
            'ticket_attachments_total' => TicketAttachment::count(),
            'message_attachments_total' => MessageAttachment::count(),
            'ticket_attachment_local_urls' => TicketAttachment::where('path', 'like', '%/storage/%')
                ->orWhere('path', 'like', 'storage/%')
                ->count(),
            'ticket_attachment_remote_urls' => TicketAttachment::where('path', 'like', 'http%')
                ->where('path', 'not like', '%/storage/%')
                ->count(),
            'message_attachment_local_urls' => MessageAttachment::where('file_path', 'like', '%/storage/%')
                ->orWhere('file_path', 'like', 'storage/%')
                ->count(),
            'message_attachment_remote_urls' => MessageAttachment::where('file_path', 'like', 'http%')
                ->where('file_path', 'not like', '%/storage/%')
                ->count(),
        ];

        return view('admin.storage.index', compact('settings', 'status', 'diagnostics'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'filesystem_disk' => 'required|in:local,public,s3',
            'attachments_disk' => 'required|in:public,s3',
            'message_attachments_disk' => 'required|in:public,s3',
            'aws_access_key_id' => 'nullable|string',
            'aws_secret_access_key' => 'nullable|string',
            'aws_default_region' => 'nullable|string',
            'aws_bucket' => 'nullable|string',
            'aws_url' => 'nullable|string',
            'aws_endpoint' => 'nullable|string',
            'aws_use_path_style_endpoint' => 'nullable|boolean',
        ]);

        EnvironmentWriter::updateMany(base_path('.env'), [
            'FILESYSTEM_DISK' => $validated['filesystem_disk'],
            'ATTACHMENTS_DISK' => $validated['attachments_disk'],
            'MESSAGE_ATTACHMENTS_DISK' => $validated['message_attachments_disk'],
            'AWS_ACCESS_KEY_ID' => $validated['aws_access_key_id'] ?? '',
            'AWS_SECRET_ACCESS_KEY' => $validated['aws_secret_access_key'] ?? '',
            'AWS_DEFAULT_REGION' => $validated['aws_default_region'] ?? 'us-east-1',
            'AWS_BUCKET' => $validated['aws_bucket'] ?? '',
            'AWS_URL' => $validated['aws_url'] ?? '',
            'AWS_ENDPOINT' => $validated['aws_endpoint'] ?? '',
            'AWS_USE_PATH_STYLE_ENDPOINT' => ($request->boolean('aws_use_path_style_endpoint') ? 'true' : 'false'),
        ]);

        config([
            'filesystems.default' => $validated['filesystem_disk'],
            'filesystems.ticket_attachments_disk' => $validated['attachments_disk'],
            'filesystems.message_attachments_disk' => $validated['message_attachments_disk'],
        ]);

        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return redirect()
            ->route(auth()->user()->portalRoutePrefix().'.storage.index')
            ->with('success', 'Storage settings updated successfully.');
    }

    public function test(Request $request)
    {
        $request->validate([
            'disk' => 'required|in:public,s3',
        ]);

        $disk = $request->string('disk')->toString();

        try {
            $result = AttachmentStorage::testDiskWrite($disk);

            if (! $result['exists_after_write']) {
                return redirect()
                    ->route(auth()->user()->portalRoutePrefix().'.storage.index')
                    ->with('error', 'Storage test failed: file could not be confirmed after write.');
            }

            return redirect()
                ->route(auth()->user()->portalRoutePrefix().'.storage.index')
                ->with('success', 'Storage test passed for disk '.$disk.'. Temporary write/delete completed successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->route(auth()->user()->portalRoutePrefix().'.storage.index')
                ->with('error', 'Storage test failed for disk '.$disk.': '.$e->getMessage());
        }
    }
}
