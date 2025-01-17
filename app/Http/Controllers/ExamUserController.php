<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExamResource;
use App\Http\Resources\ExamUserResource;
use App\Http\Resources\UserResource;
use App\Repositories\ExamRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamUserController extends Controller
{
    private $repository;

    public function __construct(ExamRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $result = $this->repository->listUser($request->all());
        $resource = ExamUserResource::collection($result);
        return $this->success($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request)
    {
        $rules = [
            'uid' => 'required',
        ];
        $request->validate($rules);
        $timeRange = $request->get('time_range', []);
        $begin = isset($timeRange[0]) ? Carbon::parse($timeRange[0])->toDateTimeString() : null;
        $end = isset($timeRange[1])? Carbon::parse($timeRange[1])->toDateTimeString() : null;

        $result = $this->repository->assignToUser($request->uid, $request->exam_id, $begin, $end);
        $resource = new ExamUserResource($result);
        return $this->success($resource, 'Assign exam success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        $result = $this->repository->removeExamUser($id);
        return $this->success($result, 'Remove user exam success!');
    }

}
