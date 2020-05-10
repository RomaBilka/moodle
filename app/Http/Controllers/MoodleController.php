<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MoodleApiService;
use App\Http\Requests\{StoreUsers, StoreCourses};
class MoodleController extends Controller
{
    private $moodle;
    public function __construct(MoodleApiService $moodle)
    {
        $this->moodle = $moodle;
    }


    public function getCourses(StoreCourses $request)
    {
        try {
			$validated = $request->validated();
			$this->moodle->setParams($validated );
			$data=[
				'courses' => $this->moodle->getCourses($validated)
			];
			return view('courses', $data);
		} catch (\Exception $e) {
			return view('error', ['message'=>$e->getMessage()] );
		}
    }
	
	public function getUsers(StoreUsers $request)
    {
        try {
			$validated = $request->validated();
			$this->moodle->setParams($validated );
			$data=[
				'users' => $this->moodle->getUsers()
			];
			return view('users', $data);
		} catch (\Exception $e) {
			return view('error', ['message'=>$e->getMessage()] );
		}
    }
	
	public function getUserCourses(StoreUsers $request)
    {
        try {
			$validated = $request->validated();
			$this->moodle->setParams($validated );
			$data=[
				'user_courses' => $this->moodle->getUserCourses()
			];
			return view('user_courses', $data);
		} catch (\Exception $e) {
			return view('error', ['message'=>$e->getMessage()] );
		}
    }
}
