<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController as APIUserController;
use App\Http\Controllers\Api\DriverController as APIDriverController;
use App\Http\Controllers\Api\CompanyController as APICompanyController;
use App\Http\Controllers\Api\AttorneyController as APIAttorneyController;
use App\Http\Controllers\Api\TicketAttachmentController as APITicketAttachmentController;
use App\Http\Controllers\Api\TicketNoteController as APITicketNoteController;

Route::middleware('auth:sanctum')->group(function () {

});


Route::middleware('auth')->name('api.')->group(function () {
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
