<?php
declare(strict_types=1); 
namespace App\Services;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class MoodleApiService
{
    private $base_url = '';
	private $params = [];
    public function __construct()
    {
        $this->base_url = Config::get('moodle.base_url');
		$this->params = [
			'moodlewsrestformat' => 'json',
			'wstoken' => Config::get('moodle.token')
		];
    }
	public function setParams(array $params=[]):void
	{
		$this->params = $params + $this->params;
	}
    public function getCourses():array
    {
        return $this->sendRequest('local_test_courses');
    }

    public function getUsers():array
    {
        return $this->sendRequest('local_test_users');
    }
    public function getUserCourses():array
    {
        return $this->sendRequest('local_test_user_courses');
    }
	
    private function sendRequest(string $alias):array
    {
		$this->params['wsfunction'] = $alias;
		$response = Http::asForm()->post($this->base_url, $this->params)->json();
		if(isset($response['exception'])){
			throw new \Exception($response['message']);
		}else{
			return $response;
		}
    }

}
