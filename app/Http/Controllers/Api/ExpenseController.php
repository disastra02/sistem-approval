<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseRequestCreate;
use App\Http\Requests\ExpenseRequestUpdate;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;

/**
 * @OA\Tag(
 *     name="Expense",
 *     description="API Endpoints for Expense"
 * )
 */
class ExpenseController extends Controller
{
    private $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/expense",
     *     tags={"Expense"},
     *     summary="Tambah expense",
     *     description="Endpoint untuk menambahkan data expense.",
     *     operationId="storeExpense",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="amount", type="integer", example="1"),
     *             )
     *         )
     *     ),
     *    @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data expense berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="amount", type="string", example="1"),
     *                 @OA\Property(property="status_id", type="integer", example=1),
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
     *                     property="amount",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={
     *                         "The amount field is required.",
     *                         "The amount must be at least 1.",
     *                         "The amount must be an integer."
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
    public function store(ExpenseRequestCreate $request)
    {
        try {
            $expense = $this->expenseRepository->create($request->all());
            return new ExpenseResource(true, 'Data expense berhasil ditambahkan', $expense);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new HttpResponseException(response(["errors" => ['message' => 'Data not found']], 404));
            }

            throw new HttpResponseException(response(["errors" => ['message' => 'Internal server error']], 500));
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/expense/{id}/approve",
     *     tags={"Expense"},
     *     summary="Expense Approve",
     *     description="Endpoint untuk approve data expanse.",
     *     operationId="approveExpense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the expense to approve",
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
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="approver_id",
     *                     type="array",
     *                     @OA\Items(type="string"), 
     *                     example={
     *                          "The approver id field is required.",
     *                          "The selected approver id is invalid."
     *                     }
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="array",
     *                     @OA\Items(type="string"), 
     *                     example={
     *                          "Tahap approval sudah dilakukan.",
     *                          "Tahap approval tidak sesuai.",
     *                          "Tidak ada tahap approval yang tersisa.",
     *                          "Internal server error."
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
     *     )
     * )
     */
    public function approve(ExpenseRequestUpdate $request, $id)
    {
        try {
            $approval = $this->expenseRepository->approve($id, $request->approver_id);
            return new ExpenseResource(true, 'Data expanse berhasil disetujui', $approval);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new HttpResponseException(response(["errors" => ['message' => 'Data not found']], 404));
            }

            throw new HttpResponseException(response(["errors" => ['message' => $e->getMessage()]], 422));
        }
    }

    /**
     * @OA\Get(
     *     path="/api/expense/{id}",
     *     tags={"Expense"},
     *     summary="Approved Expenditure Data",
     *     description="Endpoint untuk approved expenditure data.",
     *     operationId="showExpense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *    @OA\Response(
     *         response=200,
     *         description="Data expense berhasil diambil",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data expanse berhasil diambil"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="amount", type="number", format="integer", example=10),
     *                 @OA\Property(
     *                     property="status",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Disetujui")
     *                 ),
     *                 @OA\Property(
     *                     property="approvals",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(
     *                             property="approver",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Ana")
     *                         ),
     *                         @OA\Property(
     *                             property="status",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="Disetujui")
     *                         )
     *                     )
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
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $expense = $this->expenseRepository->getById($id);
            return new ExpenseResource(true, 'Data expanse berhasil diambil', $expense);
        } catch (\Exception $e) {
            throw new HttpResponseException(response(["errors" => ['message' => 'Data not found']], 404));
        }
    }
}
