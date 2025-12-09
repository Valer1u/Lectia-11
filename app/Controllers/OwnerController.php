<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\Controller;
use App\Models\Owner;
use App\Models\Car;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OwnerController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $owners = Owner::all();
        $response->getBody()->write($this->view('owners/index.view.php', [
            'owners' => $owners
        ]));
        return $response;
    }

    public function create(Request $request, Response $response)
    {
        $response->getBody()->write($this->view('owners/form.view.php', [
            'owner' => null,
            'action' => '/owners'
        ]));
        return $response;
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $errors = [];
        if(empty(trim($data['name'] ?? ''))){
            $errors[] = 'Câmpul "Nume" este obligatoriu.';
        }

        if(!empty($errors)){
            $response->getBody()->write($this->view('owners/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'owner' => null,
                'action' => '/owners'
            ]));
            return $response;
        }

        $owner = new Owner();
        $owner->name = $data['name'] ?? null;

        $carModel = trim($data['car_model'] ?? '');
        if(!empty($carModel)){
            $car = new Car();
            $car->model = $carModel;
            $car->mechanic_id = null;
            $car->save();
            $owner->car_id = $car->id;
        } else {
            $owner->car_id = null;
        }

        $owner->save();
        return $response->withHeader('Location', '/owners')->withStatus(302);
    }

    public function show(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        try {
            $owner = Owner::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $response->withStatus(404)->write('Not found');
        }
        $car = $owner->car;
        $response->getBody()->write($this->view('owners/show.view.php', [
            'owner' => $owner,
            'car' => $car
        ]));
        return $response;
    }

    public function edit(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        $owner = Owner::find($id);
        $response->getBody()->write($this->view('owners/form.view.php', [
            'owner' => $owner,
            'action' => '/owners/' . $id . '/update'
        ]));
        return $response;
    }

    public function update(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        $owner = Owner::find($id);
        if (!$owner) {
            return $response->withStatus(404)->write('Not found');
        }
        $data = $request->getParsedBody();

        $errors = [];
        if(empty(trim($data['name'] ?? ''))){
            $errors[] = 'Câmpul "Nume" este obligatoriu.';
        }
        if(!empty($errors)){
            $response->getBody()->write($this->view('owners/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'owner' => $owner,
                'action' => '/owners/' . $id . '/update'
            ]));
            return $response;
        }

        $owner->name = $data['name'] ?? $owner->name;
        $carModel = trim($data['car_model'] ?? '');
        if(!empty($carModel)){
            if($owner->car){
                $owner->car->model = $carModel;
                $owner->car->save();
                $owner->car_id = $owner->car->id;
            } else {
                $car = new Car();
                $car->model = $carModel;
                $car->mechanic_id = null;
                $car->save();
                $owner->car_id = $car->id;
            }
        }
        $owner->save();
        return $response->withHeader('Location', '/owners')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        $owner = Owner::find($id);
        if ($owner) {
            $owner->delete();
        }
        return $response->withHeader('Location', '/owners')->withStatus(302);
    }
}
