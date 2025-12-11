<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\Controller;
use App\Models\Car;
use App\Models\Mechanic;
use App\Models\Owner;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class CarController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $cars = Car::all();
        $response->getBody()->write($this->view('cars/index.view.php', [
            'cars' => $cars
        ]));
        return $response;
    }

    public function create(Request $request, Response $response)
    {
        $mechanics = Mechanic::orderBy('name')->get();
        $response->getBody()->write($this->view('cars/form.view.php', [
            'car' => null,
            'owner' => null,
            'mechanics' => $mechanics,
            'action' => '/cars'
        ]));
        return $response;
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        // validation: required fields
        $errors = [];
        if (empty(trim($data['model'] ?? ''))) {
            $errors[] = 'Câmpul "Model" este obligatoriu.';
        }
        // require mechanic selection
        $mechanicId = isset($data['mechanic_id']) ? trim($data['mechanic_id']) : '';
        if (empty($mechanicId)) {
            $errors[] = 'Câmpul "Mecanic" este obligatoriu. Selectați un mecanic.';
        } else {
            $mechanic = Mechanic::find($mechanicId);
            if (!$mechanic) {
                $errors[] = 'Mecanic selectat invalid.';
            }
        }

        // no owner handling in car form

        // $mechanic is already verified above when mechanic_id provided

        if (!empty($errors)) {
            $mechanics = Mechanic::orderBy('name')->get();
            $response->getBody()->write($this->view('cars/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'car' => null,
                'owner' => null,
                'mechanics' => $mechanics,
                'action' => '/cars'
            ]));
            return $response;
        }

        $car = new Car();
        $car->model = $data['model'] ?? null;

        // mechanic: assign selected mechanic id
        $car->mechanic_id = intval($mechanicId);

        // prefer image URL if provided
        $imageUrl = trim($data['image_url'] ?? '');
        if (!empty($imageUrl)) {
            $car->image = $imageUrl;
        } else {
            // legacy: accept uploaded file if present
            if (isset($uploadedFiles['image'])) {
                $image = $uploadedFiles['image'];
                if ($image && $image->getError() === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../public/uploads';
                    if (!is_dir($uploadDir)) {
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

        // No owner handling in car form per request

        return $response->withHeader('Location', '/cars')->withStatus(302);
    }

    public function show(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        try {
            $car = Car::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $response->withStatus(404)->write('Not found');
        }
        $owner = $car->owner;
        $response->getBody()->write($this->view('cars/show.view.php', [
            'car' => $car,
            'owner' => $owner
        ]));
        return $response;
    }

    public function edit(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        $car = Car::find($id);
        $owner = $car ? $car->owner : null;
        $mechanics = Mechanic::orderBy('name')->get();
        $response->getBody()->write($this->view('cars/form.view.php', [
            'car' => $car,
            'owner' => $owner,
            'mechanics' => $mechanics,
            'action' => '/cars/' . $id . '/update'
        ]));
        return $response;
    }

    public function update(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $car = Car::find($id);
        if (!$car) {
            return $response->withStatus(404)->write('Not found');
        }
        // validation
        $errors = [];
        if (empty(trim($data['model'] ?? ''))) {
            $errors[] = 'Câmpul "Model" este obligatoriu.';
        }
        // require mechanic selection
        $mechanicId = isset($data['mechanic_id']) ? trim($data['mechanic_id']) : '';
        if (empty($mechanicId)) {
            $errors[] = 'Câmpul "Mecanic" este obligatoriu. Selectați un mecanic.';
        } else {
            $mechanic = Mechanic::find($mechanicId);
            if (!$mechanic) {
                $errors[] = 'Mecanic selectat invalid.';
            }
        }

        // no owner handling in car form

        if (!empty($errors)) {
            $owner = $car->owner;
            $mechanics = Mechanic::orderBy('name')->get();
            $response->getBody()->write($this->view('cars/form.view.php', [
                'errors' => $errors,
                'old' => $data,
                'car' => $car,
                'owner' => $owner,
                'mechanics' => $mechanics,
                'action' => '/cars/' . $id . '/update'
            ]));
            return $response;
        }

        $car->model = $data['model'] ?? $car->model;
        // mechanic: only use selected mechanic id
        $mechanicId = isset($data['mechanic_id']) ? trim($data['mechanic_id']) : '';
        if (!empty($mechanicId)) {
            $car->mechanic_id = intval($mechanicId);
        }
        // prefer image URL if provided, otherwise accept uploaded file
        $imageUrl = trim($data['image_url'] ?? '');
        if (!empty($imageUrl)) {
            // if previous image was local file, remove it
            if (!empty($car->image) && strpos($car->image, '/uploads/') === 0) {
                $oldPath = __DIR__ . '/../../public' . $car->image;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $car->image = $imageUrl;
        } else {
            if (isset($uploadedFiles['image'])) {
                $image = $uploadedFiles['image'];
                if ($image && $image->getError() === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../public/uploads';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    // delete old local file only
                    if (!empty($car->image) && strpos($car->image, '/uploads/') === 0) {
                        $oldPath = __DIR__ . '/../../public' . $car->image;
                        if (file_exists($oldPath)) {
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


        return $response->withHeader('Location', '/cars')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $args['id'] ?? null;
        $car = Car::find($id);
        if ($car) {
            // disassociate owner if exists (do not delete owner record)
            $owner = $car->owner;
            if ($owner) {
                $owner->car_id = null;
                $owner->save();
            }
            // delete local image file only if it's in uploads
            if (!empty($car->image) && strpos($car->image, '/uploads/') === 0) {
                $oldPath = __DIR__ . '/../../public' . $car->image;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $car->delete();
        }
        return $response->withHeader('Location', '/cars')->withStatus(302);
    }
}
