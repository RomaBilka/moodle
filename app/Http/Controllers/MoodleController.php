<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MoodleApiService;
class MoodleController extends Controller
{
    private $moodle;
    public function __construct(MoodleApiService $moodle)
    {
        $this->moodle = $moodle;
    }


    public function getCourses()
    {
        try {
			$data=[
				'courses' => $this->moodle->getCourses()
			];
			return view('courses', $data);
		} catch (\Exception $e) {
			return view('error', ['message'=>$e->getMessage()] );
		}
    }
	
	public function getUsers()
    {
        try {
			$data=[
				'users' => $this->moodle->getUsers()
			];
			return view('users', $data);
		} catch (\Exception $e) {
			return view('error', ['message'=>$e->getMessage()] );
		}
    }
	
	public function getUserCourses()
    {
        try {
			$data=[
				'user_courses' => $this->moodle->getUserCourses()
			];
			return view('user_courses', $data);
		} catch (\Exception $e) {
			return view('error', ['message'=>$e->getMessage()] );
		}
    }
}
