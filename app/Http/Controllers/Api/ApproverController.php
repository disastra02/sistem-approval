<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproverRequest;
use App\Http\Resources\ApproverResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Interfaces\ApproverRepositoryInterface;

/**
 * @OA\Tag(
 *     name="Approvers",
 *     description="API Endpoints for Approvers"
 * )
 */
class ApproverController extends Controller
{
    private $approverRepository;

    public function __construct(ApproverRepositoryInterface $approverRepository)
    {
        $this->approverRepository = $approverRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/approvers",
     *     tags={"Approvers"},
     *     summary="Tambah approver",
     *     description="Endpoint untuk menambahkan data approver.",
     *     operationId="storeApprover",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Ana"),
     *             )
     *         )
     *     ),
     *    @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data approvers berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Ana"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-21T04:06:16.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-21T04:06:16.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={
     *                         "The name field is required.",
     *                         "The name has already been taken."
     *                     }
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Data not found")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Internal server error")
     *             )
     *         )
     *     )
     * )
     */
    public function store(ApproverRequest $request)
    {
        try {
            $approver = $this->approverRepository->create($request->all());
            return new ApproverResource(true, 'Data approvers berhasil ditambahkan', $approver);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new HttpResponseException(response(["errors" => ['message' => 'Data not found']], 404));
            }
            
            throw new HttpResponseException(response(["errors" => ['message' => 'Internal server error']], 500));
        }
    }
}
