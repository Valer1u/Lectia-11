<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\Controller;
use App\Models\Car;
use App\Models\Mechanic;
use App\Models\Owner;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class CarController extends Controller{
    public function index(Request $request, Response $response){
        $cars = Car::all();
        $response->getBody()->write($this->view('cars/index.view.php', [
        'cars' => $cars]));
        return $response;
    }

    public function create(Request $request, Response $response){
        $response->getBody()->write($this->view('cars/form.view.php', [
            'car' => null,
            'owner' => null,
            'action' => '/cars'
        ]));
        return $response;
    }

    public function store(Request $request, Response $response){
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        // validation: required fields
        $errors = [];
        if(empty(trim($data['model'] ?? ''))){
            $errors[] = 'Câmpul "Model" este obligatoriu.';
        }
        if(empty(trim($data['mechanic_name'] ?? ''))){
            $errors[] = 'Câmpul "Mecanic" este obligatoriu.';
        }
        if(empty(trim($data['owner_name'] ?? ''))){
            $errors[] = 'Câmpul "Nume Proprietar" este obligatoriu.';
        }

        // Check if owner already exists
        $ownerName = trim($data['owner_name'] ?? '');
        if(!empty($ownerName)){
            $existingOwner = Owner::whereRaw('LOWER(name) = ?', [strtolower($ownerName)])->first();
            if($existingOwner){
                $errors[] = 'Un proprietar cu acest nume există deja în sistem.';
            }
        }

        // Check if mechanic already has a car assigned or will be duplicated
        $mechName = trim($data['mechanic_name'] ?? '');
        if(!empty($mechName)){
            $mechanic = Mechanic::whereRaw('LOWER(name) = ?', [strtolower($mechName)])->first();
            // Check if mechanic exists and already has cars assigned (optional check)
            // If you want to prevent one mechanic having multiple cars, uncomment below:
            // if($mechanic && $mechanic->cars()->count() > 0){
            //     $errors[] = 'Acest mecanic este deja alocat unei alte mașini.';
            // }
        }

        if(!empty($errors)){
            $response->getBody()->write($this->view('cars/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'car' => null,
                'owner' => null,
                'action' => '/cars'
            ]));
            return $response;
        }

        $car = new Car();
        $car->model = $data['model'] ?? null;

        // mechanic: accept mechanic_name and find or create mechanic
        if(!empty($mechName)){
            $mechanic = Mechanic::whereRaw('LOWER(name) = ?', [strtolower($mechName)])->first();
            if(!$mechanic){
                $mechanic = new Mechanic();
                $mechanic->name = $mechName;
                $mechanic->save();
            }
            $car->mechanic_id = $mechanic->id;
        } else {
            $car->mechanic_id = null;
        }

        // prefer image URL if provided
        $imageUrl = trim($data['image_url'] ?? '');
        if(!empty($imageUrl)){
            $car->image = $imageUrl;
        } else {
            // legacy: accept uploaded file if present
            if(isset($uploadedFiles['image'])){
                $image = $uploadedFiles['image'];
                if($image && $image->getError() === UPLOAD_ERR_OK){
                    $uploadDir = __DIR__ . '/../../public/uploads';
                    if(!is_dir($uploadDir)){
                        mkdir($uploadDir, 0755, true);
                    }
                    $basename = bin2hex(random_bytes(8));
                    $filename = $basename . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $image->getClientFilename());
                    $filePath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                    $image->moveTo($filePath);
                    $car->image = '/uploads/' . $filename;
                }
            }
        }

        $car->save();

        // create owner record
        $owner = new Owner();
        $owner->name = $data['owner_name'];
        $owner->car_id = $car->id;
        $owner->save();

        return $response->withHeader('Location', '/cars')->withStatus(302);
    }

    public function show(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        try{
            $car = Car::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return $response->withStatus(404)->write('Not found');
        }
        $owner = $car->owner;
        $response->getBody()->write($this->view('cars/show.view.php', [
            'car' => $car,
            'owner' => $owner
        ]));
        return $response;
    }

    public function edit(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $car = Car::find($id);
        $owner = $car ? $car->owner : null;
        $response->getBody()->write($this->view('cars/form.view.php', [
            'car' => $car,
            'owner' => $owner,
            'action' => '/cars/' . $id . '/update'
        ]));
        return $response;
    }

    public function update(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $car = Car::find($id);
        if(!$car){
            return $response->withStatus(404)->write('Not found');
        }
        // validation
        $errors = [];
        if(empty(trim($data['model'] ?? ''))){
            $errors[] = 'Câmpul "Model" este obligatoriu.';
        }
        if(empty(trim($data['mechanic_name'] ?? ''))){
            $errors[] = 'Câmpul "Mecanic" este obligatoriu.';
        }
        if(empty(trim($data['owner_name'] ?? ''))){
            $errors[] = 'Câmpul "Nume Proprietar" este obligatoriu.';
        }

        // Check if owner name changed and if new owner already exists
        $ownerName = trim($data['owner_name'] ?? '');
        if(!empty($ownerName) && $car->owner && strtolower($car->owner->name) !== strtolower($ownerName)){
            $existingOwner = Owner::whereRaw('LOWER(name) = ?', [strtolower($ownerName)])->first();
            if($existingOwner){
                $errors[] = 'Un proprietar cu acest nume există deja în sistem.';
            }
        } elseif(!empty($ownerName) && !$car->owner){
            // If car doesn't have owner yet, check if name exists
            $existingOwner = Owner::whereRaw('LOWER(name) = ?', [strtolower($ownerName)])->first();
            if($existingOwner){
                $errors[] = 'Un proprietar cu acest nume există deja în sistem.';
            }
        }

        if(!empty($errors)){
            $owner = $car->owner;
            $response->getBody()->write($this->view('cars/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'car' => $car,
                'owner' => $owner,
                'action' => '/cars/' . $id . '/update'
            ]));
            return $response;
        }

        $car->model = $data['model'] ?? $car->model;
        // mechanic: accept mechanic_name and find or create mechanic
        $mechName = trim($data['mechanic_name'] ?? '');
        if(!empty($mechName)){
            $mechanic = Mechanic::whereRaw('LOWER(name) = ?', [strtolower($mechName)])->first();
            if(!$mechanic){
                $mechanic = new Mechanic();
                $mechanic->name = $mechName;
                $mechanic->save();
            }
            $car->mechanic_id = $mechanic->id;
        }
        // prefer image URL if provided, otherwise accept uploaded file
        $imageUrl = trim($data['image_url'] ?? '');
        if(!empty($imageUrl)){
            // if previous image was local file, remove it
            if(!empty($car->image) && strpos($car->image, '/uploads/') === 0){
                $oldPath = __DIR__ . '/../../public' . $car->image;
                if(file_exists($oldPath)){
                    @unlink($oldPath);
                }
            }
            $car->image = $imageUrl;
        } else {
            if(isset($uploadedFiles['image'])){
                $image = $uploadedFiles['image'];
                if($image && $image->getError() === UPLOAD_ERR_OK){
                    $uploadDir = __DIR__ . '/../../public/uploads';
                    if(!is_dir($uploadDir)){
                        mkdir($uploadDir, 0755, true);
                    }
                    // delete old local file only
                    if(!empty($car->image) && strpos($car->image, '/uploads/') === 0){
                        $oldPath = __DIR__ . '/../../public' . $car->image;
                        if(file_exists($oldPath)){
                            @unlink($oldPath);
                        }
                    }
                    $basename = bin2hex(random_bytes(8));
                    $filename = $basename . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $image->getClientFilename());
                    $filePath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                    $image->moveTo($filePath);
                    $car->image = '/uploads/' . $filename;
                }
            }
        }
        $car->save();

        // update or create owner
        if(!empty($data['owner_name'])){
            $owner = $car->owner;
            if(!$owner){
                $owner = new Owner();
                $owner->car_id = $car->id;
            }
            $owner->name = $data['owner_name'];
            $owner->save();
        }

        return $response->withHeader('Location', '/cars')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args){
        $id = $args['id'] ?? null;
        $car = Car::find($id);
        if($car){
            // delete owner if exists
            $owner = $car->owner;
            if($owner){ $owner->delete(); }
            // delete local image file only if it's in uploads
            if(!empty($car->image) && strpos($car->image, '/uploads/') === 0){
                $oldPath = __DIR__ . '/../../public' . $car->image;
                if(file_exists($oldPath)){
                    @unlink($oldPath);
                }
            }
            $car->delete();
        }
        return $response->withHeader('Location', '/cars')->withStatus(302);
    }
}
