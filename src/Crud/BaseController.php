<?php 
namespace Venjoy\Lumenaid\Crud;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Venjoy\Lumenaid\RestResponse;

class BaseController extends Controller
{
    protected $crud = [
        'model' => null,
        'rules' => [],
    ];

    protected $request;

    protected $response;

    public function __construct(Request $request, RestResponse $response)
    {
        $this->request =  $request;
        $this->response = $response;
    }

    public function crudValidate()
    {
        if (count($this->crud['rules'])) {
            $this->validate($this->request, $this->crud['rules']);
        }
    }
}