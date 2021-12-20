<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Src\DBConnector;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    public function index(): object
    {
        return view('main');
    }

    public function showAll(): object
    {
        $dbConnection = new DBConnector();

        return view('urls', [
            'paginatedUrls' => $dbConnection->getPaginatedUrls(),
            'lastChecks' => $dbConnection->getUrlsLastCheck()
        ]);
    }

    public function showOne(int $id): object
    {
        $dbConnection = new DBConnector();

        return view('url', [
            'url' => $dbConnection->getUrlInfo($id),
            'checks' => $dbConnection->getUrlChecks($id)
        ]);
    }

    public function store(Request $request): object
    {
        $validator = Validator::make(
            $request->input('url'),
            [
                'name' => 'required|url|max:255'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $parsedUrl = parse_url($request->input('url.name'));
        $name = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

        $dbConnection = new DBConnector();

        $url = $dbConnection->findName($name);

        if (!is_null($url)) {
            flash('The page already exists!')->success();
            return redirect()->route('urls.show', ['id' => $url->id]);
        }
        $id = $dbConnection->nameInsertGetId($name);

        flash('The page successfully added!')->success()->important();
        return redirect()->route('urls.show', ['id' => $id]);
    }

    /**
     * @throws InvalidSelectorException
     * @throws GuzzleException
     */
    public function checkUrl(int $id): object
    {
        $dbConnection = new DBConnector();

        try {
            $dbConnection->urlCheck($id);
        } catch (ConnectionException $exception) {
            return back()->withErrors($exception->getMessage())->withInput();
        }

        flash('The page successfully checked!')->success()->important();

        return redirect()->route('urls.show', ['id' => $id]);
    }
}
