<?php

require 'vendor/autoload.php';

$config_name = $_REQUEST['config'];
$config = json_decode(file_get_contents("./configs/{$config_name}.json"));

$pull_request_id = $_REQUEST['pull_request_id'];
$status = $_REQUEST['status']; // pass | fail


if ($pull_request_id === null || $pull_request_id == '') {
  exit('Pull request ID required');
} else {
  $username = $config->bitbucket_username;
  $password = $config->bitbucket_password;
  $repo_owner = $config->bitbucket_repo_owner;
  $repo_name = $config->bitbucket_repo_name;
  $credential = new Bitbucket\API\Authentication\Basic($username, $password);

  // TODO approve/decline pull request
  $message = 'Hmm...';
  if (strtolower($status) === 'pass') {
    $message = 'Test passed';
  } else {
    $message = 'Test failed';
  }

  $pr = new Bitbucket\API\Repositories\PullRequests();
  $pr->setCredentials($credential);
  $result = $pr->comments()->create($repo_owner, $repo_name, $pull_request_id, $message);

  var_dump($result);
}