<?php

namespace Venjoy\Lumenaid\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    protected $model;

    /**
     * Return whole list of brands of user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->model::paginate()
                            ->toArray();
        
        $data['items'] = $data['data'];
        unset($data['data']);

        return $this->success($data);
    }

    /**
     * Create new post<br>
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = array(
            'name'   => 'required|string',
        );
        $this->validate($request, $rules);

        $item = $request->all();
        $this->model::insert($item);

        return $this->success();
    }

    /**
     * Update post<br>
     * Only if the Posts' policy allows it
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $brand_id)
    {
        $rules = array(
            'name'   => 'required|string',
        );
        $this->validate($request, $rules);

        $brand = $this->model::find($brand_id);
        $this->authorize('update', $brand);

        try {
            $this->model::where('id', $brand['id'])
                           ->update($request->all());

            return response()->json($brand);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Brand not updated',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function delete($brandId)
    {
        $brand = $this->model::findOrFail($brandId);
        $this->authorize('delete', $brand);

        try {
            $brand->delete();
            
            return response()->json($brand);
        } catch (\Exception $e) {

            return $this->failed($e);
        }
    }

    protected function success($data = '', $message = '') 
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function failed($exception = '', $message = '') 
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'error' => $exception->getMessage()
        ], 400);
    }
}