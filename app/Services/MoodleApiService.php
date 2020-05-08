<?php
declare(strict_types=1); 
namespace App\Services;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
class MoodleApiService
{
    private $base_url = '';
	private $alias = '';
	private $data = [];
	
    public function __construct()
    {
        $this->base_url =  Config::get('moodle.base_url');
		$this->data = [
			'moodlewsrestformat' => 'json',
			'wstoken' => Config::get('moodle.token')
		];
        $this->client = new Client();
    }

    public function getCourses():array
    {
        $this->alias = 'local_test_courses';
        return $this->sendRequest();
    }

    public function getUsers():array
    {
        $this->alias = 'local_test_users';
        return $this->sendRequest();
    }
    public function getUserCourses():array
    {
        $this->alias = 'local_test_user_courses';
        return $this->sendRequest();
    }
	
    private function sendRequest():array
    {
		$this->data['wsfunction'] = $this->alias;
		$result = $this->client->request('POST', $this->base_url, [
						'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
						'form_params' => $this->data,
						'verify' => false,
					]
				);
		$data = json_decode($result->getBody()->getContents(), true);
		if(isset($data['exception'])){
			throw new \Exception($data['message']);
		}else{
			return $data;
		}
    }

}
