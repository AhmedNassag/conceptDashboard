<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Treasury;
use App\Traits\Message;
use Illuminate\Http\Request;
use App\Interfaces\OwnerAccountRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CapitalOwnerAccountController extends Controller
{
    use Message;
    protected $accountRepository;

    public function __construct(OwnerAccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(Request $request)
    {
        return $this->sendResponse(['accounts' => $this->accountRepository->getAllOwnerAccount($request)],'Data exited successfully');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'name' =>  'required|string|min:3|max:100',
                'payment_date' => 'required|date',
                'notes' =>  'required|string|min:3|max:300',
                'amount' =>  'required|numeric',
                'lend' => 'required|boolean',
                'treasury_id' => 'nullable|integer|exists:treasuries,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','amount','lend','notes','treasury_id','payment_date']);
            $data['income_id'] = $request->lend == 1 ? 4 : null;
            $data['expense_id'] = $request->lend == 0 ? 2 : null;
            $data['user_id'] = auth()->id();
            $this->accountRepository->createOwnerAccount($data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function show($id)
    {
        return $this->sendResponse(['account' => $this->accountRepository->getOwnerAccountById($id)],'Data exited successfully');
    }

    public function edit($id)
    {
        $treasuries = Treasury::where([
            ['treasury_id', null],
            ['active', 1],
        ])->get();

        return $this->sendResponse(['account' => $this->accountRepository->getOwnerAccountById($id),'treasuries'=>$treasuries],'Data exited successfully');
    }

    public function update(Request $request,$id)
    {
        DB::beginTransaction();
        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'name' =>  'required|string|min:3|max:100',
                'payment_date' => 'required|date',
                'notes' =>  'required|string|min:3|max:300',
                'amount' =>  'required|numeric',
                'lend' => 'required|boolean',
                'treasury_id' => 'nullable|integer|exists:treasuries,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','amount','lend','notes','treasury_id','payment_date']);
            $data['income_id'] = $request->lend == 1 ? 4 : null;
            $data['expense_id'] = $request->lend == 0 ? 2 : null;
            $data['user_id'] = auth()->id();
            $this->accountRepository->updateOwnerAccount($id,$data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    public function destroy($id)
    {
        $this->accountRepository->deleteOwnerAccount($id);
        return $this->sendResponse([],'Data exited successfully');
    }
}
