<?php

namespace App\Traits;

use App\Models\User;
use App\Services\NodeApiService;
use Bavix\Wallet\Models\Wallet;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Address\ScriptHashAddress;
use BitWasp\Bitcoin\Address\SegwitAddress;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Bitcoin\Mnemonic\Bip39\Wordlist\EnglishWordList;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;
use BitWasp\Bitcoin\Network\NetworkFactory;
use BitWasp\Bitcoin\Script\WitnessProgram;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait HasWalletHelpers
{
    public function generateWalletSlug(string $name): string
    {
        return Str::slug($name);
    }

    public function createWalletIfNotExists(User $model, string $name): Wallet
    {
        $slug = $this->generateWalletSlug($name);

        return $model->wallets()->firstOrCreate([
            'slug' => $slug,
        ], [
            'name' => $name,
        ]);
    }

    public function generateWalletAddress($userId)
    {
        try {
            $NodeApiService = new NodeApiService();
            $response = $NodeApiService->post('wallet/create', [
                "userId" => $userId
            ]);
      
            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'wallet' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => $response->json()
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function generateWalletAddressBitwasp()
    {
        $returnData['phrase_key'] = $returnData['private_key'] = $returnData['public_key'] = $returnData['p2pkh_address'] = $returnData['p2sh_address'] = $returnData['segwit_address'] = $returnData['p2wsh_address'] = $returnData['xpub'] = "";

        if(env("BTC_NETWORK") == "testnet"){
            Bitcoin::setNetwork(NetworkFactory::bitcoinTestnet());
        } else {
            Bitcoin::setNetwork(NetworkFactory::bitcoin());
        }

        $network = Bitcoin::getNetwork();
        $ecAdapter = Bitcoin::getEcAdapter();

        // Generate a mnemonic
        $random = new Random();
        $entropy = $random->bytes(16);
        $bip39 = new Bip39Mnemonic($ecAdapter, new EnglishWordList());
        $mnemonic = $bip39->entropyToMnemonic($entropy);
        $returnData['phrase_key'] = $mnemonic;

        // Generate the BIP39 seed from the mnemonic
        $seedGenerator = new Bip39SeedGenerator();
        $seed = $seedGenerator->getSeed($mnemonic);

        // Derive the master key from the seed
        $hdFactory = new HierarchicalKeyFactory();
        $masterKey = $hdFactory->fromEntropy($seed);

        // Derive the purpose key (m/84'/0'/0')
        $purposeKey = $masterKey->derivePath("84'/0'/0'");

        $privateKey = $purposeKey->toExtendedPrivateKey($network);
        $returnData['private_key'] = $privateKey;

        // Derive the account key (m/84'/0'/0'/0)
        $accountKey = $purposeKey->derivePath('0');

        // Derive the external key (m/84'/0'/0'/0/0)
        $externalKey = $accountKey->derivePath('0');

        // Get the public key from the external key
        $publicKey = $externalKey->getPublicKey();

        // Compressed
        $returnData['public_key'] = $publicKey->getHex();

        // Uncompressed
        // $returnData['public_key'] = $publicKey->getBuffer()->getHex();

        // Derive the public key hash
        $publicKeyHash = $publicKey->getPubKeyHash();

        $returnData['xpub'] = $externalKey->toExtendedPublicKey($network);

        // Derive the pay to public key hash address
        $p2pkh = new PayToPubKeyHashAddress($publicKeyHash);
        $p2pkhAddress = $p2pkh->getAddress($network);
        $returnData['p2pkh_address'] = $p2pkhAddress; // 34 char length

        // Derive the redeem script
        $redeemScript = $p2pkh->getScriptPubKey();
        
        // Derive the P2SH address 
        $p2sh = new ScriptHashAddress($redeemScript->getScriptHash());
        $p2shAddress = $p2sh->getAddress($network);
        $returnData['p2sh_address'] = $p2shAddress; // 34 char length

        // Derive the native segwit address 
        $p2wpkhWP = WitnessProgram::v0($publicKeyHash);
        $p2wpkh = new SegwitAddress($p2wpkhWP);
        $p2wpkhaddress = $p2wpkh->getAddress($network);
        $returnData['segwit_address'] = $p2wpkhaddress; // 42 char length

        // Derive the Pay to Witness Script Hash address
        $p2wshWP = WitnessProgram::v0($redeemScript->getWitnessScriptHash());
        $p2wsh = new SegwitAddress($p2wshWP);
        $p2wshAddress = $p2wsh->getAddress($network);
        $returnData['p2wsh_address'] = $p2wshAddress;

        return json_encode($returnData);
    }

    public function generateAddressFromXpub($data) {

        try {
            $NodeApiService = new NodeApiService();
            $response = $NodeApiService->post('common/create-btc-address-xpub', $data);
      
            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'wallet' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => $response->json()
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}