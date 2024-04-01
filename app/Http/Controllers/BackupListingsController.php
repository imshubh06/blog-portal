<?php

namespace App\Http\Controllers;

use App\Exports\BackupListingsExport;
use App\Exports\ListingsExport;
use App\Models\BackupEmail;
use App\Models\BackupListing;
use App\Models\BackupLogs;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Dropbox\Client;
use GuzzleHttp\Client as HttpClient;

class BackupListingsController extends Controller
{
    public $dropboxClient;
    /**
     * Initiate the class instance
     *
     * @return void
     */
    public function __construct(Client $dropboxClient)
    {
        $this->dropboxClient = $dropboxClient;
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function backupListings()
    {
        $getAllListings = BackupListing::all();

        return view('backup-listings.index', compact('getAllListings'));
    }

    /**
     * Export Table
     *
     * @return void
     */
    public function exportData()
    {
        $getAllListings = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])->toArray();

        $mainColums = [
            'Product id',
            'Title',
            'Description',
            'MRP',
            'Selling Price',
            'Publisher',
            'Author Name',
            'Edition',
            'Categories',
            'SKU',
            'language',
            'No of Pages',
            'Condition',
            'Binding Type',
            'Insta Mojo URL',
            'Base URL',
            'URL'
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Generate Merchant File
     *
     * @return array
     */
    public function getMerchantExportFile()
    {
        $getAllListings = [];

        $getAllListingsFromDatabase = BackupListing::all()
            ->makeHidden(['id', 'created_at', 'updated_at'])
            ->toArray();

        foreach ($getAllListingsFromDatabase as $key => $listing) {
            $listingCat = json_decode($listing['categories']);

            if (
                isset($listingCat)
                && (in_array('Stk_o', $listingCat) || in_array('stock__out', $listingCat))
            ) {
                $stock = 'out_of_stock';
            } else {
                $stock = 'in stock';
            }

            $desc = str_replace('"', ' ', $listing['description']);

            $getAllListings[$key][] = $listing['title'];
            $getAllListings[$key][] = $listing['product_id'];
            $getAllListings[$key][] = $listing['selling_price'];
            $getAllListings[$key][] = '0';
            $getAllListings[$key][] = '0';
            $getAllListings[$key][] = strtolower($listing['condition']);
            $getAllListings[$key][] = $stock;
            $getAllListings[$key][] = 'IN';
            $getAllListings[$key][] = 'Online';
            $getAllListings[$key][] = 'IN';
            $getAllListings[$key][] = 'en';
            $getAllListings[$key][] = $listing['publisher'];
            $getAllListings[$key][] = 'Online';
            $getAllListings[$key][] = '0';
            $getAllListings[$key][] = $desc;
            $getAllListings[$key][] = ' ';
            $getAllListings[$key][] = ' ';
            $getAllListings[$key][] = 'yes';
            $getAllListings[$key][] = $listing['base_url'];
            $getAllListings[$key][] = 'en';
            $getAllListings[$key][] = ' ';
            $getAllListings[$key][] = 'IN';
            $getAllListings[$key][] = '0';
            $getAllListings[$key][] = '';
        }

        $mainColums = [
            'title',
            'id',
            'price',
            'clicks',
            'unpaid clicks',
            'condition',
            'availability',
            'Free listings - disapproved or invalid',
            'channel',
            'feed label',
            'language',
            'brand',
            'channel',
            'clicks',
            'description',
            'feed label',
            'free listings - disapproved or invalid',
            'identifier exists',
            'image link',
            'language',
            'pause',
            'shipping(country)',
            'unpaid clicks',
            'update type',
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Generate Facebook Pixel File
     *
     * @return array
     */
    public function getFacebookExportFile()
    {
        $getAllListings = [];

        $getAllListingsFromDatabase = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])
            ->toArray();

        foreach ($getAllListingsFromDatabase as $key => $listing) {
            if ($listing['condition'] == 'Old') {
                $condition = 'used';
            } else if ($listing['condition'] == 'Like New') {
                $condition = 'used_like_new';
            } else {
                $condition = $listing['condition'];
            }

            $getAllListings[$key][] = $listing['product_id'];
            $getAllListings[$key][] = $listing['title'];
            $getAllListings[$key][] = $listing['description'];
            $getAllListings[$key][] = 'in stock';
            $getAllListings[$key][] = $condition ? strtolower($condition) : 'new';
            $getAllListings[$key][] = $listing['mrp'];
            $getAllListings[$key][] = $listing['url'];
            $getAllListings[$key][] = $listing['base_url'];
            $getAllListings[$key][] = $listing['publisher'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = $listing['selling_price'];
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
            $getAllListings[$key][] = '';
        }

        $mainColums = [
            'id',
            'title',
            'description',
            'availability',
            'condition',
            'price',
            'link',
            'image link',
            'brand',
            'google_product_category',
            'fb_product_category',
            'quantity_to_sell_on_facebook',
            'sale_price',
            'sale_price_effective_date',
            'item_group_id',
            'gender',
            'color',
            'size',
            'age_group',
            'material',
            'pattern',
            'shipping',
            'shipping_weight',
            'style[0]'
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Download Excel
     *
     * @return void
     */
    public function export()
    {
        if (request()->file && request()->type == 'google') {
            return Excel::download(new ListingsExport($this->getMerchantExportFile()), 'merchant-file.tsv', \Maatwebsite\Excel\Excel::TSV);
        } else if (request()->file && request()->type == 'facebook') {
            return Excel::download(new BackupListingsExport($this->getFacebookExportFile()), 'facebook-file.csv');
        } else if (request()->file && request()->type == 'report') {
            return Excel::download(new BackupListingsExport($this->exportData()), 'report.xlsx');
        } else if (request()->file && request()->type == 'sql') {
            // Return data as a downloadable file
            return response()->download($this->exportSQL()[0], 'export.sql', [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment',
            ])->deleteFileAfterSend();
        }
    }

    public function exportSQL()
    {
        // Retrieve data from the database
        $data = DB::table('backup_listings')->get();

        // Generate SQL insert statements
        $sql = 'CREATE TABLE `backup_listings` (
            `id` bigint(20) UNSIGNED NOT NULL,
            `product_id` bigint(20) NOT NULL,
            `title` text NOT NULL,
            `description` longtext NOT NULL,
            `mrp` double NOT NULL,
            `selling_price` double NOT NULL,
            `publisher` text NOT NULL,
            `author_name` varchar(191) NOT NULL,
            `edition` varchar(191) NOT NULL,
            `categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`categories`)),
            `sku` varchar(191) NOT NULL,
            `language` varchar(191) NOT NULL,
            `no_of_pages` varchar(191) NOT NULL,
            `condition` varchar(191) NOT NULL,
            `binding_type` varchar(191) NOT NULL,
            `insta_mojo_url` varchar(191) NOT NULL,
            `base_url` text NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        foreach ($data as $row) {
            $columns = [];
            $values = [];

            foreach ((array) $row as $key => $value) {
                $columns[] = "`$key`";
                $values[] = str_replace("'s", "\'s", "'$value'");
            }

            $sql .= "INSERT INTO backup_listings (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");" . PHP_EOL;
        }

        // Set file path
        $filePath = storage_path('app/export.sql');

        // Save SQL content to file
        file_put_contents($filePath, $sql);

        return [
            $filePath,
            $sql
        ];
    }

    /**
     * Read Log and Display
     *
     * @return void
     */
    public function getLoggerFile()
    {
        $logs = BackupLogs::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('settings.backup-logs', compact('logs'));
    }

    /**
     * Insert email for Backup
     *
     * @return void
     */
    public function backupEmail()
    {
        $backupMails = BackupEmail::all();

        return view('settings.backup-email', compact('backupMails'));
    }

    /**
     * Save & update email for Backup
     *
     * @return void
     */
    public function saveEmail()
    {
        try {
            $email = request()->email;
            $name = request()->name;
            $update = request()->update;

            if ((isset($update) && $existEmail = BackupEmail::find($update))) {
                $existEmail->update([
                    'email' => $email,
                    'name' => $name
                ]);
            } else {
                $this->validate(request(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:backup_emails,email',
                ]);

                BackupEmail::create([
                    'name' => $name,
                    'email' => $email
                ]);
            }

            session()->flash('success', 'Emails updated successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went Wrong!!');

            return redirect()->back();
        }
    }

    /**
     * Delete Email Successfully
     *
     * @param int $id
     * @return void
     */
    public function deleteEmail($id)
    {
        BackupEmail::find($id)->delete();

        session()->flash('success', 'Emails delete successfully');

        return redirect()->back();
    }

    /**
     * Manually Run Backup
     *
     * @return void
     */
    public function manuallyRunBackup()
    {
        try {
            Artisan::call('backup:listing'); // Replace 'command:name' with your actual command signature
            $output = Artisan::output(); // Get the output of the command if needed

            session()->flash('success', 'Backup Done Successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Export Data to SQL
     *
     * @return void
     */
    public function exportDataToSql()
    {
        // Retrieve data from the database
        $data = DB::table('backup_listings')->get();

        // Generate SQL insert statements
        $sql = 'CREATE TABLE `backup_listings` (
            `id` bigint(20) UNSIGNED NOT NULL,
            `product_id` bigint(20) NOT NULL,
            `title` text NOT NULL,
            `description` longtext NOT NULL,
            `mrp` double NOT NULL,
            `selling_price` double NOT NULL,
            `publisher` text NOT NULL,
            `author_name` varchar(191) NOT NULL,
            `edition` varchar(191) NOT NULL,
            `categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`categories`)),
            `sku` varchar(191) NOT NULL,
            `language` varchar(191) NOT NULL,
            `no_of_pages` varchar(191) NOT NULL,
            `condition` varchar(191) NOT NULL,
            `binding_type` varchar(191) NOT NULL,
            `insta_mojo_url` varchar(191) NOT NULL,
            `base_url` text NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        foreach ($data as $row) {
            $columns = [];
            $values = [];

            foreach ((array) $row as $key => $value) {
                $columns[] = "`$key`";
                $values[] = "'$value'";
            }

            $sql .= "INSERT INTO backup_listings (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");" . PHP_EOL;
        }

        // Set file path
        $filePath = storage_path('app/export.sql');

        // Save SQL content to file
        file_put_contents($filePath, $sql);

        // Return data as a downloadable file
        return response()->download($filePath, 'export.sql', [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment',
        ])->deleteFileAfterSend();
    }
}
