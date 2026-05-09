<?php

namespace App\Http\Controllers\Admin\AuditLogs;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\AuditLog;

class AuditLogController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return AuditLog::class; }
    protected function viewPath(): string { return 'admin.audit_logs'; }
    protected function routeName(): string { return 'admin.audit-logs'; }
    protected function translationNamespace(): string { return 'audit_logs'; }
}