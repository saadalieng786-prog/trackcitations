<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AttorneyController;
use App\Http\Controllers\AttorneyTicketController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverTicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ManagerTicketController;
use App\Http\Controllers\MessageReadController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesForceController;
use App\Http\Controllers\StorageSettingsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TicketExportController;
use App\Http\Controllers\ViolationController;
use App\Integrations\Salesforce\SalesforceClient;
use App\Models\User;
use App\Models\SalesForce;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController as APIUserController;
use App\Http\Controllers\Api\DriverController as APIDriverController;
use App\Http\Controllers\Api\CompanyController as APICompanyController;
use App\Http\Controllers\Api\AttorneyController as APIAttorneyController;
use App\Http\Controllers\Api\TicketAttachmentController as APITicketAttachmentController;
use App\Http\Controllers\Api\TicketNoteController as APITicketNoteController;
use App\Http\Controllers\CourtDateController;

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReadController;


Route::get('/', [HomeController::class, 'homepage'])->name('homepage');
Route::post('/submit', [HomeController::class, 'submit'])->name('submit.ticket');



Route::post('/broadcasting/custom', function (\Illuminate\Http\Request $request) {
    // Add custom logic here before the default broadcasting auth
    if (!$request->user()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    return \Illuminate\Support\Facades\Broadcast::auth($request);
})->middleware(['auth']);

// getting Upcoming Court Dates
Route::get('admin/upcoming_court_date',[CourtDateController::class, 'index'])->name('upcoming_court_date');

Route::group(['middleware' => 'auth'], function () {
    // Messaging.
    Route::get('messaging', [ConversationController::class, 'mainIndex'])->name('messaging.index');

    // Chunked Background Ticket Export API
    Route::post('tickets/export/start', [TicketExportController::class, 'start'])->name('tickets.export.start');
    Route::post('tickets/export/chunk', [TicketExportController::class, 'processChunk'])->name('tickets.export.chunk');
    Route::get('tickets/export/active', [TicketExportController::class, 'active'])->name('tickets.export.active');
    Route::get('tickets/export/status/{exportId}', [TicketExportController::class, 'status'])->name('tickets.export.status');
    Route::post('tickets/export/cancel/{exportId}', [TicketExportController::class, 'cancel'])->name('tickets.export.cancel');
    Route::get('tickets/export/download/{exportId}', [TicketExportController::class, 'download'])->name('tickets.export.download');
    Route::get('messaging/{currentConversation}', [ConversationController::class, 'mainShow'])->name('messaging.show');
    Route::post('messaging/{currentConversation}/markAllAsRead', [MessageController::class, 'markAllAsRead'])->name('messaging.markAllAsRead');
    Route::post('messaging/{message}/read', [MessageController::class, 'markAsRead'])->name('messaging.markAsRead');
    Route::post('/conversations/{conversation}/add-user', [ConversationController::class, 'addUser'])->name('conversation.addUser');
    Route::delete('/conversations/{conversation}/remove-user/{user}', [ConversationController::class, 'removeUser'])->name('conversation.removeUser');

    // Mark all notifications as read
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Mark a single notification as read
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->isInternalAdmin() || $user->isCompanyAdmin()) {
            return redirect(\route($user->portalRoutePrefix() . '.dashboard'));
        } else if ($user->hasRole(User::ROLE_ATTORNEY)) {
            return redirect(\route('attorney.dashboard'));
        } else if ($user->hasRole(User::ROLE_DRIVER)) {
            return redirect(\route('driver.dashboard'));
        }
        abort(403);
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('support', [SupportController::class, 'index'])->name('support.index');
    Route::post('support', [SupportController::class, 'store'])->name('support.store');

    // Admin Routes.
    foreach ([
        'admin' => 'role:admin',
        'super_admin' => 'role:super_admin',
        'staff_admin' => 'role:staff_admin',
    ] as $portal => $roleMiddleware) {
        Route::group([
            'middleware' => $roleMiddleware,
            'prefix' => str_replace('_', '-', $portal),
            'as' => $portal . '.',
        ], function () use ($portal) {
            // Dashboard
            Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // tickets.
            Route::post('tickets/bulk', [AdminTicketController::class, 'bulkUpdate'])->name('tickets.bulkUpdate');
            Route::get('tickets/export', [AdminTicketController::class, 'export'])->name('tickets.export');
            Route::get('tickets/archive', [AdminTicketController::class, 'archive'])->name('tickets.archive');
            Route::get('tickets/pending', [AdminTicketController::class, 'pending'])->name('tickets.pending');
            Route::post('tickets/{ticket}/restore', [AdminTicketController::class, 'restore'])->name('tickets.restore');
            Route::resource('tickets', AdminTicketController::class);
            Route::resource('admins', AdminController::class);
            Route::resource('companies', CompanyController::class);
            Route::resource('managers', ManagerController::class);
            Route::resource('attorneys', AttorneyController::class);
            Route::resource('drivers', DriverController::class);
            Route::resource('violations', ViolationController::class);

            Route::get('logs', [LogController::class, 'index'])->name('logs.index');
            Route::get('outgoing-logs', [LogController::class, 'outgoing'])->name('outgoinglogs.index');
            Route::get('salesforce/settings', [SalesForceController::class, 'index'])->name('salesforce.index');
            Route::put('salesforce/settings', [SalesForceController::class, 'update'])->name('salesforce.update');
            Route::post('salesforce/import', [SalesForceController::class, 'import'])->name('salesforce.import');
            Route::get('salesforce/import', function () use ($portal) {
                return redirect()->route($portal . '.salesforce.index');
            });
            Route::get('storage/settings', [StorageSettingsController::class, 'index'])->name('storage.index');
            Route::put('storage/settings', [StorageSettingsController::class, 'update'])->name('storage.update');
            Route::post('storage/settings/test', [StorageSettingsController::class, 'test'])->name('storage.test');
        });
    }

    // Company Admin Routes
    foreach ([
        'manager' => 'role:manager',
        'company_admin' => 'role:company_admin',
    ] as $portal => $roleMiddleware) {
        Route::group([
            'middleware' => $roleMiddleware,
            'prefix' => str_replace('_', '-', $portal),
            'as' => $portal . '.',
        ], function () {
            Route::get('dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
            Route::get('tickets/export', [ManagerTicketController::class, 'export'])->name('tickets.export');

            Route::resource('tickets', ManagerTicketController::class);
            Route::resource('companies', CompanyController::class);
            Route::resource('managers', ManagerController::class);
            Route::resource('drivers', DriverController::class);
        });
    }

    // Attorney Routes
    Route::group(['middleware' => 'role:attorney', 'prefix' => 'attorney', 'as' => 'attorney.'], function () {
        Route::get('dashboard', [AttorneyController::class, 'dashboard'])->name('dashboard');
        Route::get('tickets/export', [AttorneyTicketController::class, 'export'])->name('tickets.export');
        Route::resource('tickets', AttorneyTicketController::class);
        Route::resource('drivers', DriverController::class);

    });

    // Driver Routes
    Route::group(['middleware' => 'role:driver', 'prefix' => 'driver', 'as' => 'driver.'], function () {
        Route::get('tickets/export', [DriverTicketController::class, 'export'])->name('tickets.export');
        Route::get('dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
        Route::resource('tickets', DriverTicketController::class);
    });
});

// API Routes. TODO: Move to api.php
Route::group(['middleware' => 'auth', 'prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('users/exclude/{conversation?}', [APIUserController::class, 'exclude'])->name('users.exclude');
    Route::get('drivers', [APIDriverController::class, 'index'])->name('driver.index');
    Route::get('companies', [APICompanyController::class, 'index'])->name('company.index');
    Route::get('attorneys', [APIAttorneyController::class, 'index'])->name('attorney.index')->middleware('auth');
    Route::post('ticket/{ticket}/attach', [APITicketAttachmentController::class, 'store'])->name('tickets-attach.store')->middleware('auth');
    Route::post('ticket/{ticket}/note', [APITicketNoteController::class, 'store'])->name('tickets-note.store')->middleware('auth');

    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::post('/messages/{message}/read', [ReadController::class, 'store']);
    Route::get('/messages/{message}/reads', [ReadController::class, 'getMessageReads']);

    Route::post('setSessionCompanies', [HomeController::class, 'setSessionCompanies'])->name('setSessionCompanies');
});


// Salesforce Integrations
Route::group(['prefix' => 'salesforce', 'as' => 'salesforce.'], function () {
    Route::get('oauth', [SalesforceController::class, 'oauth'])->name('oauth');
    Route::get('callback', [SalesforceController::class, 'callback'])->name('callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
