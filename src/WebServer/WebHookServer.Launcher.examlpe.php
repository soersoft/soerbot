<?php

use SoerBot\src\WebServer\WebHookServerResearch;

function __autoload($class_name) {
    require_once $class_name . '.php';
  }

  /**
   * Examples of starting HttpWebServer
   * @param $aLoop application's work loop, not started yet
   *  - instance of React\EventLoop\LoopInterface
   * @throws UnexpectedValueException
   */
  function aHttpWebServer_StartExamples($aLoop): void
  {
      $port = 8080;
      $requestHandler=//(\Psr\Http\Server\RequestHandlerInterface)
      (
          function (ServerRequestInterface $request) {
              new \React\Http\Response(
                  200,
                  array('Content-Type' => 'text/plain'),
                  "Hello from Runner one!\n"
              );
          });
      $ws = new \SoerBot\WebServer\HttpWebServer($aLoop, $port, $requestHandler);
      $ws->startServer();
      echo "Server has started, ws->port: {$ws->port}\n";

      $port =(new \SoerBot\WebServer\HttpWebServer(
              $aLoop, 
              8081,
              function (ServerRequestInterface $request) {
                  return new \React\Http\Response(
                      200,
                      array('Content-Type' => 'text/plain'),
                      "Hello from Runner two!\n"
                  );
              }
            )
      )->startServer()->port;
      echo "Server has started, listening port: {$port}\n";

      $requestHandler=
          function (ServerRequestInterface $request) {
              new \React\Http\Response(
                  200,
                  array('Content-Type' => 'text/plain'),
                  "Hello from Runner three!\n"
              );
          };

      $port = $HttpWebServer->createServer($aLoop, 8083, $requestHandler);
      echo "Server has started from static method, listening port: {$port}\n";
  }