<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use React\Http\Server;

namespace SoerBot\WebServer{

    class WebHookServerResearch
    {
        private $loop;
        private $server;
        private $socket;

        public function __construct()
        {
            echo "WebHookServerResearch has created";

        }
        

        public function StartServer()
        {
            // https://reactphp.org/
            // $this -> loop = React\EventLoop\Factory::create();

            // $this -> server = new Server(function (ServerRequestInterface $request) {
            //     return new Response(
            //         200,
            //         array('Content-Type' => 'text/plain'),
            //         "Hello World!\n"
            //     );
            // });

            // $this -> socket = new Server(8080,  $this -> loop);
            // $this -> server->listen( $this -> socket);

            // echo "Server running at http://127.0.0.1:8080\n";

            // $this -> loop->run();

            $loop = React\EventLoop\Factory::create();

            $server = new React\Http\Server(function (Psr\Http\Message\ServerRequestInterface $request) {
                return new React\Http\Response(
                    200,
                    array('Content-Type' => 'text/plain'),
                    "Hello World!\n"
                );
            });

            $socket = new React\Socket\Server(8080, $loop);
            $server->listen($socket);

            echo "Server running at http://127.0.0.1:8080\n";

            $loop->run();
        }
    }
}
?>