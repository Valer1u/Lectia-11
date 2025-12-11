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
        $cars = Car::orderBy('model')->get();
        $response->getBody()->write($this->view('owners/form.view.php', [
            'owner' => null,
            'cars' => $cars,
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
        // optional car selection
        $carId = isset($data['car_id']) ? trim($data['car_id']) : '';
        if (!empty($carId)) {
            $car = Car::find($carId);
            if (!$car) {
                $errors[] = 'Mașina selectată este invalidă.';
            } else {
                // check if car already has an owner
                if ($car->owner) {
                    $errors[] = 'Mașina selectată are deja un proprietar.';
                }
            }
        }

        if(!empty($errors)){
            $cars = Car::orderBy('model')->get();
            $response->getBody()->write($this->view('owners/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'owner' => null,
                'cars' => $cars,
                'action' => '/owners'
            ]));
            return $response;
        }

        $owner = new Owner();
        $owner->name = $data['name'] ?? null;
        $owner->car_id = !empty($carId) ? intval($carId) : null;

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
        $cars = Car::orderBy('model')->get();
        $response->getBody()->write($this->view('owners/form.view.php', [
            'owner' => $owner,
            'cars' => $cars,
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

        $carId = isset($data['car_id']) ? trim($data['car_id']) : '';
        if (!empty($carId)) {
            $car = Car::find($carId);
            if (!$car) {
                $errors[] = 'Mașina selectată este invalidă.';
            } else {
                // allow if car is unowned or currently owned by this owner
                if ($car->owner && $car->owner->id != $owner->id) {
                    $errors[] = 'Mașina selectată este deja atribuită unui alt proprietar.';
                }
            }
        }

        if(!empty($errors)){
            $cars = Car::orderBy('model')->get();
            $response->getBody()->write($this->view('owners/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'owner' => $owner,
                'cars' => $cars,
                'action' => '/owners/' . $id . '/update'
            ]));
            return $response;
        }

        $owner->name = $data['name'] ?? $owner->name;
        $owner->car_id = !empty($carId) ? intval($carId) : null;
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
