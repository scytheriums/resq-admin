<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Review;
use Yajra\DataTables\Facades\DataTables;
class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-reviews'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-reviews'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-reviews'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-reviews'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $drivers = Review::query()
                ->with('driver', 'user','order')
                ->when($request->driver_id, function ($query) use ($request) {
                    return $query->where('driver_id', $request->driver_id);
                });
            return DataTables::of($drivers)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.reviews.destroy', $data->id) . '" data-name="Review Order ' . $data->order->order_number . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    return ' <div class="d-flex align-items-center">
                                ' . $delete . '
                            </div>';
                })
                ->editColumn('rating', function ($data) {
                    return $data->rating.' / 5';
                })
                ->editColumn('comment', function ($data) {
                    $fullComment = e($data->comment);
                    $shortComment = strlen($data->comment) > 50 ? e(substr($data->comment, 0, 50)) . '...' : $fullComment;
                    if (strlen($data->comment) > 50) {
                        $id = 'comment-toggle-' . $data->id;
                        return '<span id="' . $id . '-short">' . $shortComment . 
                            ' <a href="javascript:void(0);" onclick="document.getElementById(\'' . $id . '-short\').style.display=\'none\';document.getElementById(\'' . $id . '-full\').style.display=\'inline\';">Show more</a></span>' .
                            '<span id="' . $id . '-full" style="display:none;">' . $fullComment . 
                            ' <a href="javascript:void(0);" onclick="document.getElementById(\'' . $id . '-full\').style.display=\'none\';document.getElementById(\'' . $id . '-short\').style.display=\'inline\';">Show less</a></span>';
                    }
                    return $fullComment;
                })
                ->rawColumns(['action', 'comment'])
                ->make(true);
        }
        $title = 'Rate & Review';

        // Query for average rating
        $avgRating = Review::avg('rating');

        // Query for driver with worst average rating (only considering ratings <= 2)
        $worstDriver = Review::selectRaw('driver_id, AVG(rating) as avg_bad_rating, COUNT(*) as bad_count')
            ->groupBy('driver_id')
            ->orderBy('avg_bad_rating')
            ->orderByDesc('bad_count')
            ->with('driver')
            ->first();

        $worstDriverArr = null;
        if ($worstDriver && $worstDriver->driver) {
            $worstDriverArr = [
                'name' => $worstDriver->driver->name,
                'avg_bad_rating' => round($worstDriver->avg_bad_rating, 2)
            ];
        }

        return view('admin.review.index', compact('title', 'avgRating', 'worstDriverArr'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['success' => 'Review berhasil dihapus']);
    }
}
