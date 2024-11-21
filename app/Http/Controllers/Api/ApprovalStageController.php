<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovalStageRequest;
use App\Http\Resources\ApprovalStageResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Interfaces\ApprovalStageRepositoryInterface;

/**
 * @OA\Tag(
 *     name="Approval Stages",
 *     description="API Endpoints for Approval Stages"
 * )
 */
class ApprovalStageController extends Controller
{
    private $approvalStageRepository;

    public function __construct(ApprovalStageRepositoryInterface $approvalStageRepository)
    {
        $this->approvalStageRepository = $approvalStageRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/approval-stages",
     *     tags={"Approval Stages"},
     *     summary="Tambah tahap approval",
     *     description="Endpoint untuk menambahkan data tahap approval.",
     *     operationId="storeApprovalStage",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="approver_id", type="integer", example="1"),
     *             )
     *         )
     *     ),
     *    @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data approval stage berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="approver_id", type="integer", example=1),
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
     *                         "The approver id field is required.",
     *                         "The selected approver id is invalid.",
     *                         "The approver id has already been taken."
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
    public function store(ApprovalStageRequest $request)
    {
        try {
            $approver = $this->approvalStageRepository->create($request->all());
            return new ApprovalStageResource(true, 'Data approval stage berhasil ditambahkan', $approver);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new HttpResponseException(response(["errors" => ['message' => 'Data not found']], 404));
            }
            
            throw new HttpResponseException(response(["errors" => ['message' => 'Internal server error']], 500));
        }
    }

    /**
     * @OA\Put(
     *     path="/api/approval-stages/{id}",
     *     tags={"Approval Stages"},
     *     summary="Perbarui tahap approval",
     *     description="Endpoint untuk memperbarui data tahap approval.",
     *     operationId="updateApprovalStage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the approval stage to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="approver_id", type="integer", example="1"),
     *             )
     *         )
     *     ),
     *    @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data approval stage berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="approver_id", type="string", example="1"),
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
     *                         "The approver id field is required.",
     *                         "The approver id must be an integer.",
     *                         "The selected approver id is invalid.",
     *                         "The approver id has already been taken."
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
    public function update(ApprovalStageRequest $request, $id)
    {
        try {
            $approver = $this->approvalStageRepository->update($id, $request->all());
            return new ApprovalStageResource(true, 'Data approval stage berhasil diperbarui', $approver);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new HttpResponseException(response(["errors" => ['message' => 'Data not found']], 404));
            }

            throw new HttpResponseException(response(["errors" => ['message' => 'Internal server error']], 500));
        }
    }
}
