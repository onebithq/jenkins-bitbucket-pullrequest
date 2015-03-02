<?php

require 'vendor/autoload.php';

$json = json_decode(file_get_contents('php://input'));

$actions = ['created', 'updated'];
$key = null;
foreach ($actions as $action) {
  if (property_exists($json, "pullrequest_{$action}")){
    $key = "pullrequest_${action}";
    break;
  }
}

if ($key !== null) {
  $config_name = $_GET['config'];
  $config = json_decode(file_get_contents("./configs/{$config_name}.json"));

  $obj = $json->$key;
  $params = [];

  $sha = $obj->source->commit->sha;
  if ($sha == null || $sha == '')
    $sha = $obj->source->commit->hash;

  $params['job'] = $config->job_name;
  $params['token'] = @$config->token;
  $params['PULL_REQUEST_ID'] = $obj->id;
  $params['PULL_REQUEST_TITLE'] = $obj->title;
  $params['SOURCE_COMMIT_SHA'] = $sha;

  $query_params = http_build_query($params);
  $url = "{$config->jenkins_url}/buildByToken/buildWithParameters";
  $response = \Httpful\Request::post("{$url}?{$query_params}")->send();
  echo $response->body;
}
