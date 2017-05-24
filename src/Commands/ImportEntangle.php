<?php

namespace Knowfox\Entangle\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\CookieJar;
use Knowfox\Entangle\Models\ImportedEvent;
use Knowfox\Entangle\Models\ImportedUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImportEntangle extends Command
{
    const ENTANGLE_URL = 'https://knowfox.com';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entangle:import  {--url=} {--users=} {token} {sqlitedb}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import entangle entities from an SQlite database';

    private function getCsrfToken($url, $token)
    {
        $res = $this->client->request('GET', $url . '/entangle', [
            'query' => [
                'token' => $token,
            ],
            'cookies' => $this->jar,
            'debug' => true,
        ]);

        if ($res->getHeaderLine('content-type') != 'application/json') {
            throw new NotFoundHttpException('Cannot read CSRF token');
        }
        $response = json_decode($res->getBody());
        if (!empty($response->status) && $response->status == 'error') {
            throw new \Exception($response->message);
        }

        return $response->csrf_token;
    }

    private function importUser($url, $token, $csrf_token, $user)
    {
        $res = $this->client->request('POST', $url . '/entangle', [
            'form_params' => [
                'token' => $token,
                'type' => 'user',

                'name' => $user->realname,
                'email' => $user->email,
                'timelines' => $user->timelines->each(function ($item) {
                    return $item->getAttributes();
                })->toArray(),
            ],
            'debug' => false,
            'cookies' => $this->jar,
            'headers' => [
                'X-CSRF-TOKEN' => $csrf_token,
            ]
        ]);

        if ($res->getHeaderLine('content-type') != 'application/json') {
            throw new NotFoundHttpException('Cannot read user data: ' . $res->getBody()->getContents());
        }
        $result = json_decode($res->getBody());
        if (!empty($result->status) && $result->status == 'success') {
            return $result->person;
        }
        else {
            throw \Exception($result->message);
        }
    }

    private function importEvent($url, $token, $csrf_token, $person_id, $event)
    {
        $res = $this->client->request('POST', $url . '/entangle', [
            'form_params' => [
                'token' => $token,
                'type' => 'event',
                'person_id' => $person_id,

                'timeline_title' => $event->timeline->title,

                'source_id' => $event->source_id,
                'replicated' => $event->replicated,
                'public' => $event->public,
                'title' => $event->title,
                'description' => $event->description,
                'date_from' => $event->date_from,
                'duration' => $event->duration,
                'duration_unit' => $event->duration_unit,
                'date_to' => $event->date_to,
                'anniversary' => $event->anniversary,
                'created' => $event->created,
            ],
            'debug' => false,
            'cookies' => $this->jar,
            'headers' => [
                'X-CSRF-TOKEN' => $csrf_token,
            ]
        ]);

        if ($res->getHeaderLine('content-type') != 'application/json') {
            throw new NotFoundHttpException('Cannot read user data: ' . $res->getBody()->getContents());
        }
        $result = json_decode($res->getBody());
        if (!empty($result->status) && $result->status == 'success') {
            return $result->event;
        }
        else {
            throw \Exception($result->message);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $url = $this->option('url');
        if (empty($url)) {
            $url = self::ENTANGLE_URL;
        }
        $user_names = $this->option('users');

        $token = $this->argument('token');
        $sqlitedb = $this->argument('sqlitedb');

        $this->info("Starting import of entangle entities from {$sqlitedb} with token {$token} into {$url} ...");

        Config::set('database.connections.sqlite.database', $sqlitedb);

        $this->client = new GuzzleClient([
            'http_errors' => false,
        ]);

        // The CSRF validation is linked to the Laravel session
        $this->jar = new CookieJar();

        if (empty($user_names)) {
            $users = ImportedUser::with(['timelines'])
                ->all();
        }
        else {
            $users = ImportedUser::with(['timelines'])
                ->whereIn('username', explode(',', $user_names))
                ->get();
        }

        $csrf_token = $this->getCsrfToken($url, $token);

        foreach ($users as $imported_user) {
            $this->info('Importing user ' . $imported_user->username . ' ...');
            $person = $this->importUser($url, $token, $csrf_token, $imported_user);

            $events = ImportedEvent::with('timeline')
                ->whereIn('timeline_id', $imported_user->timelines->pluck('id'))
                ->get();

            foreach ($events as $imported_event) {
                $this->info(' - Importing event ' . $imported_event->title . ' ...');
                $this->importEvent($url, $token, $csrf_token, $person->id, $imported_event);
            }
        }
    }
}
