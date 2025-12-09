<?php
namespace App\Controllers;
use App\Models\Car;
use App\Models\Mechanic;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MechanicController extends Controller{
    public function index(Request $request, Response $response, $args){
        $mechanics = Mechanic::all();
        $response->getBody()->write($this->view('mechanic/index.view.php', [
            'mechanics' => $mechanics
        ]));
        return $response;
    }   

    public function create(Request $request, Response $response){
        $response->getBody()->write($this->view('mechanic/form.view.php', [
            'mechanic' => null,
            'action' => '/mechanic'
        ]));
        return $response;
    }

    public function store(Request $request, Response $response){
        $data = $request->getParsedBody();
        $mech = new Mechanic();
        $mech->name = $data['name'] ?? null;
        $mech->save();
        return $response->withHeader('Location', '/mechanic')->withStatus(302);
    }

    public function show(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $mech = Mechanic::find($id);
        if(!$mech){
            return $response->withStatus(404)->write('Not found');
        }
        $cars = $mech->cars()->get();
        $response->getBody()->write($this->view('mechanic/show.view.php', [
            'mechanic' => $mech,
            'cars' => $cars
        ]));
        return $response;
    }

    public function edit(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $mech = Mechanic::find($id);
        $response->getBody()->write($this->view('mechanic/form.view.php', [
            'mechanic' => $mech,
            'action' => '/mechanic/' . $id . '/update'
        ]));
        return $response;
    }

    public function update(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $mech = Mechanic::find($id);
        if(!$mech){
            return $response->withStatus(404)->write('Not found');
        }
        $data = $request->getParsedBody();
        $mech->name = $data['name'] ?? $mech->name;
        $mech->save();
        return $response->withHeader('Location', '/mechanic')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $mech = Mechanic::find($id);
        if($mech){
            $mech->delete();
        }
        return $response->withHeader('Location', '/mechanic')->withStatus(302);
    }
}