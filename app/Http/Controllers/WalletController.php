<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HasWalletHelpers;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{
    use HasWalletHelpers;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = auth()->user()->wallets;
        return view('wallets.index', compact('wallets'));
    }

    public function getWalletsData(Request $request)
    {
        $user = Auth::user();

        $wallets = Wallet::with('holder')
            ->when(!$user->isAdmin(), function ($query) use ($user) {
                $query->where('holder_type', get_class($user))
                    ->where('holder_id', $user->id);
            });

        return DataTables::of($wallets)
            ->addColumn('user', function ($wallet) {
                return $wallet->holder->name ?? '-';
            })
            ->editColumn('balance', function ($wallet) {
                return number_format($wallet->balanceFloat, 8);
            })
            ->addColumn('actions', function ($wallet) {
                return '<a href="javascript:;" class="btn btn-sm btn-primary openCardPreviewModal">View</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::id());

        $name = $request->name;
        
        $wallet = $this->createWalletIfNotExists($user, $name);
        if($wallet) {
            $addressJson = $this->generateWalletAddress($user->id);
            $responseData = $addressJson->getData();
            if($responseData->status) {
                $metaArray = [
                    'phrase_key' => Crypt::encryptString($responseData->wallet->phrase_key),
                    'private_key' => $responseData->wallet->private_key,
                    'public_key' => $responseData->wallet->public_key,
                    'xpub' => $responseData->wallet->xpub,
                    'address' => $responseData->wallet->p2pkh_address,
                    'segwit_address' => $responseData->wallet->segwit_address,
                    'p2pkh_address' => $responseData->wallet->p2pkh_address,
                    'p2sh_address' => $responseData->wallet->p2sh_address,
                    'p2wsh_address' => $responseData->wallet->p2wsh_address,
                ];

                Wallet::where("id", $wallet->id)->update([
                    "meta" => json_encode($metaArray)
                ]);
            }
        }
        return redirect()->route('wallets');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
