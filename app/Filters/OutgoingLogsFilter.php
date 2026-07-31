<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Filters;

use App\Filters\Filters;

class OutgoingLogsFilter extends Filters
{
    protected $filters = ['request', 'response', 'sender_type', 'context_type'];

    protected function request($request) {
        if ($request) {
            return $this->builder->whereLike('request', "%$request%");
        }
    }
    protected function response($response) {
        if ($response) {
            return $this->builder->whereLike('response', "%$response%");
        }
    }

    protected function sender_type($sender_type) {
        if ($sender_type) {
            return $this->builder->where('sender_type', $sender_type);
        }
    }

    protected function context_type($context_type) {
        if ($context_type) {
            return $this->builder->where('context_type', $context_type);
        }
    }
}
