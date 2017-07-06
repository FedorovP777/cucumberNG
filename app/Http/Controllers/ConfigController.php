<?php

namespace App\Http\Controllers;

use App\NginxServer;
use Illuminate\Http\Request;
use App\NginxConfig;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $ngxConfig = new NginxConfig();
        $nginxServer = new NginxServer();
        $nginxServer->checkConfig();
        $result = $ngxConfig->getAllHosts();

        return response()->json($result);

    }

    /**
     * Show the form for creating a new resource.
     * @param  int $filename
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $hostsDefaultPath = '/etc/nginx/sites-enabled/';

        if ($request->has('newName')) {

            $newName = $hostsDefaultPath . $request->newName;
            $fullPath = base64_decode($request->filename);

            rename($fullPath, $newName);

        } else {
            $fullPath = $hostsDefaultPath . $request->filename;
            file_put_contents($fullPath, '');
        }
        $nginxServer = new NginxServer();
        $nginxServer->checkConfig();
        $nginxServer->reloadConfig();


        return response('', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $ngxConfig = new NginxConfig();
        $result = $ngxConfig->getHost($id);

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $path
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $path)
    {

        $ngxConfig = new NginxConfig();
        $ngxConfig->path = base64_decode($path);
        $ngxConfig->content = $request->text;
        $ngxConfig->backupInTmp();
        $ngxConfig->saveConfig();

        $nginxServer = new NginxServer();
        $nginxServer->checkConfig();

        if (sizeof($nginxServer->errors) > 0) {

            $ngxConfig->restoreTmp();
            $result = (object)[];
            $result->errorText = $nginxServer->rawResult;
            $result->errorParse = $nginxServer->errors;
            return response(json_encode($result), 451);
            //  return response(null,451)->json($nginxServer->errors);

        }
        $nginxServer->reloadConfig();
        return response(null, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $path
     * @return \Illuminate\Http\Response
     */
    public function destroy($path)
    {

        $ngxConfig = new NginxConfig();
        $ngxConfig->path = base64_decode($path);

        $ngxConfig->backupInTmp();
        unlink(base64_decode($path));

        $nginxServer = new NginxServer();
        $nginxServer->checkConfig();

        if (sizeof($nginxServer->errors) > 0) {

            $ngxConfig->restoreTmp();
        }
        $nginxServer->reloadConfig();
        return response(null, 200);
    }
}
