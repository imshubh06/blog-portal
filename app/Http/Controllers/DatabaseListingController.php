<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Jobs\PublishProducts;
use App\Jobs\UpdateProducts;
use App\Models\BackupListing;
use App\Models\Job;
use App\Models\Listing;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleService;
use Carbon\Carbon;

class DatabaseListingController extends Controller
{
    /**
     * Constructor
     *
     * @param GoogleService $googleService
     */
    public function __construct(protected GoogleService $googleService) {}

    /**
     * Display the listing of the resources
     */
    public function index()
    {
        $googlePosts = Listing::with('created_by_user')
            ->orderBy('created_at', 'desc')
            ->where('categories', 'LIKE', '%' . request()->category . '%')
            ->whereNull('product_id');

        if (request()->user != 'all') {
            $googlePosts = $googlePosts->where('created_by', request()->user);
        }

        if (request()->status) $googlePosts = $googlePosts->where('status', request()->status);

        if (!auth()->user()->hasRole('Super Admin')) {
            $googlePosts = $googlePosts->where('created_by', auth()->user()->id);
        }

        $googlePosts = $googlePosts->paginate(150);

        $allCounts = Listing::whereNull('product_id')->count();

        $pendingCounts = Listing::where('status', 0)
            ->whereNull('product_id');

        $rejectedCounts = Listing::where('status', 2)
            ->whereNull('product_id');

        if (!auth()->user()->hasRole('Super Admin')) {
            $pendingCounts = $pendingCounts->where('created_by', auth()->user()->id);

            $rejectedCounts = $rejectedCounts->where('created_by', auth()->user()->id);
        }

        $pendingCounts = $pendingCounts->count();

        $rejectedCounts = $rejectedCounts->count();

        $users = User::where('status', 1)->get();

        return view('database-listing.index', compact('googlePosts', 'allCounts', 'pendingCounts', 'rejectedCounts', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::withoutVerifying()
            ->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        $siteSetting = SiteSetting::first();

        return view('database-listing.create', compact('categories', 'siteSetting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        try {
            $data = [
                '_token' => $request->_token,
                'title' => $request->title,
                'description' => $request->description,
                'mrp' => $request->mrp,
                'selling_price' => $request->selling_price,
                'publisher' => $request->publication,
                'author_name' => $request->author_name,
                'edition' => $request->edition,
                'categories' => $request->label,
                'sku' => $request->sku,
                'language' => $request->language,
                'no_of_pages' => $request->pages,
                'condition' => $request->condition,
                'binding_type' => $request->binding,
                'insta_mojo_url' => $request->url,
                'images' => $request->images[0],
                'multiple_images' => $request->multipleImages,
                'status' => 0,
                'created_by' => auth()->user()->id
            ];

            $listing = Listing::create($data);

            UserListingInfo::create([
                'image' => $request->images[0],
                'title' => $request->title,
                'created_by' => auth()->user()->id,
                'approved_by' => null,
                'approved_at' => null,
                'status' => 0,
                'status_listing' => 'Created',
                'listings_id' => $listing->id,
            ]);

            $this->updateTheCount('Created', 'create_count');

            if ($listing) {
                session()->flash('success', 'Listing created successfully');

                return redirect()->back();
            }

            session()->flash('error', 'Someting went wrong');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went Wrong!!');

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $listing = Listing::find($id);

        $siteSetting = SiteSetting::first();

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::withoutVerifying()
            ->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        return view('database-listing.edit', compact('listing', 'siteSetting', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
    {
        $data = [
            '_token' => $request->_token,
            'title' => $request->title,
            'description' => $request->description,
            'mrp' => $request->mrp,
            'selling_price' => $request->selling_price,
            'publisher' => $request->publication,
            'author_name' => $request->author_name,
            'edition' => $request->edition,
            'categories' => $request->label,
            'sku' => $request->sku,
            'language' => $request->language,
            'no_of_pages' => $request->pages,
            'condition' => $request->condition,
            'binding_type' => $request->binding,
            'insta_mojo_url' => $request->url,
            'images' => $request->images[0],
            'multiple_images' => $request->multipleImages,
            'created_by' => auth()->user()->id
        ];

        $listing = Listing::find($id);

        if ($request->status == 2) {
            $this->updateTheCount('Created', 'reject_count');

            $data['status'] = request()->status;

            $additionalInfo = UserListingInfo::where('image', $request->images[0])
                ->where('title', request()->title)
                ->first();

            $additionalInfo->update([
                'status' => request()->status
            ]);
        } else if ($request->status == 1) {
            $additionalInfo = UserListingInfo::where('image', $request->images[0])
                ->where('title', request()->title)
                ->first();

            $additionalInfo->update([
                'status' => request()->status
            ]);
        }

        if ($listing->update($data)) {
            session()->flash('success', 'Listing Updated successfully');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $listing = Listing::find($id);

        UserListingInfo::where('title', $listing->title)
            ->where('image', $listing->images)
            ->delete();

        if ($listing->delete()) {

            if (request()->edit) {
                $this->updateTheCount('Edited', 'delete_count');
            } else {
                $this->updateTheCount('Created', 'delete_count');
            }

            session()->flash('success', 'Listing deleted succesfully.');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');

        return redirect()->back();
    }

    /**
     * Update Status of Listing
     *
     * @return void
     */
    public function updateStatus()
    {
        // For Publishing and saving to Draft
        if (request()->publish == 3 || request()->publish == 4) {
            foreach (request()->ids as $loopIndex => $id) {
                $job = PublishProducts::dispatch($id, request()->publish, auth()->user()->id)
                    ->delay(now()->addSeconds(10 * $loopIndex));

                $loopIndex++;

                $jobRow = Job::orderBy('id', 'desc')->first();

                if ($jobRow) {
                    Listing::find($id)->update([
                        'job_id' => $jobRow->id,
                        'error' => 'Queued',
                    ]);
                }
            }
        } else if (request()->publish == 5) {
            foreach (request()->ids as $loopIndex => $id) {
                $job = UpdateProducts::dispatch($id, request()->publish, auth()->user()->id)
                    ->delay(now()->addSeconds(10 * $loopIndex));

                $loopIndex++;

                $jobRow = Job::orderBy('id', 'desc')->first();

                if ($jobRow) {
                    Listing::find($id)->update([
                        'job_id' => $jobRow->id,
                        'error' => 'Queued',
                    ]);
                }
            }
        } else {
            $listings = Listing::whereIn('id', request()->formData[1])
                ->get();

            $status = request()->formData[0]['value'];

            foreach ($listings as $listing) {
                $userCount = UserListingCount::where('user_id', $listing->created_by)
                    ->whereDate('date', $listing->created_at)
                    ->first();

                if ($status == 2 && !$userCount) {
                    UserListingCount::create([
                        'user_id' => $listing->created_by,
                        'approved_count' => 0,
                        'reject_count' => 1,
                    ]);
                } else if ($status == 2 && $userCount) {
                    $userCount->update([
                        'reject_count' => ++$userCount->reject_count,
                    ]);
                }

                if ($status == 0 && $userCount) {
                    $userCount->update([
                        'reject_count' => --$userCount->reject_count,
                    ]);
                }
            }

            $listings->map(function ($list) use ($status) {
                $additionalInfo = UserListingInfo::where("image", $list->images)
                    ->where('title', $list->title)
                    ->first();

                $additionalInfo->update([
                    'status' => $status
                ]);

                $list->update(['status' => $status]);
            });

            return true;
        }
    }

    /**
     * Get Publish Pending Listing
     *
     * @return void
     */
    public function getPublishPending()
    {
        $googlePosts = Listing::with('created_by_user')
            ->orderBy('created_at', 'desc')
            // ->where('categories', 'LIKE', '%' . request()->category . '%')
            ->whereNotNull('product_id');

        $allCounts = Listing::whereNotNull('product_id');

        $pendingCounts = Listing::whereNotNull('product_id')->where('status', 0);

        $rejectedCounts = Listing::whereNotNull('product_id')->where('status', 2);

        if (!auth()->user()->hasRole('Super Admin')) {
            $pendingCounts = $pendingCounts->where('created_by', auth()->user()->id);

            $rejectedCounts = $rejectedCounts->where('created_by', auth()->user()->id);

            $googlePosts->where('created_by', auth()->user()->id);
        }

        if (isset(request()->user) && request()->user != 'all') {
            $googlePosts->where('created_by', request()->user);

            $pendingCounts = $pendingCounts->where('created_by', request()->user);

            $rejectedCounts = $rejectedCounts->where('created_by', request()->user);

            $allCounts = $allCounts->where('created_by', request()->user);
        }

        $pendingCounts = $pendingCounts->count();

        $rejectedCounts = $rejectedCounts->count();

        $allCounts = $allCounts->count();

        $googlePosts = $googlePosts->paginate(150);

        $users = User::where('status', 1)->get();

        return view('database-listing.publish-pending', compact('users', 'allCounts', 'googlePosts', 'pendingCounts', 'rejectedCounts'));
    }

    /**
     * Edit the Publish 
     *
     * @param int $id
     * @return void
     */
    public function editPublish($id)
    {
        $listing = Listing::find($id);

        $siteSetting = SiteSetting::first();

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::withoutVerifying()
            ->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        return view('database-listing.publish-edit', compact('listing', 'siteSetting', 'categories'));
    }

    /**
     * Edit Post in DB
     */
    public function editInDB($id)
    {
        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::withoutVerifying()->get($url . '/feeds/posts/default/' . $id . '?alt=json');

        $products = (object) ($response->json()['entry']);

        $images = [];
        $doc = new \DOMDocument();
        if (((array)($products->content))['$t']) {
            @$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . ((array)($products->content))['$t']);
        }
        $td = $doc->getElementsByTagName('td');
        $a = $doc->getElementsByTagName('a');
        $div = $doc->getElementsByTagName('div');

        $price = explode('-', $td->item(1)->textContent ?? '');
        $selling = $price[0] ?? 0;
        $mrp = $price[1] ?? 0;
        $image = $doc->getElementsByTagName("img")?->item(0)?->getAttribute('src');
        $productId = explode('-', ((array)$products->id)['$t'])[2];
        $productTitle = ((array)$products->title)['$t'];
        $published = ((array)$products->published)['$t'];
        $updated = ((array)$products->updated)['$t'];

        $edition_author_lang = explode(',', $td->item(7)->textContent ?? '');
        $author_name = $edition_author_lang[0];
        $edition = $edition_author_lang[1] ?? '';
        $lang = $edition_author_lang[2] ?? '';

        $bindingType = explode(',', $td->item(9)->textContent ?? '');
        $binding = $bindingType[0] ?? '';
        $condition = $bindingType[1] ?? '';

        $page_no = $td->item(11)->textContent ?? '';

        $instaUrl = "";
        for ($i = 0; $i < $a->length; $i++) {
            $item = trim($a->item($i)->textContent);
            if ($item == 'BUY AT INSTAMOJO') {
                $instaUrl = $a->item($i)->getAttribute('href');
            }
        }

        $sku = '';
        $publication = '';
        for ($i = 0; $i < $td->length; $i++) {
            if ($td->item($i)->getAttribute('itemprop') == 'sku') {
                $sku = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'color') {
                $publication = trim($td->item($i)->textContent);
            }
        }

        $desc = [];
        for ($i = 0; $i < $div->length; $i++) {
            if ($div->item($i)->getAttribute('class') == 'pbl box dtmoredetail dt_content') {
                // Access the individual DOMNode
                $node = $div->item($i);

                // Get the inner HTML content of the node (without the <div> tag)
                $innerHTML = '';
                foreach ($node->childNodes as $child) {
                    $innerHTML .= $node->ownerDocument->saveHTML($child);
                }

                // Add the inner HTML content to the $desc array
                $desc[] = $innerHTML;
            }
        }

        if ($doc->getElementsByTagName("img")->length > 1) {
            for ($i = 0; $i < $doc->getElementsByTagName("img")->length; $i++) {
                $imageElement = $doc->getElementsByTagName("img")->item($i);
                $images[] = $imageElement->getAttribute('src');
            }
        }

        $link = '';
        if (isset($products->link[4])) {
            $link = $products->link[4]['href'];
        } else {
            $link = $products->link[2]['href'];
        }

        if (strlen($publication) > 100) {
            $publication = explode(',', $publication)[0];
        }

        $productTitle = str_replace("'", "", trim($productTitle));

        $allInfo = [
            'product_id' => trim($productId),
            'title' => trim($productTitle),
            'description' => $desc[0] ?? '',
            'mrp' => (int) trim($mrp),
            'selling_price' => (int) trim($selling),
            'publisher' => trim($publication) ?? 'Exam360',
            'author_name' => trim($author_name),
            'edition' => trim($edition),
            'categories' => (collect($products->category ?? [])->pluck('term')->toArray()),
            'sku' => trim($sku),
            'language' => trim($lang),
            'no_of_pages' => trim($page_no),
            'binding_type' => trim($binding),
            'condition' => trim($condition),
            'insta_mojo_url' => trim($instaUrl),
            'images' => $image ?? '',
            'multipleImages' => $images,
            'url' => $link,
            'created_by' => auth()->user()->id,
            'status' => 0,
            'baseimg' => $image ?? '',
            'multiple_images' => $images ?? '',
        ];

        $response = Http::withoutVerifying()
            ->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        $siteSetting = SiteSetting::first();

        $labels = (collect($products->category ?? [])->pluck('term')->toArray());

        $listing = (object) ($allInfo);

        return view('database-listing.publish-edit', compact('categories', 'listing', 'labels', 'siteSetting', 'productId', 'productTitle'));
    }

    /**
     * Edit the post in Database
     */
    public function publshInDB($id)
    {
        $allInfo = [
            'product_id' => trim(request()->database),
            'title' => trim(request()->title),
            'description' => request()->description ?? '',
            'mrp' => (int) trim(request()->mrp),
            'selling_price' => (int) trim(request()->selling_price),
            'publisher' => trim(request()->publication) ?? 'Exam360',
            'author_name' => trim(request()->author_name),
            'edition' => trim(request()->edition),
            'categories' => request()->label,
            'sku' => trim(request()->sku),
            'language' => trim(request()->language),
            'no_of_pages' => trim(request()->pages),
            'binding_type' => trim(request()->binding),
            'condition' => trim(request()->condition),
            'insta_mojo_url' => trim(request()->url),
            'images' => request()->images ?? "",
            'multiple_images' => request()->multipleImages ? json_encode(request()->multipleImages) : null,
            'url' => request()->product_url,
            'created_by' => auth()->user()->id,
            'status' => 0,
        ];

        $listing = Listing::create($allInfo);

        $data = [
            'image' => $listing->images[0],
            'title' => $listing->title,
            'created_by' => auth()->user()->id,
            'approved_by' => null,
            'approved_at' => null,
            'status' => 0,
            'status_listing' => 'Edited',
            'listings_id' =>  $listing->id
        ];

        UserListingInfo::create($data);

        $this->updateTheCount('Edited', 'create_count');

        session()->flash('success', 'Pending for Approval');

        return redirect()->route('inventory.index', ['startIndex' => '1', 'category' => 'Product']);
    }

    /**
     * List the Articles
     *
     * @return void
     */
    public function articles()
    {
        $articles = BackupListing::whereRaw('NOT JSON_CONTAINS(categories, \'"\Product"\')')
            ->paginate(150);

        return view('listing.articles', compact('articles'));
    }

    /**
     * Update or Create Count
     *
     * @param string $status
     * @return void
     */
    public function updateTheCount($status, $column)
    {
        // Get the current date
        $currentDate = Carbon::now()->toDateString(); // This will give you 'YYYY-MM-DD' format

        // Check if a record exists for the current date and user
        $userListingCount = UserListingCount::where('user_id', auth()->user()->id)
            ->where('status', $status)
            ->whereDate('created_at', $currentDate)
            ->first();

        if ($userListingCount) {
            // If record exists, increment the approved_count
            $userListingCount->increment($column);
            $userListingCount->status = $status; // Update status if needed
            $userListingCount->save();
        } else {
            // If no record exists, create a new record
            UserListingCount::create([
                'user_id' => auth()->user()->id,
                $column => 1,
                'status' => $status,
            ]);
        }
    }
}
