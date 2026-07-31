<?php
namespace App\Services;

use App\Models\OutgoingLog;

class LogService
{
    public function storeOutgoingLog($sender, $context, $request, $response) {
        $log = new OutgoingLog();
        $log->sender_type = $sender;
        $log->context()->associate($context);
        $log->request = $request;
        $log->response = $response;
        $log->save();
    }
}
