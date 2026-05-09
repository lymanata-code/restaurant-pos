<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Read-only DataTable controller (no create/edit/delete).
 * Used for orders, kitchen tickets, login histories, audit logs etc.
 */
abstract class AbstractReadOnlyController extends Controller
{
    /** @return class-string<Model> */
    abstract protected function modelClass(): string;
    abstract protected function viewPath(): string;
    abstract protected function routeName(): string;
    abstract protected function translationNamespace(): string;

    protected function dataTableQuery(): Builder
    {
        $cls = $this->modelClass();
        return $cls::query();
    }

    protected function configureDataTable($dt)
    {
        return $dt;
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            $dt = DataTables::eloquent($this->dataTableQuery());
            return $this->configureDataTable($dt)->toJson();
        }

        return view($this->viewPath() . '.index', [
            'routeName' => $this->routeName(),
            'translationNamespace' => $this->translationNamespace(),
        ]);
    }
}
