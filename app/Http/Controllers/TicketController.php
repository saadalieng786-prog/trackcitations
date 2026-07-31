<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function export(Request $request)
    {
        $exportController = app(TicketExportController::class);
        return $exportController->start($request);
    }
}
