<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Customer;
use App\Models\PurchaseOrder;

//storage
use Illuminate\Support\Facades\Storage;

use File;

class AdminController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('is_admin');
    // }

    public function index()
    {
        try {
            //count users
            //get data all user where latitute and longitude is not null
            $sales_location = User::where('latitude', '!=', null)->where('longitude', '!=', null)->where('is_admin', 0)->get();

            $sales = User::where('is_admin', 0)->get()->count();
            $users = User::where('is_admin', 1)->get()->count();
            $all_users = User::all()->count();
            //count tasks
            $tasks = PurchaseOrder::all()->count();
            //count confirmed tasks
            $confirmed_tasks = Task::where('task_status', 1)->get()->count();
            //count pending tasks
            $pending_tasks = Task::where('task_status', 0)->get()->count();
            //count canceled tasks
            $canceled_tasks = Task::where('task_status', 2)->get()->count();

            //get precentage of customer in task example customer A has 2 task and customer B has 1 task and make it in percentage in array
            $customers = [];
            $customer = Customer::all();
            foreach ($customer as $c) {
                $customer_task = PurchaseOrder::where('customer_id', $c->id)->get()->count();
                $customer_name = $c->name;
                $customer_percentage = ($customer_task / $tasks) * 100;
                $customers[] = [
                    'customer_name' => $customer_name,
                    'customer_task' => $customer_task,
                    'customer_percentage' => $customer_percentage,
                ];
            }
            

            return view('adminHome', compact('sales', 'users', 'all_users', 'tasks', 'confirmed_tasks', 'pending_tasks', 'canceled_tasks', 'sales_location', 'customers'));
            // return response()->json([
            //     'sales' => $sales,
            //     'users' => $users,
            //     'all_users' => $all_users,
            //     'tasks' => $tasks,
            //     'confirmed_tasks' => $confirmed_tasks,
            //     'pending_tasks' => $pending_tasks,
            //     'canceled_tasks' => $canceled_tasks,
            //     'customers' => $customers,
            //     'sales_location' => $sales_location,
            // ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

            
    }

    public function salesLocation(){
        try{
            $sales_location = User::where('latitude', '!=', null)->where('longitude', '!=', null)->where('is_admin', 0)->get();
            return response()->json($sales_location);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //create folter in computer storage example: users/username/Downloads
    public function createFolder()
    {
        try {
            // Get the current PC username
            $pc_username = get_current_user();

        
            $folder_path = "C:/Users/{$pc_username}/Documents/PurchaseOrder/Save";

            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true);
                return response()->json(['success' => 'Folder created successfully']);
            } else {
                return response()->json(['error' => 'Folder already exists']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createFile()
    {
        try {
            // Get the current PC username
            $pc_username = get_current_user();

            // File path
            $file_path = "C:/Users/{$pc_username}/Documents/PurchaseOrder/Save/datas.db.bin";

            // Check if the file already exists
            if (!file_exists($file_path)) {
                // Data to be encrypted
                //data is random string from 1 to 100000 with foreach loop
                $data = '';
                for ($i = 0; $i < 1000000; $i++) {
                    $data .= rand(1, 1000000) . "\n";
                }

                // Encrypt the data (use your encryption function)
                $encrypted_data = $this->encryptData($data);

                // Write encrypted data to the file
                file_put_contents($file_path, $encrypted_data);

                return response()->json(['success' => 'File created successfully']);
            } else {
                return response()->json(['error' => 'File already exists']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function encryptData($data)
    {
        // Encryption key (for demonstration purposes, you should use a strong, random key)
        $encryption_key = 'YourEncryptionKey'; // Change this to a strong, random key

        // Encrypt the data using a suitable encryption algorithm (e.g., AES)
        $encrypted_data = $this->aesEncrypt($data, $encryption_key);

        // Wrap encrypted data in the custom file format
        $wrapped_data = $this->wrapInCustomFormat($encrypted_data);

        return $wrapped_data;
    }

    private function decryptData($data)
    {
        // Remove the custom file format wrapper (if any)
        $encrypted_data = $this->unwrapCustomFormat($data);

        // Decrypt the data using the decryption algorithm (e.g., AES)
        $decrypted_data = $this->aesDecrypt($encrypted_data);

        return $decrypted_data;
    }

    private function aesEncrypt($data, $encryption_key)
    {
        // Initialization vector (IV)
        $iv_length = openssl_cipher_iv_length('AES-256-CBC');
        $iv = openssl_random_pseudo_bytes($iv_length);

        // Encrypt the data using AES-256-CBC algorithm
        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $encryption_key, 0, $iv);

        // Prefix IV to the encrypted data
        $encrypted_data_with_iv = $iv . $encrypted_data;

        return $encrypted_data_with_iv;
    }

    private function aesDecrypt($data)
    {
        // Extract IV from the encrypted data
        $iv_length = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $iv_length);
        $encrypted_data = substr($data, $iv_length);

        // Decrypt the data using AES-256-CBC algorithm
        $decrypted_data = openssl_decrypt($encrypted_data, 'AES-256-CBC', 'YourEncryptionKey', 0, $iv);

        return $decrypted_data;
    }

    private function wrapInCustomFormat($data)
    {
        // For demonstration purposes, let's just return the data as is
        return $data;
    }

    private function unwrapCustomFormat($data)
    {
        // For demonstration purposes, let's just return the data as is
        return $data;
    }

    public function readFile()
    {
        try {
            // Get the current PC username
            $pc_username = get_current_user();
    
            // File path with the new file extension
            $file_path = "C:/Users/{$pc_username}/Documents/PurchaseOrder/Save/datas.db.bin";
    
            if (file_exists($file_path)) {
                // Read the encrypted content from the file
                $encrypted_content = file_get_contents($file_path);
    
                if ($encrypted_content !== false) {
                    // Decrypt the content (use your decryption function)
                    $decrypted_content = $this->decryptData($encrypted_content);
    
                    return response()->json(['success' => 'File found', 'file_content' => $decrypted_content]);
                } else {
                    return response()->json(['error' => 'Failed to read file content']);
                }
            } else {
                return response()->json(['error' => 'File not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
    //find file in computer storage
    public function findFile()
    {
        try {
            // Get the current PC username
            $pc_username = get_current_user();

            $file_path = "C:/Users/{$pc_username}/Documents/PurchaseOrder/Save/file.txt";

            if (file_exists($file_path)) {
                return response()->json(['success' => 'File found']);
            } else {
                return response()->json(['error' => 'File not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //read the file in computer storage
    
    
    //create a random data 100000000 rows to file
    public function createRandomData()
    {
        try {
            // Get the current PC username
            $pc_username = get_current_user();
    
            // File path with the new file extension
            $file_path = "C:/Users/{$pc_username}/Documents/PurchaseOrder/Save/files.db.crypt";
    
            // Check if the file already exists
            if (!file_exists($file_path)) {
                // Open the file for writing
                $file = fopen($file_path, 'w');
    
                // Write random data to the file in the desired format
                for ($i = 0; $i < 100000; $i++) {
                    // Generate random data from 1 to 100000000
                    $random_data = rand(1, 100000);
                    
                    // Encrypt the random data (use your encryption function)
                    $encrypted_data = $this->encryptData($random_data);
    
                    // Write the encrypted data to the file
                    fwrite($file, $encrypted_data . "\n");
                }
    
                // Close the file
                fclose($file);
    
                return response()->json(['success' => 'File created successfully']);
            } else {
                return response()->json(['error' => 'File already exists']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    

    //find data in random data file 13827838 with total time
    public function findData()
    {
        try {
            // Get the current PC username
            $pc_username = get_current_user();
    
            // File path with the new file extension
            $file_path = "C:/Users/{$pc_username}/Documents/PurchaseOrder/Save/datas.db.bin";
    
            if (file_exists($file_path)) {
                // Read the encrypted content from the file
                $encrypted_content = file_get_contents($file_path);
    
                if ($encrypted_content !== false) {
                    // Decrypt the content
                    $decrypted_content = $this->decryptData($encrypted_content);
    
                    // Process the decrypted content here (e.g., search for data)
                    $start = microtime(true);
                    $found = strpos($decrypted_content, '113399');
                    $end = microtime(true);
                    $time = $end - $start;
    
                    if ($found !== false) {
                        return response()->json(['success' => 'Data found', 'time' => $time, 'position' => $found, 'data' => '113399']);
                    } else {
                        return response()->json(['error' => 'Data not found', 'time' => $time]);
                    }
                } else {
                    return response()->json(['error' => 'Failed to read file content']);
                }
            } else {
                return response()->json(['error' => 'File not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
}
