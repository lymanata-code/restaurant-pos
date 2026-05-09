<?php

namespace App\Http\Controllers\Admin\Branches;

use App\Http\Controllers\Controller;
use App\Support\BranchContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BranchSwitchController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $id = $request->input('restaurant_id');
        BranchContext::setCurrent($id !== null && $id !== '' ? (int) $id : null);
        return back();
    }
}
