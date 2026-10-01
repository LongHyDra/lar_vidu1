<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAudit;

class AuditController extends Controller
{
    public function index()
    {
        return view('admin.audits.index', ['audits' => AdminAudit::with('user')->latest()->paginate(30)]);
    }
}
