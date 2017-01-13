<?php

namespace Venjoy\Lumenaid\Crud;

class OwnerController extends BaseController
{
    public function index()
    {
        $data = $this->crud['model']::where('user_id', $this->request->user()->id)
                            ->paginate()
                            ->toArray();

        $this->authorize('isLoggedIn', $this->crud['model']);
        
        $data['items'] = $data['data'];
        unset($data['data']);

        return $this->response->success($data);
    }

    public function create()
    {
        $this->crudValidate();

        $this->authorize('isLoggedIn', $this->crud['model']);

        $item = $this->request->all();
        $item['user_id'] = $this->request->user()->id;
        $this->crud['model']::insert($item);

        return $this->response->success();
    }

    public function update($itemId)
    {
        $this->crudValidate();

        $item = $this->crud['model']::find($itemId);
        
        $this->authorize('isSelf', $item);

        try {
            $this->crud['model']::where('id', $item['id'])
                           ->update($this->request->all());

            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Brand not updated',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function delete($itemId)
    {
        $item = $this->crud['model']::findOrFail($itemId);
        $this->authorize('isSelf', $item);

        try {
            $item->delete();
            
            return response()->json($item);
        } catch (\Exception $e) {

            return $this->failed($e);
        }
    }

}