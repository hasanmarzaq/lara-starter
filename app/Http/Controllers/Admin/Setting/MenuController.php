<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Setting\MenuStoreRequest;
use App\Http\Requests\Setting\MenuUpdateRequest;

class MenuController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = ('Menu');
    }

    public static function middleware(): array
    {
        return [
            'role:masteradmin|superadmin|admin',
            'permission:index admin/master/menus|create admin/master/menus/create|store admin/master/menus/store|edit admin/master/menus/edit|update admin/master/menus/update|delete admin/master/menus/delete'
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::orderBy('name')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <form action="' . route('menus.destroy', $row->id) . '" method="POST" class="d-inline delete-data">
                        ' . method_field('DELETE') . csrf_field() . '
                        <div class="btn-group">
                            <button type="button" data-id="' . $row->id . '" data-url="' . route('menus.edit', $row->id) . '" class="btn btn-warning edit">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                            <button type="submit" class="btn btn-danger" title="Delete">
                                <i class="fa fa-trash-can"></i>
                            </button>
                        </div>
                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'page_title' => $this->module,
        ];

        return view('backend.setting.menu.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuStoreRequest $request)
    {
        $data = $request->validated();
        $menus = Menu::create($data);

        return response()->json([
            'success' => __('Data Created Successfully'),
            'data' => $menus
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $menu = Menu::find($id);

        if ($menu) {
            return response()->json([
                'menu' => $menu,
            ]);
        }

        return response()->json(['error' => __('Data not found')], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuUpdateRequest $request, Menu $menu)
    {
        $data = $request->validated();

        $menu->update($data);

        return response()->json([
            'success' => __('Data Updated Successfully'),
            'data' => $menu
        ], 200);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'success' => __('Data Deleted Successfully'),
        ], 200);
    }
}