<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Generic Admin CRUD controller.
 *
 * Each subclass overrides the abstract methods to point at its model, view
 * folder, route prefix, validation rules and DataTable shape.
 */
abstract class AbstractCrudController extends Controller
{
    /** @return class-string<Model> */
    abstract protected function modelClass(): string;

    /** Blade view directory (e.g. "admin.branches"). */
    abstract protected function viewPath(): string;

    /** Named route prefix WITHOUT trailing dot (e.g. "admin.branches"). */
    abstract protected function routeName(): string;

    /** Translation namespace for module labels (e.g. "branches.title"). */
    abstract protected function translationNamespace(): string;

    /**
     * Validation rules for store/update.
     * @return array<string, mixed>
     */
    abstract protected function rules(?int $id = null): array;

    /**
     * DataTable base query (already filtered for branch via model scopes).
     */
    protected function dataTableQuery(): Builder
    {
        $cls = $this->modelClass();
        return $cls::query();
    }

    /**
     * Configure the Yajra DataTable (columns, edits). Override per module.
     */
    protected function configureDataTable($dt)
    {
        return $dt;
    }

    /**
     * Map request data into model attributes (override to hash passwords etc.).
     */
    protected function mapPayload(Request $request): array
    {
        $rules = $this->rules();
        return $request->only(array_keys($rules));
    }

    /**
     * Hook called after a record is created. Override to handle relations.
     */
    protected function afterStore(Model $model, Request $request): void {}

    /**
     * Hook called after a record is updated. Override to handle relations.
     */
    protected function afterUpdate(Model $model, Request $request): void {}

    /**
     * Extra data passed to index / create / edit views (selects, etc.).
     */
    protected function viewData(): array
    {
        return [];
    }

    /**
     * Field configuration for the generic crud form.
     * Each entry: ['type' => 'text|email|textarea|select|date|datetime|time|number|password|checkbox', 'label' => 'lang_key', 'required' => bool, 'col' => 'md-6', 'options' => [val=>label]].
     */
    protected function fields(): array
    {
        return [];
    }

    /**
     * Use the generic form view if no per-module form.blade.php exists.
     */
    protected function formView(): string
    {
        $custom = $this->viewPath() . '.form';
        if (view()->exists($custom)) {
            return $custom;
        }
        return 'admin._partials.crud_form';
    }

    // -----------------------------------------------------------------
    // Standard endpoints
    // -----------------------------------------------------------------

    public function index(Request $request): View|JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            $dt = DataTables::eloquent($this->dataTableQuery());
            return $this->configureDataTable($dt)->toJson();
        }

        return view($this->viewPath() . '.index', array_merge([
            'routeName' => $this->routeName(),
            'translationNamespace' => $this->translationNamespace(),
        ], $this->viewData()));
    }

    public function create(): View
    {
        $cls = $this->modelClass();
        return view($this->formView(), array_merge([
            'model' => new $cls(),
            'routeName' => $this->routeName(),
            'translationNamespace' => $this->translationNamespace(),
            'isCreate' => true,
            'fields' => $this->fields(),
        ], $this->viewData()));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate($this->rules());
        $cls = $this->modelClass();
        $model = $cls::create($this->mapPayload($request));
        $this->afterStore($model, $request);
        sweetalert()->addSuccess(__('common.saved_success'));
        return redirect()->route($this->routeName() . '.index');
    }

    public function edit(int $id): View
    {
        $cls = $this->modelClass();
        $model = $cls::findOrFail($id);
        return view($this->formView(), array_merge([
            'model' => $model,
            'routeName' => $this->routeName(),
            'translationNamespace' => $this->translationNamespace(),
            'isCreate' => false,
            'fields' => $this->fields(),
        ], $this->viewData()));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $cls = $this->modelClass();
        $model = $cls::findOrFail($id);
        $request->validate($this->rules($id));
        $model->update($this->mapPayload($request));
        $this->afterUpdate($model, $request);
        sweetalert()->addSuccess(__('common.saved_success'));
        return redirect()->route($this->routeName() . '.index');
    }

    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        $cls = $this->modelClass();
        $model = $cls::findOrFail($id);
        $model->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => __('common.deleted_success'),
            ]);
        }

        sweetalert()->addSuccess(__('common.deleted_success'));
        return redirect()->route($this->routeName() . '.index');
    }
}
