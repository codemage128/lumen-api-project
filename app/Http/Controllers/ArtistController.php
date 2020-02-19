<?php


namespace App\Http\Controllers;


use App\Artist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class ArtistController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $artists = Artist::all();
        return $this->success(['artists' => $artists]);
    }

    public function list(Request $request)
    {
        $searchKey = '';
        if ($request->search_key)
            $searchKey = $request->search_key;
        $query = Artist::where('code', 'like', '%' . $searchKey . '%')
            ->orWhere('name', 'like', '%' . $searchKey . '%')
            ->orWhere('email', 'like', '%' . $searchKey . '%')
            ->orderBy($request->column, $request->order)->paginate($request->per_page ?? 5);
        return $this->success($query);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'code' => 'required',
                'name' => 'required',
                'email' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        try {
            $data = $request->all();
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $artist = Artist::create($data);

            return $this->success(['artist' => $artist, 'message' => 'Artist is created successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Artist create failed!']);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        try {
            $artist = Artist::find($request->id);
            $artist->code = $request->code;
            $artist->name = $request->name;
            $artist->email = $request->email;
            $artist->save();
            return $this->success(['artist' => $artist, 'message' => 'Artist is updated successfully!']);
        } catch (\Exception $exception) {
            return $this->fail(['Artist update failed!']);
        }
    }

    public function delete($id)
    {
        try {
            $artist = Artist::find($id);
            $artist->delete();
            return $this->success(['artist' => $artist, 'message' => 'Artist is deleted successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['Artist create failed!']);
        }
    }
}
