<?php
namespace SoerBot\WebServer;
    //composer require react/http:^0.8.4
    //composer require react/socket:^1.2
    use Psr\Http\Message\ServerRequestInterface;
    use React\Http\Response;
    use React\Http\Server;
    use React\EventLoop\Factory;
    use React\Socket\Server as Socket;

class WebHookServer
{
    private $loop; 
    private $server;
    private $socket;
    private $port;
    private $response;
    /**
     * Constrictor: 
     * - create instance of WebHookServer
     * 
     * @param $aLoop instance of React\EventLoop\LoopInterface
     * @param $aPort int 0..9999
     * @param $aResponse instance of React\Http\Response
     * @throws UnexpectedValueException
     */
    public function __construct($aLoop, $aPort, $aResponse)
    {
        if (!($aLoop instanceof \React\EventLoop\LoopInterface))
            throw new UnexpectedValueException();
        if (!is_int($aPort)||($aPort<0||$aPort>9999))
            throw new UnexpectedValueException();
        if (!($aResponse instanceof \React\Http\Response))
            throw new UnexpectedValueException();

        $this->loop = $aLoop;
        $this->port = $aPort;
        $this->response = $aResponse;

        echo "WebHookServer has created\n";
        // $this->client->emit('debug', "WebHookServer has created");
    }
    
    /**
     * StartServer: 
     * - starting server for listening spicified(in constructor) port .
     * - responds with spicified(in constructor) responds for every request.
     * 
     * @throws UnexpectedValueException
     */
    public function StartServer()
    {
        $this->server = new Server(
                function (ServerRequestInterface $request) {return $this->response;}
            );

        $this->socket = new Socket($this->port, $this->loop);
        $this->server->listen( $this->socket);

        echo "WebHookServer started(port{$this->port})\n";
        // $this->client->emit('debug', "WebHookServer started(port{$this->port})");

        // return $request;//PHP Notice:  Undefined variable: request in H:\0.GitHub\soerbot\src\WebServer\WebHookServer.php on line 63
    }
}
