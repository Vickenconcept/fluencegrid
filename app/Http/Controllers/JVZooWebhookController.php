<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class JVZooWebhookController extends Controller
{
    //

    public function JVZoo(Request $request)
    {

        // Verify the JVZoo IPN request
        // if (!$this->jvzipnVerification($request)) {
        //     return response()->json(['message' => 'Verification Failed!']);
        // }

        $data = $request->all();

        // if (!isset($_POST["cverify"])) {
        //     return response()->json(['message' => 'Verification Failed!']);
        // }

        $TYPE = isset($data['ctransaction']) ? $data['ctransaction'] : 'SALE';

        // Collect necessary data from the request
        $d = array(
            "TYPE"            => $TYPE,
            "EMAIL"           => isset($data['ccustemail']) ? $data['ccustemail'] : null,
            "TRANSACTION_ID"  => isset($data["ctransreceipt"]) ? $data["ctransreceipt"] : null,
            "PRODUCT_ID"      => isset($data['cproditem']) ? $data['cproditem'] : null
        );

        // Check if required data exists
        if (!$d['PRODUCT_ID'] || !$d['EMAIL']) {
            // if (!$d['PRODUCT_ID'] || !$d['EMAIL'] || !$d['TRANSACTION_ID']) {
            return response()->json(['message' => 'Item does not exist.']);
        }

        // Get user email, product ID, and transaction ID
        $email = $d['EMAIL'];
        $productID = $d['PRODUCT_ID'];
        $transactionID = $d['TRANSACTION_ID'];

        // Find the role (related to the product) by product ID
        $role = Product::where('product_id', $productID)->first();

        // If the role doesn't exist, return an error
        if (!$role) {
            return response()->json(['message' => 'Product not found!']);
        }

        // Process based on transaction type (SALE or RFND)
        switch ($TYPE) {
            case 'SALE':
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $password = Str::random(10);
                    $user = User::create([
                        'name' => substr($email, 0, strpos($email, '@')),
                        'email' => $email,
                        'password' => Hash::make($password),
                        'is_admin' => 1,
                    ]);


                    $user->assignRole($role->funnel);

                    Mail::to($email)->send(new WelcomeMail($password));
                    Mail::to('vicken408@gmail.com')->send(new WelcomeMail($password));

                    return response()->json(['message' => 'User created successfully!']);
                } else {
                    $user->syncRoles([$role->funnel]);

                    return response()->json(['message' => 'User role updated successfully!']);
                }

                break;

            case 'RFND':
                // Handle refund transaction
                $user = User::where('email', $email)->first();


                if (!$user) {
                    return response()->json(['message' => 'User not found!'], 404);
                }

                try {
                    DB::transaction(function () use ($user) {
                        DB::table('messages')->whereIn('conversation_id', function ($query) use ($user) {
                            $query->select('id')->from('conversations')->where('user_id', $user->id);
                        })->delete();

                        DB::table('conversations')->where('user_id', $user->id)->delete();

                        $user->delete();
                    });

                    return response()->json(['message' => 'User refunded and deleted successfully!'], 200);
                } catch (QueryException $e) {
                    return response()->json(['error' => 'Cannot delete user due to related data: ' . $e->getMessage()], 400);
                }

                break;

            default:
                return response()->json(['message' => 'Invalid transaction type!']);
        }
    }


    // This method verifies the JVZoo IPN request
    public function jvzipnVerification(Request $request)
    {
        $secretKey = "yP5LoM3o0NZVxHSU0QmC"; // Replace with your actual JVZoo secret key
        $pop = "";
        $ipnFields = array();

        foreach ($_POST as $key => $value) {
            if ($key == "cverify") {
                continue;
            }
            $ipnFields[] = $key;
        }

        sort($ipnFields);
        foreach ($ipnFields as $field) {
            $pop = $pop . $_POST[$field] . "|";
        }

        $pop = $pop . $secretKey;
        if ('UTF-8' != mb_detect_encoding($pop)) {
            $pop = mb_convert_encoding($pop, "UTF-8");
        }

        $calcedVerify = sha1($pop);
        $calcedVerify = strtoupper(substr($calcedVerify, 0, 8));

        return $calcedVerify == $_POST["cverify"];
    }
}
